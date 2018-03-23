<template>
    <tile :position="position" modifiers="overflow">
        <section class="statistics">
            <h1>TICKETS {{formatNumber(line)}}th LINE</h1>
            <ul>
                <li class="statistic">
                    <span class="statistic__label">NEW</span>
                    <span class="statistic__count">{{ formatNumber(tnew) }}</span>
                </li>
                <li class="statistic">
                    <span class="statistic__label">PUPDATE</span>
                    <span class="statistic__count">{{ formatNumber(partnerupdate) }}</span>
                </li>
                <li class="statistic">
                    <span class="statistic__label">PROGRESS</span>
                    <span class="statistic__count">{{ formatNumber(inprogress) }}</span>
                </li>
                <li class="statistic">
                    <span class="statistic__label">VENDOR</span>
                    <span class="statistic__count">{{ formatNumber(vendor) }}</span>
                </li>
                <li class="statistic">
                    <span class="statistic__label">VUPDATE</span>
                    <span class="statistic__count">{{ formatNumber(vendorupdate) }}</span>
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

        mixins: [echo, saveState],

        props: ['position','line'],

        data() {
            return {
                tnew: 0,
                partnerupdate: 0,
                inprogress: 0,
                vendor: 0,
                vendorupdate: 0,
            };
        },

        methods: {
            formatNumber,

            getEventHandlers() {
                return {
                    '.App.Events.Topdesk.TicketStats': response => {
                        console.log(response);
                        this.tnew = response.new;
                        this.partnerupdate = response.partnerupdate;
                        this.inprogress = response.inprogress;
                        this.vendor = response.vendor;
                        this.vendorupdate = response.vendorupdate;
                    },
                };
            },

            getSaveStateConfig() {
                return {
                    cacheKey: 'tickets',
                };
            },
        },
    };

</script>
