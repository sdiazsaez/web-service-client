<?php

namespace Larangular\WebServiceClient\Request;

use Larangular\WebServiceManager\Facades\ServiceRequest;

class WSClientRequest {

    public function getRequestableWithDescriptorName(string $clientService, string $descriptorName, array $data,
        int $objectId, string $objectType): RequestController {
        $requestable = ServiceRequest::getRequestableWithDescriptorName($descriptorName, $data);
        return new RequestController($requestable, $objectId, $objectType, $clientService);
    }

}
