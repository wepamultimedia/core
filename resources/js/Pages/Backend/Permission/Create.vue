<script>
import MainLayout from "@pages/Core/Layouts/Backend/MainLayout.vue";

export default {
    layout: (h, page) => h(MainLayout, {
        title: "roles_permissions", icon: "key", bc: [
            {label: "permissions", route: "admin.permissions.index"}, {label: "create"}
        ]
    }, () => page)
};
</script>
<script setup>
import { reactive, defineProps } from "vue";
import { Inertia } from "@inertiajs/inertia";
import Input from "@core/Components/Form/Input.vue";

const props = defineProps({
    errors: Object
});

const form = reactive({
    name: ""
});

const submit = () => {
    Inertia.post(route("admin.permissions.store"), form);
};
</script>
<template>
    <!--Title-->
    <div class="flex justify-between my-0 items-center h-14 rounded-lg overflow-hidden mt-4">
        <span class="font-medium text-xl">{{ __("create_title") }}</span>
    </div>
    <form @submit.prevent="submit">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div class="col-span-1">
                <p class="text-sm">{{ __("create_summary") }}</p>
            </div>
            <div class="col-span-2
                        overflow-hidden
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
                <div class="flex items-center justify-end px-4 py-3 bg-gray-50 dark:bg-gray-500 text-right">
                    <button class="inline-flex
                                       items-center
                                       px-4 py-2
                                       bg-gray-800
                                       border border-transparent
                                       rounded-md
                                       font-semibold
                                       text-xs text-white uppercase tracking-widest
                                       hover:bg-gray-700
                                       active:bg-gray-900
                                       focus:outline-none
                                       focus:border-gray-900
                                       focus:ring
                                       focus:ring-gray-300
                                       disabled:opacity-25
                                       transition"
                            type="submit">
                        {{ __("save") }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</template>
<style lang="scss"
       scoped></style>
