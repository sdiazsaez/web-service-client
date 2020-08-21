<?php

namespace Larangular\WebServiceClient\Http\Controllers\WebServiceClient;

use Larangular\WebServiceManager\Register\Service;
use Larangular\WebServiceManager\Register\ServiceRecords;
use Msd\Sura\Autoclick\Cotiza\{Oferta, OfertaBase, OfertaBaseRequest, Producto, ProductoBase, ProductoRequest};
use Msd\Sura\Autoclick\Detalle\{Detalle, DetalleProducto, DetalleRequest};
use Msd\Sura\Autoclick\Generar\{Cotizacion, CotizacionRequest, Poliza, PolizaRequest};
use Msd\Sura\Autoclick\Models\ServiceForm;
use Msd\WebServiceLogger\Http\Resources\WebServiceLogResource;
use Msd\WebServiceLogger\Models\WebServiceLog;
use Psy\Util\Str;
use Sura\Autoclick\{ClassMap, EnumType, ServiceType, StructType};


class Gateway {

    private $serviceDescription;
    private $serviceRecords;

    public function __construct(ServiceRecords $serviceRecords) {
        $this->serviceRecords = $serviceRecords;
    }

    public function makeRequest($service, $data) {
        $serviceData = $this->serviceDescription->getServiceDescription($service);
        $serviceInstance = $this->getServiceInstance($serviceData, $data);
        return $serviceInstance->getResponse();
    }

    public function makaeRequest(string $service, Quote $quote) {
        $transformer = $this->getTransformer($service);
        $request = $transformer->transform($quote);

        if (empty($request)) {
            return null;
        }

        $response = $this->getLog($quote->id, $service, $request, $this->getExpireTime($service), function ($response) {
            return $this->hasError($response);
        }, function () use ($service, $request) {
            $r = $this->autoclickController->makeRequest($service, $request);
            return $r;
        });

        return $response;
    }

    public function getRequest(string $service, Quote $quote) {
        $transformer = $this->getTransformer($service);
        $request = $transformer->transform($quote);

        return (empty($request))
            ? null
            : $request;
    }

}
