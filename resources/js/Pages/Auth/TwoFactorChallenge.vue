<script setup>
import { nextTick, ref } from "vue";
import { Head, useForm } from "@inertiajs/inertia-vue3";
import AuthenticationCard from "@/Core/Components/AuthenticationCard.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { useDark } from "@vueuse/core";
import Input from "@core/Components/Form/Input.vue";

const isDark = useDark();

const recovery = ref(false);

const form = useForm({
    code: '',
    recovery_code: '',
});

const recoveryCodeInput = ref(null);
const codeInput = ref(null);

const toggleRecovery = async () => {
    recovery.value ^= true;

    await nextTick();

    if (recovery.value) {
        form.code = '';
    } else {
        form.recovery_code = '';
    }
};

const submit = () => {
    form.post(route('two-factor.login'));
};
</script>
<template>
    <Head :title="__('Two-factor Confirmation')"/>
    <AuthenticationCard class="dark:bg-gray-900">
        <template #logo>
            <img :alt="$page.props.default.appName"
                 class="h-14"
                 src="/images/logo.svg">
        </template>
        <div class="my-4 text-sm text-center">
            <template v-if="! recovery">
                {{ __('enter-code-by-authenticator') }}
            </template>

            <template v-else>
                {{ __('enter-recovery-code') }}
            </template>
        </div>

        <form @submit.prevent="submit">
            <div v-if="! recovery">
                <Input v-model="form.code"
                       :errors="form.errors"
                       :label="__('code')"
                       autofocus
                       name="code"
                       autocomplete="one-time-code"
                       required
                       type="text"/>
            </div>

            <div v-else>
                <Input v-model="form.recovery_code"
                       :errors="form.errors"
                       :label="__('recovery_code')"
                       autofocus
                       name="recovery_code"
                       required
                       type="text"/>
            </div>

            <div class="flex items-center justify-end mt-4">
                <button type="button" class="text-sm text-gray-600 hover:text-gray-900 underline cursor-pointer" @click.prevent="toggleRecovery">
                    <template v-if="! recovery">
                        {{ __("Use a recovery code") }}
                    </template>
                    <template v-else>
                        {{ __("Use an authentication code") }}
                    </template>
                </button>
                <PrimaryButton :class="{ 'opacity-25': form.processing }"
                           :disabled="form.processing"
                           class="ml-4">
                    {{ __("login") }}
                </PrimaryButton>
            </div>
        </form>
    </AuthenticationCard>
</template>
