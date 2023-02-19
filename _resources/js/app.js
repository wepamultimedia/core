import "./bootstrap";
import "../css/app.css";

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { InertiaProgress } from "@inertiajs/progress";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy/dist/vue.m";
import { translations } from "@/Vendor/Core/Mixins/translations";
import Heroicon from "@/Vendor/Core/Components/Heroicon.vue";
import InlineSvg from "vue-inline-svg";
import store from "@/Vendor/Core/Store/index";

const appName = window.document.getElementsByTagName("title")[0]?.innerText || "Laravel";

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => {
        return resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob("./Pages/**/*.vue"));
    },
    setup({el, App, props, plugin}) {
        return createApp({render: () => h(App, props)})
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
    showSpinner: false,
    // includeCSS: true,
    color: "#8B4584",
    delay: 100
});
