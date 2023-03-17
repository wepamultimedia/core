<script>
import MainLayout from "@pages/Vendor/Core/Backend/Layouts/MainLayout/MainLayout.vue";

export default {
    layout: (h, page) => h(MainLayout, {
        title: "site",
        icon: "key",
        bc: [
            {label: "site"}
        ]
    }, () => page)
};
</script>
<script setup>
import { onMounted, reactive, ref, toRefs } from "vue";
import { useForm, usePage, router } from "@inertiajs/vue3";
import Input from "@/Vendor/Core/Components/Form/Input.vue";
import SeoForm from "@/Vendor/Core/Components/Backend/SeoForm.vue";
import SaveFormButton from "@/Vendor/Core/Components/Form/SaveFormButton.vue";
import { useStore } from "vuex";
import { __ } from "@/Vendor/Core/Mixins/translations";
import iconSizes from "@/Vendor/Core/Mixins/iconSizes";

const props = defineProps(["site", "errors", "seo"]);
const store = useStore();
const {site, seo, errors} = toRefs(props);

const form = reactive({
    id: site.value.id,
    icon: site.value.icon,
    maintenance: site.value.maintenance,
    company: site.value.company,
    email: site.value.email,
    phone: site.value.phone,
    mobile: site.value.mobile,
    address: site.value.address,
    latitude: site.value.latitude,
    longitude: site.value.longitude,
    facebook: site.value.facebook,
    twitter: site.value.twitter,
    youtube: site.value.youtube,
    skype: site.value.skype,
    linkedin: site.value.linkedin,
    instagram: site.value.instagram,
    vimeo: site.value.vimeo,
    twitch: site.value.twitch,
    whatsapp: site.value.whatsapp,
    seo: site.value.seo
});
const formIcon = useForm({
    file: null,
    sizes: iconSizes
});
const SeoFormComponent = ref();
const submiting = ref(false);
const defaultProps = usePage().props.default;
const sections = reactive({
    general: true,
    social: false
});

function checkIcons() {
    const url = `${defaultProps.storageUrl}/icons`;
    Object.keys(formIcon.sizes).forEach(key => {
        formIcon.sizes[key].map(size => {
            let image = new Image();
            image.src = `${url}/${size.name}.png?${Math.random()}`;
            image.onload = () => size.ok = true;
        });
    });
}

function submit() {
    submiting.value = true;
    router.put(route("admin.site.update"), form, {
        preserveScroll: true,
        preserveState: true,
        onSuccess() {
            store.dispatch("backend/addAlert", {type: "success", message: __("saved")});
            submiting.value = false;
        },
        onError(){
            store.dispatch("backend/addAlert", {type: "error", message: errors.value?.seo});
        },
        onFinish(){
            submiting.value = false;
        }
    });
}

function submitIcon() {
    if (formIcon.file) {
        formIcon.post(route("admin.site.icons.generate"), {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                store.dispatch("backend/addAlert", {type: "success", message: __("icons_generated")});
                checkIcons();
            }
        });
    }
}
function activeSeccion(section) {
    Object.keys(sections).forEach(function (key) {
        sections[key] = false;
    });
    sections[section] = true;
}

