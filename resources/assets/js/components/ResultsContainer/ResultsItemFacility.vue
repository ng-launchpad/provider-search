<template>
    <div class="results-item results-item--facility">
        <div class="results-item__header">
            <router-link
                v-bind:to="`/facility/${item.tin}`"
                class="results-item__title"
            >
                {{ item.facility_name }}
            </router-link>

            <router-link
                v-bind:to="`/facility/${item.tin}`"
                class="results-item__detail results-item__detail--desktop"
            >
                <span>View facility details</span>
                <img
                    v-if="!isEven"
                    src="images/arrow-right.svg"
                    class="results-item__detail-icon"
                >
                <img
                    v-else
                    src="images/arrow-right-white.svg"
                    class="results-item__detail-icon"
                >
            </router-link>
        </div>
        <div class="results-item__info">
            <div class="results-item__info-col">
                <div class="results-item__char results-item__char--location">
                    <img
                        v-if="!isEven"
                        src="images/map-pin.svg"
                        alt=""
                    >
                    <img
                        v-else
                        src="images/map-pin-white.svg"
                        alt=""
                    >
                    <span>
                        <template v-if="item.locations.length > 1">
                            <router-link
                                v-bind:to="`/facility/${item.tin}`"
                                class="text--styled-link text--bold"
                            >{{ item.locations.length }} facilities</router-link>,&nbsp;including:<br>
                        </template>
                        {{ item.locations[0].addr_line_1 }}, {{ item.locations[0].addr_line_2 ? `${item.locations[0].addr_line_2},` : '' }} {{ item.locations[0].city }}, {{ item.locations[0].state }}, {{ item.locations[0].zip }} <br>
                    </span>
                </div>
                <div class="results-item__char results-item__char--phone">
                    <img
                        v-if="!isEven"
                        src="images/phone-icon.svg"
                        alt=""
                    >
                    <img
                        v-else
                        src="images/phone-icon-white.svg"
                        alt=""
                    >
                    <a v-bind:href="`tel:${item.locations[0].phone_number}`">{{ item.locations[0].phone_number }}</a>
                </div>
            </div>
            <div
                v-if="facilityType"
                class="results-item__info-col"
            >
                <div class="results-item__char text--regular">
                    Services
                </div>
                <div class="results-item__char">
                    {{ facilityType }}
                </div>
            </div>
        </div>
        <div class="align-right">
            <router-link
                v-bind:to="`/facility/${item.tin}`"
                class="results-item__detail results-item__detail--mobile"
            >
                <span>View facility details</span>
                <img
                    v-if="!isEven"
                    src="images/arrow-right.svg"
                    class="results-item__detail-icon"
                >
                <img
                    v-else
                    src="images/arrow-right-white.svg"
                    class="results-item__detail-icon"
                >
            </router-link>
        </div>
    </div>
</template>

<script>
export default {
    name: 'ResultsItemFacility',

    props: {
        item: {
            type: Object,
            required: false,
            default: false
        },
        isEven: {
            type: Boolean,
            required: false,
            default: false
        }
    },

    computed: {
        facilityType: function() {
            return this.item.locations[0].services.trim().split(',').join(', ');
        }
    }
}
</script>
