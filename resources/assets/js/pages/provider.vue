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
                    {{ provider.first_name }} {{ provider.middle_name }} {{ provider.last_name }}, {{ provider.credentials }}
                </p>
                <p
                    v-if="provider.accepting_new_patients === 'Yes' || provider.accepting_new_patients === 'No'"
                    class="page-header__sub-title">
                    {{ provider.accepting_new_patients ? 'Accepting new patients' : 'Not accepting new patients' }}
                    <img
                        v-if="provider.accepting_new_patients === 'Yes'"
                        src="../../img/svg/check-circle-white.svg"
                        class="page-header__header-icon"
                    >
                    <img
                        v-if="provider.accepting_new_patients === 'No'"
                        src="../../img/svg/x-circle-white.svg"
                        class="page-header__header-icon"
                    >
                </p>
                <div
                    v-if="provider.locations && provider.locations.length"
                    class="page-header__text">
                    Here’s the information we have for {{ provider.first_name }} {{ provider.middle_name }}
                    {{ provider.last_name }}.
                    Provider information and availability change frequently, so we advise you to call
                    <a v-bind:href="`tel:${provider.locations[0].practice_phone}`">{{ provider.locations[0].practice_phone }}</a> to confirm availability for
                    your plan.
                </div>
            </div>
        </div>
        <div class="container">
            <div class="provider-content">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="provider-content__item provider-content__item--location">
                            <div class="provider-content__char text--regular">
                                Address
                            </div>
                            <div
                                v-if="provider.locations && provider.locations.length"
                                class="provider-content__char provider-content__char--location"
                            >
                                <img src="../../img/svg/map-pin.svg" alt="">
                                <span class="provider-content__char-text">
                                    <span class="text--light">
                                        {{ provider.locations[0].addr_line_1 }}, {{ provider.locations[0].addr_line_2 ? provider.locations[0].addr_line_2 + ', ' : '' }} {{ provider.locations[0].city }}, {{ provider.locations[0].state }}, {{ provider.locations[0].zip }}
                                    </span>

                                    <a
                                        v-bind:href="`https://maps.google.com/?q=${provider.locations[0].addr_line_1},${provider.locations[0].city},${provider.locations[0].state},${provider.locations[0].zip}`"
                                        class="provider-content__char-location-link text--bold"
                                        target="_blank"
                                    >
                                        <span>View on a map</span>
                                        <img src="../../img/svg/arrow-right.svg" alt="">
                                    </a>
                                </span>
                            </div>

                            <div
                                v-if="provider.locations && provider.locations[0].practice_phone"
                                class="provider-content__item d-xl-none d-block mt-3"
                            >
                                <div class="provider-content__char text--regular">
                                    Phone number
                                </div>
                                <div class="provider-content__char provider-content__char--phone text--light">
                                    <img src="../../img/svg/phone-icon.svg" alt="">
                                    <a
                                        v-bind:href="`tel:${provider.locations[0].practice_phone}`"
                                    >
                                        {{ provider.locations[0].practice_phone }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div
                            class="row"
                        >
                            <div
                                v-if="provider.gender"
                                class="col-md-6 col-md-4 mt-lg-5 mt-3">
                                <div
                                    class="provider-content__item"
                                >
                                    <div class="provider-content__char text--regular">
                                        Gender
                                    </div>
                                    <div class="provider-content__char provider-content__char--location text--light">
                                        {{ provider.gender }}
                                    </div>
                                </div>
                            </div>

                            <div
                                v-if="provider.age"
                                class="col-md-6 col-md-4 mt-lg-5 mt-3">
                                <div
                                    class="provider-content__item"
                                >
                                    <div class="provider-content__char text--regular">
                                        Age
                                    </div>
                                    <div class="provider-content__char provider-content__char--location text--light">
                                        {{ provider.age }} years
                                    </div>
                                </div>
                            </div>

                            <div
                                v-if="provider.provider_type"
                                class="col-md-6 col-md-4 mt-lg-5 mt-3">
                                <div
                                    class="provider-content__item"
                                >
                                    <div class="provider-content__char text--regular">
                                        Provider type
                                    </div>
                                    <div class="provider-content__char provider-content__char--location text--light">
                                        {{ provider.provider_type }}
                                    </div>
                                </div>
                            </div>

                            <div
                                v-if="provider.specialty"
                                class="col-md-6 mt-lg-5 mt-3">
                                <div
                                    class="provider-content__item"
                                >
                                    <div class="provider-content__char text--regular">
                                        Specialties
                                    </div>
                                    <div class="provider-content__char provider-content__char--location text--light">
                                        {{ provider.specialty }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 mt-xl-0 mt-3">
                        <div class="row">
                            <div
                                v-if="provider.locations && provider.locations[0].practice_phone"
                                class="col-md-6 mb-lg-5 mb-3 d-xl-block d-none">
                                <div class="provider-content__item">
                                    <div class="provider-content__char text--regular">
                                        Phone number
                                    </div>
                                    <div class="provider-content__char provider-content__char--phone text--light">
                                        <img src="../../img/svg/phone-icon.svg" alt="">
                                        <a
                                            v-bind:href="`tel:${provider.locations[0].practice_phone}`"
                                        >
                                            {{ provider.locations[0].practice_phone }}
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div v-if="provider.practice_name" class="col-md-6 mb-lg-5 mb-3">
                                <div
                                    class="provider-content__item"
                                >
                                    <div class="provider-content__char text--regular">
                                        Practice name
                                    </div>
                                    <div class="provider-content__char provider-content__char--location text--light">
                                        {{ provider.practice_name }}
                                    </div>
                                </div>
                            </div>

                            <div v-if="provider.num_years_experience" class="col-md-6 mb-lg-5 mb-3">
                                <div
                                    class="provider-content__item"
                                >
                                    <div class="provider-content__char text--regular">
                                        Experience
                                    </div>
                                    <div class="provider-content__char provider-content__char--location text--light">
                                        {{ provider.num_years_experience }} {{ provider.num_years_experience === 1 && provider.num_years_experience === 0 ? 'year' : 'years' }}
                                    </div>
                                </div>
                            </div>

                            <div v-if="provider.medical_school" class="col-md-6 mb-lg-5 mb-3">
                                <div
                                    class="provider-content__item"
                                >
                                    <div class="provider-content__char text--regular">
                                        Medical School
                                    </div>
                                    <div class="provider-content__char provider-content__char--location text--light">
                                        {{ provider.medical_school }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <template v-if="provider.locations && provider.locations.length > 1">
                    <div class="separator" />
                    <div
                        class="provider-content__item"
                    >
                        <div class="provider-content__char text--regular mb-3">
                            Other locations
                        </div>

                        <div
                            v-for="item in provider.locations.slice(1)"
                            class="row provider-content__char-line">
                            <div class="col-lg-2">
                                <div class="provider-content__char text--light">
                                    {{ item.practice_name }}
                                </div>
                            </div>
                            <div class="col-lg-4 mt-lg-0 mt-2">
                                <div class="provider-content__char provider-content__char--location text--light">
                                    <div class="provider-content__char-text">
                                    <span class="text--light">
                                        {{ item.addr_line_1 }}, {{ item.addr_line_2 ? item.addr_line_2 + ', ' : '' }} {{ item.city }}, {{ item.state }}, {{ item.zip }}
                                    </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 mt-lg-0 mt-2">
                                <a
                                    v-bind:href="`https://maps.google.com/?q=${provider.locations[0].addr_line_1},${provider.locations[0].city},${provider.locations[0].state},${provider.locations[0].zip}`"
                                    class="text--bold text--styled-link"
                                    target="_blank"
                                >
                                    <span>View on a map</span>
                                    <img src="../../img/svg/arrow-right.svg" alt="">
                                </a>
                            </div>
                            <div class="col-lg-2 mt-lg-0 mt-2">
                                <div
                                    v-if="item.practice_phone"
                                    class="provider-content__char provider-content__char--phone provider-content__char--icon-small text--light">
                                    <img src="../../img/svg/phone-icon.svg" alt="">
                                    <a
                                        v-bind:href="`tel: ${item.practice_phone}`"
                                    >
                                        {{ item.practice_phone }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
import api from '../api/mock';

export default {
    name: 'DoctorPage',

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
        this.provider = await api.getDoctor(this.$route.params.id);
    },

    methods: {
        goBack: function () {
            this.prevRoute.fullPath !== '/' ? this.$router.push(this.prevRoute) : this.$router.push('/provider-search/');
        }
    }
}
</script>

<style lang="scss" scoped>

</style>
