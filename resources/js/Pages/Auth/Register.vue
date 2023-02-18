<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import AuthenticationCard from "@/Core/Components/AuthenticationCard.vue";
import AuthenticationCardLogo from "@/Core/Components/AuthenticationCardLogo.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Checkbox from "@/Components/Checkbox.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";
import { useDark } from "@vueuse/core";

const isDark = useDark();
const form = useForm({
    name: "", email: "", password: "", password_confirmation: "", terms: false
});

const submit = () => {
    if (form.terms && $page.props.jetstream.hasTermsAndPrivacyPolicyFeature) {
        form.post(route("register"), {
            onFinish: () => form.reset("password", "password_confirmation")
        });
    }
};
</script>
<template>
    <Head :title="__('register')"/>
    <AuthenticationCard class="dark:bg-gray-900">
        <template #logo>
            <AuthenticationCardLogo />
        </template>
        <form @submit.prevent="submit">
            <div>
                <InputLabel :value="__('name')"
                          for="name"/>
                <TextInput id="name"
                          v-model="form.name"
                          autocomplete="name"
                          autofocus
                          class="mt-1 block w-full"
                          required
                          type="text"/>
                <InputError :message="form.errors.name"
                            class="mt-2"/>
            </div>
            <div class="mt-4">
                <InputLabel :value="__('email')"
                          for="email"/>
                <TextInput id="email"
                          v-model="form.email"
                          class="mt-1 block w-full"
                          required
                          type="email"/>
                <InputError :message="form.errors.email"
                            class="mt-2"/>
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
                <InputError :message="form.errors.password"
                            class="mt-2"/>
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
            <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature"
                 class="mt-4">
                <InputLabel for="terms">
                    <div class="flex items-center">
                        <Checkbox id="terms"
                                     v-model:checked="form.terms"
                                     name="terms"
                                     required/>
                        <div class="ml-2">
                            {{ __("agree_to") }}
                            <a :href="route('terms.show')"
                               class="underline text-sm text-gray-600 hover:text-gray-900"
                               target="_blank">
                                {{ __("term_of_service") }}
                            </a>
                            {{ __("and") }}
                            <a :href="route('policy.show')"
                               class="underline text-sm text-gray-600 hover:text-gray-900"
                               target="_blank">
                                {{ __("privacy_policy") }}
                            </a>
                        </div>
                    </div>
                </InputLabel>
            </div>
            <div class="flex items-center justify-end mt-4">
                <Link :href="route('login')"
                      class="underline text-sm text-gray-600 hover:text-gray-900">
                    {{ __("already_registered") }}
                </Link>
                <PrimaryButton :class="{ 'opacity-25': form.processing }"
                           :disabled="form.processing"
                           class="ml-4">
                    {{ __("register") }}
                </PrimaryButton>
            </div>
        </form>
    </AuthenticationCard>
</template>
