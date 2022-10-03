<script setup>
import { Head, useForm } from "@inertiajs/inertia-vue3";
import JetAuthenticationCard from "@/Jetstream/AuthenticationCard.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";

defineProps({
    status: String
});

const form = useForm({
    email: ""
});

const submit = () => {
    form.post(route("password.email"));
};
</script>
<template>
    <Head :title="__('forgot_password')"/>
    <JetAuthenticationCard>
        <template #logo>
            <img :alt="$page.props.appName"
                 class="h-14"
                 src="/images/logo.svg">
        </template>
        <div class="mb-4 text-sm text-gray-600 text-center">
            {{
                __("forgot_password_summary")
            }}
        </div>
        <div v-if="status"
             class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>
        <JetValidationErrors class="mb-4"/>
        <form @submit.prevent="submit">
            <div>
                <JetLabel :value="__('email')"
                          for="email"/>
                <JetInput id="email"
                          v-model="form.email"
                          autofocus
                          class="mt-1 block w-full"
                          required
                          type="email"/>
            </div>
            <div class="flex items-center justify-end mt-4">
                <JetButton :class="{ 'opacity-25': form.processing }"
                           :disabled="form.processing">
                    {{ __("email_password_reset") }}
                </JetButton>
            </div>
        </form>
    </JetAuthenticationCard>
</template>
