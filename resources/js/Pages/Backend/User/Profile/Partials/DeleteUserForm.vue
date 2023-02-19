<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import Flap from "@/Vendor/Core/Components/Flap.vue";
import Input from "@/Vendor/Core/Components/Form/Input.vue";

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: ""
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    setTimeout(() => passwordInput.value.focus(), 250);
};

const deleteUser = () => {
    form.delete(route("current-user.destroy"), {
        preserveScroll: true,
        onSuccess: () => closeFlap(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset()
    });
};

const closeFlap = () => {
    confirmingUserDeletion.value = false;

    form.reset();
};
</script>
<template>
    <div class="p-6">
        <div class="max-w-xl text-sm">
            {{ __('delete_account_legend') }}
        </div>
        <div class="mt-5">
            <button class="btn btn-danger"
                    @click="confirmUserDeletion">
                {{ __("delete_account") }}
            </button>
        </div>
        <Flap v-model="confirmingUserDeletion"
              :title="__('delete_account')"
              close-background
              md>
            <p>
                {{ __("delete_account_question") }}
            </p>
            <div class="mt-4">
                <Input ref="passwordInput"
                       v-model="form.password"
                       :errors="form.errors"
                       :placeholder="__('password')"
                       name="password"
                       type="password"
                       @keyup.enter="deleteUser"/>
            </div>
            <div class="mt-4 flex gap-2">
                <button class="btn btn-secondary"
                        @click="closeFlap">
                    {{ __("cancel") }}
                </button>
                <button :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        class="btn btn-danger"
                        @click="deleteUser">
                    {{ __("delete_account") }}
                </button>
            </div>
        </Flap>
    </div>
</template>
