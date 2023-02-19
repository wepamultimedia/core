<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import FormSection from "@/Vendor/Core/Components/Form/FormSection.vue";
import ActionMessage from "@/Vendor/Core/Components/ActionMessage.vue";
import Input from "@/Vendor/Core/Components/Form/Input.vue";
import SaveFormButton from "@/Vendor/Core/Components/Form/SaveFormButton.vue";

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: "",
    password: "",
    password_confirmation: ""
});
const updatePassword = () => {
    form.put(route("user-password.update"), {
        errorBag: "updatePassword",
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset("password", "password_confirmation");
                passwordInput.value.focus();
            }

            if (form.errors.current_password) {
                form.reset("current_password");
                currentPasswordInput.value.focus();
            }
        }
    });
};
</script>
<template>
    <FormSection @submitted="updatePassword">
        <template #form>
            <div class="col-span-6 sm:col-span-4">
                <Input id="current_password"
                       ref="currentPasswordInput"
                       v-model="form.current_password"
                       :errors="form.errors"
                       :label="__('current_password')"
                       autocomplete="current-password"
                       name="current_password"
                       type="password"/>
            </div>
            <div class="col-span-6 sm:col-span-4 mt-4">
                <Input id="password"
                       ref="passwordInput"
                       v-model="form.password"
                       :errors="form.errors"
                       :label="__('new_password')"
                       autocomplete="new-password"
                       name="password"
                       type="password"/>
            </div>
            <div class="col-span-6 sm:col-span-4 mt-4">
                <Input id="password_confirmation"
                       v-model="form.password_confirmation"
                       :errors="form.errors"
                       :label="__('confirm_password')"
                       autocomplete="new-password"
                       class="mt-1 block w-full"
                       name="password_confirmation"
                       type="password"/>
            </div>
        </template>
        <template #actions>
            <SaveFormButton :form="form" />
        </template>
    </FormSection>
</template>
