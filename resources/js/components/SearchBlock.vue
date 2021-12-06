<template>
    <div
        class="search-block"
        v-bind:class="{
            'search-block--with-filters': browsingFilters,
            'search-block--header': isHeader,
            'search-block--inner-page': isInner
        }"
    >
        <div class="search-block__inner">
            <div class="container">
                <div
                    v-if="backLink"
                    class="search-block__back"
                >
                    <router-link
                        v-bind:to="backLink"
                        class="search-block__back-link"
                    >
                        <img src="../../images/arrow-left.svg" alt="" class="search-block__back-icon">
                        <span>
                            {{ backLinkText }}
                        </span>
                    </router-link>
                </div>
                <p
                    v-if="title"
                    class="search-block__title heading color-white"
                >
                    {{ title }}
                </p>
                <p
                    v-if="subTitle"
                    class="search-block__sub-title color-white"
                >
                    {{ subTitle }}
                </p>
                <div class="search-block__form-wrap">
                    <div class="search-block__form-header">
                        <div class="search-block__form-logo">
                            <img src="../../images/asce_st_vincents_logo.png" alt="">
                        </div>
                        <div class="search-block__form-header-right">
                            <p class="search-block__form-title">
                                Ascension St. Vincent's Physician Alliance network search
                            </p>
                            <p class="search-block__form-sub-title">
                                For group PPO and Advantage plans
                            </p>
                        </div>
                    </div>
                    <form v-on:submit.prevent="newSearch">
                        <div class="search-block__form">
                            <input type="text" class="search-block__input search-block__input--desktop" placeholder="Search by doctor or facility name, specialty or address" v-model="searchQuery">
                            <input type="text" class="search-block__input search-block__input--mobile" placeholder="Name, facility, specialty or address" v-model="searchQuery">
                            <div class="search-block__submit">
                                <button
                                    type="submit"
                                    class="button button--secondary"
                                    v-bind:disabled="!canSearch"
                                >Search</button>
                            </div>
                        </div>
                    </form>
                    <div
                        v-if="caption"
                        class="search-block__form-caption"
                    >
                        {{ caption }}
                    </div>
                    <div
                        v-if="!browsingFilters"
                        class="search-block__form-descr"
                    >
                        <span>Need inspiration? <router-link to="/provider-search/st-vincents">Browse providers</router-link></span>
                    </div>
                    <template v-else>
                        <div class="search-block__filters search-block__filters--desktop">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="search-block__filters-label">
                                        Browse providers
                                    </div>
                                    <div class="search-block__filters-items">
                                        <div class="search-block__filters-item">
                                            <input type="radio" name="browse_by" id="browse-by-city" value="provider_city" v-model="browseBy">
                                            <label for="browse-by-city">
                                                City
                                            </label>
                                        </div>
                                        <div class="search-block__filters-item">
                                            <input type="radio" name="browse_by" id="browse-by-speciality" value="provider_specialty" v-model="browseBy">
                                            <label for="browse-by-speciality">
                                                Speciality
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 offset-md-1">
                                    <div class="search-block__filters-label">
                                        Browse facilities
                                    </div>
                                    <div class="search-block__filters-items">
                                        <div class="search-block__filters-item">
                                            <input type="radio" name="browse_by" id="browse-facility-by-city" value="facility_city" v-model="browseBy">
                                            <label for="browse-facility-by-city">
                                                City
                                            </label>
                                        </div>
                                        <div class="search-block__filters-item">
                                            <input type="radio" name="browse_by" id="browse-facility-by-type" value="facility_services" v-model="browseBy">
                                            <label for="browse-facility-by-type">
                                                Services
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="search-block__filters search-block__filters--mobile">
                            <div class="search-block__filters-items">
                                <div class="search-block__filters-item">
                                    <input type="radio" name="browse_tab" id="tab-doctors" value="browse_doctors" v-model="browseTab">
                                    <label for="tab-doctors">
                                        Browse providers
                                    </label>
                                </div>
                                <div class="search-block__filters-item">
                                    <input type="radio" name="browse_tab" id="tab-facilities" value="browse_facilities" v-model="browseTab">
                                    <label for="tab-facilities">
                                        Browse facilities
                                    </label>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
            <template v-if="browsingFilters && isMobile">
                <div
                    v-if="browseTab === 'browse_doctors'"
                    class="search-block__tab-content"
                >
                    <div class="search-block__tab-filter">
                        <input type="radio" name="browse_by" id="browse-by-city-tab" value="provider_city" v-model="browseBy">
                        <label for="browse-by-city-tab">
                            City
                        </label>
                    </div>
                    <div class="search-block__tab-filter">
                        <input type="radio" name="browse_by" id="browse-by-speciality-tab" value="provider_specialty" v-model="browseBy">
                        <label for="browse-by-speciality-tab">
                            Speciality
                        </label>
                    </div>
                </div>
                <div
                    v-if="browseTab === 'browse_facilities'"
                    class="search-block__tab-content"
                >
                    <div class="search-block__tab-filter">
                        <input type="radio" name="browse_by" id="browse-facility-by-city-tab" value="facility_city" v-model="browseBy">
                        <label for="browse-facility-by-city-tab">
                            City
                        </label>
                    </div>
                    <div class="search-block__tab-filter">
                        <input type="radio" name="browse_by" id="browse-facility-by-type-tab" value="facility_services" v-model="browseBy">
                        <label for="browse-facility-by-type-tab">
                            Services
                        </label>
                    </div>
                </div>
            </template>
        </div>

        <div
            v-if="browsingFilters"
            class="search-block__content"
            v-bind:class="{
                'is-loading': loading
            }"
        >
            <div class="container">
                <div class="search-block__content-inner">
                    <ul
                        v-for="listItems in chunkedListItems"
                        class="search-block__filter-list"
                    >
                        <li v-for="item in listItems">
                            <router-link
                                v-bind:to="{path: '/provider-search/results', query: {[browseBy]: item}}"
                                class="text--styled-link"
                            >
                                {{ item }}
                            </router-link>
                        </li>
                    </ul>
                </div>
            </div>

            <div
                v-if="loading"
                class="search-block__loader"
            >
                <div
                    class="spinner-border text-warning"
                    role="status"
                >
                    <span
                        class="sr-only"
                        role="status"
                    >
                        Loading...
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import api from '../api/mock/index';
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
            if (this.searchQuery) this.$router.push({path: '/provider-search/results', query: {search: this.searchQuery}}).catch(()=>{});
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
