<script setup>
import { Head, usePage } from "@inertiajs/inertia-vue3";
import IconSizes from "@/Core/Mixins/iconSizes";

const props = defineProps(["title"]);

const page = usePage().props.value;
const seo = page.seo;
const title = seo?.title ? seo.title : props.title;
const url = page.default.baseUrl + (seo?.slug ? `/${seo.slug}` : "");
const locale = page.default.locales.find(loc => loc.code === page.default.locale);

</script>
<template>
    <Head :title="title">
        <link v-if="seo.canonical"
              :href="url"
              rel="canonical">
        <meta :content="seo.robots"
              name="robots">
        <meta v-if="seo.description" :content="seo.description"
              name="description">
        <meta :content="seo.image"
              itemprop="image">

        <!-- Social media -->
        <meta :content="locale.iso"
              property="og:locale">
        <meta v-if="seo.page_type" :content="seo.page_type"
              property="og:type">
        <meta :content="seo.facebook_title ? seo.facebook_title : seo.title"
              property="og:title">
        <meta :content="seo.facebook_description ? seo.facebook_description : seo.description"
              property="og:description">
        <meta :content="url"
              property="og:url">
        <meta :content="seo.title"
              property="og:site_name">
        <meta v-if="seo.image" :content="seo.image"
              property="og:image">
        <meta v-if="seo.site.address" :content="seo.site.address"
              property="og:street-address">
        <meta v-if="seo.site.latitude" :content="seo.site.latitude"
              property="og:latitude">
        <meta v-if="seo.site.longitude" :content="seo.site.longitude"
              property="og:longitude">

        <!--    <meta property="og:phone_number" :content="seo.title">-->
        <!-- Twitter -->
        <meta :content="seo.twitter_title ? seo.twitter_title : seo.title" property="twitter:title">
        <meta :content="seo.twitter_description ? seo.twitter_description : seo.description" property="twitter:description">
        <meta :content="seo.twitter_image ? seo.twitter_image : seo.image" property="twitter:image">
        <meta content="summary_large_image" property="twitter:card">


        <!-- Icons -->
        <link v-for="icon in IconSizes['android']"
              :href="`${page.default.storageUrl}/icons/${icon.name}.png`"
              :rel="icon.rel"
              :sizes="`${icon.size}x${icon.size}`"
              type="image/png">

        <link v-for="icon in IconSizes['favicon']"
              :href="`${page.default.storageUrl}/icons/${icon.name}.png`"
              :rel="icon.rel"
              :sizes="`${icon.size}x${icon.size}`"
              type="image/png">

        <link v-for="icon in IconSizes['apple']"
              :href="`${page.default.storageUrl}/icons/${icon.name}.png`"
              :rel="icon.rel"
              :sizes="`${icon.size}x${icon.size}`">



        <!--    <meta property="twitter:site" content="">-->
<!--        <link :title="seo.title"-->
<!--              href=""-->
<!--              rel="alternate"-->
<!--              type="application/rss+xml">-->


    </Head>
</template>
<style scoped></style>
