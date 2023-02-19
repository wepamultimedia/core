<script>
import MainLayout from "@pages/Vendor/Core/Backend/Layouts/MainLayout/MainLayout.vue";

export default {
    layout: (h, page) => h(MainLayout, {
        title: "Translations",
        icon: "flag",
        bc: [{label: "translation"}]
    }, () => page)
};
</script>
<script setup>
</script>
<template>
    <h1>Translation</h1>
</template>
<style scoped></style>
