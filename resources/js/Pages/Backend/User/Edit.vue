<script>
import MainLayout from "@pages/Vendor/Core/Backend/Layouts/MainLayout/MainLayout.vue";

export default {
    layout: (h, page) => h(MainLayout, {
        title: "users", icon: "users", bc: [{label: "users", route: "admin.users.index"}, {label: "edit"}]
    }, () => page)
};
</script>
<script setup>
import { toRefs } from "vue";
import Checkbox from "@/Vendor/Core/Components/Form/Checkbox.vue";
import Input from "@/Vendor/Core/Components/Form/Input.vue";
import SaveFormButton from "@/Vendor/Core/Components/Form/SaveFormButton.vue";
import Modal from "@/Vendor/Core/Components/Modal.vue";
import { useForm } from "@inertiajs/vue3";
import { useStore } from "vuex";
import { __ } from "@/Vendor/Core/Mixins/translations";

const props = defineProps({
    roles: Array, selectedRoles: Array, user: Object, errors: Object
});
const {user, selectedRoles} = toRefs(props);

const form = useForm({
    name: user.value.name,
    email: user.value.email,
    selectedRoles: selectedRoles.value
});
const store = useStore();

const submit = () => {
    form.put(route("admin.users.update", {id: user.value.id}), {
        onSuccess: () => store.dispatch("backend/addAlert", {type: "success", message: __("saved")}),
        onError: () => store.dispatch("backend/addAlert", {type: "error", message: form.errors})
    });
};
</script>
<template>
    <!--Title-->
    <div class="flex justify-between my-0 items-center h-14 mt-4">
        <span class="dark:text-light font-medium text-xl">{{ __("edit_title") }}</span>
    </div>
    <form @submit.prevent="submit">
        <div class="max-w-7xl">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 dark:text-light">
                <div class="col-span-1">
                    <p class="text-sm">{{ __("edit_summary") }}</p>
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
                            <Input v-model="form.email"
                                   :errors="errors"
                                   :label="__('email')"
                                   name="email"/>
                        </div>
                    </div>
                    <div class="p-3 bg-gray-200 dark:bg-gray-500 flex justify-end">
                        <SaveFormButton :form="form"/>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 dark:text-light mt-8">
                <div class="col-span-1">
                    <p class="text-sm">{{ __("select_roles") }}</p>
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
                            <span>{{ __("roles") }}</span>
                        </h2>
                        <Checkbox v-model="form.selectedRoles"
                                  :errors="errors"
                                  :options="roles"
                                  class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-y-6"
                                  label="description"
                                  name="selectedRoles"
                                  property-value="name"/>
                    </div>
                    <div class="p-3 bg-gray-200 dark:bg-gray-500 flex justify-end">
                        <SaveFormButton :form="form"/>
                    </div>
                </div>
            </div>
            <div class="text-right my-14">
                <Modal :href="route('admin.users.destroy', {id:user.id})"
                       :message="__('delete_message')"
                       :title="__('delete_user')"
                       danger
                       method="delete">
                    <template #button="{open}">
                        <button class="px-4 py-2 bg-red-500 rounded-md text-white"
                                type="button"
                                @click="open">
                            {{ __("delete_user") }}
                        </button>
                    </template>
                </Modal>
            </div>
        </div>
    </form>
</template>
<style lang="scss"
       scoped></style>
