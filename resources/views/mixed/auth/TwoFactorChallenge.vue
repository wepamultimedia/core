<script setup>
import { nextTick, ref } from "vue";
import { Head, useForm } from "@inertiajs/inertia-vue3";
import JetAuthenticationCard from "@/Jetstream/AuthenticationCard.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";

const recovery = ref(false);

const form = useForm({
    code: "", recovery_code: ""
});

const recoveryCodeInput = ref(null);
const codeInput = ref(null);

const toggleRecovery = async () => {
    recovery.value ^= true;

    await nextTick();

    if (recovery.value) {
        recoveryCodeInput.value.focus();
        form.code = "";
    } else {
        codeInput.value.focus();
        form.recovery_code = "";
    }
};

const submit = () => {
    form.post(route("two-factor.login"));
};
</script>
<template>
    <Head :title="__('Two-factor Confirmation')"/>
    <JetAuthenticationCard>
        <template #logo>
            <img :alt="$page.props.appName"
                 class="h-14"
                 src="/images/logo.svg">
        </template>
        <div class="mb-4 text-sm text-gray-600">
            <template v-if="! recovery">
                Please confirm access to your account by entering the authentication code provided by your authenticator
                application.
            </template>
            <template v-else>
                Please confirm access to your account by entering one of your emergency recovery codes.
            </template>
        </div>
        <JetValidationErrors class="mb-4"/>
        <form @submit.prevent="submit">
            <div v-if="! recovery">
                <JetLabel :value="__('Code')"
                          for="code"/>
                <JetInput id="code"
                          ref="codeInput"
                          v-model="form.code"
                          autocomplete="one-time-code"
                          autofocus
                          class="mt-1 block w-full"
                          inputmode="numeric"
                          type="text"/>
            </div>
            <div v-else>
                <JetLabel :value="__('Recovery Code')"
                          for="recovery_code"/>
                <JetInput id="recovery_code"
                          ref="recoveryCodeInput"
                          v-model="form.recovery_code"
                          autocomplete="one-time-code"
                          class="mt-1 block w-full"
                          type="text"/>
            </div>
            <div class="flex items-center justify-end mt-4">
                <button class="text-sm text-gray-600 hover:text-gray-900 underline cursor-pointer"
                        type="button"
                        @click.prevent="toggleRecovery">
                    <template v-if="! recovery">
                        {{ __("Use a recovery code") }}
                    </template>
                    <template v-else>
                        {{ __("Use an authentication code") }}
                    </template>
                </button>
                <JetButton :class="{ 'opacity-25': form.processing }"
                           :disabled="form.processing"
                           class="ml-4">
                    {{ __("login") }}
                </JetButton>
            </div>
        </form>
    </JetAuthenticationCard>
</template>
