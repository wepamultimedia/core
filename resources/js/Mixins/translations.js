import { usePage } from "@inertiajs/inertia-vue3";

export const __ = (key, replace = {}) => {
    let translation = usePage().props.value.default.translation[key] || key;

    Object.keys(replace).forEach(function (key) {
        translation = translation.replace(":" + key, replace[key]);
    });

    return translation;
};

export const translations = {
    methods: {
        /**
         * Translate the given key.
         */
        __(key, replace = {}) {
            let translation = this.$page.props.default.translation[key] || key;

            Object.keys(replace).forEach(function (key) {
                translation = translation.replace(":" + key, replace[key]);
            });

            return translation;
        }
    }
};

