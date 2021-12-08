<template>
    <div
        class="search-block"
    >
        <div class="search-block__title">
            {{ title }}
        </div>

        <div class="search-block__form">
            <input type="text" class="search-block__input" placeholder="Search by doctor or facility name, specialty or address" v-model="searchQuery">
            <div class="search-block__form-descr">
                <span>Not sure what to search for?<br><router-link to="/allstate">Browse providers & facilities</router-link></span>
            </div>
        </div>

        <div class="search-block__network">
            <div class="search-block__network-title heading heading--lg">
                Select your network
            </div>
            <div class="search-block__network-container">
                <div class="search-block__network-row">
                    <div class="search-block__network-item">
                        <div class="search-block__network-item-head">
                            <div class="search-block__network-item-logo"></div>
                            <div class="search-block__network-item-text">
                                Secure choice <br>
                                <strong>Broad</strong>
                            </div>
                        </div>
                        <div class="search-block__network-item-label">
                            Medical & dental providers
                        </div>
                    </div>
                    <div class="search-block__network-item">
                        <div class="search-block__network-item-head">
                            <div class="search-block__network-item-logo"></div>
                            <div class="search-block__network-item-text">
                                Secure choice <br>
                                <strong>Select</strong>
                            </div>
                        </div>
                        <div class="search-block__network-item-label">
                            Medical providers
                        </div>
                    </div>
                    <div class="search-block__network-item">
                        <div class="search-block__network-item-head">
                            <div class="search-block__network-item-logo"></div>
                            <div class="search-block__network-item-text">
                                Secure choice <br>
                                <strong>Broad & Select</strong>
                            </div>
                        </div>
                        <div class="search-block__network-item-label">
                            Vision providers
                        </div>
                    </div>
                    <div class="search-block__network-item">
                        <div class="search-block__network-item-head">
                            <div class="search-block__network-item-logo"></div>
                            <div class="search-block__network-item-text">
                                Secure choice <br>
                                <strong>Broad & Select</strong>
                            </div>
                        </div>
                        <div class="search-block__network-item-label">
                            Pharmacy directory
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="search-block__submit">
            <button
                type="submit"
                v-bind:disabled="!canSearch"
                class="button button--primary"
                v-on:click="newSearch"
            >Search</button>
        </div>
    </div>
</template>

<script>
import api from '../api/mock';
import chunk from '../utility/chunk';
import mock from "../api/mock";

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

        isHeader: {
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

        matchQuery: {
            type: String,
            required: false,
            default: 'search'
        }
    },

    data() {
        return {
            searchQuery: '',
            browseBy: 'provider_city',
            browsingList: [],
            loading: false,
            browseTab: 'browse_doctors',
            windowWidth: window.innerWidth
        }
    },

    mounted() {
        window.onresize = () => {
            this.windowWidth = window.innerWidth
        }
    },

    computed: {
        chunkedListItems: function() {
            return chunk(this.browsingList, Math.round(this.browsingList.length / 3.5));
        },

        canSearch: function() {
            return !!this.searchQuery;
        },

        isMobile: function() {
            return this.windowWidth < 768;
        }
    },

    watch: {
        browseTab: {
            handler: function(newVal) {
                this.$nextTick(() => {
                    if (newVal === 'browse_doctors') {
                        this.browseBy = 'provider_city'
                    } else if (newVal === 'browse_facilities') {
                        this.browseBy = 'facility_city'
                    }
                });
            }
        },

        matchQuery: {
            handler: function() {
                this.searchQuery = this.$route.query[this.matchQuery]
            },
            immediate: true
        },

        searchQuery: {
            handler: function() {
                this.$emit('query-changed', this.searchQuery);
            },
            immediate: true
        },

        browseBy: {
            handler: async function() {
                this.loading = true;
                this.browsingList = await api.getItems(this.browseBy);
                this.loading = false;
            },
            immediate: true
        }
    },

    methods: {
        newSearch: async function() {
            if (this.searchQuery) this.$router.push({path: '/results', query: {search: this.searchQuery}}).catch(()=>{});
        },

        searchProviders: async function(query) {
            this.loading = true;
            this.providers = await mock.search(query).then(providers => {
                return providers;
            });
            this.loading = false;
        }
    },
}
</script>