onMounted(() => {
    checkIcons();
})
</script>
<template>
    <!--Title-->
    <div class="flex justify-between my-0 items-center h-14 mt-4">
        <h1 class="dark:text-light font-medium text-xl">{{ __("edit_title") }}</h1>
    </div>
    <div class="max-w-7xl">
        <form @submit.prevent="submit">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 text-skin-base  mb-8">
                <div class="col-span-1">
                    <p class="text-sm">{{ __("edit_summary") }}</p>
                </div>
                <div class="col-span-2">
                    <!-- Buttons -->
                    <div class="grid grid-cols-2 gap-1 mb-2 sm:mb-0">
                        <button :class="{'bg-white dark:bg-gray-600 font-bold': sections.general}"
                                class="sm:mb-0 border sm:border-b-0 border-gray-300 dark:border-gray-700 z-10 px-4 py-2 rounded sm:rounded-b-none"
                                type="button"
                                @click="activeSeccion('general')">
                            {{ __("general") }}
                        </button>
                        <button :class="{'bg-white dark:bg-gray-600': sections.social}"
                                class="sm:mb-0 border sm:border-b-0 border-gray-300 dark:border-gray-700 z-10 px-4 py-2 rounded sm:rounded-b-none"
                                type="button"
                                @click="activeSeccion('social')">
                            {{ __("social_networks") }}
                        </button>
                    </div>
                    <div class="border
                                border border-t-0 border-gray-300 dark:border-gray-700
                                dark:border-gray-600
                                bg-white dark:bg-gray-600
                                text-gray-700 dark:text-gray-300
                                rounded-b-lg
                                shadow">
                        <!-- General -->
                        <div v-show="sections.general"
                             class="grid grid-cols-6 p-6">
                            <div class="col-span-6 sm:col-span-6 lg:col-span-5 xl:col-span-4 mb-6">
                                <div class="mb-4">
                                    <Input v-model="form.company"
                                           :errors="errors"
                                           :label="__('company')"
                                           autofocus
                                           name="company"/>
                                </div>
                                <div class="mb-4">
                                    <Input v-model="form.email"
                                           :errors="errors"
                                           :label="__('email')"
                                           name="email"
                                           type="email"/>
                                </div>
                                <div class="mb-4 grid grid-cols-2 gap-2">
                                    <Input v-model="form.phone"
                                           :errors="errors"
                                           :label="__('phone')"
                                           name="phone"/>
                                    <Input v-model="form.mobile"
                                           :errors="errors"
                                           :label="__('mobile')"
                                           name="mobile"/>
                                </div>
                                <div class="mb-4">
                                    <Input v-model="form.address"
                                           :errors="errors"
                                           :label="__('address')"
                                           name="address"/>
                                </div>
                                <div class="mb-4 grid grid-cols-2 gap-2">
                                    <Input v-model="form.latitude"
                                           :errors="errors"
                                           :label="__('latitude')"
                                           name="latitude"/>
                                    <Input v-model="form.longitude"
                                           :errors="errors"
                                           :label="__('longitude')"
                                           name="longitude"/>
                                </div>
                                <div></div>
                            </div>
                        </div>
                        <!-- Social networks -->
                        <div v-show="sections.social"
                             class="grid grid-cols-6 p-6">
                            <div class="col-span-6 sm:col-span-6 lg:col-span-5 xl:col-span-4 mb-6">
                                <div class="mb-4">
                                    <Input v-model="form.facebook"
                                           :errors="errors"
                                           autofocus
                                           name="facebook">
                                        <template #icon>
                                            <inline-svg class="w-5 h-5 fill-skin-base stroke-skin-base"
                                                        src="/vendor/core/icons/social-networks/facebook.svg"></inline-svg>
                                        </template>
                                    </Input>
                                </div>
                                <div class="mb-4">
                                    <Input v-model="form.twitter"
                                           :errors="errors"
                                           autofocus
                                           name="twitter">
                                        <template #icon>
                                            <inline-svg class="w-5 h-5 fill-skin-base stroke-skin-base"
                                                        src="/vendor/core/icons/social-networks/twitter.svg"></inline-svg>
                                        </template>
                                    </Input>
                                </div>
                                <div class="mb-4">
                                    <Input v-model="form.youtube"
                                           :errors="errors"
                                           autofocus
                                           name="youtube">
                                        <template #icon>
                                            <inline-svg class="w-5 h-5 fill-skin-base stroke-skin-base"
                                                        src="/vendor/core/icons/social-networks/youtube.svg"></inline-svg>
                                        </template>
                                    </Input>
                                </div>
                                <div class="mb-4">
                                    <Input v-model="form.skype"
                                           :errors="errors"
                                           autofocus
                                           name="skype">
                                        <template #icon>
                                            <inline-svg class="w-5 h-5 fill-skin-base stroke-skin-base"
                                                        src="/vendor/core/icons/social-networks/skype.svg"></inline-svg>
                                        </template>
                                    </Input>
                                </div>
                                <div class="mb-4">
                                    <Input v-model="form.linkedin"
                                           :errors="errors"
                                           autofocus
                                           name="linkedin">
                                        <template #icon>
                                            <inline-svg class="w-5 h-5 fill-skin-base stroke-skin-base"
                                                        src="/vendor/core/icons/social-networks/linkedin.svg"></inline-svg>
                                        </template>
                                    </Input>
                                </div>
                                <div class="mb-4">
                                    <Input v-model="form.instagram"
                                           :errors="errors"
                                           autofocus
                                           name="instagram">
                                        <template #icon>
                                            <inline-svg class="w-5 h-5 fill-skin-base stroke-skin-base"
                                                        src="/vendor/core/icons/social-networks/instagram.svg"></inline-svg>
                                        </template>
                                    </Input>
                                </div>
                                <div class="mb-4">
                                    <Input v-model="form.vimeo"
                                           :errors="errors"
                                           autofocus
                                           name="vimeo">
                                        <template #icon>
                                            <inline-svg class="w-5 h-5 fill-skin-base stroke-skin-base"
                                                        src="/vendor/core/icons/social-networks/vimeo.svg"></inline-svg>
                                        </template>
                                    </Input>
                                </div>
                                <div class="mb-4">
                                    <Input v-model="form.twitch"
                                           :errors="errors"
                                           autofocus
                                           name="twitch">
                                        <template #icon>
                                            <inline-svg class="w-5 h-5 fill-skin-base stroke-skin-base"
                                                        src="/vendor/core/icons/social-networks/twitch.svg"></inline-svg>
                                        </template>
                                    </Input>
                                </div>
                                <div class="mb-4">
                                    <Input v-model="form.whatsapp"
                                           :errors="errors"
                                           autofocus
                                           name="whatsapp">
                                        <template #icon>
                                            <inline-svg class="w-5 h-5 fill-skin-base stroke-skin-base"
                                                        src="/vendor/core/icons/social-networks/whatsapp.svg"></inline-svg>
                                        </template>
                                    </Input>
                                </div>
                            </div>
                        </div>
                        <div class="rounded-b-lg overflow-hidden">
                            <div class="p-3 bg-gray-200 dark:bg-gray-500 flex justify-end items-center">
                                <SaveFormButton :form="form"
                                                :loading="submiting"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- SEO -->
            <div class="pb-8">
                <h2 class="uppercase">{{ __("seo") }}</h2>
                <SeoForm v-model:seo="form.seo"
                         :errors="errors.seo"/>
            </div>
            <!-- Icons -->
            <div class="pb-8">
                <h2 class="uppercase">{{ __("icons") }}</h2>
                <div class="col-span-2
                        border
                        dark:border-gray-600
                        bg-white dark:bg-gray-600
                        text-gray-700 dark:text-gray-300
                        rounded-lg
                        shadow">
                    <div class="">
                        <div class="p-4 rounded-t flex items-center gap-2">
                            <input id="file"
                                   accept=".png"
                                   class="file:mr-4 file:py-2 file:px-4
                                          file:rounded file:border-0
                                          file:text-sm file:font-medium
                                          file:bg-gray-200 file:text-gray-700
                                          hover:file:cursor-pointer hover:file:bg-gray-300
                                          hover:file:text-gray-700
                                          block w-full
                                          text-sm text-gray-900
                                          border border-gray-300
                                          rounded-lg
                                          cursor-pointer
                                          bg-gray-50 dark:text-gray-400
                                          focus:outline-none
                                          dark:bg-gray-700
                                          dark:border-gray-600
                                          dark:placeholder-gray-400"
                                   name="file"
                                   type="file"
                                   @input="formIcon.file = $event.target.files[0]"/>
                            <SaveFormButton v-if="formIcon.file"
                                            :form="formIcon"
                                            type="button"
                                            @click.prevent="submitIcon()">{{ __("generate_icons") }}
                            </SaveFormButton>
                        </div>
                        <div class="p-6">
                            <ul class="grid md:grid-cols-2 xl:grid-cols-4 gap-12">
                                <li v-for="(icons, category) in formIcon.sizes">
                                    <h3>{{ category.toUpperCase() }}</h3>
                                    <ul>
                                        <li v-for="icon in icons"
                                            class="flex items-center gap-2">
                                            <svg v-if="icon.ok"
                                                 aria-hidden="true"
                                                 class="h-5 w-5 fill-green-500"
                                                 viewBox="0 0 20 20"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path clip-rule="evenodd"
                                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                                      fill-rule="evenodd"></path>
                                            </svg>
                                            <svg v-else
                                                 aria-hidden="true"
                                                 class="h-5 w-5 fill-red-300"
                                                 viewBox="0 0 20 20"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path clip-rule="evenodd"
                                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z"
                                                      fill-rule="evenodd"></path>
                                            </svg>
                                            <a :href="`${defaultProps.storageUrl}/icons/${icon.name}.png`"
                                               class="text-sm"
                                               target="_blank">{{ icon.name }}
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="p-6 flex items-center justify-center gap-2">
                            <a :href="`${defaultProps.storageUrl}/icons/manifest.json`"
                               class="btn btn-info"
                               target="_blank">manifest.json
                            </a>
                            <a :href="`${defaultProps.storageUrl}/icons/browserconfig.xml`"
                               class="btn btn-info"
                               target="_blank">browserconfig.xml
                            </a>
                        </div>
                    </div>
                    <div class="rounded-b-lg overflow-hidden">
                        <div class="p-3 bg-gray-200 dark:bg-gray-500 flex justify-end items-center">
                            <SaveFormButton :form="form"
                                            :loading="submiting"/>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>
<style lang="scss"
       scoped></style>
