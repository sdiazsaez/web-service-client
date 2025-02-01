<?php

namespace Larangular\WebServiceClient\Request;

use Larangular\WebServiceLogger\WebServiceLog\WebServiceLoggeable;
use Larangular\WebServiceManager\Request\Requestable;

class RequestController {

    use WebServiceLoggeable;

    public $requestable;
    public $objectId;
    public $objectType;
    public $clientService;

    public function __construct(Requestable $requestable, int $objectId, string $objectType, string $clientService) {
        $this->requestable = $requestable;
        $this->objectId = $objectId;
        $this->objectType = $objectType;
        $this->clientService = $clientService;
    }

    public function getResponse() {
        return $this->request();
    }

    public function hasError($response): bool {
        $errorCode = $response['response']['Fault']['codigoError'];
        return ($response == null || $response['response'] == null || !($errorCode === 0 || $errorCode === '0'));
    }

    private function request() {
        $serviceCaller = $this->requestable->getServiceCaller();
        $provider = $this->requestable->descriptor->provider();
        $service = $this->requestable->descriptor->serviceName();
        $url = $serviceCaller->service->url;
        $request = $this->requestable->getTransformedData();

        return $this->getLog($this->objectId, $this->objectType, $this->clientService, $provider, $service, $url,
            $request, $this->getExpireTime($provider, $service), static function ($response) use ($serviceCaller) {
                return !$serviceCaller->isValidResponse($response);
            }, static function () use ($serviceCaller) {
                $response = $serviceCaller->getResponse();
                return [
                    'response'     => $response,
                    'raw_request'  => $response->getServiceClient()
                                               ->getLastRequest(),
                    'raw_response' => $response->getServiceClient()
                                               ->getLastResponse(),
                ];
            });
    }

    private function getExpireTime(string $provider, string $service): Int {
        $expireTime = config($provider . '-services.services.' . $service . '.cache-expire', 0);
        return \is_int($expireTime)
            ? $expireTime
            : 0;
    }

}
