<template>
    <div
        class="search-block"
        v-bind:class="{
            'search-block--header': isResults
        }"
    >
        <div class="container">
            <div class="search-block__inner">

                <div class="special-terms">
                    <div class="special-terms__container">
                        <strong class="special-terms__title">
                            For plans issued in Missiouri:
                        </strong>
                        <i>(Search within the Health Link Open Access III directory first. If the desired provider is not located there, search within the Freedom Network Select directory).</i>
                        <ul class="special-terms__list">
                            <li class="list-item">
                                <strong class="list-item__heading">HealthLink Open Access III:</strong>
                                <a class="list-item__link" href="https://www.healthlink.com/ipf" target="_blank">
                                    https://www.healthlink.com/ipf
                                </a>
                            </li>
                            <li class="list-item">
                                <strong class="list-item__heading">Freedom Network Select:</strong>
                                <a class="list-item__link" href="https://phpkc.com/provider-search/" target="_blank">
                                    https://phpkc.com/provider-search/
                                </a>
                            </li>
                            <li class="list-item">
                                <strong class="list-item__heading">Pediatric Vision Providers:</strong>
                                <a class="list-item__link" href="https://www.vsp.com/choiceonly" target="_blank">
                                    https://www.vsp.com/choiceonly
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="special-terms__container">
                        <strong class="special-terms__title">
                            For plans issued in Indiana:
                        </strong>
                        <ul class="special-terms__list">
                            <li class="list-item">
                                <strong class="list-item__heading">EncoreCombined:</strong>
                                <a class="list-item__link" href="https://encoreconnect.com/encbgnrc/" target="_blank">
                                    https://encoreconnect.com/encbgnrc/
                                </a>
                            </li>
                            <li class="list-item">
                                <strong class="list-item__heading">First Health:</strong>
                                <a class="list-item__link" href="https://www.myfirsthealth.com/" target="_blank">
                                    https://www.myfirsthealth.com/
                                </a>
                            </li>
                            <li class="list-item">
                                <strong class="list-item__heading">Pediatric Vision Providers:</strong>
                                <a class="list-item__link" href="https://www.vsp.com/choiceonly" target="_blank">
                                    https://www.vsp.com/choiceonly
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="special-terms__container">
                        <strong class="special-terms__title">
                            For plans issued in Florida:
                        </strong>
                        <ul class="special-terms__list">
                            <li class="list-item">
                                <strong class="list-item__heading">Centivo:</strong>
                                <a class="list-item__link" href="https://allstatebenefits.centivo.com/" target="_blank">
                                    https://allstatebenefits.centivo.com/
                                </a>
                            </li>
                            <li class="list-item">
                                <strong class="list-item__heading">First Health:</strong>
                                <a class="list-item__link" href="https://www.myfirsthealth.com/" target="_blank">
                                    https://www.myfirsthealth.com/
                                </a>
                            </li>
                            <li class="list-item">
                                <strong class="list-item__heading">Pediatric Vision Providers:</strong>
                                <a class="list-item__link" href="https://www.vsp.com/choiceonly" target="_blank">
                                    https://www.vsp.com/choiceonly
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="special-terms__container">
                        <strong class="special-terms__title">
                            For plans issued in Texas, please use the directory below.
                        </strong>
                        <ul class="special-terms__list">
                            <li class="list-item">
                                <a class="list-item__link" href="https://www.healthlink.com/ipf" target="_blank">
                                    https://www.healthlink.com/ipf
                                </a>
                            </li>
                            <li class="list-item">
                                <a class="list-item__link" href="https://www.vsp.com/choiceonly" target="_blank">
                                    https://www.vsp.com/choiceonly
                                </a>
                            </li>
                        </ul>
                    </div>

                </div>

                <div class="search-block__title">
                    {{ title }}
                </div>

                <div class="search-block__form">
                    <div v-if="isResults" class="search-block__network-label">
                        Searching: {{ selectedNetworkLabel }}
                    </div>
                    <div class="search-block__form-inner">
                        <input type="text" class="search-block__input" placeholder="Search by doctor or facility name, specialty or address" v-model="searchQuery">
                        <div
                            v-if="!isResults"
                            class="search-block__form-descr"
                        >
                            <span>Not sure what to search for?<br><router-link to="/allstate">Browse providers & facilities</router-link></span>
                        </div>
                        <div
                            v-if="isResults"
                            class="search-block__submit"
                        >
                            <button
                                type="submit"
                                v-bind:disabled="!canSearch"
                                class="button button--primary"
                                v-on:click="newSearch"
                            >Search</button>
                        </div>
                        <router-link
                            v-if="isResults"
                            to="/allstate"
                            class="text--styled-link search-block__browse-all"
                        >
                            Browse all
                        </router-link>
                    </div>
                </div>

                <div
                    v-if="!isResults"
                    class="search-block__network"
                >
                    <div class="search-block__network-title heading heading--lg">
                        Select your network
                    </div>
                    <div class="search-block__network-container">
                        <div class="search-block__network-row">
                            <div
                                v-for="network in networks"
                                class="search-block__network-item"
                                v-bind:key="network.id"
                                v-on:click="setNetwork(network.id)"
                                v-bind:class="{
                                    'is-selected': selectedNetwork === network.id
                                }"
                            >
                                <div class="search-block__network-item-head">
                                    <div class="search-block__network-item-label">
                                        {{ network.search_label }}
                                    </div>
                                    <div class="search-block__network-item-head-inner">
                                        <div class="search-block__network-item-logo">
                                            <img v-bind:src="'/images/logo_vertical.png'" alt="">
                                        </div>
                                        <div
                                            class="search-block__network-item-text"
                                            v-html="network.search_sublabel"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    v-if="!isResults"
                    class="search-block__submit"
                >
                    <button
                        type="submit"
                        v-bind:disabled="!canSearch"
                        class="button button--primary"
                        v-on:click="newSearch"
                    >Search</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import chunk from '../utility/chunk';

