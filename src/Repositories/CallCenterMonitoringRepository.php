<?php

namespace Jvleeuwen\Broadsoft\Repositories;

use Jvleeuwen\Broadsoft\Database\Models\bsCallcenterMonitoring;
use Jvleeuwen\Broadsoft\Models\CallCenterMonitoring;
use Jvleeuwen\Broadsoft\Contracts\CallCenterMonitoringContract;

class CallCenterMonitoringRepository implements CallCenterMonitoringContract
{
    public function SaveToDB($CallCenterMonitoringArray)
    {
        $targetId = (string)$CallCenterMonitoringArray['targetId'];
        $averageHandlingTime = (int)$CallCenterMonitoringArray['averageHandlingTime'];
        $expectedWaitTime = (int)$CallCenterMonitoringArray['expectedWaitTime'];
        $averageSpeedOfAnswer = (int)$CallCenterMonitoringArray['averageSpeedOfAnswer'];
        $longestWaitTime = (int)$CallCenterMonitoringArray['longestWaitTime'];
        $numCallsInQueue = (int)$CallCenterMonitoringArray['numCallsInQueue'];
        $numAgentsAssigned = (int)$CallCenterMonitoringArray['numAgentsAssigned'];
        $numAgentsStaffed = (int)$CallCenterMonitoringArray['numAgentsStaffed'];
        $numStaffedAgentsIdle = (int)$CallCenterMonitoringArray['numStaffedAgentsIdle'];
        $numStaffedAgentsUnavailable = (int)$CallCenterMonitoringArray['numStaffedAgentsUnavailable'];

        $ExistingCallCenter = CallCenterMonitoring::where('targetId', $targetId)->first();
        if (! $ExistingCallCenter) {
            $NewCallcenter = new CallCenterMonitoring;
            $NewCallcenter->targetID = $targetId;
            $NewCallcenter->averageHandlingTime = $averageHandlingTime;
            $NewCallcenter->expectedWaitTime = $expectedWaitTime;
            $NewCallcenter->averageSpeedOfAnswer = $averageSpeedOfAnswer;
            $NewCallcenter->longestWaitTime = $longestWaitTime;
            $NewCallcenter->numCallsInQueue = $numCallsInQueue;
            $NewCallcenter->numAgentsAssigned = $numAgentsAssigned;
            $NewCallcenter->numAgentsStaffed = $numAgentsStaffed;
            $NewCallcenter->numStaffedAgentsIdle = $numStaffedAgentsIdle;
            $NewCallcenter->numStaffedAgentsUnavailable = $numStaffedAgentsUnavailable;
            $NewCallcenter->save();
        } else {
            $ExistingCallCenter->targetID = $targetId;
            $ExistingCallCenter->averageHandlingTime = $averageHandlingTime;
            $ExistingCallCenter->expectedWaitTime = $expectedWaitTime;
            $ExistingCallCenter->averageSpeedOfAnswer = $averageSpeedOfAnswer;
            $ExistingCallCenter->longestWaitTime = $longestWaitTime;
            $ExistingCallCenter->numCallsInQueue = $numCallsInQueue;
            $ExistingCallCenter->numAgentsAssigned = $numAgentsAssigned;
            $ExistingCallCenter->numAgentsStaffed = $numAgentsStaffed;
            $ExistingCallCenter->numStaffedAgentsIdle = $numStaffedAgentsIdle;
            $ExistingCallCenter->numStaffedAgentsUnavailable = $numStaffedAgentsUnavailable;
            $ExistingCallCenter->save();
        }
        return true;
    }
}
