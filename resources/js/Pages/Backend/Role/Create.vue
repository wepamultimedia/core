<script setup>
import MainLayout from "@layouts/Core/Backend/MainLayout.vue";
import { reactive, ref, toRef } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { Link } from "@inertiajs/inertia-vue3";
import Checkbox from "@components/Form/Checkbox.vue";
import Input from "@components/Form/Input.vue";

const props = defineProps({
    permissions: Array,
    errors: Object
});

const role = toRef(props, "role");
const selectedPermissions = ref([]);

const form = reactive({
    name: '',
    selectedPermissions: selectedPermissions.value
});

const submit = () => {
    Inertia.post(route("admin.roles.store"), form);
};
</script>
<template>
    <MainLayout :bc="[{label: __('roles'), route: 'admin.roles.index'}, {label: __('create')}]"
                :title="__('roles_permissions')"
                icon="key">
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
                                   translation
                                   name="description"/>
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
    </MainLayout>
</template>
<style lang="scss"
       scoped></style>
