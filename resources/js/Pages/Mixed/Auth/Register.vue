<script setup>
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import JetAuthenticationCard from "@/Jetstream/AuthenticationCard.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetCheckbox from "@/Jetstream/Checkbox.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";

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
    <JetAuthenticationCard>
        <template #logo>
            <img :alt="$page.props.appName"
                 class="h-14"
                 src="/images/logo.svg">
        </template>
        <JetValidationErrors class="mb-4"/>
        <form @submit.prevent="submit">
            <div>
                <JetLabel :value="__('name')"
                          for="name"/>
                <JetInput id="name"
                          v-model="form.name"
                          autocomplete="name"
                          autofocus
                          class="mt-1 block w-full"
                          required
                          type="text"/>
            </div>
            <div class="mt-4">
                <JetLabel :value="__('email')"
                          for="email"/>
                <JetInput id="email"
                          v-model="form.email"
                          class="mt-1 block w-full"
                          required
                          type="email"/>
            </div>
            <div class="mt-4">
                <JetLabel :value="__('password')"
                          for="password"/>
                <JetInput id="password"
                          v-model="form.password"
                          autocomplete="new-password"
                          class="mt-1 block w-full"
                          required
                          type="password"/>
            </div>
            <div class="mt-4">
                <JetLabel :value="__('confirm_password')"
                          for="password_confirmation"/>
                <JetInput id="password_confirmation"
                          v-model="form.password_confirmation"
                          autocomplete="new-password"
                          class="mt-1 block w-full"
                          required
                          type="password"/>
            </div>
            <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature"
                 class="mt-4">
                <JetLabel for="terms">
                    <div class="flex items-center">
                        <JetCheckbox id="terms"
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
                </JetLabel>
            </div>
            <div class="flex items-center justify-end mt-4">
                <Link :href="route('login')"
                      class="underline text-sm text-gray-600 hover:text-gray-900">
                    {{ __("already_registered") }}
                </Link>
                <JetButton :class="{ 'opacity-25': form.processing }"
                           :disabled="form.processing"
                           class="ml-4">
                    {{ __("register") }}
                </JetButton>
            </div>
        </form>
    </JetAuthenticationCard>
</template>
