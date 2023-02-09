<script setup>
import { Head, useForm } from "@inertiajs/inertia-vue3";
import AuthenticationCard from "@/Core/Components/AuthenticationCard.vue";
import AuthenticationCardLogo from "@/Core/Components/AuthenticationCardLogo.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { useDark } from "@vueuse/core";
import Input from "@core/Components/Form/Input.vue";

const isDark = useDark();

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
    <AuthenticationCard class="dark:bg-gray-900">
        <template #logo>
            <AuthenticationCardLogo/>
        </template>
        <div class="mb-4 text-sm text-center">
            {{
                __("forgot_password_summary")
            }}
        </div>
        <div v-if="status"
             class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>
        <form @submit.prevent="submit">
            <div>
                <Input v-model="form.email"
                       :errors="form.errors"
                       :label="__('email')"
                       autofocus
                       name="email"
                       required
                       type="email"/>
            </div>
            <div class="flex items-center justify-end mt-4">
                <PrimaryButton :class="{ 'opacity-25': form.processing }"
                               :disabled="form.processing">
                    {{ __("email_password_reset") }}
                </PrimaryButton>
            </div>
        </form>
    </AuthenticationCard>
</template>
