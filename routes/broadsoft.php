<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'bs'], function () {
    Route::post('CallCenterMonitoring', '\Jvleeuwen\Broadsoft\Controllers\CallCenterMonitoringController@Incomming');
    Route::post('CallCenterAgent', '\Jvleeuwen\Broadsoft\Controllers\CallCenterAgentController@Incomming');
    Route::post('AdvancedCall', '\Jvleeuwen\Broadsoft\Controllers\AdvancedCallController@Incomming');
});
