<script setup>
import { Head, useForm } from "@inertiajs/inertia-vue3";
import AuthenticationCard from "@/Core/Components/AuthenticationCard.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import { useDark } from "@vueuse/core";
import Input from "@core/Components/Form/Input.vue";

const isDark = useDark();

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
    <AuthenticationCard class="dark:bg-gray-900">
        <template #logo>
            <img :alt="$page.props.default.appName"
                 class="h-14"
                 src="/images/logo.svg">
        </template>
        <form @submit.prevent="submit">
            <div class="my-4">
                <Input v-model="form.email"
                       :errors="form.errors"
                       :label="__('email')"
                       autofocus
                       name="email"
                       required
                       type="email"/>
            </div>
            <div class="my-4">
                <Input v-model="form.password"
                       :errors="form.errors"
                       :label="__('password')"
                       autocomplete="new-password"
                       autofocus
                       name="password"
                       required
                       type="password"/>
            </div>
            <div class="my-4">
                <Input v-model="form.password_confirmation"
                       :errors="form.errors"
                       :label="__('confirm_password')"
                       autocomplete="new-password"
                       autofocus
                       name="password_confirmation"
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
