<template>
    <div>
        <div class="page-header page-header--bg-color">
            <div class="container">
                <p class="page-header__title heading">
                    {{ provider.label }}{{ provider.degree ? `, ${provider.degree}` : '' }}
                </p>
                <p
                    v-if="provider.is_accepting_new_patients !== null"
                    class="page-header__sub-title">
                    {{ provider.is_accepting_new_patients ? 'Accepting new patients' : 'Not accepting new patients' }}
                </p>
                <div class="row mt-4">
                    <div
                        v-if="provider.locations"
                        class="col-md-4 mb-4 mb-md-0"
                    >
                        <div
                            class="page-header__char"
                        >
                            <img v-bind:src="'/images/map-pin-white.svg'" alt="">
                            <div
                                v-if="primaryAddress"
                                class="page-header__char-title"
                            >
                                <strong>Address</strong>
                                <br>
                                {{ primaryAddress.address.string }}
                                <br>
                            </div>
                        </div>
                        <div class="page-header__char page-header__char--map-link">
                            <img v-bind:src="'/images/icon-pointer.svg'" alt="">
                            <div class="page-header__char-title">
                                <a
                                    v-bind:href="`${primaryAddress.address.map}`"
                                    class="mt-2 d-inline-block text--styled-link"
                                >
                                    View on a map
                                </a>
                            </div>
                        </div>
                    </div>
                    <div
                        v-if="primaryAddress.phone"
                        class="col-md-4 mb-4 mb-md-0"
                    >
                        <div class="page-header__char">
                            <img v-bind:src="'/images/phone-icon-white.svg'" alt="">
                            <div class="page-header__char-title">
                                <strong>Phone number</strong>
                                <br>
                                <a v-bind:href="`tel:${primaryAddress.phone}`">{{ primaryAddress.phone }}</a>
                            </div>
                        </div>
                    </div>
                    <div
                        v-if="specialities"
                        class="col-md-4 mb-4 mb-md-0"
                    >
                        <div class="page-header__char">
                            <img v-bind:src="'/images/check-white.svg'" alt="">
                            <div class="page-header__char-title">
                                <strong>Specialties</strong>
                                <br>
                                {{ specialities }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="provider-content">
                <div class="row">
                    <div class="col-md-3">
                        <div
                            v-if="provider.type"
                            class="provider-content__item mb-4"
                        >
                            <div class="provider-content__char text--bold">
                                Provider type
                            </div>
                            <div class="provider-content__char text--regular">
                                {{ provider.type }}
                            </div>
                        </div>
                        <div
                            v-if="provider.gender"
                            class="provider-content__item mb-4"
                        >
                            <div class="provider-content__char text--bold">
                                Gender
                            </div>
                            <div class="provider-content__char text--regular">
                                {{ provider.gender }}
                            </div>
                        </div>
                        <div
                            v-if="provider.npi"
                            class="provider-content__item mb-4"
                        >
                            <div class="provider-content__char text--bold">
                                NPI ID
                            </div>
                            <div class="provider-content__char text--regular">
                                {{ provider.npi }}
                            </div>
                        </div>
                    </div>
                    <div
                        v-if="provider.network || provider.website"
                        class="col-md-4"
                    >
                        <div
                            v-if="provider.website"
                            class="provider-content__item mb-4"
                        >
                            <div class="provider-content__char text--bold">
                                Website
                            </div>
                            <div class="provider-content__char text--regular">
                                <a v-bind:href="provider.website">{{ provider.website }}</a>
                            </div>
                        </div>
                        <div
                            v-if="provider.network"
                            class="provider-content__item mb-4"
                        >
                            <div class="provider-content__char text--bold">
                                Network name
                            </div>
                            <div class="provider-content__char text--regular">
                                {{ provider.network.network_label }}
                            </div>
                        </div>
                    </div>
                    <div
                        class="col-md-4"
                    >
                        <div
                            class="provider-content__item mb-4"
                        >
                            <div class="provider-content__char text--bold">
                                Languages spoken
                            </div>
                            <div class="provider-content__char text--regular">
                                English<span v-if="languages.length">,</span> {{ languages }}
                            </div>
                        </div>
                        <div
                            v-if="provider.hospitals.length"
                            class="provider-content__item"
                        >
                            <div class="provider-content__char text--bold">
                                Affiliated hospitals
                            </div>
                            <div
                                v-for="hospital in provider.hospitals"
                                class="provider-content__char text--regular"
                            >
                                {{ hospital }}
                            </div>
                        </div>
                    </div>
                </div>

                <template v-if="provider.locations && provider.locations.length > 1">
                    <div class="separator mt-5 mb-5" />
                    <div
                        class="provider-content__item"
                    >
                        <div class="provider-content__char text--bold mb-3">
                            Other locations
                        </div>

                        <div
                            v-for="item in provider.locations.slice(1)"
                            class="provider-content__char-line"
                        >
                            {{ item.address.string }}
                            <a
                                v-bind:href="`${item.address.map}`"
                                class="text--regular text--styled-link"
                                target="_blank"
                            >
                                <br><span>view on a map</span>
                                <img src="images/arrow-right.svg" alt="">
                            </a>
                            &mdash;
                            <a
                                v-bind:href="`tel: ${item.phone}`"
                                class="text--styled-link"
                            >
                                <img src="images/phone-icon.svg" alt="">
                                {{ item.phone }}
                            </a>
                        </div>
                    </div>
                </template>

                <div
                    v-if="provider.network &&  provider.network.legal.provider"
                    class="mt-5 pt-5"
                    v-html="provider.network.legal.provider"
                />
            </div>
        </div>
    </div>
</template>

<script>
import api from '../api';

export default {
    name: 'ProviderPage',

    data() {
        return {
            provider: {},
            prevRoute: ''
        }
    },

    beforeRouteEnter(to, from, next) {
        next(vm => {
            vm.prevRoute = from
        })
    },

    async created() {
        await this.fetchData();
    },

    methods: {
        async fetchData() {
            const {data} = await api.getProvider(this.$route.params.id);
            this.provider = data.data;
        }
    },

    computed: {
        primaryAddress() {
            if (this.provider.locations) {
                return this.provider.locations.find(location => location.is_primary);
            }
            return '';
        },

        specialities() {
            if (this.provider.specialities) {
                return this.provider.specialities.join(', ');
            }
            return '';
        },

        languages() {
            if (this.provider.languages) {
                return this.provider.languages.join(', ');
            }
            return '';
        },
    }
}
</script>

<style lang="scss" scoped>

</style>
