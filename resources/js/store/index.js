import Vuex from "vuex";
import createPersistedState from "vuex-persistedstate";

export default new Vuex.Store({
    state: {
        token: null,
        userName: "",
    },
    mutations: {
        saveLoginInfo(state, loginInfo) {
            state.token = loginInfo.data.token;
            state.userName = loginInfo.data.name;
        },
        logout(state) {
            (state.token = null), (state.userName = "");
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
