<?php

Route::group([
    'prefix'     => 'api/webservice-client',
    'middleware' => 'web',
    'namespace'  => 'Larangular\WebServiceClient\Http\Controllers',
    'as'         => 'larangular.api.web-service-client.',
], static function () {
    Route::get('service/{service}/{quote}', 'WebServiceClient\Gateway@makeRequest');
    Route::get('service/{service}/{quote}/request{extension?}', 'WebServiceClient\Gateway@getRequest');
});
