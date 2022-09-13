import "./bootstrap";
import "../css/app.css";

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/inertia-vue3";
import { InertiaProgress } from "@inertiajs/progress";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy/dist/vue.m";
import { translations } from "@core/Mixins/translations";
import Heroicon from "@components/Heroicon.vue";
import InlineSvg from "vue-inline-svg";

const __ = translations.methods.__;
const appName = window.document.getElementsByTagName("title")[0]?.innerText || "Laravel";

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob("./Pages/**/*.vue")),
    setup({
        el,
        app,
        props,
        plugin
    }) {
        return createApp({render: () => h(app, props)})
        .use(plugin)
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