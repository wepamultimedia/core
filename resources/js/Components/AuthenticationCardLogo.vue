<script setup>
import { Link } from '@inertiajs/inertia-vue3';
</script>

<template>
    <Link :href="'/'">
        <img :alt="$page.props.appName"
             class="h-14"
             src="/images/logo.svg">
    </Link>
</template>
