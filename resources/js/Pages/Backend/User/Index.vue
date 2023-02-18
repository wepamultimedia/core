<script>
import MainLayout from "@pages/Core/Backend/Layouts/MainLayout/MainLayout.vue";

export default {
    layout: (h, page) => h(MainLayout, {
        title: "users",
        icon: "users",
        bc: [{label: "users"}]
    }, () => page)
};
</script>
<script setup>
import { Link } from "@inertiajs/vue3";
import Table from "@core/Components/Table.vue";

defineProps(["users"]);
</script>
<template>
    <div class="flex justify-between my-0 items-center h-14 rounded-lg overflow-hidden my-6">
        <span class="text-skin-base dark:text-skin-base-dark font-medium text-xl">{{ __("users_list") }}</span>
        <Link :href="route('admin.users.create')"
              as="button"
              class="btn btn-success text-sm"
              type="button">{{ __("create") }}
        </Link>
    </div>
    <div class="w-full
                    bg-white dark:bg-gray-700
                    overflow-hidden
                    shadow
                    text-skin-base dark:text-skin-base-dark
                    rounded-lg
                    mb-20">
        <Table :columns="['name', {name: 'email', show: 'lg'}, {name: 'roles', show: 'md'}]"
               :data="users"
               delete-route="admin.users.destroy"
               divide-x
               edit-route="admin.users.edit"
               even
               search-route="admin.users.index">
            <template #col-content-roles="{item}">
                <span v-for="role in item.roles"
                      class="text-xs font-bold leading-sm uppercase px-3 py-1 rounded-full bg-gray-100 dark:border-gray-600 dark:bg-gray-600 text-skin-muted dark:text-skin-muted-dark border mx-0.5">
                    {{ role.name }}
                </span>
            </template>
        </Table>
    </div>
</template>
<style lang="scss"
       scoped></style>
