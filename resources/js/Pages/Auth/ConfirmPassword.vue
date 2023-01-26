<script setup>
import { ref } from "vue";
import { Head, useForm } from "@inertiajs/inertia-vue3";
import AuthenticationCard from "@/Components/AuthenticationCard.vue";
import AuthenticationCardLogo from "@/Core/Components/AuthenticationCardLogo.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";

const form = useForm({
    password: ""
});

const passwordInput = ref(null);

const submit = () => {
    form.post(route("password.confirm"), {
        onFinish: () => {
            form.reset();

            passwordInput.value.focus();
        }
    });
};
</script>
<template>
    <Head :title="__('secure_area')"/>
    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo/>
        </template>
        <div class="mb-4 text-sm text-gray-600">
            {{ __("auth.secure_area_summary") }}
        </div>
        <form @submit.prevent="submit">
            <div>
                <TextInput :value="__('password')"
                           for="password"/>
                <TextInput id="password"
                           ref="passwordInput"
                           v-model="form.password"
                           autocomplete="current-password"
                           autofocus
                           class="mt-1 block w-full"
                           required
                           type="password"/>
                <InputError :message="form.errors.password"
                            class="mt-2"/>
            </div>
            <div class="flex justify-end mt-4">
                <PrimaryButton :class="{ 'opacity-25': form.processing }"
                               :disabled="form.processing"
                               class="ml-4">
                    {{ __("confirm") }}
                </PrimaryButton>
            </div>
        </form>
    </AuthenticationCard>
</template>
