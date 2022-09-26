<script>
import MainLayout from "@core/Layouts/Backend/MainLayout.vue";

export default {
    layout: (h, page) => h(MainLayout, {
        title: "roles_permissions", icon: "key", bc: [{label: "roles", route: "admin.roles.index"}, {label: "create"}]
    }, () => page)
};
</script>
<script setup>
import { reactive, ref, toRef } from "vue";
import { Inertia } from "@inertiajs/inertia";
import Checkbox from "@components/Form/Checkbox.vue";
import Input from "@components/Form/Input.vue";

const props = defineProps({
    permissions: Array, errors: Object
});

const role = toRef(props, "role");
const selectedPermissions = ref([]);

const form = reactive({
    name: "", selectedPermissions: selectedPermissions.value
});

const submit = () => {
    Inertia.post(route("admin.roles.store"), form);
};
</script>
<template>
    <!--Title-->
    <div class="flex justify-between my-0 items-center h-14 rounded-lg overflow-hidden mt-4">
        <span class="dark:text-light font-medium text-xl">{{ __("create_title") }}</span>
    </div>
    <form @submit.prevent="submit">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 dark:text-light">
            <div class="col-span-1">
                <p class="text-sm">{{ __("create_summary") }}</p>
            </div>
            <div class="col-span-2
                        overflow-hidden
                        border
                        dark:border-gray-600
                        bg-white dark:bg-gray-600
                        text-gray-700 dark:text-gray-300
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
                <div class="p-3 bg-gray-50 dark:bg-gray-500 text-right">
                    <button class="btn btn-primary"
                            type="submit">
                        {{ __("save") }}
                    </button>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 dark:text-light mt-8">
            <div class="col-span-1">
                <p class="text-sm">{{ __("select_permissions") }}</p>
            </div>
            <div class="col-span-2
                            overflow-hidden
                            border
                            dark:border-gray-600
                            bg-white dark:bg-gray-600
                            text-gray-700 dark:text-gray-300
                            rounded-lg
                            shadow">
                <div class="p-6">
                    <h2 class="text-xl block mb-6 font-medium text-sm text-gray-700 dark:text-light">
                        <span>{{ __("permissions") }}</span>
                    </h2>
                    <Checkbox v-model="form.selectedPermissions"
                              :errors="errors"
                              :options="permissions"
                              class="grid md:grid-cols-2 lg:grid-cols-3 gap-y-6"
                              label="description"
                              name="selectedPermissions"
                              value="name"/>
                </div>
                <div class="p-3 bg-gray-50 dark:bg-gray-500 text-right">
                    <button class="btn btn-primary"
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
