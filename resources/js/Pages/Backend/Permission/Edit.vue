<script>
import MainLayout from "@core/Layouts/Backend/MainLayout.vue";

export default {
    layout: (h, page) => h(MainLayout, {
        title: "roles_permissions", icon: "key", bc: [
            {label: "permissions", route: "admin.permissions.index"}, {label: "edit"}
        ]
    }, () => page)
};
</script>
<script setup>
import { reactive, toRefs } from "vue";
import { Inertia } from "@inertiajs/inertia";
import Input from "@components/Form/Input.vue";
import Modal from "@components/Modal.vue";

const props = defineProps({
    translations: Object, permission: Object, errors: Object
});

const {translations, permission} = toRefs(props);
const form = reactive({
    name: permission.value.name, ...translations.value
});

const submit = () => {
    Inertia.put(route("admin.permissions.update", {id: permission.value.id}), form);
};
</script>
<template>
    <!--Title-->
    <div class="flex justify-between my-0 items-center h-14 rounded-lg overflow-hidden mt-4">
        <span class="dark:text-light font-medium text-xl">{{ __("edit_title") }}</span>
    </div>
    <form @submit.prevent="submit">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 dark:text-light">
            <div class="col-span-1">
                <p class="text-sm">{{ __("edit_summary") }}</p>
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
                               autofocus
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
        <div class="my-14 flex justify-end">
            <Modal :href="route('admin.permissions.destroy', {id:permission.id})"
                   :message="__('delete_message')"
                   :title="__('delete_permission')"
                   danger
                   method="delete">
                <template #button="{open}">
                    <button class="px-4 py-2 bg-red-500 rounded-md text-white"
                            type="button"
                            @click="open">
                        {{ __("delete_permission") }}
                    </button>
                </template>
            </Modal>
        </div>
    </form>
</template>
<style lang="scss"
       scoped></style>
