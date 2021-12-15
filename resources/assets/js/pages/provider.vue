<template>
    <div>
        <div class="page-header page-header--bg-color">
            <div class="container">
                <p class="page-header__title heading">
                    {{ provider.label }}{{ provider.degree ? `, ${provider.degree}` : '' }}
                </p>
                <p
                    class="page-header__sub-title">
                    Accepting new patients
                </p>
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div
                            v-if="provider.locations"
                            class="page-header__char"
                        >
                            <img v-bind:src="'/images/map-pin-white.svg'" alt="">
                            <div
                                v-if="primaryAddress"
                                class="page-header__char-title"
                            >
                                <strong>Primary address</strong>
                                <br>
                                {{ primaryAddress.address.line_1 }}, {{ primaryAddress.address.line_2 ? primaryAddress.address.line_2 + ', ' : '' }} {{ primaryAddress.address.city }}, {{ primaryAddress.address.state.label }}, {{ primaryAddress.address.zip }}
                                <br>
                                <a
                                    v-bind:href="`https://maps.google.com/?q=${primaryAddress.address.addr_line_1},${primaryAddress.address.city},${primaryAddress.address.state.label},${primaryAddress.address.zip}`"
                                    class="mt-2 d-inline-block text--styled-link"
                                >
                                    View on a map
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
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
                        class="col-md-4"
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
                            class="provider-content__item"
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
                            class="provider-content__item mt-4"
                        >
                            <div class="provider-content__char text--bold">
                                Network name
                            </div>
                            <div class="provider-content__char text--regular">
                                {{ provider.network.label }}
                            </div>
                        </div>
                    </div>
                    <div
                        v-if="languages || true"
                        class="col-md-4"
                    >
                        <div
                            v-if="languages"
                            class="provider-content__item"
                        >
                            <div class="provider-content__char text--bold">
                                Languages spoken
                            </div>
                            <div class="provider-content__char text--regular">
                                {{ languages }}
                            </div>
                        </div>
                        <div
                            class="provider-content__item mt-4"
                        >
                            <div class="provider-content__char text--bold">
                                Affiliated hospitals
                            </div>
                            <div class="provider-content__char text--regular">
                                Affiliated hospital 1 name
                            </div>
                            <div class="provider-content__char text--regular">
                                Affiliated hospital 2 name
                            </div>
                            <div class="provider-content__char text--regular">
                                Affiliated hospital 3 name
                            </div>
                            <div class="provider-content__char text--regular">
                                Affiliated hospital 4 name
                            </div>
                            <div class="provider-content__char text--regular">
                                Affiliated hospital 5 name
                            </div>
                            <div class="provider-content__char text--regular">
                                Affiliated hospital 6 name
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
                            {{ item.address.line_1 }}, {{ item.address.line_2 ? item.address.line_2 + ', ' : '' }} {{ item.address.city }}, {{ item.address.state.label }}, {{ item.address.zip }}
                            <a
                                v-bind:href="`https://maps.google.com/?q=${item.address.line_1},${item.address.city},${item.address.state.label},${item.address.zip}`"
                                class="text--regular text--styled-link"
                                target="_blank"
                            >
                                <span>view on a map</span>
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
            if (this.provider.specialities.length) {
                return this.provider.specialities.map(spec => spec.label).join(', ');
            }
            return '';
        },

        languages() {
            if (this.provider.languages.length) {
                return this.provider.languages.map(lang => lang.label).join(', ');
            }
            return '';
        },
    }
}
</script>

<style lang="scss" scoped>

</style>