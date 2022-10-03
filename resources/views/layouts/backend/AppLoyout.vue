<script setup>
import { ref } from "vue";
import { Inertia } from "@inertiajs/inertia";
import {
    Head,
    Link
} from "@inertiajs/inertia-vue3";
</script>

<template>
    <div>
        <Head :title="title" />
        <div class="min-h-screen bg-gray-100">
            <div class="bg-blue-900 min-w-[200px]">sidebar</div>
            <div>content</div>
        </div>
    </div>
</template>
<style scoped></style>