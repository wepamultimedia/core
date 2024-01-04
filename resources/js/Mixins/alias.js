import {usePage} from "@inertiajs/vue3";

export const alias = (key) => {
    return usePage().props.aliasSlug[key] || key;
};

export const aliasSlug = {
    methods: {
        /**
         * Translate the given key.
         */
        alias(key) {
            return usePage().props.aliasSlug[key] === null ? '/' : '/' + usePage().props.aliasSlug[key] || key;
        }
    }
};

