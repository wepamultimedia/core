<script setup>
import MainLayout from "@layouts/Core/Backend/MainLayout.vue";
import { Link } from "@inertiajs/inertia-vue3";
import Table from "@components/Table.vue";

defineProps(["users"]);
</script>
<template>
    <MainLayout :bc="[{label: __('users')}]"
                :title="__('users')"
                icon="users">
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
                          class="text-xs font-bold leading-sm uppercase px-3 py-1 rounded-full bg-gray-100 dark:border-gray-600 dark:bg-gray-600 text-skin-muted dark:text-skin-muted-dark border">
                        {{ role.name }}
                    </span>
                </template>
            </Table>
        </div>
    </MainLayout>
</template>
<style lang="scss"
       scoped></style>