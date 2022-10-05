<script setup>
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import JetAuthenticationCard from "@/Jetstream/AuthenticationCard.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetCheckbox from "@/Jetstream/Checkbox.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";

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
    <JetAuthenticationCard>
        <template #logo>
            <img :alt="$page.props.appName"
                 class="h-14"
                 src="/images/logo.svg">
        </template>
        <JetValidationErrors class="mb-4"/>
        <div v-if="status"
             class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>
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
            <div class="mt-4">
                <JetLabel :value="__('password')"
                          for="password"/>
                <JetInput id="password"
                          v-model="form.password"
                          autocomplete="current-password"
                          class="mt-1 block w-full"
                          required
                          type="password"/>
            </div>
            <div class="block mt-4">
                <label class="flex items-center">
                    <JetCheckbox v-model:checked="form.remember"
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
                <JetButton :class="{ 'opacity-25': form.processing }"
                           :disabled="form.processing"
                           class="ml-4">
                    {{ __("login") }}
                </JetButton>
            </div>
        </form>
    </JetAuthenticationCard>
</template>
