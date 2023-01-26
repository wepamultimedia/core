<script setup>
import { nextTick, reactive, ref } from "vue";
import Flap from "@core/Components/Flap.vue";
import Input from "@core/Components/Form/Input.vue";

const emit = defineEmits(["confirmed"]);

defineProps({
    title: {
        type: String,
        default: "Confirm Password"
    },
    content: {
        type: String,
        default: "For your security, please confirm your password to continue."
    },
    button: {
        type: String,
        default: "Confirm"
    }
});

const confirmingPassword = ref(false);

const form = reactive({
    password: "",
    errors: {},
    processing: false
});

const passwordInput = ref(null);

const startConfirmingPassword = () => {
    axios.get(route("password.confirmation")).then(response => {
        if (response.data.confirmed) {
            emit("confirmed");
        } else {
            confirmingPassword.value = true;

            setTimeout(() => passwordInput.value.focus(), 250);
        }
    });
};

const confirmPassword = () => {
    form.processing = true;

    axios.post(route("password.confirm"), {
        password: form.password
    }).then(() => {
        form.processing = false;

        closeFlap();
        nextTick().then(() => emit("confirmed"));

    }).catch(error => {
        form.processing = false;
        form.errors = error.response.data.errors;
        passwordInput.value.focus();
    });
};

const closeFlap = () => {
    confirmingPassword.value = false;
    form.password = "";
    form.errors = "";
};
</script>
<template>
    <span>
        <span @click="startConfirmingPassword">
            <slot/>
        </span>
        <Flap v-model="confirmingPassword"
              close-background
              md
              @close="closeFlap">
            <h2 class="pb-4">{{ __("confirm_password_title") }}</h2>
            <p class="pb-4">{{ __("confirm_password_summary") }}</p>
            <div class="mt-4">
                <Input ref="passwordInput"
                       v-model="form.password"
                       :errors="form.errors"
                       :label="__('password')"
                       name="password"
                       type="password"
                       @keyup.enter="confirmPassword"/>
            </div>
            <div class="mt-4 flex gap-2">
                <button :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        class="btn btn-success justify-center"
                        type="button"
                        @click="confirmPassword">{{ __("confirm") }}
                </button>
                <button class="btn btn-secondary justify-center"
                        @click.prevent="closeFlap">{{ __("close") }}
                </button>
            </div>
        </Flap>
    </span>
</template>
