import Vuex from "vuex";
import createPersistedState from "vuex-persistedstate";

export default new Vuex.Store({
    state: {
        token: null,
    },
    mutations: {
        saveToken(state, token) {
            state.token = token;
        },
    },
    actions: {
        saveToken({ commit }, loginInfo) {
            commit("saveToken", loginInfo.data.token);
        },
    },
    plugins: [createPersistedState()],
});
