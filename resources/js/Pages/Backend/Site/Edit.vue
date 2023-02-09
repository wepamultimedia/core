<script>
import MainLayout from "@pages/Core/Backend/Layouts/MainLayout/MainLayout.vue";

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
import { reactive, ref, toRefs } from "vue";
import { useForm, usePage, Head } from "@inertiajs/inertia-vue3";
import Input from "@core/Components/Form/Input.vue";
import SeoForm from "@core/Components/Backend/SeoForm.vue";
import SaveFormButton from "@core/Components/Form/SaveFormButton.vue";
import { useStore } from "vuex";
import { __ } from "@core/Mixins/translations";
import iconSizes from "@core/Mixins/iconSizes";

const props = defineProps(["site","errors", "seo"]);
const store = useStore();
const {site, seo} = toRefs(props);

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
    seo: site.value.seo
});
const formIcon = useForm({
    file: null,
    sizes: iconSizes
});
const SeoFormComponent = ref();
const submiting = ref(false);
const defaultProps = usePage().props.value.default;

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
    axios.put(route("admin.site.update"), form, {
        preserveScroll: true,
        preserveState: true,
    }).then(() => {
        store.dispatch("addAlert", {type: "success", message: __("saved")});
        submiting.value = false;
    }).catch(()=> {
        store.dispatch("addAlert", {type: "error", message: errors.value});

        submiting.value = false;
    })


    // form.put(route("admin.site.update"), {
    //     preserveScroll: true,
    //     preserveState: true,
    //     onSuccess: () => {
    //         store.dispatch("addAlert", {type: "success", message: __("saved")});
    //         form.clearErrors();
    //     },
    //     onError: () => {
    //         store.dispatch("addAlert", {type: "error", message: form.errors});
    //     },
    //     onFinish: () => {
    //         submiting.value = false;
    //     }
    // });
    // SeoFormComponent.value.submit({
    //     onSuccess: () => {
    //     },
    //     onError: errors => {
    //         store.dispatch("addAlert", {
    //             type: "error",
    //             message: errors
    //         });
    //         submiting.value = false;
    //     }
    // });
}
function submitIcon() {
    if(formIcon.file) {
        formIcon.post(route("admin.site.icons.generate"), {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                store.dispatch("addAlert", {type: "success", message: __("icons_generated")});
                checkIcons()
            }
        });
    }
}
function setPlace(place){
    console.log(place);
}

checkIcons();
</script>
<template>
    <!--Title-->
    <div class="flex justify-between my-0 items-center h-14 mt-4">
        <h1 class="dark:text-light font-medium text-xl">{{ __("edit_title") }}</h1>
    </div>
    <div class="max-w-7xl">
        <form @submit.prevent="submit">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 text-skin-base dark:text-skin-base-dark mb-8">
                <div class="col-span-1">
                    <p class="text-sm">{{ __("edit_summary") }}</p>
                </div>
                <div class="col-span-2
                        border
                        dark:border-gray-600
                        bg-white dark:bg-gray-600
                        text-gray-700 dark:text-gray-300
                        rounded-lg
                        shadow">
                    <div class="grid grid-cols-6 p-6">
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
                            <div>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-b-lg overflow-hidden">
                        <div class="p-3 bg-gray-200 dark:bg-gray-500 flex justify-end items-center">
                            <SaveFormButton :form="form"
                                            :submiting="submiting"/>
                        </div>
                    </div>
                </div>
            </div>
            <!-- SEO -->
            <div class="pb-8">
                <h2 class="uppercase">{{ __("seo") }}</h2>
                <SeoForm v-model:seo="form.seo"/>
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
                            <SaveFormButton :form="formIcon" v-if="formIcon.file"
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
                                            <a :href="`${defaultProps.storageUrl}/icons/${icon.name}.png`" target="_blank" class="text-sm">{{ icon.name }}</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="p-6 flex items-center justify-center gap-2">
                            <a class="btn btn-info" :href="`${defaultProps.storageUrl}/icons/manifest.json`" target="_blank">manifest.json</a>
                            <a class="btn btn-info" :href="`${defaultProps.storageUrl}/icons/browserconfig.xml`" target="_blank">browserconfig.xml</a>
                        </div>
                    </div>
                    <div class="rounded-b-lg overflow-hidden">
                        <div class="p-3 bg-gray-200 dark:bg-gray-500 flex justify-end items-center">
                            <SaveFormButton :form="form"
                                            :submiting="submiting"/>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>
</template>
<style lang="scss"
       scoped></style>
