<?php

Route::group([
    'prefix'     => 'api/webservice-client',
    'middleware' => 'web',
    'namespace'  => 'Larangular\WebServiceClient\Http\Controllers',
    'as'         => 'larangular.api.web-service-client.',
], function () {
    Route::get('service/{service}/{quote}', 'Request\Gateway@makeRequest');
    Route::get('service/{service}/{quote}/request{extension?}', 'Request\Gateway@getRequest');
});
