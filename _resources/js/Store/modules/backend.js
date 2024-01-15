import {usePage} from "@inertiajs/vue3";

export default {
    namespaced: true,
    state: {
        loading: false,
        alerts: [],
        menu: [],
        formLocale: "",
    },
    actions: {
        async menu({commit, dispatch}, app) {
            axios.get(route("api.v1.menu.index", {app: "backend"})).then(response => {
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
        },
        formLocale({commit}, value) {
            commit("SET_FORM_LOCALE", value);
        }

    }, getters: {
        menu: state => state.menu,
        loading: state => state.loading,
        alerts: state => state.alerts,
        formLocale: state => {
            if (state.formLocale === "") {
                state.formLocale = usePage().props.default.locale
            }

            return state.formLocale;
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
                if (a.show)
                    showAny = true;
            });

            if (!showAny)
                state.alerts = state.alerts.filter(a => a.show === true);

            alert.id = uuid();
            alert.show = true;
            state.alerts.push(alert);
        },
        REMOVE_ALERT(state, alert) {
            let index = state.alerts.indexOf(state.alerts.find(a => a.id === alert.id));
            state.alerts[index].show = false;
        },
        SET_FORM_LOCALE(state, value) {
            state.formLocale = value;
        }
    }
};

function uuid() {
    return "xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx".replace(/[xy]/g, function (c) {
        let r = Math.random() * 16 | 0,
            v = c === "x" ? r : (r & 0x3 | 0x8);
        return v.toString(16);
    });
}

