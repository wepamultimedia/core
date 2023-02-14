<script setup>
import HorizontalNavbar from "@core/Pages/Frontend/Layouts/MainLayout/Partials/HorizontalNavbar/HorizontalNavbar.vue";
import MenuButton from "@core/Components/Frontend/HorizontalNavbar/Partials/MenuButton.vue";
import Dropdown from "@core/Components/Dropdown.vue";
import { Head, Link, usePage } from "@inertiajs/inertia-vue3";
import { computed, ref } from "vue";
import DarkModeToggle from "@/Core/Components/DarkModeToggle.vue";
import SeoInject from "@core/Components/Frontend/SeoInject.vue";
import storeFrontend from "@core/Store/frontend";
import SocialNetworks from "@core/Components/Frontend/SocialNetworks.vue"

const props = defineProps(["title"]);
const pageProps = usePage().props.value.default;
const selectedLocale = ref(pageProps.locales.find(locale => locale.code === pageProps.locale));

const site = computed(() => {
    return storeFrontend.getters["site"];
});

storeFrontend.dispatch("loadSite");
</script>
<template>
    <SeoInject :title="title"/>
    <Head>
        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css?family=aclonica:400"
              rel="stylesheet"/>
    </Head>
    <div class="default-theme bg-gray-100 dark:bg-gray-800 min-h-screen">
        <HorizontalNavbar :logo-alt="$page.props.appName"
                          content-class="container-fluid"
                          fixed
                          logo-src="/images/logo.svg"
                          navbar-class="bg-gray-200 dark:bg-gray-700">
            <template #header>
               <div class="h-8 flex items-center border-b border-gray-300 dark:border-gray-600">
                   <div class="container-fluid flex items-center">
                        <SocialNetworks class="px-1 fill-skin-base dark:fill-skin-base-dark" />
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
                              class="px-4 py-2 text-sm text-skin-base dark:text-skin-base-dark"
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
