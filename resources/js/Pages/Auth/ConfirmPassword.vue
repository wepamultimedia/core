<script setup>
import { ref } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import AuthenticationCard from "@/Core/Components/AuthenticationCard.vue";
import AuthenticationCardLogo from "@/Core/Components/AuthenticationCardLogo.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";
import { useDark } from "@vueuse/core";
import Input from "@core/Components/Form/Input.vue";


const isDark = useDark();

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
    <AuthenticationCard class="dark:bg-gray-900">
        <template #logo>
            <AuthenticationCardLogo/>
        </template>
        <div class="mb-4 text-sm text-gray-600">
            {{ __("auth.secure_area_summary") }}
        </div>
        <form @submit.prevent="submit">
            <div>
                <Input v-model="form.password"
                       ref="passwordInput"
                       autofocus
                       :errors="form.errors"
                       :label="__('password')"
                       name="password"
                       required
                       type="password"/>
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
