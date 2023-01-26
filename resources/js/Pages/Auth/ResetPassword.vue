<script setup>
import { Head, useForm } from "@inertiajs/inertia-vue3";
import AuthenticationCard from "@/Components/AuthenticationCard.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";

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
    <AuthenticationCard>
        <template #logo>
            <img :alt="$page.props.appName"
                 class="h-14"
                 src="/images/logo.svg">
        </template>
        <form @submit.prevent="submit">
            <div>
                <InputLabel :value="__('email')"
                          for="email"/>
                <TextInput id="email"
                          v-model="form.email"
                          autofocus
                          class="mt-1 block w-full"
                          disabled
                          required
                          type="email"/>
            </div>
            <div class="mt-4">
                <InputLabel :value="__('password')"
                          for="password"/>
                <TextInput id="password"
                          v-model="form.password"
                          autocomplete="new-password"
                          class="mt-1 block w-full"
                          required
                          type="password"/>
            </div>
            <div class="mt-4">
                <InputLabel :value="__('confirm_password')"
                          for="password_confirmation"/>
                <TextInput id="password_confirmation"
                          v-model="form.password_confirmation"
                          autocomplete="new-password"
                          class="mt-1 block w-full"
                          required
                          type="password"/>
            </div>
            <div class="flex items-center justify-end mt-4">
                <PrimaryButton :class="{ 'opacity-25': form.processing }"
                           :disabled="form.processing">
                    {{ __("reset_password") }}
                </PrimaryButton>
            </div>
        </form>
    </AuthenticationCard>
</template>
