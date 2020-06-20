export default {
    state: {
        token: ''
    },

    setToken (token) {
        this.state.token = token;
    },

    getToken () {
        return this.state.token;
    }
}
