<script>
import MainLayout from "@/Pages/Core/Layouts/Backend/MainLayout.vue";

export default {
    layout: (h, page) => h(MainLayout, {
        title: "profile",
        icon: "user",
        bc: [{label: "profile"}]
    }, () => page)
};
</script>
<script setup>
import { Link } from "@inertiajs/inertia-vue3";
import UpdateProfileInformationForm from "@core/Pages/Backend/User/Profile/Partials/UpdateProfileInformationForm.vue";
import UpdatePasswordForm from "@core/Pages/Backend/User/Profile/Partials/UpdatePasswordForm.vue";
import TwoFactorAuthenticationForm from "@core/Pages/Backend/User/Profile/Partials/TwoFactorAuthenticationForm.vue";
import LogoutOtherBrowserSessionsForm from "@core/Pages/Backend/User/Profile/Partials/LogoutOtherBrowserSessionsForm.vue";
import DeleteUserForm from "@core/Pages/Backend/User/Profile/Partials/DeleteUserForm.vue";
import Card from "@core/Components/Backend/Card.vue";

defineProps({
    user: Object,
    confirmsTwoFactorAuthentication: Boolean,
    sessions: Array
});
</script>
<template>
    <!-- Profile information -->
    <div v-if="$page.props.jetstream.canUpdateProfileInformation"
         class="grid grid-cols-1 lg:grid-cols-3 gap-4 dark:text-light mt-8">
        <div class="col-span-1">
            <div class="pb-4 text-skin-base dark:text-skin-base-dark font-medium text-xl">
                {{ __("information_title") }}
            </div>
            <p class="text-sm">{{ __("information_summary") }}</p>
        </div>
        <Card class="col-span-2">
            <UpdateProfileInformationForm :user="user"/>
        </Card>
    </div>
    <hr class="border-gray-300 dark:border-gray-800 my-10">
    <!-- Password -->
    <div v-if="$page.props.jetstream.canUpdatePassword"
         class="grid grid-cols-1 lg:grid-cols-3 gap-4 dark:text-light">
        <div class="col-span-1">
            <div class="pb-4 text-skin-base dark:text-skin-base-dark font-medium text-xl">
                {{ __("password_title") }}
            </div>
            <p class="text-sm">{{ __("password_summary") }}</p>
        </div>
        <Card class="col-span-2">
            <UpdatePasswordForm/>
        </Card>
    </div>
    <hr class="border-gray-300 dark:border-gray-800 my-10">
    <!-- Two factor authenticator -->
    <div v-if="$page.props.jetstream.canManageTwoFactorAuthentication"
         class="grid grid-cols-1 lg:grid-cols-3 gap-4 dark:text-light">
        <div class="col-span-1">
            <div class="pb-4 text-skin-base dark:text-skin-base-dark font-medium text-xl">
                {{ __("two_factor_title") }}
            </div>
            <p class="text-sm">{{ __("two_factor_summary") }}</p>
        </div>
        <div class="col-span-2 w-full
                bg-white dark:bg-gray-700
                overflow-hidden
                shadow
                text-skin-base dark:text-skin-base-dark
                rounded-lg">
            <TwoFactorAuthenticationForm :requires-confirmation="confirmsTwoFactorAuthentication"/>
        </div>
    </div>

    <hr class="border-gray-300 dark:border-gray-800 my-10">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 dark:text-light">
        <div class="col-span-1">
            <div class="pb-4 text-skin-base dark:text-skin-base-dark font-medium text-xl">
                {{ __("browser_sessions_title") }}
            </div>
            <p class="text-sm">{{ __("browser_sessions_summary") }}</p>
        </div>
        <div class="col-span-2 w-full
                bg-white dark:bg-gray-700
                overflow-hidden
                shadow
                text-skin-base dark:text-skin-base-dark
                rounded-lg">
            <LogoutOtherBrowserSessionsForm :sessions="sessions" class="mt-10 sm:mt-0" />
        </div>
    </div>

    <hr class="border-gray-300 dark:border-gray-800 my-10">

    <template v-if="$page.props.jetstream.hasAccountDeletionFeatures">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 dark:text-light mb-10">
            <div class="col-span-1">
                <div class="pb-4 text-skin-base dark:text-skin-base-dark font-medium text-xl">
                    {{ __("delete_account_title") }}
                </div>
                <p class="text-sm">{{ __("delete_account_summary") }}</p>
            </div>
            <div class="col-span-2 w-full
                    bg-white dark:bg-gray-700
                    overflow-hidden
                    shadow
                    text-skin-base dark:text-skin-base-dark
                    rounded-lg">
                <DeleteUserForm class="mt-10 sm:mt-0" />
            </div>
        </div>
    </template>
</template>
<style lang="scss"
       scoped></style>
