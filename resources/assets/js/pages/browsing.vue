<template>
    <div>
        <search-block
            browsing-filters
            is-header
            back-link="/provider-search/"
            back-link-text="Back to doctor and facility search"
            caption="The St Vincent's Network is based around Alabama only."
        />
    </div>
</template>

<script>
import SearchBlock from '../components/SearchBlock';

export default {
    name: 'BrowsingPage',

    components: {
        SearchBlock
    }
};
</script>
