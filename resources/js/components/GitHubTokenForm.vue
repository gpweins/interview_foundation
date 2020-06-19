<template>
    <div>
        <b-form @submit="onSubmit" @reset="onReset">
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

            <b-button type="submit" variant="primary">Save Token</b-button>
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

            },
            onReset () {

            }
        }
    }
</script>
