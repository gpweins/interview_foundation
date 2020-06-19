<template>
    <div>
        <b-form @submit.prevent="onSubmit">
            <b-form-group
                id="token-group"
                label="GitHub Token:"
                label-for="token-input"
                description="Inform your GitHub token"
            >
                <b-form-input
                    id="token-input"
                    v-model="form.token"
                    type="text"
                    required
                    placeholder="GitHub token"
                ></b-form-input>
            </b-form-group>

            <b-alert
                show variant="info"
                v-if="showAlert"
            >
                No Token? Click
                <a
                    href="https://help.github.com/en/github/authenticating-to-github/creating-a-personal-access-token-for-the-command-line"
                    target="_blank"
                >here</a>
                to learn how to make a token
            </b-alert>

            <b-button
                type="submit"
                variant="primary"
                :disabled="showAlert"
            >Save Token</b-button>
        </b-form>
    </div>
</template>

<script>
    export default {
        name: 'GitHubTokenForm',
        data () {
            return {
                form: {
                    token: this.token
                }
            }
        },

        props: {
            token: {
                required: true,
                type: String
            }
        },

        computed: {
            showAlert: function () {
                return this.form.token == '';
            }
        },

        methods: {
            onSubmit () {
                axios
                    .put('/save-token', {
                        token: this.form.token
                    })
                    .then(function(response) {
                        console.log(response.data)
                    })
                    .catch(function(error) {
                        console.error(error);
                    });
            }
        }
    }
</script>
