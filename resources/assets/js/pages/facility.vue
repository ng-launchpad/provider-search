<template>
    <div>
        <div class="page-header page-header--bg-color">
            <div class="container">
                <p class="page-header__title heading">
                    {{ provider.label }}
                </p>
                <div class="page-header__sub-title">
                    Healthcare facility - {{ locationsCount }} location{{ locationsCount > 1 ? 's' : '' }}
                </div>
            </div>
        </div>

        <div class="container">
            <div class="provider-content provider-content--facility">
                <h2 class="mt-0 mb-4">Locations</h2>
                <div class="row mb-2 d-lg-flex d-none">
                    <div class="col-lg-5">
                        <div class="provider-content__char provider-content__char--head provider-content__char--location text--bold">
                            Address
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="provider-content__char provider-content__char--head provider-content__char--phone text--bold">
                            Phone number
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="provider-content__char provider-content__char--head provider-content__char--services text--bold">
                            County
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="provider-content__char provider-content__char--head provider-content__char--type text--bold">
                            Facility type
                        </div>
                    </div>
                </div>
                <div
                    v-for="item in provider.locations"
                    v-if="provider.locations"
                    class="row mb-4"
                >
                    <div class="col-lg-5 mb-2 mb-lg-0">
                        <div class="provider-content__item">
                            <div class="provider-content__char provider-content__char--location provider-content__char--facility">
                                <span class="provider-content__char-text text--light flex-xl-row flex-column">
                                    <span>
                                        {{ item.address.string }}
                                    </span>

                                    <a
                                        v-bind:href="`${item.address.map}`"
                                        class="provider-content__char-location-link ml-xl-3 ml-0"
                                        target="_blank"
                                    >
                                        <br><span>view on a map</span>
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 mb-2 mb-lg-0">
                        <div
                            class="provider-content__item">
                            <div class="provider-content__char provider-content__char--phone text--light provider-content__char--facility">
                                <img class="d-inline-block d-lg-none" src="../../img/svg/phone-icon.svg" alt="">
                                <a
                                    v-bind:href="`tel:${item.phone}`"
                                >
                                    {{ item.phone }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div
                            class="provider-content__item"
                        >
                            <div class="provider-content__char text--light provider-content__char--facility provider-content__char--facility-serv">
                                <img class="d-inline-block d-lg-none" src="../../img/svg/facility.svg" alt="">
                                {{ item.address.county }}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div
                            class="provider-content__item"
                        >
                            <div class="provider-content__char text--light provider-content__char--facility provider-content__char--facility-serv">
                                <img class="d-inline-block d-lg-none" src="../../img/svg/facility.svg" alt="">
                                {{ item.type }}
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="mt-5 separator">

                <div
                    v-if="provider.speciality_groups.length"
                    class="mt-5"
                >
                    <h2 class="mt-0 mb-4">Service providers at this location</h2>

                    <div class="row">
                        <div
                            v-for="(group, index) in provider.speciality_groups"
                            v-bind:key="index"
                            class="col-md-4 mb-4"
                        >
                            <div class="text--md text--bold mb-4">
                                {{ group.label }}
                            </div>
                            <div
                                v-for="(person, index) in group.people"
                                class="mb-3"
                            >
                                <router-link
                                    v-bind:to="`/provider/${person.id}`"
                                    class="text--link text--md"
                                >
                                    {{ person.label }}
                                </router-link>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    v-if="provider.network && provider.network.legal.facility"
                    class="mt-2 pt-2"
                    v-html="provider.network.legal.facility"
                />
            </div>
        </div>
    </div>
</template>

<script>
import api from '../api';

export default {
    name: 'FacilityPage',

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
        locationsCount() {
            if (this.provider.locations) {
                return this.provider.locations.length;
            }
            return 0;
        }
    }
}
</script>
