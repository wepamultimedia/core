<script setup>
import { Link } from "@inertiajs/vue3";</script>
<template>
    <Link :href="'/'">
        <inline-svg :alt="$page.props.default.appName"
                    class="h-14"
                    src="/vendor/core/images/logo.svg"/>
    </Link>
</template>
