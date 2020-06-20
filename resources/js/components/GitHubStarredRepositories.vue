<template>
    <div>
        <b-button
                type="submit"
                variant="primary"
                :disabled="!hasToken"
                @click.prevent="load"
            >Get Starred Repos</b-button>

        <div v-if="loading" class="my-3">
            <p>
                Getting your data
                <b-spinner small variant="primary"></b-spinner>
            </p>
        </div>
        <b-list-group class="my-3" v-if="loaded">
            <b-list-group-item
                class="my-1"
                v-for="repository in repositories.data"
                :key="repository.id"
                :href="repository.html_url"
                target="_blank"
            >{{repository.name}}</b-list-group-item>
        </b-list-group>
        <b-alert
            class="m-3"
            show variant="danger"
            v-if="error"
        >{{ error }}</b-alert>
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

    data() {
        return {
            loaded: false,
            loading: false,
            repositories: [],
            error: null
        };
    },

    methods: {
        load() {
            this.loading = true;
            this.error = null;
            axios.get("/github/starred")
                .then(response => {
                    this.repositories = response.data;
                    this.loaded = true;
                    this.loading = false;
                })
                .catch(e => {
                    this.loading = false;
                    this.error = e.response.data.error;
                });
        }
    }
}
</script>
