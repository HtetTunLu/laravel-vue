import Vuex from "vuex";
import createPersistedState from "vuex-persistedstate";

export default new Vuex.Store({
    state: {
        token: null,
        user: null,
    },
    mutations: {
        saveLoginInfo(state, loginInfo) {
            state.token = loginInfo.data.token;
            state.user = loginInfo.data.user;
        },
        logout(state) {
            (state.token = null), (state.user = null);
        },
    },
    actions: {
        saveToken({ commit }, loginInfo) {
            commit("saveLoginInfo", loginInfo);
        },
        logout({ commit }) {
            commit("logout");
        },
    },
    plugins: [createPersistedState()],
});
