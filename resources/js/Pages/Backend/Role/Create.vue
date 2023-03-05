<script>
import MainLayout from "@pages/Vendor/Core/Backend/Layouts/MainLayout/MainLayout.vue";

export default {
    layout: (h, page) => h(MainLayout, {
        title: "roles_permissions",
        icon: "key",
        bc: [
            {
                label: "roles",
                route: "admin.roles.index"
            }, {label: "create"}
        ]
    }, () => page)
};
</script>
<script setup>
import { ref, toRef } from "vue";
import Checkbox from "@/Vendor/Core/Components/Form/Checkbox.vue";
import Input from "@/Vendor/Core/Components/Form/Input.vue";
import SaveFormButton from "@/Vendor/Core/Components/Form/SaveFormButton.vue";
import { __ } from "@/Vendor/Core/Mixins/translations";
import { useForm } from "@inertiajs/vue3";
import { useStore } from "vuex";

const props = defineProps({
    permissions: Array,
    errors: Object
});

const store = useStore();
const role = toRef(props, "role");
const selectedPermissions = ref([]);

const form = useForm({
    name: "",
    translations: {},
    selectedPermissions: selectedPermissions.value
});

const submit = () => {
    form.post(route("admin.roles.store"), {
        onSuccess: () => store.dispatch("addAlert", {type: 'success', message: __('saved')}),
        onError: () => store.dispatch("addAlert", {type: 'error', message: form.errors})
    });
};
</script>
<template>
    <!--Title-->
    <div class="flex justify-between my-0 items-center h-14 rounded-lg overflow-hidden mt-4">
        <span class="dark:text-light font-medium text-xl">{{ __("create_title") }}</span>
    </div>
    <form @submit.prevent="submit">
        <div class="max-w-7xl">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 text-skin-base ">
                <div class="col-span-1">
                    <p class="text-sm">{{ __("create_summary") }}</p>
                </div>
                <div class="col-span-2
                        border
                        dark:border-gray-600
                        bg-white dark:bg-gray-600
                        rounded-lg
                        shadow">
                    <div class="grid grid-cols-6 p-6">
                        <div class="col-span-6 sm:col-span-6 lg:col-span-5 xl:col-span-4 mb-6">
                            <Input v-model="form.name"
                                   :errors="errors"
                                   :label="__('name')"
                                   name="name"/>
                        </div>
                        <div class="col-span-6 sm:col-span-6 lg:col-span-5 xl:col-span-4 mb-6">
                            <Input v-model="form"
                                   :errors="errors"
                                   :label="__('description')"
                                   name="description"
                                   translation/>
                        </div>
                    </div>
                    <div class="rounded-b-lg overflow-hidden">
                        <div class="p-3 bg-gray-50 dark:bg-gray-500 flex justify-end">
                            <SaveFormButton :form="form"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mt-8">
                <div class="col-span-1">
                    <p class="text-sm">{{ __("select_permissions") }}</p>
                </div>
                <div class="col-span-2
                            overflow-hidden
                            border
                            dark:border-gray-600
                            bg-white dark:bg-gray-600
                            rounded-lg
                            shadow">
                    <div class="p-6">
                        <h2 class="text-xl block mb-6 font-medium text-sm">
                            <span>{{ __("permissions") }}</span>
                        </h2>
                        <Checkbox v-model="form.selectedPermissions"
                                  :errors="errors"
                                  :options="permissions"
                                  class="grid md:grid-cols-2 lg:grid-cols-3 gap-y-6"
                                  label="description"
                                  name="selectedPermissions"
                                  property-value="name"/>
                    </div>
                    <div class="p-3 bg-gray-50 dark:bg-gray-500 flex justify-end">
                        <SaveFormButton :form="form"/>
                    </div>
                </div>
            </div>
        </div>
    </form>
</template>
<style lang="scss"
       scoped></style>
