<template>
    <div class="results-item">
        <div class="results-item__header">
            <router-link
                v-bind:to="`/provider/${item.npi}`"
                class="results-item__title"
            >
                {{ item.first_name }} {{ item.middle_name }} {{ item.last_name }}<template v-if="item.credentials">, {{ item.credentials }}</template>
            </router-link>

            <div
                v-if="item.accepting_new_patients === 'Yes' || item.accepting_new_patients === 'No'"
                class="results-item__sub-title">
                Provider: {{ item.accepting_new_patients === 'Yes' ? 'Accepting new patients' : item.accepting_new_patients === 'No' ? 'Not accepting new patients' : ''}}

                <img
                    v-if="item.accepting_new_patients === 'Yes'"
                    src="images/check-circle.svg"
                    class="results-item__header-icon"
                >
                <img
                    v-if="item.accepting_new_patients === 'No'"
                    src="images/x-circle.svg"
                    class="results-item__header-icon"
                >
            </div>

            <router-link
                v-bind:to="`/provider/${item.npi}`"
                class="results-item__detail results-item__detail--desktop"
            >
                <span>View {{ item.first_name }} {{ item.last_name }}'s details</span>
                <img
                    v-if="isEven"
                    src="images/arrow-right-white.svg"
                    class="results-item__detail-icon"
                >
                <img
                    v-else
                    src="images/arrow-right.svg"
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
                        Primary address: <br>
                        {{ item.locations[0].addr_line_1 }}, {{ item.locations[0].city }}, {{ item.locations[0].state }}, {{ item.locations[0].zip }} <br>
                        <router-link
                            v-if="item.locations.length > 1"
                            v-bind:to="`/provider/${item.npi}`"
                            class="text--styled-link text--bold"
                        >+ {{ item.locations.length - 1 }} location{{ item.locations.length - 1 > 1 ? 's' : '' }}</router-link>
                    </span>
                </div>
                <div
                    v-if="item.locations[0].practice_phone"
                    class="results-item__char results-item__char--phone">
                    <img
                        v-if="!isEven"
                        src="images/phone-icon.svg" alt=""
                    >
                    <img
                        v-else
                        src="images/phone-icon-white.svg" alt=""
                    >
                    <a v-bind:href="`tel:${item.locations[0].practice_phone}`">
                        {{ item.locations[0].practice_phone }}
                    </a>
                </div>
            </div>
            <div
                v-if="specialties"
                class="results-item__info-col"
            >
                <div class="results-item__char text--regular">
                    Specialties:
                </div>
                <div class="results-item__char">
                    {{ specialties }}
                </div>
            </div>
        </div>
        <div class="align-right">
            <router-link
                v-bind:to="`/provider/${item.npi}`"
                class="results-item__detail results-item__detail--mobile"
            >
                <span>View {{ item.first_name }} {{ item.last_name }}'s details</span>
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
    name: 'ResultsItemDoctor',

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
        specialties: function() {
            if (this.item.specialty) {
                return this.item.specialty.trim().split(',').join(', ');
            } else {
                return [];
            }
        }
    }
}
</script>
