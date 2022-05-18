<template>
    <div class="results-item results-item--facility">
        <div class="results-item__header">
            <router-link
                v-bind:to="`/facility/${item.id}`"
                class="results-item__title"
            >
                {{ item.label }}
            </router-link>

            <router-link
                v-bind:to="`/facility/${item.id}`"
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
                                v-bind:to="`/facility/${item.id}`"
                                class="text--styled-link text--bold"
                            >{{ item.locations.length }} locations including:</router-link><br>
                        </template>
                        {{ item.locations[0].address.line_1 }}, {{ item.locations[0].address.line_2 ? `${item.locations[0].address.line_2},` : '' }} {{ item.locations[0].address.city }}, {{ item.locations[0].address.state.label }}, {{ item.locations[0].address.zip }} <br>
                    </span>
                </div>
                <div class="results-item__char results-item__char--phone">
                    <img
                        v-if="!isEven"
                        src="images/phone-icon.svg" alt=""
                    >
                    <img
                        v-else
                        src="images/phone-icon-white.svg" alt=""
                    >
                    <a v-bind:href="`tel:${item.locations[0].phone}`">{{ item.locations[0].phone }}</a>
                </div>
            </div>
            <div
                v-if="item.type"
                class="results-item__info-col"
            >
                <div class="results-item__char text--bold">
                    Facility type:
                </div>
                <div class="results-item__char">
                    {{ item.type }}
                </div>
            </div>
            <div
                v-if="item.network"
                class="results-item__info-col"
            >
                <div class="results-item__char text--bold">
                    Network:
                </div>
                <div
                    class="results-item__char"
                    v-html="item.network.network_label"
                />
            </div>
        </div>
        <div class="align-right">
            <router-link
                v-bind:to="`/facility/${item.id}`"
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
}
</script>
