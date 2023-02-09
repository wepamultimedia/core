<script setup>
import { Link } from "@inertiajs/inertia-vue3";</script>
<template>
    <Link :href="'/'">
        <inline-svg :alt="$page.props.default.appName"
                    class="h-14"
                    src="/images/logo.svg"/>
    </Link>
</template>
