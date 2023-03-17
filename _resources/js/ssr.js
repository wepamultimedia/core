import { createSSRApp, h } from "vue";
import { renderToString } from "@vue/server-renderer";
import { createInertiaApp } from "@inertiajs/vue3";
import createServer from "@inertiajs/vue3/server";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy/dist/vue.m";
import Heroicon from "@/Vendor/Core/Components/Heroicon.vue";
import InlineSvg from "vue-inline-svg";
import store from "@core/Store";
import { translations } from "@core/Mixins/translations";

const appName = "Laravel";

createServer((page) =>
    createInertiaApp({
        page,
        render: renderToString,
        title: (title) => `${title} - ${appName}`,
        resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob("./Pages/**/*.vue")),
        setup({App, props, plugin}) {
            return createSSRApp({render: () => h(App, props)})
            .use(plugin)
            .use(store)
            .use(ZiggyVue, {
                ...page.props.ziggy,
                location: new URL(page.props.ziggy.location)
            })
            .mixin(translations)
            .component("inline-svg", InlineSvg)
            .component("icon", Heroicon);
        }
    })
);
