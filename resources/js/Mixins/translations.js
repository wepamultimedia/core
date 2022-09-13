export const translations = {
    methods: {
        /**
         * Translate the given key.
         */
        __(key, replace = {}) {
            let translation = this.$page.props.translation[key] || key;

            Object.keys(replace).forEach(function (key) {
                translation = translation.replace(":" + key, replace[key]);
            });

            return translation;
        }
    }
};