<script setup>
import { Head, useForm } from "@inertiajs/inertia-vue3";
import JetAuthenticationCard from "@/Jetstream/AuthenticationCard.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";

const props = defineProps({
    email: String, token: String
});

const form = useForm({
    token: props.token, email: props.email, password: "", password_confirmation: ""
});

const submit = () => {
    form.post(route("password.update"), {
        onFinish: () => form.reset("password", "password_confirmation")
    });
};
</script>
<template>
    <Head :title="__('reset_password')"/>
    <JetAuthenticationCard>
        <template #logo>
            <img :alt="$page.props.appName"
                 class="h-14"
                 src="/images/logo.svg">
        </template>
        <JetValidationErrors class="mb-4"/>
        <form @submit.prevent="submit">
            <div>
                <JetLabel :value="__('email')"
                          for="email"/>
                <JetInput id="email"
                          v-model="form.email"
                          autofocus
                          class="mt-1 block w-full"
                          disabled
                          required
                          type="email"/>
            </div>
            <div class="mt-4">
                <JetLabel :value="__('password')"
                          for="password"/>
                <JetInput id="password"
                          v-model="form.password"
                          autocomplete="new-password"
                          class="mt-1 block w-full"
                          required
                          type="password"/>
            </div>
            <div class="mt-4">
                <JetLabel :value="__('confirm_password')"
                          for="password_confirmation"/>
                <JetInput id="password_confirmation"
                          v-model="form.password_confirmation"
                          autocomplete="new-password"
                          class="mt-1 block w-full"
                          required
                          type="password"/>
            </div>
            <div class="flex items-center justify-end mt-4">
                <JetButton :class="{ 'opacity-25': form.processing }"
                           :disabled="form.processing">
                    {{ __("reset_password") }}
                </JetButton>
            </div>
        </form>
    </JetAuthenticationCard>
</template>
