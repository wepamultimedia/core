<script>
import MainLayout from "@pages/Vendor/Core/Backend/Layouts/MainLayout/MainLayout.vue";

export default {
    layout: (h, page) => h(MainLayout, {
        title: "Seo",
        bc: [{label: "seo"}]
    }, () => page)
};
</script>
<script setup>
import { Link } from "@inertiajs/vue3";
import Table from "@/Vendor/Core/Components/Table.vue";

defineProps(["routes"]);
</script>
<template>
    <div class="flex justify-between my-0 items-center h-14 my-6">
        <span class="font-medium text-xl">{{ __("routes_list") }}</span>
        <div class="flex justify-end gap-2">
        <Link :href="route('admin.seo.create')"
              as="button"
              class="btn btn-success text-sm"
              type="button">{{ __("create") }}
        </Link>
        </div>
    </div>
    <div class="w-full
                overflow-hidden
                shadow
                bg-white dark:bg-gray-700
                rounded-lg
                mb-20">
        <Table :columns="['alias', 'slug', 'package']"
               :data="routes"
               delete-route="admin.seo.destroy"
               divide-x
               edit-route="admin.seo.edit"
               even
               search-route="admin.seo.index">
            <template #col-content-slug="{item}">
                <a :href="$page.props.default.baseUrl + '/' + item.slug"
                   :title="$page.props.default.baseUrl + '/' + item.slug"
                   class="font-bold text-lg block"
                   :class="{'text-green-400': !item.slug_redirect, 'text-red-400': item.slug_redirect}"
                   target="_blank">{{ item.slug ? item.slug : '/' }}
                </a>
                <div v-show="item.slug_redirect" class="mt-2">
                    <span class="text-sm font-bold">{{ __("slug_redirect") }}: {{item.redirect}}</span>
                    <a  :href="$page.props.default.baseUrl + '/' + item.slug_redirect"
                       :title="$page.props.default.baseUrl + '/' + item.slug_redirect"
                       class="font-bold text-lg block text-green-400"
                       target="_blank">{{ item.slug_redirect }}
                    </a>
                </div>
                <div class="mt-2">
                    <div v-if="item.controller && item.action">
                        <span class="text-sm">
                            {{ item.controller?.split("\\")[item.controller.split("\\").length - 1] }}
                        </span>
                        <span class="text-sm">/{{ item.action }}</span>
                    </div>
                    <div v-if="item.route_params">
                        <span class="text-sm font-bold">{{ __("route_params") }}:</span>
                        <span class="text-sm">{{ item.route_params }}</span>
                    </div>
                    <div v-if="item.request_params">
                        <span class="text-sm font-bold">{{ __("request_params") }}:</span>
                        <span class="text-sm">{{ item.request_params }}</span>
                    </div>
                </div>
            </template>
        </Table>
    </div>
</template>
<style lang="scss"
       scoped></style>
