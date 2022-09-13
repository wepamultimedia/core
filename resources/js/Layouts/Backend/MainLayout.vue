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
    <div class="bg-[#F3F4F6] dark:bg-gray-800 min-h-screen grid">
        <Sidebar :title="title"
                 bg-class="bg-gradient-to-r to-primary-400 from-primary-600"
                 dark
                 icon-class="fill-light"
                 text-class="text-light dark:text-light">
            <template #header>
                <Head :title="title"></Head>
                <div class="flex pl-4 justify-between items-center">
                    <div class="text-dark dark:text-light font-medium text-lg min-w-max flex items-center gap-2">
                        <icon v-if="icon"
                              :icon="icon"
                              class="fill-dark dark:fill-light"/>
                        <span>{{ title }}</span>
                    </div>
                    <div class="flex">
                        <DarkModeToggle class="md:mr-4"></DarkModeToggle>
                        <Dropdown class="hidden sm:inline-block"
                                  shadow>
                            <template #button="{open}">
                                <button v-if="$page.props.jetstream.managesProfilePhotos"
                                        class="flex items-center text-sm rounded-full transition pr-2">
                                    <img :alt="$page.props.user.name"
                                         :src="$page.props.user.profile_photo_url"
                                         class="h-8 w-8 rounded-full object-cover mr-2">
                                    <span class="hidden md:block">{{ $page.props.user.name }}</span>
                                    <inline-svg src="/core/icons/solid/chevron-down.svg"
                                                class="transition-all duration-200 ease-out fill-dark dark:fill-light grow ml-2"
                                                :class="{'rotate-180': open}"/>
                                </button>
                            </template>
                            <div class="flex flex-col min-w-[200px]">
                                <button class="px-4 py-4 text-left">
                                    <a href="#">{{ __("profile") }}</a>
                                </button>
                                <!-- Authentication -->
                                <Link :href="route('logout')"
                                      as="button"
                                      class="px-4 py-4 border-t border-gray-100 text-left"
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
                <div class="text-md bg-white shadow dark:bg-gray-900 text-gray-800 dark:text-gray-300 rounded-lg py-2 px-4 mb-4">
                    <Link :href="route('admin.dashboard')">Dashboard</Link>
                    <template v-if="bc.length"> /
                        <template v-for="(item, index) in bc">
                            <Link v-if="item.route"
                                  :href="route(item.route)">{{ item.label }}
                            </Link>
                            <span v-else>{{ item.label }}</span>
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