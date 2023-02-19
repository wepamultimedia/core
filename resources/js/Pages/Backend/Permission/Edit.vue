<script>
import MainLayout from "@pages/Vendor/Core/Backend/Layouts/MainLayout/MainLayout.vue";

export default {
    layout: (h, page) => h(MainLayout, {
        title: "roles_permissions",
        icon: "key",
        bc: [
            {
                label: "permissions",
                route: "admin.permissions.index"
            }, {label: "edit"}
        ]
    }, () => page)
};
</script>
<script setup>
import { toRefs } from "vue";
import Input from "@/Vendor/Core/Components/Form/Input.vue";
import Modal from "@/Vendor/Core/Components/Modal.vue";
import { useForm } from "@inertiajs/vue3";
import { __ } from "@/Vendor/Core/Mixins/translations";
import { useStore } from "vuex";
import SaveFormButton from "@/Vendor/Core/Components/Form/SaveFormButton.vue";

const props = defineProps({
    translations: Object,
    permission: Object,
    errors: Object
});

const {
          translations,
          permission
      } = toRefs(props);

const store = useStore();
const form = useForm({
    name: permission.value.name,
    translations: translations.value
});

const submit = () => {
    form.put(route("admin.permissions.update", {id: permission.value.id}), {
        onSuccess: () => store.dispatch("addAlert", {type: "success", message: __("saved")}),
        onError: () => store.dispatch("addAlert", {type: "error", message: form.errors})
    });
};
</script>
<template>
    <!--Title-->
    <div class="flex justify-between my-0 items-center h-14 rounded-lg overflow-hidden mt-4">
        <span class="font-medium text-xl">{{ __("edit_title") }}</span>
    </div>
    <form @submit.prevent="submit">
        <div class="max-w-7xl">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <div class="col-span-1">
                    <p class="text-sm">{{ __("edit_summary") }}</p>
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
                    <div class="rounded-b-lg overflow-hidden">
                        <div class="p-3 bg-gray-200 dark:bg-gray-500 flex justify-end">
                            <SaveFormButton :form="form"/>
                        </div>
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
        </div>
    </form>
</template>
<style lang="scss"
       scoped></style>
