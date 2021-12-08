<template>
    <div>
        <div class="page-header page-header--bg-color">
            <div class="container">
                <div class="page-header__back">
                    <a
                        href="#"
                        v-on:click.prevent="goBack"
                        class="page-header__back-link"
                    >
                        <img src="../../img/svg/arrow-left.svg" alt="" class="page-header__back-icon">
                        <span>Back to results</span>
                    </a>
                </div>
                <p class="page-header__title heading">
                    {{ provider.facility_name }}
                </p>
                <div class="page-header__sub-title">
                    Healthcare facility
                </div>
            </div>
        </div>

        <div class="container">
            <div class="provider-content">
                <div class="row mb-3 d-lg-flex d-none">
                    <div class="col-lg-6">
                        <div class="provider-content__char provider-content__char--head provider-content__char--location text--regular">
                            <img class="d-lg-inline-block d-none mr-2" src="../../img/svg/map-pin.svg" alt="">
                            Address
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="provider-content__char provider-content__char--head provider-content__char--location text--regular">
                            <img class="d-lg-inline-block d-none mr-2" src="../../img/svg/phone-icon.svg" alt="">
                            Phone number
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="provider-content__char provider-content__char--head provider-content__char--location text--regular">
                            <img class="d-lg-inline-block d-none mr-2" src="../../img/svg/facility.svg" alt="">
                            Services
                        </div>
                    </div>
                </div>
                <div
                    v-for="item in provider.locations"
                    v-if="provider.locations"
                    class="row mb-4"
                >
                    <div class="col-lg-6 mb-2 mb-lg-0">
                        <div class="provider-content__item">
                            <div class="provider-content__char provider-content__char--location provider-content__char--facility">
                                <img class="d-inline-block d-lg-none" src="../../img/svg/map-pin.svg" alt="">
                                <span class="provider-content__char-text text--light flex-xl-row flex-column">
                                    <span>
                                        {{ item.addr_line_1 }}, {{ item.addr_line_2 ? item.addr_line_2+',' : '' }} {{ item.city }}, {{ item.state }}, {{ item.zip }}
                                    </span>

                                    <a
                                        v-bind:href="`https://maps.google.com/?q=${item.addr_line_1},${item.addr_line_2 ? item.addr_line_2+',' : ''}${item.city},${item.state},${item.zip}`"
                                        class="provider-content__char-location-link ml-xl-3 ml-0"
                                        target="_blank"
                                    >
                                        <span>View on a map</span>
                                        <img src="../../img/svg/arrow-right.svg" alt="">
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 mb-2 mb-lg-0">
                        <div
                            v-if="item.phone_number"
                            class="provider-content__item">
                            <div class="provider-content__char provider-content__char--phone text--light provider-content__char--facility">
                                <img class="d-inline-block d-lg-none" src="../../img/svg/phone-icon.svg" alt="">
                                <a
                                    v-bind:href="`tel:${item.phone_number}`"
                                >
                                    {{ item.phone_number }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div
                            v-if="item.services"
                            class="provider-content__item"
                        >
                            <div class="provider-content__char text--light provider-content__char--facility provider-content__char--facility-serv">
                                <img class="d-inline-block d-lg-none" src="../../img/svg/facility.svg" alt="">
                                {{ item.services }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import api from '../api/mock';

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
        this.provider = await api.getFacility(this.$route.params.id);
    },

    methods: {
        goBack: function() {
            this.prevRoute.fullPath !== '/' ? this.$router.push(this.prevRoute) : this.$router.push('/');
        }
    }
}
</script>

<style scoped>

</style>
