<script>
import MainLayout from "@pages/Core/Layouts/Backend/MainLayout.vue";

export default {
    layout: (h, page) => h(MainLayout, {
        title: "users",
        icon: "users",
        bc: [
            {
                label: "users",
                route: "admin.users.index"
            },
            {label: "create"}
        ]
    }, () => page)
};
</script>
<script setup>
import { reactive, ref, defineProps } from "vue";
import { Inertia } from "@inertiajs/inertia";
import Checkbox from "@core/Components/Form/Checkbox.vue";
import Input from "@core/Components/Form/Input.vue";

defineProps({
    roles: Array,
    errors: Object
});

const selectedRoles = ref([]);
const form = reactive({
    name: null,
    email: "",
    selectedRoles: selectedRoles.value,
    password: "",
    password_confirmation: ""
});
const submit = () => {
    Inertia.post(route("admin.users.store"), form);
};
</script>
<template>
    <form class="mb-14 text-skin-base dark:text-skin-base-dark"
          @submit.prevent="submit">
        <!--Title-->
        <div class="flex justify-between my-0 items-center h-14 rounded-lg overflow-hidden mt-4">
            <span class=" font-medium text-xl">{{ __("create_title") }}</span>
        </div>
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
                        <Input v-model="form.email"
                               :errors="errors"
                               :label="__('email')"
                               name="email"/>
                    </div>
                    <div class="col-span-6 sm:col-span-6 lg:col-span-5 xl:col-span-4 mb-6">
                        <Input v-model="form.password"
                               :errors="errors"
                               :label="__('password')"
                               name="password"
                               type="password"/>
                    </div>
                    <div class="col-span-6 sm:col-span-6 lg:col-span-5 xl:col-span-4">
                        <Input v-model="form.password_confirmation"
                               :errors="errors"
                               :label="__('repassword')"
                               name="password_confirmation"
                               type="password"/>
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
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mt-8">
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
                    <h2 class="text-xl block mb-6 font-medium text-sm">
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
    </form>
</template>
<style lang="scss"
       scoped></style>