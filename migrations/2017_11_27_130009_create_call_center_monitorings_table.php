<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCallCenterMonitoringsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('call_center_monitorings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('eventType');
            $table->string('eventID');
            $table->integer('sequenceNumber');
            $table->string('subscriptionId');
            $table->string('targetId');
            $table->integer('averageHandlingTime');
            $table->integer('expectedWaitTime');
            $table->integer('averageSpeedOfAnswer');
            $table->integer('longestWaitTime');
            $table->integer('numCallsInQueue');
            $table->integer('numAgentsAssigned');
            $table->integer('numAgentsStaffed');
            $table->integer('numStaffedAgentsIdle');
            $table->integer('numStaffedAgentsUnavailable');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('call_center_monitorings');
    }
}
