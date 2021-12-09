<template>
    <div>
        <div class="page-header page-header--bg-color">
            <div class="container">
                <p class="page-header__title heading">
                    {{ provider.first_name }} {{ provider.middle_name }} {{ provider.last_name }}, {{ provider.credentials }}
                </p>
                <p
                    class="page-header__sub-title">
                    Accepting new patients
                </p>
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="page-header__char">
                            <img src="../../img/svg/map-pin.svg" alt="">
                            <div class="page-header__char-title">
                                <strong>Primary address</strong>
                                <br>
                                {{ provider.locations[0].addr_line_1 }}, {{ provider.locations[0].addr_line_2 ? provider.locations[0].addr_line_2 + ', ' : '' }} {{ provider.locations[0].city }}, {{ provider.locations[0].state }}, {{ provider.locations[0].zip }}
                                <br>
                                <a href="#" class="mt-2 d-inline-block text--styled-link">View on a map</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="page-header__char">
                            <img src="../../img/svg/map-pin.svg" alt="">
                            <div class="page-header__char-title">
                                <strong>Phone number</strong>
                                <br>
                                <a href="#">813-926-5454</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="page-header__char">
                            <img src="../../img/svg/map-pin.svg" alt="">
                            <div class="page-header__char-title">
                                <strong>Specialties</strong>
                                <br>
                                {{ provider.specialty }}
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
                            v-if="provider.provider_type"
                            class="provider-content__item"
                        >
                            <div class="provider-content__char text--bold">
                                Provider type
                            </div>
                            <div class="provider-content__char text--regular">
                                {{ provider.provider_type }}
                            </div>
                        </div>
                        <div
                            v-if="provider.gender"
                            class="provider-content__item mt-4"
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
                            class="provider-content__item mt-4"
                        >
                            <div class="provider-content__char text--bold">
                                NPI ID
                            </div>
                            <div class="provider-content__char text--regular">
                                {{ provider.npi }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div
                            class="provider-content__item"
                        >
                            <div class="provider-content__char text--bold">
                                Website
                            </div>
                            <div class="provider-content__char text--regular">
                                <a href="testwebsite.com">testwebsite.com</a>
                            </div>
                        </div>
                        <div
                            v-if="provider.practice_name"
                            class="provider-content__item mt-4"
                        >
                            <div class="provider-content__char text--bold">
                                Network name
                            </div>
                            <div class="provider-content__char text--regular">
                                {{ provider.practice_name }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div
                            class="provider-content__item"
                        >
                            <div class="provider-content__char text--bold">
                                Languages spoken
                            </div>
                            <div class="provider-content__char text--regular">
                                English, Arabic, Bengali, Russian, Portuguese
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
<!--                    <div class="col-xl-3">-->
<!--                        <div class="provider-content__item provider-content__item&#45;&#45;location">-->
<!--                            <div class="provider-content__char text&#45;&#45;regular">-->
<!--                                Address-->
<!--                            </div>-->
<!--                            <div-->
<!--                                v-if="provider.locations && provider.locations.length"-->
<!--                                class="provider-content__char provider-content__char&#45;&#45;location"-->
<!--                            >-->
<!--                                <img src="../../img/svg/map-pin.svg" alt="">-->
<!--                                <span class="provider-content__char-text">-->
<!--                                    <span class="text&#45;&#45;light">-->
<!--                                        {{ provider.locations[0].addr_line_1 }}, {{ provider.locations[0].addr_line_2 ? provider.locations[0].addr_line_2 + ', ' : '' }} {{ provider.locations[0].city }}, {{ provider.locations[0].state }}, {{ provider.locations[0].zip }}-->
<!--                                    </span>-->

<!--                                    <a-->
<!--                                        v-bind:href="`https://maps.google.com/?q=${provider.locations[0].addr_line_1},${provider.locations[0].city},${provider.locations[0].state},${provider.locations[0].zip}`"-->
<!--                                        class="provider-content__char-location-link text&#45;&#45;bold"-->
<!--                                        target="_blank"-->
<!--                                    >-->
<!--                                        <span>View on a map</span>-->
<!--                                        <img src="../../img/svg/arrow-right.svg" alt="">-->
<!--                                    </a>-->
<!--                                </span>-->
<!--                            </div>-->

<!--                            <div-->
<!--                                v-if="provider.locations && provider.locations[0].practice_phone"-->
<!--                                class="provider-content__item d-xl-none d-block mt-3"-->
<!--                            >-->
<!--                                <div class="provider-content__char text&#45;&#45;regular">-->
<!--                                    Phone number-->
<!--                                </div>-->
<!--                                <div class="provider-content__char provider-content__char&#45;&#45;phone text&#45;&#45;light">-->
<!--                                    <img src="../../img/svg/phone-icon.svg" alt="">-->
<!--                                    <a-->
<!--                                        v-bind:href="`tel:${provider.locations[0].practice_phone}`"-->
<!--                                    >-->
<!--                                        {{ provider.locations[0].practice_phone }}-->
<!--                                    </a>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        <div-->
<!--                            class="row"-->
<!--                        >-->
<!--                            <div-->
<!--                                v-if="provider.gender"-->
<!--                                class="col-md-6 col-md-4 mt-lg-5 mt-3">-->
<!--                                <div-->
<!--                                    class="provider-content__item"-->
<!--                                >-->
<!--                                    <div class="provider-content__char text&#45;&#45;regular">-->
<!--                                        Gender-->
<!--                                    </div>-->
<!--                                    <div class="provider-content__char provider-content__char&#45;&#45;location text&#45;&#45;light">-->
<!--                                        {{ provider.gender }}-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->

<!--                            <div-->
<!--                                v-if="provider.age"-->
<!--                                class="col-md-6 col-md-4 mt-lg-5 mt-3">-->
<!--                                <div-->
<!--                                    class="provider-content__item"-->
<!--                                >-->
<!--                                    <div class="provider-content__char text&#45;&#45;regular">-->
<!--                                        Age-->
<!--                                    </div>-->
<!--                                    <div class="provider-content__char provider-content__char&#45;&#45;location text&#45;&#45;light">-->
<!--                                        {{ provider.age }} years-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->

<!--                            <div-->
<!--                                v-if="provider.provider_type"-->
<!--                                class="col-md-6 col-md-4 mt-lg-5 mt-3">-->
<!--                                <div-->
<!--                                    class="provider-content__item"-->
<!--                                >-->
<!--                                    <div class="provider-content__char text&#45;&#45;regular">-->
<!--                                        Provider type-->
<!--                                    </div>-->
<!--                                    <div class="provider-content__char provider-content__char&#45;&#45;location text&#45;&#45;light">-->
<!--                                        {{ provider.provider_type }}-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->

<!--                            <div-->
<!--                                v-if="provider.specialty"-->
<!--                                class="col-md-6 mt-lg-5 mt-3">-->
<!--                                <div-->
<!--                                    class="provider-content__item"-->
<!--                                >-->
<!--                                    <div class="provider-content__char text&#45;&#45;regular">-->
<!--                                        Specialties-->
<!--                                    </div>-->
<!--                                    <div class="provider-content__char provider-content__char&#45;&#45;location text&#45;&#45;light">-->
<!--                                        {{ provider.specialty }}-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="col-xl-4 mt-xl-0 mt-3">-->
<!--                        <div class="row">-->
<!--                            <div-->
<!--                                v-if="provider.locations && provider.locations[0].practice_phone"-->
<!--                                class="col-md-6 mb-lg-5 mb-3 d-xl-block d-none">-->
<!--                                <div class="provider-content__item">-->
<!--                                    <div class="provider-content__char text&#45;&#45;regular">-->
<!--                                        Phone number-->
<!--                                    </div>-->
<!--                                    <div class="provider-content__char provider-content__char&#45;&#45;phone text&#45;&#45;light">-->
<!--                                        <img src="../../img/svg/phone-icon.svg" alt="">-->
<!--                                        <a-->
<!--                                            v-bind:href="`tel:${provider.locations[0].practice_phone}`"-->
<!--                                        >-->
<!--                                            {{ provider.locations[0].practice_phone }}-->
<!--                                        </a>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->

<!--                            <div v-if="provider.practice_name" class="col-md-6 mb-lg-5 mb-3">-->
<!--                                <div-->
<!--                                    class="provider-content__item"-->
<!--                                >-->
<!--                                    <div class="provider-content__char text&#45;&#45;regular">-->
<!--                                        Practice name-->
<!--                                    </div>-->
<!--                                    <div class="provider-content__char provider-content__char&#45;&#45;location text&#45;&#45;light">-->
<!--                                        {{ provider.practice_name }}-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->

<!--                            <div v-if="provider.num_years_experience" class="col-md-6 mb-lg-5 mb-3">-->
<!--                                <div-->
<!--                                    class="provider-content__item"-->
<!--                                >-->
<!--                                    <div class="provider-content__char text&#45;&#45;regular">-->
<!--                                        Experience-->
<!--                                    </div>-->
<!--                                    <div class="provider-content__char provider-content__char&#45;&#45;location text&#45;&#45;light">-->
<!--                                        {{ provider.num_years_experience }} {{ provider.num_years_experience === 1 && provider.num_years_experience === 0 ? 'year' : 'years' }}-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->

<!--                            <div v-if="provider.medical_school" class="col-md-6 mb-lg-5 mb-3">-->
<!--                                <div-->
<!--                                    class="provider-content__item"-->
<!--                                >-->
<!--                                    <div class="provider-content__char text&#45;&#45;regular">-->
<!--                                        Medical School-->
<!--                                    </div>-->
<!--                                    <div class="provider-content__char provider-content__char&#45;&#45;location text&#45;&#45;light">-->
<!--                                        {{ provider.medical_school }}-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
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
                            {{ item.addr_line_1 }}, {{ item.addr_line_2 ? item.addr_line_2 + ', ' : '' }} {{ item.city }}, {{ item.state }}, {{ item.zip }}
                            <a
                                v-bind:href="`https://maps.google.com/?q=${provider.locations[0].addr_line_1},${provider.locations[0].city},${provider.locations[0].state},${provider.locations[0].zip}`"
                                class="text--regular text--styled-link"
                                target="_blank"
                            >
                                <span>view on a map</span>
                                <img src="images/arrow-right.svg" alt="">
                            </a>
                            &mdash;
                            <a
                                v-bind:href="`tel: ${item.practice_phone}`"
                                class="text--styled-link"
                            >
                                <img src="images/phone-icon.svg" alt="">
                                {{ item.practice_phone }}
                            </a>
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
            this.prevRoute.fullPath !== '/' ? this.$router.push(this.prevRoute) : this.$router.push('/');
        }
    }
}
</script>

<style lang="scss" scoped>

</style>
