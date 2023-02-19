<script>
import MainLayout from "@pages/Core/Backend/Layouts/MainLayout/MainLayout.vue";

export default {
    layout: (h, page) => h(MainLayout, {
        title: "seo",
        icon: "key",
        bc: [
            {
                label: "seo",
                route: "admin.seo.index"
            }, {label: "edit"}
        ]
    }, () => page)
};
</script>
<script setup>
import { onBeforeMount, ref, toRefs } from "vue";
import { router } from "@inertiajs/vue3";
import Input from "@core/Components/Form/Input.vue";
import Modal from "@core/Components/Modal.vue";
import SeoForm from "@core/Components/Backend/SeoForm.vue";
import SaveFormButton from "@core/Components/Form/SaveFormButton.vue";

const props = defineProps(["seo", "errors"]);

const {seo} = toRefs(props);

const form = ref({
    id: null,
    alias: "",
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
    router.put(route("admin.seo.update", {id: seo.value?.id}), form.value, {
        onFinish(){
            loading.value = false;
        }
    });
};

onBeforeMount(() => {
    if (seo.value.hasOwnProperty("id")) {
        form["id"] = seo.value.id;
    }
    Object.keys(seo.value).filter(key => key in form.value).forEach(key => form.value[key] = seo.value[key]);
});
</script>
<template>
    <!--Title-->
    <div class="flex justify-between my-0 items-center h-14 mt-4">
        <span class="dark:text-light font-medium text-xl">{{ __("edit_title") }}</span>
    </div>
    <form @submit.prevent="submit">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 text-skin-base dark:text-skin-base-dark mb-8">
            <div class="col-span-1">
                <p class="text-sm">{{ __("edit_summary") }}</p>
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
                               :disabled="form.alias === 'home'"
                               :errors="errors"
                               :label="__('Alias')"
                               autofocus
                               name="alias"/>
                    </div>
                </div>
                <div v-if="form.alias !== 'home'"
                     class="rounded-b-lg overflow-hidden">
                    <div class="p-3 bg-gray-200 dark:bg-gray-500 flex justify-end">
                        <SaveFormButton :form="form" :loading="loading"/>
                    </div>
                </div>
            </div>
        </div>
        <SeoForm v-model:seo="form"
                 :errors="errors"/>
        <div class="my-14 flex justify-end">
            <Modal :href="route('admin.seo.destroy', {id:seo?.id})"
                   :message="__('delete_message')"
                   :title="__('delete_seo_route')"
                   danger
                   method="delete">
                <template #button="{open}">
                    <button class="px-4 py-2 bg-red-500 rounded-md text-white"
                            type="button"
                            @click="open">
                        {{ __("delete_seo_route") }}
                    </button>
                </template>
            </Modal>
        </div>
    </form>
</template>
<style lang="scss"
       scoped></style>
