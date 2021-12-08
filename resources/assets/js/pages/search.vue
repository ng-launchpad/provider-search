<template>
    <div>
        <div class="hero">
            <div class="container">
                <div class="hero__inner">
                    <img class="hero__img" src="images/hero-woman.png" alt="">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="hero__title">
                                group health fully-insured <span>provider search</span>
                            </div>
                            <div class="hero__sub-title">
                                Locate participating providers, facilities, and pharmacies.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <search-block
            class="mt-5 mb-5"
            title="Start your search"
            caption="The St Vincent's Network is based around Alabama only."
        />

        <div class="pt-2 pb-5">
            <div class="container">
                Insert any legal disclaimers here.
            </div>
        </div>
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
