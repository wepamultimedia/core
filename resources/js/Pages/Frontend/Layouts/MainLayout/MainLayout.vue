<script setup>
import HorizontalNavbar from "@pages/Vendor/Core/Frontend/Layouts/MainLayout/Partials/HorizontalNavbar/HorizontalNavbar.vue";
import MenuButton from "@/Vendor/Core/Components/Frontend/HorizontalNavbar/Partials/MenuButton.vue";
import Dropdown from "@/Vendor/Core/Components/Dropdown.vue";
import { Link, usePage } from "@inertiajs/vue3";
import { onMounted, ref } from "vue";
import DarkModeToggle from "@/Vendor/Core/Components/DarkModeToggle.vue";
import SeoInject from "@/Vendor/Core/Components/Frontend/SeoInject.vue";
import SocialNetworks from "@/Vendor/Core/Components/Frontend/SocialNetworks.vue"
import { useStore } from "vuex";

const store = useStore();
const props = defineProps(["title"]);
const pageProps = usePage().props.default;
const selectedLocale = ref(pageProps.locales.find(locale => locale.code === pageProps.locale));

const site = computed(() => {
    return store.getters["frontend/site"];
});

onMounted(() => {
    store.dispatch("frontend/loadSite");
})
</script>
<template>
    <SeoInject :title="title" :key="route().current()"/>

    <div class="default-theme bg-gray-100 dark:bg-gray-800 min-h-screen">
        <HorizontalNavbar :logo-alt="$page.props.appName"
                          content-class="container-fluid"
                          fixed
                          logo-src="/images/logo.svg"
                          navbar-class="bg-gray-200 dark:bg-gray-700">
            <template #header>
               <div class="h-8 flex items-center border-b border-gray-300 dark:border-gray-600">
                   <div class="container-fluid flex items-center">
                        <SocialNetworks class="px-1 fill-skin-base " />
                   </div>
               </div>
            </template>
            <template #logo>
                <img class="h-14 my-4"
                     src="/images/logo.svg"/>
            </template>
            <template #default="{close}">
                <div class="flex overflow-hidden flex-col md:flex-row justify-center h-full divide-y md:divide-x-0 md:divide-y-0 divide-gray-300 dark:divide-gray-600">
                    <MenuButton class="text-sm py-4"
                                @click="close()">
                        Home
                    </MenuButton>
                    <MenuButton class="text-sm py-4"
                                @click="close()">
                        About us
                    </MenuButton>
                    <MenuButton class="text-sm py-4"
                                @click="close()">
                        Blog
                    </MenuButton>
                    <MenuButton class="text-sm py-4"
                                @click="close()">
                        Contact
                    </MenuButton>
                </div>
                <Dropdown center>
                    <template #button="{open}">
                        <button class="min-w-max w-full px-4 py-2 flex items-center justify-center gap-2 text-sm"
                                type="button">
                            <img :src="'https://www.countryflagicons.com/FLAT/64/' + selectedLocale.iso.slice(-2) +'.png'"
                                 alt=""
                                 class="w-6">
                            <span>
                                {{ selectedLocale.code.toUpperCase() }}
                            </span>
                        </button>
                    </template>
                    <div class="grid divide-y divide-gray-200 dark:divide-gray-700">
                        <Link v-for="locale in $page.props.default.locales"
                              :href="route('locale', {locale: locale.code})"
                              class="px-4 py-2 text-sm text-skin-base "
                              type="button"
                              @click="selectedLocale=locale">
                            {{ locale.name }}
                        </Link>
                    </div>
                </Dropdown>
                <dark-mode-toggle/>
            </template>
        </HorizontalNavbar>
        <main class="container-fluid py-8">
            <slot></slot>
        </main>
    </div>
</template>
<style scoped></style>
