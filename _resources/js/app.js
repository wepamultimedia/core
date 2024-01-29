import "./bootstrap";
import "../scss/app.scss";

import {createApp, h} from "vue";
import {createInertiaApp} from "@inertiajs/vue3";
import {InertiaProgress} from "@inertiajs/progress";
import {resolvePageComponent} from "laravel-vite-plugin/inertia-helpers";
import {ZiggyVue} from "../../vendor/tightenco/ziggy/dist/vue.m";
import {translations} from "@core/Mixins/translations";
import {aliasSlug} from "@core/Mixins/alias";
import Heroicon from "@core/Components/Heroicon.vue";
import InlineSvg from "vue-inline-svg";
import store from "@/Store";

createInertiaApp({
    title: (title) => `${title}`,
    resolve: (name) => {
        return resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob("./Pages/**/*.vue"));
    },
    setup({el, App, props, plugin}) {
        return createApp({render: () => h(App, props)})
            .use(plugin)
            .use(store)
            .use(ZiggyVue, Ziggy)
            .component("inline-svg", InlineSvg)
            .component("icon", Heroicon)
            .mixin(translations)
            .mixin(aliasSlug)
            .mount(el);
    }
});

InertiaProgress.init({
    showSpinner: false,
    // includeCSS: true,
    color: "#8B4584",
    delay: 100
});
