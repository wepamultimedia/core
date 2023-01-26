<script setup>
import { Head, useForm } from "@inertiajs/inertia-vue3";
import AuthenticationCard from "@/Components/AuthenticationCard.vue";
import AuthenticationCardLogo from "@/Core/Components/AuthenticationCardLogo.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";

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
    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />
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
                <InputError :message="form.errors.email"
                            class="mt-2"/>
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
