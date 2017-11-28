<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'bs'], function () {
    Route::post('CallCenterMonitoring', '\Jvleeuwen\Broadsoft\Controllers\CallCenterMonitoringController@Incomming');
});
