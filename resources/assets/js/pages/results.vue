<template>
    <div class="pb-4">
        <search-block
            is-results
            back-link="/allstate"
            back-link-text="Start over"
            v-bind:match-query="matchQuery"
            v-on:query-changed="searchQuery = $event"
            title="Search results"
        />

        <div class="container">
            <div class="results-container">
                <div class="results-container__header">
                    <div class="results-container__left">
                        <div class="results-container__results-count">
                            Showing {{ providers.length }} {{ latestQueryString ? resultsLabel : '' }} ‘{{ latestQueryString }}’
                        </div>
                        <div class="results-container__print">
                            <a href="javascript:if(window.print)window.print()" class="text--color-white text--line-height-fix text--styled-link">Print</a>
                        </div>
                    </div>
                    <div class="results-container__right">
                        <div class="results-container__select">
                            <v-select
                                class="custom-select"
                                v-model="matchQuery"
                                v-bind:options="matchQueryOptions"
                                v-bind:reduce="option => option.value"
                                v-bind:clearable="false"
                                v-bind:searchable="false"
                                v-on:input="newSearch"
                            />
                        </div>
                    </div>
                </div>

                <div
                    class="results-container__body"
                    v-bind:class="{
                        'is-loading': loading,
                        'results-container__body--no-results': !providers.length
                    }"
                >
                    <component
                        v-for="(provider, index) in providers"
                        v-bind:key="index"
                        v-bind:is="getItemTypeComponent(provider.type)"
                        v-bind:item="provider"
                        v-bind:is-even="index%2 !== 0"
                    />

                    <div
                        v-if="loading"
                        class="results-container__loader"
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

                    <template v-if="!providers.length && !loading">
                        <p class="h2 align-center">
                            We couldn't find any results for that search. Please <a href="/contact-us.php" class="text--styled-link">contact us</a> if you need further assistance.
                        </p>
                    </template>
                </div>

                <div class="results-container__pagination">
                    <div class="pagination">
                        <div
                            v-for="index in paginationItems"
                            class="pagination__item"
                            v-bind:key="index"
                            v-bind:class="{
                                'is-active': index === currentPage
                            }"
                            v-on:click="setPage(index)"
                        >
                            {{ index }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import mock from '../api/mock';
import api from '../api';
import SearchBlock from '../components/SearchBlock';
import ResultsItemDoctor from '../components/ResultsContainer/ResultsItemDoctor';
import ResultsItemFacility from '../components/ResultsContainer/ResultsItemFacility';

export default {
    name: 'ResultsPage',

    components: {
        SearchBlock,
        ResultsItemDoctor
    },

    data() {
        return {
            currentPage: 1,
            providers: [],
            providersMeta: {},
            searchQuery: '',
            loading: false,
            matchQueryOptions: [
                {
                    label: 'All fields',
                    value: 'keywords'
                },
                {
                    label: 'Provider name',
                    value: 'provider_name'
                },
                {
                    label: 'Provider city',
                    value: 'provider_city'
                },
                {
                    label: 'Provider specialty',
                    value: 'provider_specialty'
                },
                {
                    label: 'Facility city',
                    value: 'facility_city'
                },
                {
                    label: 'Facility services',
                    value: 'facility_services'
                }
            ],
            matchQuery: 'keywords',
        }
    },

    async mounted() {
        this.matchQuery = Object.keys(this.$route.query)[0];
    },

    watch: {
        '$route.query': {
            handler: async function(){
                this.matchQuery = Object.keys(this.$route.query)[0];

                // let queryKey = Object.keys(this.$route.query)[0];
                // let queryVal = this.$route.query[queryKey];
                if (this.$route.query.page) {
                    this.currentPage = parseInt(this.$route.query.page);
                }

                await this.searchProviders(this.$route.query);
            },
            immediate: true
        },
    },

    computed: {
        latestQueryKey: function() {
            return Object.keys(this.$route.query)[0];
        },

        latestQueryString: function() {
            return this.$route.query[this.latestQueryKey]
        },

        resultsLabel: function() {
            return this.providers.length === 1 ? 'result for' : 'results for';
        },

        paginationItems: function() {
            return Math.ceil(this.providersMeta.total / this.providersMeta.per_page)
        }
    },

    methods: {
        searchProviders: async function(query) {
            this.loading = true;
            const {data} = await api.search(query);
            this.providers = data.data;
            this.providersMeta = data.meta;
            this.loading = false;
        },

        newSearch: async function() {
            this.$router.push({query: {[this.matchQuery]: this.searchQuery}}).catch(()=>{});
        },

        getItemTypeComponent(type) {
            if (type === 'provider') {
                return ResultsItemDoctor;
            } else if (type === 'facility') {
                return ResultsItemFacility;
            }
        },

        setPage(page) {
            this.$router.push({query: {...this.$route.query, page: page}}).catch(()=>{});
        }
    }
}
</script>
