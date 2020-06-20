<template>
    <div>
        <b-button
                type="submit"
                variant="primary"
                :disabled="!hasToken"
            >Get Starred Repos</b-button>
    </div>
</template>

<script>
export default {
    name: 'GitHubStarredRepositories',

    props: {
        token: {
            required: true,
            type:  String
        }
    },

    computed: {
        hasToken: function () {
            return !!(this.token || this.$root.store.getToken());
        }
    },



}
</script>
