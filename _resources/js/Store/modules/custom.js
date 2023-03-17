export default {
    namespaced: true,
    state: {
        tvFlap: false
    }, actions: {
        setTVFlap({commit}, payload) {
            commit("SET_TV_FLAP", payload);
        }
    }, getters: {
        tvFlap: state => {
            return state.tvFlap;
        }
    },
    mutations: {
        SET_TV_FLAP(state, value) {
            state.tvFlap = value;
        }
    }
};
