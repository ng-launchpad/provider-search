<template>
    <div>
        <div class="page-header page-header--bg-image" v-bind:style="{backgroundImage: 'url(\'../assets/images/browse-networks-bg.jpg\')'}">
            <div class="page-header__bg" v-bind:style="{backgroundImage: 'url(\'../assets/images/browse-networks-bg.jpg\')'}"></div>
            <div class="container">
                <div class="page-header__back">
                    <router-link
                        to="/provider-search/"
                        class="page-header__back-link"
                    >
                        <img src="../../img/svg/arrow-left.svg" alt="" class="page-header__back-icon">
                        <span>Back to doctor and provider search</span>
                    </router-link>
                </div>
                <p class="page-header__title heading">
                    Browse networks
                </p>
                <p class="page-header__sub-title">
                    Browse or search our wide range of a providers and facilities.
                </p>
            </div>
        </div>

        <div class="container">
            <div class="page-content page-content--browse">
                <div class="row">
                    <div class="col-lg-10 offset-lg-1">
                        <div class="heading heading--md heading--md-xl mb-2 mb-md-5 text-md-center">
                            Individuals
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4 mb-md-0">
                                <div class="text text--display text--sm text--md-lg color-weak mb-2">
                                    Short term medical
                                </div>
                                <ul class="list list--unstyled-list text--xs text--md-sm">
                                    <li>
                                        <a href="https://www.mynatgen.com/CignaPPO" target="_blank" class="text--network-link">
                                            <span>Cigna® PPO network</span>
                                            <img src="../../img/svg/link.svg" alt="">
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.goperspecta.com/PDN/nhic/public/ProviderSearch/Main" target="_blank" class="text--network-link">
                                            <span>Aetna® Open Choice PPO network</span>
                                            <img src="../../img/svg/link.svg" alt="">
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://MyNatGen.com/CignaLocalPlus" target="_blank" class="text--network-link">
                                            <span>Arizona only - Local Plus</span>
                                            <img src="../../img/svg/link.svg" alt="">
                                        </a>
                                    </li>
                                    <!-- <li>
                                        <a href="/provider-search/browsing" class="text--network-link">
                                            <span class="d-inline-block mt-2">Advent Health PPO network</span>
                                        </a>
                                    </li> -->
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <div class="text text--display text--sm text--md-lg  color-weak mb-2">
                                    Dental PPO
                                </div>
                                <ul class="list list--unstyled-list text--xs text--md-sm">
                                    <li>
                                        <a href="https://www1.careington.com/(S(q02cnphkhkrmixll0wgzlfyb))/NGAHDSavings/search.aspx" target="_blank" class="text--network-link">
                                            <span>Careington network</span>
                                            <img src="../../img/svg/link.svg" alt="">
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="row mt-4 mt-md-5">
                            <div class="col-md-6 mb-4 mb-md-0">
                                <div class="text text--display text--sm text--md-lg color-weak mb-2">
                                    Fixed-benefit medical
                                </div>
                                <ul class="list list--unstyled-list text--xs text--md-sm">
                                    <li>
                                        <a href="https://www.firsthealthlbp.com/LocateProvider/SelectNetworkType" target="_blank" class="text--network-link">
                                            <span>First Health® network</span>
                                            <img src="../../img/svg/link.svg" alt="">
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <div class="text text--display text--sm text--md-lg color-weak mb-2">
                                    Dental Indemnity
                                </div>
                                <ul class="list list--unstyled-list text--xs text--md-sm">
                                    <li>
                                        <a href="https://www1.careington.com/(S(gu0qyt0zebqec31z3mb1qei1))/NGAHDSavings/search.aspx" target="_blank" class="text--network-link">
                                            <span>Careington network (indemnity)</span>
                                            <img src="../../img/svg/link.svg" alt="">
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="row mt-4 mt-md-5">
                            <div class="col-md-6 mb-4 mb-md-0">
                                <div class="text text--display text--sm text--md-lg color-weak mb-2">
                                    Select Dental PPO
                                </div>
                                <ul class="list list--unstyled-list text--xs text--md-sm">
                                    <li>
                                        <a href="https://www.goperspecta.com/VPD/ngicdental/public/ProviderSearch/Main" target="_blank" class="text--network-link">
                                            <span>Aetna® Dental Administrators network</span>
                                            <img src="../../img/svg/link.svg" alt="">
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <div class="text text--display text--sm text--md-lg color-weak mb-2">
                                    Vision
                                </div>
                                <ul class="list list--unstyled-list text--xs text--md-sm">
                                    <li>
                                        <a href="https://www.avesis.com/Commercial3/ProviderSearch_Gen.aspx" target="_blank" class="text--network-link">
                                            <span>Avesis Vision</span>
                                            <img src="../../img/svg/link.svg" alt="">
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="row mt-4 mt-md-5">
                            <div class="col-md-6 mb-4 mb-md-5">
                                <div class="text text--display text--sm text--md-lg color-weak mb-2">
                                    National General DVH
                                </div>
                                <ul class="list list--unstyled-list text--xs text--md-sm">
                                    <li>
                                        <a href="https://www.goperspecta.com/VPD/ngicdental/public/ProviderSearch/Main" target="_blank" class="text--network-link">
                                            <span>Aetna® Dental Administrators network</span>
                                            <img src="../../img/svg/link.svg" alt="">
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6 mb-5">
                                <div class="text text--display text--sm text--md-lg color-weak mb-2">
                                    Hearing
                                </div>
                                <ul class="list list--unstyled-list text--xs text--md-sm">
                                    <li>
                                        <a href="https://www.amplifonusa.com/NGIC" target="_blank" class="text--network-link">
                                            <span>Amplifon Hearing Health Care®</span>
                                            <img src="../../img/svg/link.svg" alt="">
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="row mt-2 mt-md-5">
                            <div class="col-lg-10 offset-lg-1">
                                <div class="heading heading--md heading--md-xl mb-2 mb-md-5 text-md-center">
                                    Businesses and groups
                                </div>
                            </div>

                            <div class="col-md-6 mb-5">
                                <ul class="list list--unstyled-list text--xs text--md-sm">
                                    <li>
                                        <a href="#" target="_blank" class="text--network-link">
                                            <span>Allied Benefits System</span>
                                            <img src="../../img/svg/link.svg" alt="">
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="text--network-link">
                                            <span>Ascension St. Vincent's Physician Alliance network</span>
                                            <img src="../../img/svg/link.svg" alt="">
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <div class="col-md-6 mb-5">
                                <ul class="list list--unstyled-list text--xs text--md-sm">
                                    <li>
                                        <a href="#" target="_blank" class="text--network-link">
                                            <span>Meritain Health</span>
                                            <img src="../../img/svg/link.svg" alt="">
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <div class="col-md-12">
                                <div class="row justify-content-md-center">
                                    <div class="col-md-7">
                                        <div class="text--color-weak text--display text--sm text--md-slg text-md-center mb-4">
                                            NationCare policies in California
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <ul class="list list--unstyled-list text--xs text--md-sm">
                                    <li>
                                        <a href="https://www.aetna.com/dsepublic/#/contentPage?page=providerSearchLanding&site_id=mymeritain" target="_blank" class="text--network-link">
                                            <span>Aetna® Open Choice PPO network</span>
                                            <img src="../../img/svg/link.svg" alt="">
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://natgenhealth.com/california-plan-docs.php" class="text--network-link">
                                            <span>California plan documents</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://natgenhealth.com/Consumer%20Friendly%20Formulary_0119.pdf" target="_blank" class="text--network-link">
                                            <span>Drug Formulary</span>
                                            <img src="../../img/svg/link.svg" alt="">
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list list--unstyled-list text--xs text--md-sm">
                                    <li>
                                        <a href="https://natgenhealth.com/2020%20Nation%20Care%20Std%20RX%20Drug%20Formular1.pdf" target="_blank" class="text--network-link">
                                            <span>Drug Formulary 2020</span>
                                            <img src="../../img/svg/link.svg" alt="">
                                        </a>
                                    </li>
                                    <li>
                                        <span class="d-inline-block mt-2 heading heading--xs heading--md-sm">COVID-19 notices: <a href="https://natgenhealth.com/Nationcare%20CA%20COVID-19%20Plan%20Enhancements%20-%20member%20letter.docx" target="_blank" class="text--styled-link">members</a>&nbsp;&nbsp;&nbsp;<a href="https://natgenhealth.com/NATIONCARE%20Group%20Plans%20Notice%20to%20Providers.docx" class="text--styled-link">providers</a></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text--sm align-center mb-4">
            We’re pleased to introduce our newest network, St Vincent’s, for group PPO and Advantage plans.
        </div>
        <search-block
            caption="The St Vincent's Network is based around Alabama only."
            is-inner
        />
    </div>
</template>

<script>
import mock from '../api/mock';
import SearchBlock from '../components/SearchBlock';

export default {
    name: 'BrowseNetworksPage',

    components: {
        SearchBlock
    },

    data() {
        return {
            networkItems: [],
            loading: false
        }
    },

    async mounted() {
        await this.getItems();
    },

    methods: {
        getItems: async function () {
            this.networkItems = await mock.getLinks();
        }
    }
}
</script>
