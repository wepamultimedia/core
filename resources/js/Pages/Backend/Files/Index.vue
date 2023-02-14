<script>
import MainLayout from "@pages/Core/Backend/Layouts/MainLayout/MainLayout.vue";

export default {
    layout: (h, page) => h(MainLayout, {
        title: "files",
        icon: "flag",
        bc: [{label: "files"}]
    }, () => page)
};
</script>
<script setup>
import FileManager from "@/Core/Components/Backend/FileManager.vue";
import { ref } from "vue";

const selectedFile = ref("");
</script>
<template>
    <FileManager v-model="selectedFile"
                 breadcrumb-classes="mb-4"
                 content-classes="w-full overflow-hidden shadow bg-white dark:bg-gray-700 rounded-lg p-6"/>
</template>
<style scoped></style>
