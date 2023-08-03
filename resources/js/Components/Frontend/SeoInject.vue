<script setup>
import {Head, usePage} from "@inertiajs/vue3";
import {ref} from "vue";

const props = defineProps(["title"]);

const page = usePage().props;
const seo = page.seo;
const title = ref(seo.title ? seo.title : props.title);
const url = page.default.baseUrl + (seo.slug ? `/${seo.slug}` : "");
const locale = page.default.locales.find(loc => loc.code === page.default.locale);

</script>
<template>
    <Head>
        <title>{{title}}</title>
        <link v-if="seo.canonical"
              :href="url"
              rel="canonical">
        <meta :content="seo.robots"
              name="robots">
        <meta v-if="seo.description"
              :content="seo.description"
              name="description">
        <meta :content="seo.image"
              itemprop="image">

        <!-- Social media -->
        <meta :content="locale.iso"
              property="og:locale">
        <meta v-if="seo.page_type"
              :content="seo.page_type"
              property="og:type">
        <meta :content="seo.facebook_title ? seo.facebook_title : seo.title"
              property="og:title">
        <meta :content="seo.facebook_description ? seo.facebook_description : seo.description"
              property="og:description">
        <meta :content="url"
              property="og:url">
        <meta :content="seo.title"
              property="og:site_name">
        <meta v-if="seo.image"
              :content="seo.image"
              property="og:image">
        <meta v-if="seo.site.address"
              :content="seo.site.address"
              property="og:street-address">
        <meta v-if="seo.site.latitude"
              :content="seo.site.latitude"
              property="og:latitude">
        <meta v-if="seo.site.longitude"
              :content="seo.site.longitude"
              property="og:longitude">

        <!--    <meta property="og:phone_number" :content="seo.title">-->
        <!-- Twitter -->
        <meta :content="seo.twitter_title ? seo.twitter_title : seo.title"
              property="twitter:title">
        <meta :content="seo.twitter_description ? seo.twitter_description : seo.description"
              property="twitter:description">
        <meta :content="seo.twitter_image ? seo.twitter_image : seo.image"
              property="twitter:image">
        <meta content="summary_large_image"
              property="twitter:card">


        <!-- Icons -->
        <link :href="`${page.default.storageUrl}/icons/android-icon-48x48.png`"
              rel="icon"
              sizes="48x48"
              type="image/png">
        <link :href="`${page.default.storageUrl}/icons/android-icon-96x96.png`"
              rel="icon"
              sizes="96x96"
              type="image/png">
        <link :href="`${page.default.storageUrl}/icons/android-icon-192x192.png`"
              rel="icon"
              sizes="192x192"
              type="image/png">
        <link :href="`${page.default.storageUrl}/icons/android-icon-256x256.png`"
              rel="icon"
              sizes="256x256"
              type="image/png">
        <link :href="`${page.default.storageUrl}/icons/favicon-16x16.png`"
              rel="icon"
              sizes="16x16"
              type="image/png">
        <link :href="`${page.default.storageUrl}/icons/favicon-32x32.png`"
              rel="icon"
              sizes="32x32"
              type="image/png">
        <link :href="`${page.default.storageUrl}/icons/favicon-48x48.png`"
              rel="icon"
              sizes="48x48"
              type="image/png">
        <link :href="`${page.default.storageUrl}/icons/favicon-57x57.png`"
              rel="icon"
              sizes="57x57"
              type="image/png">
        <link :href="`${page.default.storageUrl}/icons/favicon-57x57.png`"
              rel="icon"
              sizes="57x57"
              type="image/png">
        <link :href="`${page.default.storageUrl}/icons/favicon-60x60.png`"
              rel="icon"
              sizes="60x60"
              type="image/png">
        <link :href="`${page.default.storageUrl}/icons/favicon-70x70.png`"
              rel="icon"
              sizes="70x70"
              type="image/png">
        <link :href="`${page.default.storageUrl}/icons/favicon-72x72.png`"
              rel="icon"
              sizes="72x72"
              type="image/png">
        <link :href="`${page.default.storageUrl}/icons/favicon-76x76.png`"
              rel="icon"
              sizes="76x76"
              type="image/png">
        <link :href="`${page.default.storageUrl}/icons/favicon-114x114.png`"
              rel="icon"
              sizes="114x114"
              type="image/png">
        <link :href="`${page.default.storageUrl}/icons/favicon-120x120.png`"
              rel="icon"
              sizes="120x120"
              type="image/png">
        <link :href="`${page.default.storageUrl}/icons/favicon-144x144.png`"
              rel="icon"
              sizes="144x144"
              type="image/png">
        <link :href="`${page.default.storageUrl}/icons/favicon-150x150.png`"
              rel="icon"
              sizes="150x150"
              type="image/png">
        <link :href="`${page.default.storageUrl}/icons/favicon-152x152.png`"
              rel="icon"
              sizes="152x152"
              type="image/png">
        <link :href="`${page.default.storageUrl}/icons/favicon-180x180.png`"
              rel="icon"
              sizes="180x180"
              type="image/png">
        <link :href="`${page.default.storageUrl}/icons/favicon-192x192.png`"
              rel="icon"
              sizes="192x192"
              type="image/png">
        <link :href="`${page.default.storageUrl}/icons/favicon-256x256.png`"
              rel="icon"
              sizes="256x256"
              type="image/png">
        <link :href="`${page.default.storageUrl}/icons/favicon-310x310.png`"
              rel="icon"
              sizes="310x310"
              type="image/png">
        <link :href="`${page.default.storageUrl}/icons/favicon-512x512.png`"
              rel="icon"
              sizes="512x512"
              type="image/png">
        <link :href="`${page.default.storageUrl}/icons/apple-icon-57x57.png`"
              rel="apple-touch-icon"
              sizes="57x57">
        <link :href="`${page.default.storageUrl}/icons/apple-icon-60x60.png`"
              rel="apple-touch-icon"
              sizes="60x60">
        <link :href="`${page.default.storageUrl}/icons/apple-icon-70x70.png`"
              rel="apple-touch-icon"
              sizes="70x70">
        <link :href="`${page.default.storageUrl}/icons/apple-icon-76x76.png`"
              rel="apple-touch-icon"
              sizes="76x76">
        <link :href="`${page.default.storageUrl}/icons/apple-icon-114x114.png`"
              rel="apple-touch-icon"
              sizes="114x114">
        <link :href="`${page.default.storageUrl}/icons/apple-icon-120x120.png`"
              rel="apple-touch-icon"
              sizes="120x120">
        <link :href="`${page.default.storageUrl}/icons/apple-icon-144x144.png`"
              rel="apple-touch-icon"
              sizes="144x144">
        <link :href="`${page.default.storageUrl}/icons/apple-icon-142x142.png`"
              rel="apple-touch-icon"
              sizes="142x142">
        <link :href="`${page.default.storageUrl}/icons/apple-icon-180x180.png`"
              rel="apple-touch-icon"
              sizes="180x180">
    </Head>
</template>
<style scoped></style>
