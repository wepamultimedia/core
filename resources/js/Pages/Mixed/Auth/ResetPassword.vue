<script setup>
import {
    Head,
    useForm
} from "@inertiajs/inertia-vue3";
import JetAuthenticationCard from "@/Jetstream/AuthenticationCard.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";

const props = defineProps({
    email: String,
    token: String
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: "",
    password_confirmation: ""
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
            <img src="/images/logo.svg"
                 :alt="$page.props.appName"
                 class="h-14">
        </template>
        <JetValidationErrors class="mb-4"/>
        <form @submit.prevent="submit">
            <div>
                <JetLabel for="email"
                          :value="__('email')"/>
                <JetInput id="email"
                          disabled
                          v-model="form.email"
                          type="email"
                          class="mt-1 block w-full"
                          required
                          autofocus/>
            </div>
            <div class="mt-4">
                <JetLabel for="password"
                          :value="__('password')"/>
                <JetInput id="password"
                          v-model="form.password"
                          type="password"
                          class="mt-1 block w-full"
                          required
                          autocomplete="new-password"/>
            </div>
            <div class="mt-4">
                <JetLabel for="password_confirmation"
                          :value="__('confirm_password')"/>
                <JetInput id="password_confirmation"
                          v-model="form.password_confirmation"
                          type="password"
                          class="mt-1 block w-full"
                          required
                          autocomplete="new-password"/>
            </div>
            <div class="flex items-center justify-end mt-4">
                <JetButton :class="{ 'opacity-25': form.processing }"
                           :disabled="form.processing">
                    {{ __('reset_password') }}
                </JetButton>
            </div>
        </form>
    </JetAuthenticationCard>
</template>
