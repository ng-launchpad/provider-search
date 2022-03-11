<template>
    <div class="results-item">
        <div class="results-item__header">
            <router-link
                v-bind:to="`/provider/${item.id}`"
                class="results-item__title"
            >
                {{ item.label }}<template v-if="item.degree">, {{ item.degree }}</template>
            </router-link>

            <div
                v-if="!!item.is_accepting_new_patients"
                class="results-item__sub-title">
                {{ item.is_accepting_new_patients ? 'Accepting new patients' : 'Not accepting new patients' }}
            </div>

            <router-link
                v-bind:to="`/provider/${item.id}`"
                class="results-item__detail results-item__detail--desktop"
            >
                <span>View {{ item.label }}'s details</span>
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
                        <span class="text--bold">Address:</span> <br>
                        {{ primaryAddress.address.string }}<br>
                        <router-link
                            v-if="item.locations.length > 1"
                            v-bind:to="`/provider/${item.id}`"
                            class="text--styled-link text--regular"
                        >+ {{ item.locations.length - 1 }} location{{ item.locations.length - 1 > 1 ? 's' : '' }}</router-link>
                    </span>
                </div>
                <div
                    v-if="primaryAddress.phone"
                    class="results-item__char results-item__char--phone">
                    <img
                        v-if="!isEven"
                        src="images/phone-icon.svg" alt=""
                    >
                    <img
                        v-else
                        src="images/phone-icon-white.svg" alt=""
                    >
                    <a v-bind:href="`tel:${primaryAddress.phone}`">
                        {{ primaryAddress.phone }}
                    </a>
                </div>
            </div>
            <div
                v-if="languages"
                class="results-item__info-col"
            >
                <div class="results-item__char text--bold">
                    Languages spoken:
                </div>
                <div class="results-item__char">
                    English<span v-if="languages.length">,</span> {{ languages }}
                </div>
            </div>
            <div
                v-if="item.network || specialities"
                class="results-item__info-col"
            >
                <div
                    v-if="specialities"
                    class="mb-2"
                >
                    <div class="results-item__char text--bold">
                        Specialties:
                    </div>
                    <div class="results-item__char">
                        {{ specialities }}
                    </div>
                </div>

                <div
                    v-if="item.network"
                >
                    <div class="results-item__char text--bold">
                        Network:
                    </div>
                    <div class="results-item__char">
                        {{ item.network.search_label }}
                    </div>
                </div>
            </div>
        </div>
        <div class="align-right">
            <router-link
                v-bind:to="`/provider/${item.id}`"
                class="results-item__detail results-item__detail--mobile"
            >
                <span>View {{ item.label }}'s details</span>
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
        primaryAddress() {
            return this.item.locations.find(location => location.is_primary);
        },

        specialities() {
            if (this.item.specialities.length) {
                return this.item.specialities.join(', ');
            }
            return '';
        },

        languages() {
            if (this.item.languages.length) {
                return this.item.languages.join(', ');
            }
            return '';
        },
    }
}
</script>
