<template>
    <div class="pb-4">
        <search-block
            is-results
            back-link="/allstate"
            back-link-text="Start over"
            v-on:query-changed="searchQuery = $event"
            title="Search results"
        />

        <div class="container">
            <div class="results-container">
                <div class="results-container__header">
                    <div class="results-container__left">
                        <div class="results-container__results-count">
                            Showing {{ providersMeta.total }} {{ resultsLabel }} ‘{{ queryResultString }}’
                        </div>
                        <div class="results-container__print">
                            <a href="javascript:if(window.print)window.print()" class="text--color-white text--line-height-fix text--styled-link">Print
                                all results</a>
                        </div>
                    </div>
                    <div class="results-container__right">
                        <div class="results-container__select">
                            <v-select
                                class="custom-select"
                                v-model="filterValue"
                                v-bind:options="filterOptions"
                                v-bind:clearable="false"
                                v-bind:reduce="option => option.value"
                                v-bind:searchable="false"
                                v-on:option:selected="setFilter"
                                placeholder="Filter results by"
                            >
                                <template #list-header>
                                    <li class="custom-select__list-top">Match your search query to:</li>
                                </template>
                                <template #open-indicator="{ attributes }">
                                    <span
                                        v-bind="attributes"
                                        class="custom-select__open"
                                    >
                                        <span></span>
                                        <span></span>
                                    </span>
                                </template>
                            </v-select>
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
                        v-bind:is="getItemTypeComponent(provider.is_facility)"
                        v-bind:item="provider"
                        v-bind:is-even="index%2 !== 0"
                        v-bind:class='`index-${index}`'
                    />

                    <div
                        v-if="loading"
                        class="results-container__loader"
                    >
                        <div
                            class="spinner-border"
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
                            We couldn't find any results for that search. Please
                            <a href="/contact-us.php" class="text--styled-link">contact us</a> if you need further
                            assistance.
                        </p>
                    </template>
                </div>

                <div class="results-container__pagination">
                    <div class="pagination">
                        <!--                        <div-->
                        <!--                            v-for="(link, index) in providersMeta.links"-->
                        <!--                            class="pagination__item"-->
                        <!--                            v-bind:key="index"-->
                        <!--                            v-bind:class="{-->
                        <!--                                'is-active': link.active,-->
                        <!--                                'is-disabled': !link.url-->
                        <!--                            }"-->
                        <!--                            v-on:click="setPage(index)"-->
                        <!--                            v-html="link.label"-->
                        <!--                        />-->
                        <div
                            v-if="currentPage > 3 && paginationItems > 6"
                            class="pagination__item"
                            v-on:click="setPage(1)"
                            v-bind:class="{
                                'is-active': currentPage === 1
                            }"
                        >1
                        </div>

                        <div
                            v-if="currentPage > 3 && paginationItems > 6"
                            class="disabled"
                        >
                            ..
                        </div>

                        <div
                            v-for="n in paginationItems"
                            class="pagination__item"
                            v-bind:class="{
                                'is-active' : n == currentPage
                            }"
                            v-on:click="setPage(n)"
                        >
                            {{ n }}
                        </div>

                        <div
                            v-if="currentPage < paginationItems - 1 && paginationItems > 6"
                            class="disabled"
                        >
                            ...
                        </div>

                        <div
                            v-if="currentPage < paginationItems - 4 && paginationItems > 6"
                            class="pagination__item"
                            v-on:click="setPage(providersMeta.last_page)"
                        >{{ providersMeta.last_page }}
                        </div>
                    </div>
                </div>
            </div>

            <div
                v-if="selectedNetwork && selectedNetwork.legal && selectedNetwork.legal.search"
                class="mt-2 mb-5"
                v-html="selectedNetwork.legal.search"
            />
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
            networks: [],
            providers: [],
            providersMeta: {},
            searchQuery: '',
            loading: false,
            filterOptions: [
                {
                    label: 'Providers only',
                    value: 'provider',
                    name: 'type'
                },
                {
                    label: 'Healthcare facilities only',
                    value: 'facility',
                    name: 'type'
                },
                {
                    label: 'City',
                    value: 'city',
                    name: 'scope'
                },
                // {
                //     label: 'Speciality',
                //     value: 'speciality',
                //     name: 'scope'
                // },
                // {
                //     label: 'Language',
                //     value: 'language',
                //     name: 'scope'
                // }
            ],
            filterValue: '',
        }
    },

    mounted() {
        this.networks = window.networks;
    },

    watch: {
        '$route.query': {
            handler: async function () {
                if (this.$route.query.page) {
                    this.currentPage = parseInt(this.$route.query.page);
                }

                if (this.$route.query.type || this.$route.query.scope) {
                    this.filterValue = this.$route.query.type || this.$route.query.scope;
                }

                await this.searchProviders(this.$route.query);
            },
            immediate: true
        },
    },

    computed: {
        queryResultString: function () {
            return this.$route.query.keywords;
        },

        resultsLabel: function () {
            return this.providers.length === 1 ? 'result for' : 'results for';
        },

        paginationLength: function () {
            if (this.providersMeta.total) {
                return Math.ceil(this.providersMeta.total / this.providersMeta.per_page);
            }
        },

        pagMin: function () {
            if (this.currentPage < 4) {
                return 1;
            } else if (this.currentPage > this.paginationLength - 4) {
                return this.paginationLength - 5;
            } else {
                return this.currentPage - 2;
            }
        },

        pagMax: function () {
            if (this.paginationLength < 6) {
                return this.paginationLength;
            } else if (this.currentPage < 4) {
                return 6;
            } else if (this.currentPage > this.paginationLength - 5) {
                return this.paginationLength;
            } else {
                return this.currentPage + 3;
            }
        },

        paginationItems: function () {
            let temp = [];
            for (let i = this.pagMin; i < this.pagMax + 1; i++) {
                temp.push(i);
            }
            return temp
        },

        selectedNetwork: function () {
            return this.networks.find(network => network.id == this.$route.query.network_id);
        }
    },

    methods: {
        searchProviders: async function (query) {
            this.loading = true;
            const {data} = await api.search(query);
            this.providers = data.data;
            this.providersMeta = data.meta;
            this.loading = false;
        },

        newSearch: async function () {
            this.$router.push({query: {...this.$route.query}}).catch(() => {
            });
        },

        getItemTypeComponent(isFacilitiy) {
            if (!isFacilitiy) {
                return ResultsItemDoctor;
            } else {
                return ResultsItemFacility;
            }
        },

        setFilter() {
            let query = {
                ...this.$route.query
            };

            delete query.type;
            delete query.scope;

            if (this.filterValue) {
                let currentOption = this.filterOptions.find(option => this.filterValue === option.value);

                query[currentOption.name] = currentOption.value;
            }

            this.$router.push({query: {...query}}).catch(() => {
            });
        },

        setPage(page) {
            this.$router.push({query: {...this.$route.query, page: page}}).catch(() => {
            });
        }
    }
}
</script>
