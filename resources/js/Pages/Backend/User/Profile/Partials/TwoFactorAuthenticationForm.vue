<script setup>
import { computed, ref, watch } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { useForm, usePage } from "@inertiajs/vue3";
import ConfirmsPassword from "@pages/Vendor/Core/Backend/User/Profile/Partials/ConfirmsPassword.vue";
import Card from "@/Vendor/Core/Components/Backend/Card.vue";
import Input from "@/Vendor/Core/Components/Form/Input.vue";


const props = defineProps({
    requiresConfirmation: Boolean
});

const enabling = ref(false);
const confirming = ref(false);
const disabling = ref(false);
const qrCode = ref(null);
const setupKey = ref(null);
const recoveryCodes = ref([]);

const confirmationForm = useForm({
    code: ""
});

const twoFactorEnabled = computed(() => !enabling.value && usePage().props.user?.two_factor_enabled);

watch(twoFactorEnabled, () => {
    if (!twoFactorEnabled.value) {
        confirmationForm.reset();
        confirmationForm.clearErrors();
    }
});

const enableTwoFactorAuthentication = () => {
    enabling.value = true;

    Inertia.post("/user/two-factor-authentication", {}, {
        preserveScroll: true,
        onSuccess: () => Promise.all([
            showQrCode(), showSetupKey(), showRecoveryCodes()
        ]),
        onFinish: () => {
            enabling.value = false;
            confirming.value = props.requiresConfirmation;
        }
    });
};

const showQrCode = () => {
    return axios.get("/user/two-factor-qr-code").then(response => {
        qrCode.value = response.data.svg;
    });
};

const showSetupKey = () => {
    return axios.get("/user/two-factor-secret-key").then(response => {
        setupKey.value = response.data.secretKey;
    });
};

const showRecoveryCodes = () => {
    return axios.get("/user/two-factor-recovery-codes").then(response => {
        recoveryCodes.value = response.data;
    });
};

const confirmTwoFactorAuthentication = () => {
    confirmationForm.post("/user/confirmed-two-factor-authentication", {
        errorBag: "confirmTwoFactorAuthentication",
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            confirming.value = false;
            qrCode.value = null;
            setupKey.value = null;
        }
    });
};

const regenerateRecoveryCodes = () => {
    axios
    .post("/user/two-factor-recovery-codes")
    .then(() => showRecoveryCodes());
};

const disableTwoFactorAuthentication = () => {
    disabling.value = true;

    Inertia.delete("/user/two-factor-authentication", {
        preserveScroll: true,
        onSuccess: () => {
            disabling.value = false;
            confirming.value = false;
        }
    });
};
</script>
<template>
    <Card class="p-6 text-skin-base dark:text-skin-base-dark">
        <h3 v-if="twoFactorEnabled && !confirming"
            class="text-lg font-medium">
            {{ __('two_factor_enabled') }}
        </h3>
        <h3 v-else-if="twoFactorEnabled && confirming"
            class="text-lg font-medium">
            {{ __('two_factor_finish_enabling') }}
        </h3>
        <h3 v-else
            class="text-lg font-medium">
            {{ __('two_factor_not_enabled') }}
        </h3>
        <div class="mt-3 max-w-xl text-sm">
            <p>
                {{ __('two_factor_legend') }}
            </p>
        </div>
        <div v-if="twoFactorEnabled">
            <div v-if="qrCode">
                <div class="mt-4 max-w-xl text-sm">
                    <p v-if="confirming"
                       class="font-semibold">
                        {{ __('finish_enabling_legend') }}
                    </p>
                    <p v-else>
                        {{ __('enabled_legend') }}
                    </p>
                </div>
                <div class="mt-4"
                     v-html="qrCode"/>
                <div v-if="setupKey"
                     class="mt-4 max-w-xl text-sm">
                    <p class="font-semibold">
                        {{ __('two_factor_setup_key') }}:
                        <span v-html="setupKey"></span>
                    </p>
                </div>
                <div v-if="confirming"
                     class="mt-4">
                    <Input id="code"
                           v-model="confirmationForm.code"
                           :errors="confirmationForm.errors"
                           :label="__('code')"
                           autocomplete="one-time-code"
                           autofocus
                           class="block mt-1 w-1/2"
                           inputmode="numeric"
                           name="code"
                           type="text"
                           @keyup.enter="confirmTwoFactorAuthentication"/>
                </div>
            </div>
            <div v-if="recoveryCodes.length > 0 && ! confirming">
                <div class="mt-4 max-w-xl text-sm">
                    <p class="font-semibold">
                        {{ __('store_recovery_codes') }}
                    </p>
                </div>
                <div class="grid gap-1 max-w-xl mt-4 px-4 py-4 font-mono text-sm bg-gray-100 dark:bg-gray-800 rounded-lg">
                    <div v-for="code in recoveryCodes"
                         :key="code">
                        {{ code }}
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-5">
            <div v-if="!twoFactorEnabled">
                <ConfirmsPassword @confirmed="enableTwoFactorAuthentication">
                    <button :class="{ 'opacity-25': enabling }"
                            :disabled="enabling"
                            class="btn btn-success"
                            type="button">
                        {{ __("enable") }}
                    </button>
                </ConfirmsPassword>
            </div>
            <div v-else>
                <ConfirmsPassword @confirmed="confirmTwoFactorAuthentication">
                    <button v-if="confirming"
                            :class="{ 'opacity-25': enabling }"
                            :disabled="enabling"
                            class="btn btn-success mr-3"
                            type="button">
                        {{ __("confirm") }}
                    </button>
                </ConfirmsPassword>
                <ConfirmsPassword @confirmed="regenerateRecoveryCodes">
                    <button v-if="recoveryCodes.length > 0 && ! confirming"
                            class="btn btn-secondary mr-3">
                        {{ __('regenerate_recovery_codes') }}
                    </button>
                </ConfirmsPassword>
                <ConfirmsPassword @confirmed="showRecoveryCodes">
                    <button v-if="recoveryCodes.length === 0 && ! confirming"
                                     class="btn btn-info mr-3">
                        {{ __('show_recovery_codes') }}
                    </button>
                </ConfirmsPassword>
                <ConfirmsPassword @confirmed="disableTwoFactorAuthentication">
                    <button v-if="confirming"
                            :class="{ 'opacity-25': disabling }"
                            :disabled="disabling"
                            class="btn btn-secondary">
                        Cancel
                    </button>
                </ConfirmsPassword>
                <ConfirmsPassword @confirmed="disableTwoFactorAuthentication">
                    <button v-if="! confirming"
                            :class="{ 'opacity-25': disabling }"
                            :disabled="disabling"
                            class="btn btn-danger">
                        Disable
                    </button>
                </ConfirmsPassword>
            </div>
        </div>
    </Card>
</template>
