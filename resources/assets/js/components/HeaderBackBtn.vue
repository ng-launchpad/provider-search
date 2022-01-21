<template>
    <button
        v-if="isNotHome"
        type="button"
        class="header__back-btn"
        v-on:click="resolvePath()"
    >
        <span>
            {{ buttonText }}
        </span>
    </button>
</template>

<script>
export default {
    name: 'HeaderBackBtn',

    computed: {
        routeName() {
            return this.$route.name;
        },

        isNotHome() {
            return this.routeName !== 'home';
        },

        buttonText() {
            if (this.routeName) {
                switch (this.routeName) {
                    case 'results':
                        return 'Start a new search'
                    case 'provider':
                        return 'Back to results'
                    case 'facility':
                        return 'Back to results'
                    default:
                        return 'Start a new search'
                }
            }
        },
    },

    methods: {
        resolvePath() {
            switch (this.routeName) {
                case 'results':
                    this.$router.push('/');
                    break;
                case 'provider':
                    this.$router.go(-1);
                    break;
                case 'facility':
                    this.$router.go(-1);
                    break;
                default:
                    this.$router.push('/');
                    break;
            }
        }
    }
}
</script>
