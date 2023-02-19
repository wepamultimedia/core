<script setup>
import { computed } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import AuthenticationCard from "@/Vendor/Core/Components/AuthenticationCard.vue";
import AuthenticationCardLogo from "@/Vendor/Core/Components/AuthenticationCardLogo.vue";
import { useDark } from "@vueuse/core";

const isDark = useDark();

const props = defineProps({
    status: String
});

const form = useForm();

const submit = () => {
    form.post(route("verification.send"));
};

const verificationLinkSent = computed(() => props.status === "verification-link-sent");
</script>
<template>
    <Head :title="__('email_verification')"/>
    <AuthenticationCard class="dark:bg-gray-900">
        <template #logo>
            <AuthenticationCardLogo />
        </template>
        <div class="mb-4 text-sm text-gray-600">
            {{
                __("auth.email_verification_summary_1")
            }}
        </div>
        <div v-if="verificationLinkSent"
             class="mb-4 font-medium text-sm text-green-600">
            {{
                __("auth.email_verification_summary_2")
            }}
        </div>
        <form @submit.prevent="submit">
            <div class="mt-4 flex items-center justify-between">
                <PrimaryButton :class="{ 'opacity-25': form.processing }"
                               :disabled="form.processing"
                               class="ml-4">
                    {{ __("auth.resend_verification_email") }}
                </PrimaryButton>
                <div>
                    <Link :href="route('profile.show')"
                          class="underline text-sm text-gray-600 hover:text-gray-900">
                        {{ __("edit_profile") }}
                    </Link>
                    <Link :href="route('logout')"
                          as="button"
                          class="underline text-sm text-gray-600 hover:text-gray-900 ml-2"
                          method="post">
                        {{ __("logout") }}
                    </Link>
                </div>
            </div>
        </form>
    </AuthenticationCard>
</template>
