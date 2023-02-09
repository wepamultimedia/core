import { createStore } from "vuex";
import { usePage } from "@inertiajs/inertia-vue3";
import { random } from "lodash";

const store = createStore({
    state: {
        loading: false,
        alerts: [],
        menu: []
    }, actions: {
        async menu({commit, dispatch}, app) {
            axios.get("/api/menu/" + app, {
                withCredentials: true, headers: {
                    "Authorization": `Bearer ${usePage().props.value.default.access_token}`
                }
            }).then(response => {
                commit("SET_MENU", response.data);
                dispatch("menuActiveItem");
            }).catch(() => {
                commit("SET_MENU", null);
            });
        },
        menuActiveItem({state, commit}, itemId) {
            function reset(items) {
                return items.map(i => {
                    i.isActive = itemId ? itemId === i.id : route().current(i.active);

                    if (i.hasOwnProperty("submenu")) {
                        i.submenu = reset(i.submenu);
                    }
                    return i;
                });
            }

            commit("SET_MENU", reset(state.menu));
        },
        loading({commit}, value) {
            commit("SET_LOADING", value);
        },
        addAlert({commit}, alert) {
            commit("ADD_ALERT", alert);
        },
        removeAlert({commit}, alert) {
            commit("REMOVE_ALERT", alert);
        }

    }, getters: {
        menu: state => {
            return state.menu;
        },
        loading: state => {
            return state.loading;
        },
        alerts: state => {
            return state.alerts;
        }
    }, mutations: {
        SET_MENU(state, value) {
            state.menu = value;
        },
        SET_LOADING(state, value) {
            state.loading = value;
        },
        ADD_ALERT(state, alert) {
            let showAny = false;
            state.alerts.map(a => {
                if(a.show)
                    showAny = true;
            });

            if(!showAny)
                state.alerts = state.alerts.filter(a => a.show === true);

            alert.id = uuid();
            alert.show = true;
            state.alerts.push(alert);
        },
        REMOVE_ALERT(state, alert) {
            let index = state.alerts.indexOf(state.alerts.find(a => a.id === alert.id));
            state.alerts[index].show = false
        }
    }
});

export default store;

function uuid() {
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        let r = Math.random() * 16 | 0, v = c === 'x' ? r : (r & 0x3 | 0x8);
        return v.toString(16);
    });
}

