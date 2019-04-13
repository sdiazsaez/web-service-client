<?php

namespace Larangular\WebServiceClient\Facades;

use Illuminate\Support\Facades\Facade;

class WSClientRequest extends Facade {
    protected static function getFacadeAccessor() {
        return \Larangular\WebServiceClient\Request\WSClientRequest::class;
    }
}
