<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import AuthenticationCard from "@/Vendor/Core/Components/AuthenticationCard.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Checkbox from "@/Components/Checkbox.vue";
import AuthenticationCardLogo from "@/Vendor/Core/Components/AuthenticationCardLogo.vue";
import { useDark } from "@vueuse/core";
import Input from "@/Vendor/Core/Components/Form/Input.vue";

const isDark = useDark();

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
            <AuthenticationCardLogo/>
        </template>
        <div v-if="status"
             class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>
        <form class="text-skin-base dark:text-skin-base-dark"
              @submit.prevent="submit">
            <div class="my-4">
                <Input v-model="form.email"
                       :errors="form.errors"
                       :label="__('email')"
                       autofocus
                       name="email"
                       required
                       type="email"/>
            </div>
            <div class="mt-4">
                <Input v-model="form.password"
                       :errors="form.errors"
                       :label="__('password')"
                       name="password"
                       required
                       type="password"/>
            </div>
            <div class="block mt-4">
                <label class="flex items-center">
                    <Checkbox v-model:checked="form.remember"
                              name="remember"/>
                    <span class="ml-2 text-sm">{{ __("remember_me") }}</span>
                </label>
            </div>
            <div class="flex items-center justify-end mt-4">
                <Link v-if="canResetPassword"
                      :href="route('password.request')"
                      class="underline text-sm">
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
