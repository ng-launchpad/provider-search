<template>
    <div>
        <router-view v-bind:key="$route.fullPath"></router-view>
    </div>
</template>

<script>
export default {
    name: 'App'
};
</script>
