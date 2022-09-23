<script setup>
import MainLayout from "@layouts/Core/Backend/MainLayout.vue";
import { reactive, toRefs } from "vue";
import { Inertia } from "@inertiajs/inertia";
import Checkbox from "@components/Form/Checkbox.vue";
import Input from "@components/Form/Input.vue";
import Modal from "@components/Modal.vue"

const props = defineProps({
    roles: Array,
    selectedRoles: Array,
    user: Object,
    errors: Object
});

const {
          user,
          selectedRoles
      } = toRefs(props);

const form = reactive({
    name: user.value.name,
    email: user.value.email,
    selectedRoles: selectedRoles.value
});

const submit = () => {
    Inertia.put(route("admin.users.update", {id: user.value.id}), form);
};

</script>
<template>
    <MainLayout :bc="[{label: __('users'), route: 'admin.users.index'}, {label: __('update')}]"
                icon="users"
                title="Users">
        <!--Title-->
        <div class="flex justify-between my-0 items-center h-14 mt-4">
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
                                   name="name"/>
                        </div>
                        <div class="col-span-6 sm:col-span-6 lg:col-span-5 xl:col-span-4 mb-6">
                            <Input v-model="form.email"
                                   :errors="errors"
                                   :label="__('email')"
                                   name="email"/>
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
                    <p class="text-sm">{{ __("select_roles") }}</p>
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
                            <span>{{ __("roles") }}</span>
                        </h2>
                        <Checkbox v-model="form.selectedRoles"
                                  :errors="errors"
                                  :options="roles"
                                  class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-y-6"
                                  label="description"
                                  name="selectedRoles"
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
            <div class="text-right my-14">
                <Modal :href="route('admin.users.destroy', {id:user.id})"
                       :message="__('delete_message')"
                       danger
                       method="delete"
                       :title="__('delete_user')">
                    <template #button="{open}">
                        <button type="button" class="px-4 py-2 bg-red-500 rounded-md text-white"
                                @click="open">
                            {{ __('delete_user') }}
                        </button>
                    </template>
                </Modal>
            </div>
        </form>
    </MainLayout>
</template>
<style lang="scss"
       scoped></style>
