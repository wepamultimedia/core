<script setup>
import { Head, Link } from "@inertiajs/inertia-vue3";
import Sidebar from "@components/Sidebar.vue";
import Dropdown from "@components/Dropdown.vue";
import DarkModeToggle from "@components/DarkModeToggle.vue";
import { Inertia } from "@inertiajs/inertia";

const props = defineProps({
    title: String,
    icon: String,
    bc: {
        type: Array,
        default: []
    }
});

const logout = () => {
    Inertia.post(route("logout"));
};
</script>
<template>
    <div class="admin-custom bg-skin-background dark:bg-skin-background-dark min-h-screen grid">
        <Sidebar :title="title"
                 bg-class="bg-skin-primary"
                 dark
                 icon-class="fill-skin-base dark:fill-skin-base-dark">
            <template #header>
                <Head :title="title"></Head>
                <div class="flex pl-4 justify-between items-center">
                    <div class="min-w-max flex items-center gap-2">
                        <icon v-if="icon"
                              :icon="icon"
                              class="fill-skin-base dark:fill-skin-base-dark"/>
                        <h3 class="font-medium text-lg text-skin-base dark:text-skin-base-dark">{{ title }}</h3>
                    </div>
                    <div class="flex">
                        <DarkModeToggle class="md:mr-4"></DarkModeToggle>
                        <Dropdown class="hidden sm:inline-block"
                                  shadow>
                            <template #button="{open}">
                                <button v-if="$page.props.jetstream.managesProfilePhotos"
                                        class="flex items-center text-skin-base dark:text-skin-base-dark text-sm rounded-full transition pr-2">
                                    <img :alt="$page.props.user.name"
                                         :src="$page.props.user.profile_photo_url"
                                         class="h-8 w-8 rounded-full object-cover mr-2">
                                    <span class="hidden md:block">{{ $page.props.user.name }}</span>
                                    <inline-svg :class="{'rotate-180': open}"
                                                class="transition-all duration-200 ease-out fill-dark dark:fill-light grow ml-2"
                                                src="/core/icons/solid/chevron-down.svg"/>
                                </button>
                            </template>
                            <div class="flex flex-col min-w-[200px] divide-y divide-gray-200 dark:divide-gray-700 text-skin-base dark:text-skin-base-dark">
                                <button class="px-4 py-4 text-left">
                                    <a href="#">{{ __("profile") }}</a>
                                </button>
                                <!-- Authentication -->
                                <Link :href="route('logout')"
                                      as="button"
                                      class="px-4 py-4 text-left"
                                      method="post">
                                    {{ __("logout") }}
                                </Link>
                            </div>
                        </Dropdown>
                    </div>
                </div>
            </template>
            <!--Content-->
            <div class="mx-4 md:ml-0 md:mr-4">
                <div class="text-md bg-skin-inverted dark:bg-skin-inverted-dark shadow text-skin-base dark:text-skin-base-dark rounded-lg py-2 px-4 mb-4">
                    <Link :href="route('admin.dashboard')">Dashboard</Link>
                    <template v-if="bc.length"> /
                        <template v-for="(item, index) in bc">
                            <Link v-if="item.route"
                                  :href="route(item.route)"><span :class="{'font-bold': index === Object.keys(bc).length-1}">{{ item.label }}</span>
                            </Link>
                            <span v-else> <span :class="{'font-bold': index === Object.keys(bc).length-1}">{{ item.label }}</span></span>
                            <span v-if="index < Object.keys(bc).length-1"> / </span>
                        </template>
                    </template>
                </div>
                <div class="px-2 mt-6">
                    <slot></slot>
                </div>
            </div>
        </Sidebar>
    </div>
</template>
<style scoped></style>