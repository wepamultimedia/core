<script setup>
import { computed } from "vue";
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import JetAuthenticationCard from "@/Jetstream/AuthenticationCard.vue";
import JetButton from "@/Jetstream/Button.vue";

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
    <JetAuthenticationCard>
        <template #logo>
            <img :alt="$page.props.appName"
                 class="h-14"
                 src="/images/logo.svg">
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
                <JetButton :class="{ 'opacity-25': form.processing }"
                           :disabled="form.processing">
                    {{ __("auth.resend_verification_email") }}
                </JetButton>
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
    </JetAuthenticationCard>
</template>
