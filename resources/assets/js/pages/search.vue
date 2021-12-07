<template>
    <div>
        <div class="search-hero" v-bind:style="{backgroundImage: 'url(\'../assets/images/search-bg.jpg\')'}">
            <div class="search-hero__bg" v-bind:style="{backgroundImage: 'url(\'../assets/images/search-bg.jpg\')'}"></div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-7 col-md-10">
                        <p class="heading search-hero__title">Doctor and facility search</p>
                        <p class="search-hero__subtitle color-weak">
                            Browse or search our wide range of providers and facilities.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="search-offer">
            <div class="container">
                <div class="search-offer__title heading align-center">
                    We offer a wide range of providers and facilities based on your coverage choice.
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="search-offer__item">
                            <div class="search-offer__item-img">
                                <img src="../../img/already-customer.svg" />
                            </div>
                            <p class="search-offer__item-title heading">
                                Are you already a customer?
                            </p>
                            <p class="search-offer__item-descr color-weak">
                                To find providers in your network, check your plan ID card and select the matching Provider Network or TPA.
                            </p>
                            <div class="search-offer__item-action">
                                <multiselect
                                    class="custom-select"
                                    v-model="customerOption"
                                    placeholder="Coverage type"
                                    v-bind:options="networkItems"
                                    group-label="label"
                                    group-values="products"
                                    label="label"
                                    selectGroupLabel=""
                                    selectLabel=""
                                    deselectLabel=""
                                    v-bind:group-select="true"
                                    v-bind:clearable="false"
                                    v-bind:searchable="false"
                                    v-on:input="fakeLoading"
                                />
                            </div>
                            <div
                                v-if="loading"
                                class="search-offer__links is-loading"
                            >
                                <div class="search-offer__loader">
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
                            <div
                                v-if="customerOption && !loading"
                                class="search-offer__links"
                            >
                                <ul
                                    class="search-offer__links-list"
                                    v-if="customerOption.links.length"
                                >
                                    <li v-for="(link, key) in customerOption.links" v-bind:key="key">
                                        <a
                                            v-bind:href="link.url"
                                            v-bind:target="link.external ? '_blank' : ''"
                                        >
                                            <span>
                                                {{ link.label }}
                                            </span>
                                            <img v-if="link.external" src="../../img/link.svg" alt="">
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mt-5 mt-md-0">
                        <div class="search-offer__item">
                            <div class="search-offer__item-img">
                                <img src="../../img/browse-networks.svg" />
                            </div>
                            <p class="search-offer__item-title heading">
                                Curious about our networks?
                            </p>
                            <p class="search-offer__item-descr color-weak">
                                Our networks vary based on the coverage you choose. Browse our different networks now.
                            </p>
                            <div class="search-offer__item-action">
                                <router-link
                                    to="/provider-search/browse-networks"
                                    class="button button--secondary"
                                >
                                    Browse networks
                                </router-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <search-block
            title="Search our newest network"
            caption="The St Vincent's Network is based around Alabama only."
        />
    </div>
</template>

<script>
import mock from '../api/mock';
import SearchBlock from '../components/SearchBlock';

export default {
    name: 'SearchPage',

    components: {
        SearchBlock
    },

    data() {
        return {
            heroHolder: {
                img: '581x338',
                auto: 'yes',
                theme: 'gray'
            },
            offerHolder: {
                img: '343x219',
                auto: 'yes',
                theme: 'gray'
            },
            offerHolder1: {
                img: '343x219',
                auto: 'yes',
                theme: 'gray'
            },
            networkItems: [],
            links: [],
            customerOption: '',
            loading: false
        }
    },

    async mounted() {
        this.networkItems = await mock.getLinks();
    },

    methods: {
        fakeLoading: function() {
            this.loading = true;

            let timeout;

            clearTimeout(timeout);

            setTimeout(() => {
                this.loading = false
            }, 1000)
        }
    }
}
</script>
