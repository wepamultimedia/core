<script setup>
import { ref } from "vue";
import { Link, useForm, router } from "@inertiajs/vue3";
import FormSection from "@/Vendor/Core/Components/Form/FormSection.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Input from "@/Vendor/Core/Components/Form/Input.vue";
import SaveFormButton from "@/Vendor/Core/Components/Form/SaveFormButton.vue";

const props = defineProps({
    user: Object
});

const form = useForm({
    _method: "PUT",
    name: props.user.name,
    email: props.user.email,
    photo: null
});

const verificationLinkSent = ref(null);
const photoPreview = ref(null);
const photoInput = ref(null);

const updateProfileInformation = () => {
    if (photoInput.value) {
        form.photo = photoInput.value.files[0];
    }

    form.post(route("user-profile-information.update"), {
        errorBag: "updateProfileInformation",
        preserveScroll: true,
        onSuccess: () => clearPhotoFileInput()
    });
};

const sendEmailVerification = () => {
    verificationLinkSent.value = true;
};

const selectNewPhoto = () => {
    photoInput.value.click();
};

const updatePhotoPreview = () => {
    const photo = photoInput.value.files[0];

    if (!photo) return;

    const reader = new FileReader();

    reader.onload = (e) => {
        photoPreview.value = e.target.result;
    };

    reader.readAsDataURL(photo);
};

const deletePhoto = () => {
    router.delete(route("current-user-photo.destroy"), {
        preserveScroll: true,
        onSuccess: () => {
            photoPreview.value = null;
            clearPhotoFileInput();
        }
    });
};

const clearPhotoFileInput = () => {
    if (photoInput.value?.value) {
        photoInput.value.value = null;
    }
};
</script>
<template>
    <FormSection @submitted="updateProfileInformation">
        <template #form>
            <div class="grid grid-cols-6">
                <!-- Profile Photo -->
                <div class="col-span-6 sm:col-span-4">
                    <input ref="photoInput"
                           class="hidden"
                           type="file"
                           @change="updatePhotoPreview"/>
                    <InputLabel :value="__('photo')"
                                class="text-skin-base "
                                for="photo"/>
                    <!-- Current Profile Photo -->
                    <div v-show="!photoPreview"
                         class="mt-2">
                        <img :alt="user.name"
                             :src="user.profile_photo_url"
                             class="rounded-full h-20 w-20 object-cover border-2 border-gray-300 dark:border-gray-800">
                    </div>
                    <!-- New Profile Photo Preview -->
                    <div v-show="photoPreview"
                         class="mt-2">
                        <span :style="'background-image: url(\'' + photoPreview + '\');'"
                              class="block rounded-full w-20 h-20 bg-cover bg-no-repeat bg-center"/>
                    </div>
                    <button class="btn btn-info mt-2 mr-2"
                            type="button"
                            @click.prevent="selectNewPhoto">
                        {{ __("select_new_photo") }}
                    </button>
                    <button v-if="user.profile_photo_path"
                            class="btn btn-secondary mt-2"
                            type="button"
                            @click.prevent="deletePhoto">
                        {{ __("remove_photo") }}
                    </button>
                    <InputError :message="form.errors.photo"
                                class="mt-2"/>
                </div>
                <!-- Name -->
                <div class="col-span-6 sm:col-span-4 mt-4">
                    <Input id="name"
                           v-model="form.name"
                           :errors="form.errors"
                           :label="__('name')"
                           autocomplete="name"
                           name="name"/>
                </div>
                <!-- Email -->
                <div class="col-span-6 sm:col-span-4 mt-4">
                    <Input id="email"
                           v-model="form.email"
                           :errors="form.errors"
                           :label="__('email')"
                           name="email"
                           type="email"/>
                    <div v-if="$page.props.jetstream.hasEmailVerification && user.email_verified_at === null">
                        <p class="text-sm mt-3">
                            {{ __("email_address_unverified") }}. <br>
                            <Link :href="route('verification.send')"
                                  as="button"
                                  class="underline text-gray-600 hover:text-gray-900"
                                  method="post"
                                  @click.prevent="sendEmailVerification">
                                {{ __("click_resend_verification_email") }}.
                            </Link>
                        </p>
                        <div v-show="verificationLinkSent"
                             class="mt-2 font-medium text-sm text-green-600">
                            {{ __("new_verification_link_send") }}
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <template #actions>
            <SaveFormButton :form="form"/>
        </template>
    </FormSection>
</template>