export default {
    name: 'SearchBlock',

    props: {
        isInner: {
            type: Boolean,
            required: false,
            default: false
        },

        browsingFilters: {
            type: Boolean,
            required: false,
            default: false
        },

        title: {
            type: String,
            required: false,
            default: ''
        },

        subTitle: {
            type: String,
            required: false,
            default: ''
        },

        caption: {
            type: String,
            required: false,
            default: ''
        },

        isResults: {
            type: Boolean,
            required: false,
            default: false
        },

        backLink: {
            type: String,
            required: false,
            default: ''
        },

        backLinkText: {
            type: String,
            required: false,
            default: ''
        },
    },

    data() {
        return {
            searchQuery: '',
            loading: false,
            windowWidth: window.innerWidth,
            networks: [],
            selectedNetwork: ''
        }
    },

    mounted() {
        window.onresize = () => {
            this.windowWidth = window.innerWidth
        }
        this.networks = window.networks;

        if (this.$route.query.network_id) {
            this.selectedNetwork = this.$route.query.network_id;
        }

        if (this.$route.query.keywords) {
            this.searchQuery = this.$route.query.keywords;
        }
    },

    computed: {
        chunkedListItems: function() {
            return chunk(this.browsingList, Math.round(this.browsingList.length / 3.5));
        },

        canSearch: function() {
            return this.searchQuery && this.selectedNetwork;
        },

        isMobile: function() {
            return this.windowWidth < 768;
        },

        selectedNetworkLabel: function() {
            if (this.networks.length) {
                return this.networks.find(network => network.id == this.selectedNetwork).searching_label || '';
            }
        },

        selectedIndex() {
            if (this.networks.length) {
                return this.networks.indexOf(this.networks.find(network => network.id == this.selectedNetwork));
            }
        }
    },

    methods: {
        newSearch: async function() {
            if (this.searchQuery) this.$router.push({path: '/results', query: {
                    ...this.$route.query,
                    keywords: this.searchQuery,
                    network_id: this.selectedNetwork,
            }}).catch(()=>{});
        },

        setNetwork: function(id) {
            this.selectedNetwork = id;
            this.$emit('select-network', this.networks.find(network => network.id === this.selectedNetwork));
        }
    },
}
</script>
