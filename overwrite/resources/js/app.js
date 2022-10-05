import "./bootstrap";
import "../css/app.css";

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/inertia-vue3";
import { InertiaProgress } from "@inertiajs/progress";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy/dist/vue.m";
import { translations } from "@core/Mixins/translations";
import Heroicon from "@core/components/Heroicon.vue";
import InlineSvg from "vue-inline-svg";
import store from "@core/store/index";

const appName = window.document.getElementsByTagName("title")[0]?.innerText || "Laravel";

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => {
        return resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob("./Pages/**/*.vue"));
    },
    setup({
        el,
        app,
        props,
        plugin
    }) {
        return createApp({render: () => h(app, props)})
        .use(plugin)
        .use(store)
        .component("inline-svg", InlineSvg)
        .component("icon", Heroicon)
        .use(ZiggyVue, Ziggy)
        .mixin(translations)
        .mount(el);
    }
});

InertiaProgress.init({
    showSpinner: true,
    includeCSS: true,
    color: "#2f2f2f",
    delay: 4000

});