<template>
    <div>
        <div class="page-header page-header--browsing">
            <div class="container">
                <div class="page-header__title">
                    Browse providers
                </div>
                <div class="browsing-tabs">
                    <div
                        v-for="network in networks"
                        class="browsing-tabs__item"
                        v-bind:key="network.id"
                        v-bind:class="{
                            'is-active': network.id === selectedNetwork
                        }"
                        v-on:click="selectNetwork(network.id)"
                    >
                        {{ network.search_label }}
                    </div>
                </div>
            </div>
        </div>
        <div class="pt-5 pb-5">
            <div class="container">
                <div class="browsing-tabs browsing-tabs--small">
                    <div
                        class="browsing-tabs__item"
                        v-on:click="setBrowse('city')"
                        v-bind:class="{
                            'is-active': browseBy === 'city'
                        }"
                    >
                        By City
                    </div>
                    <div
                        class="browsing-tabs__item"
                        v-on:click="setBrowse('speciality')"
                        v-bind:class="{
                            'is-active': browseBy === 'speciality'
                        }"
                    >
                        By Speciality
                    </div>
                </div>
                <div
                    v-if="browseBy === 'city'"
                    class="browsing-container"
                >
                    <ul
                        v-for="citiesList in chunkedCities"
                        class="browsing-container__list"
                    >
                        <li
                            v-for="(item, index) in citiesList"
                            class="browsing-container__list-item"
                            v-bind:key="index"
                        >
                            <router-link
                                v-bind:to="{path: '/results', query: {keywords: item,network_id: selectedNetwork, scope: 'city'}}"
                                class="browsing-container__list-link"
                            >
                                {{ item }}
                            </router-link>
                        </li>
                    </ul>
                </div>
                <div
                    v-if="browseBy === 'speciality'"
                    class="browsing-container"
                >
                    <ul
                        class="browsing-container__list"
                    >
                        <li
                            v-for="(item, index) in specialities"
                            class="browsing-container__list-item"
                            v-bind:key="index"
                        >
                            <router-link
                                v-bind:to="{path: '/results', query: {keywords: item,network_id: selectedNetwork, scope: 'speciality'}}"
                                class="browsing-container__list-link"
                            >
                                {{ item }}
                            </router-link>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import api from '../api';
import chunk from "../utility/chunk";

export default {
    name: 'BrowsingPage',

    data() {
        return {
            networks: [],
            selectedNetwork: '',
            cities: [],
            specialities: [],
            browseBy: 'city' // 'city' or 'speciality'
        }
    },

    async created() {
        this.networks = window.networks;
        this.selectedNetwork = this.networks[0].id;
    },

    watch: {
        selectedNetwork: {
            handler: async function() {
                await this.fetchData();
            }
        },
    },

    computed: {
        chunkedCities: function() {
            return chunk(this.cities, Math.round(this.cities.length / 3.5));
        },

        chunkedSpecialities: function() {
            return chunk(this.specialities, Math.round(this.specialities.length / 3.5));
        },
    },

    methods: {
        setBrowse(type) {
            this.browseBy = type;
        },

        selectNetwork(id) {
            this.selectedNetwork = id;
        },

        async fetchData() {
            const cities = await api.getCities(this.selectedNetwork);
            this.cities = cities.data.data;
            const specialities = await api.getSpecialities(this.selectedNetwork);
            this.specialities = specialities.data.data;
        }
    },
};
</script>
