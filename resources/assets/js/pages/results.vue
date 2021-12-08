<template>
    <div class="pb-4">
        <search-block
            is-header
            back-link="/allstate"
            back-link-text="Start over"
            v-bind:match-query="matchQuery"
            v-on:query-changed="searchQuery = $event"
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
                        v-bind:class="{
                            'is-odd': index%2 !== 0
                        }"
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
            </div>
        </div>
    </div>
</template>

<script>
import mock from '../api/mock';
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
            providers: [],
            searchQuery: '',
            loading: false,
            matchQueryOptions: [
                {
                    label: 'All fields',
                    value: 'search'
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
            matchQuery: 'search',
        }
    },

    async mounted() {
        this.matchQuery = Object.keys(this.$route.query)[0];
    },

    watch: {
        '$route.query': {
            handler: async function(){
                this.matchQuery = Object.keys(this.$route.query)[0];

                let queryKey = Object.keys(this.$route.query)[0];
                let queryVal = this.$route.query[queryKey];

                await this.searchProviders(queryKey, queryVal);
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
        }
    },

    methods: {
        searchProviders: async function(queryKey, queryVal) {
            this.loading = true;
            this.providers = await mock.search(queryKey, queryVal).then(providers => {
                return providers;
            });
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
        }
    }
}
</script>
