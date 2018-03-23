<template>
    <tile :position="position" modifiers="overflow">
        <section class="statistics">
            <h1>Queue Agents</h1>
            <ul>
                <li class="statistic">
                    <span class="statistic__label">Assigned</span>
                    <span class="statistic__count">{{ formatNumber(numAgentsAssigned) }}</span>
                </li>
                <li class="statistic">
                    <span class="statistic__label">Staffed</span>
                    <span class="statistic__count">{{ formatNumber(numAgentsStaffed) }}</span>
                </li>
                <li class="statistic">
                    <span class="statistic__label">Idle</span>
                    <span class="statistic__count">{{ formatNumber(numStaffedAgentsIdle) }}</span>
                </li>
                <li class="statistic">
                    <span class="statistic__label">Unavailable</span>
                    <span class="statistic__count">{{ formatNumber(numStaffedAgentsUnavailable) }}</span>
                </li>
            </ul>
        </section>
    </tile>
</template>

<script>
    import { formatNumber } from '../helpers';
    import echo from '../mixins/echo';
    import Tile from './atoms/Tile';
    import saveState from 'vue-save-state';

    export default {

        components: {
            Tile,
        },

        mixins: [echo],

        props: ['position','queue'],

        data() {
            return {
                eventType : this.queue.eventType,
                eventID: this.queue.eventID,
                sequenceNumber : this.queue.sequenceNumber,
                subscriptionId : this.queue.subscriptionId,
                targetId : this.queue.targetId,
                averageHandlingTime : this.queue.averageHandlingTime,
                expectedWaitTime : this.queue.expectedWaitTime,
                averageSpeedOfAnswer : this.queue.averageSpeedOfAnswer,
                longestWaitTime : this.queue.longestWaitTime,
                numCallsInQueue: this.queue.numCallsInQueue,
                numAgentsAssigned : this.queue.numAgentsAssigned,
                numAgentsStaffed : this.queue.numAgentsStaffed,
                numStaffedAgentsIdle : this.queue.numStaffedAgentsIdle,
                numStaffedAgentsUnavailable : this.queue.numStaffedAgentsUnavailable,
            }
        },

        methods: {
            formatNumber,

            getEventHandlers() {
                return {
                    'Broadsoft.CallCenterMonitoringEvent': response => {
                        if (this.targetId = response.CallCenterMonitoring.targetId) {
                            this.eventType = response.CallCenterMonitoring.eventType;
                            this.eventID = response.CallCenterMonitoring.eventID;
                            this.sequenceNumber = response.CallCenterMonitoring.sequenceNumber;
                            this.subscriptionId = response.CallCenterMonitoring.subscriptionId;
                            this.targetId = response.CallCenterMonitoring.targetId;
                            this.averageHandlingTime = response.CallCenterMonitoring.averageHandlingTime;
                            this.expectedWaitTime = response.CallCenterMonitoring.expectedWaitTime;
                            this.averageSpeedOfAnswer = response.CallCenterMonitoring.averageSpeedOfAnswer;
                            this.longestWaitTime = response.CallCenterMonitoring.longestWaitTime;
                            this.numCallsInQueue = response.CallCenterMonitoring.numCallsInQueue;
                            this.numAgentsAssigned = response.CallCenterMonitoring.numAgentsAssigned;
                            this.numAgentsStaffed = response.CallCenterMonitoring.numAgentsStaffed;
                            this.numStaffedAgentsIdle = response.CallCenterMonitoring.numStaffedAgentsIdle;
                            this.numStaffedAgentsUnavailable = response.CallCenterMonitoring.numStaffedAgentsUnavailable;
                        }
                    },
                };
            },
        },
    };

</script>
