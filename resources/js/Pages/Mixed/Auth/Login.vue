<script setup>
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import AuthenticationCard from "@/Components/AuthenticationCard.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import Checkbox from "@/Components/Checkbox.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    admin: Boolean,
    canResetPassword: Boolean,
    status: String
});

const form = useForm({
    email: "",
    password: "",
    remember: false
});

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? "on" : ""
    })).post(route(props.admin ? "admin.login" : "login"), {
        onFinish: () => form.reset("password")
    });
};
</script>
<template>
    <Head :title="__('login')"/>
    <AuthenticationCard>
        <template #logo>
            <img :alt="$page.props.appName"
                 class="h-14"
                 src="/images/logo.svg">
        </template>
        <div v-if="status"
             class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>
        <form @submit.prevent="submit">
            <div>
                <InputLabel :value="__('email')"
                          for="email"/>
                <TextInput id="email"
                          v-model="form.email"
                          autofocus
                          class="mt-1 block w-full"
                          required
                          type="email"/>
                <InputError class="mt-2" :message="form.errors.email" />
            </div>
            <div class="mt-4">
                <InputLabel :value="__('password')"
                          for="password"/>
                <TextInput id="password"
                          v-model="form.password"
                          autocomplete="current-password"
                          class="mt-1 block w-full"
                          required
                          type="password"/>
                <InputError class="mt-2" :message="form.errors.password" />
            </div>
            <div class="block mt-4">
                <label class="flex items-center">
                    <Checkbox v-model:checked="form.remember"
                                 name="remember"/>
                    <span class="ml-2 text-sm text-gray-600">{{ __("remember_me") }}</span>
                </label>
            </div>
            <div class="flex items-center justify-end mt-4">
                <Link v-if="canResetPassword"
                      :href="route('password.request')"
                      class="underline text-sm text-gray-600 hover:text-gray-900">
                    {{ __("forgot_password") }}
                </Link>
                <PrimaryButton :class="{ 'opacity-25': form.processing }"
                           :disabled="form.processing"
                           class="ml-4">
                    {{ __("login") }}
                </PrimaryButton>
            </div>
        </form>
    </AuthenticationCard>
</template>
