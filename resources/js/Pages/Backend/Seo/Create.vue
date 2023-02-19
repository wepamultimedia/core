<script>
import MainLayout from "@pages/Vendor/Core/Backend/Layouts/MainLayout/MainLayout.vue";

export default {
    layout: (h, page) => h(MainLayout, {
        title: "seo",
        icon: "key",
        bc: [
            {
                label: "seo",
                route: "admin.seo.index"
            }, {label: "create"}
        ]
    }, () => page)
};
</script>
<script setup>
import { ref, toRefs} from "vue";
import { router } from "@inertiajs/vue3";
import Input from "@/Vendor/Core/Components/Form/Input.vue";
import SeoForm from "@/Vendor/Core/Components/Backend/SeoForm.vue";
import SaveFormButton from "@/Vendor/Core/Components/Form/SaveFormButton.vue";

const props = defineProps(["errors"]);

const {seo} = toRefs(props);

const form = ref({
    alias: null,
    controller: null,
    action: null,
    page_type: "website",
    article_type: "article",
    image: null,
    facebook_image: null,
    twitter_image: null,
    canonical: false,
    robots: [],
    translations: {}
});
const loading = ref(false);

const submit = () => {
    loading.value = true;
    router.post(route("admin.seo.store"), form.value, {
        onFinish(){
            loading.value = false;
        }
    });
};
</script>
<template>
    <!--Title-->
    <div class="flex justify-between my-0 items-center h-14 rounded-lg overflow-hidden mt-4">
        <span class="dark:text-light font-medium text-xl">{{ __("create_title") }}</span>
    </div>
    <form @submit.prevent="submit">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 text-skin-base dark:text-skin-base-dark mb-8">
            <div class="col-span-1">
                <p class="text-sm">{{ __("craate_summary") }}</p>
            </div>
            <div class="col-span-2
                        border
                        dark:border-gray-600
                        bg-white dark:bg-gray-600
                        text-gray-700 dark:text-gray-300
                        rounded-lg
                        shadow">
                <div class="grid grid-cols-6 p-6">
                    <div class="col-span-6 sm:col-span-6 lg:col-span-5 xl:col-span-4 mb-6">
                        <Input v-model="form.alias"
                               :errors="errors"
                               :label="__('Alias')"
                               autofocus
                               name="alias"/>
                    </div>
                </div>
                <div class="rounded-b-lg overflow-hidden">
                    <div class="p-3 bg-gray-200 dark:bg-gray-500 flex justify-end">
                        <SaveFormButton :form="form" :loading="loading"/>
                    </div>
                </div>
            </div>
        </div>
        <SeoForm v-model:seo="form"
                 :errors="errors"/>
    </form>
</template>
<style lang="scss"
       scoped></style>
