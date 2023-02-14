import { createStore } from "vuex";

const store = createStore({
    state: {
        site: []
    }, actions: {
        loadSite({commit, state}) {
            if(!state.site.length) {
                axios.get(route("api.v1.site")).then(response => {
                    commit("SET_SITE", response.data);
                });
            }
        }
    }, getters: {
        site: state => {
            return state.site;
        }
    },
    mutations: {
        SET_SITE(state, value) {
            state.site = value;
        }
    }
});

export default store;

