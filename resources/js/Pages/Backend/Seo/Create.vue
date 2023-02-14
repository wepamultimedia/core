<script>
import MainLayout from "@pages/Core/Backend/Layouts/MainLayout/MainLayout.vue";

export default {
    layout: (h, page) => h(MainLayout, {
        title: "seo",
        icon: "key",
        bc: [
            {
                label: "seo",
                route: "admin.seo.index"
            }, {label: "create"}
        ]
    }, () => page)
};
</script>
<script setup>
import { onBeforeMount, onMounted, reactive, ref, toRefs, watch, watchEffect } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { usePage } from "@inertiajs/inertia-vue3";
import Input from "@core/Components/Form/Input.vue";
import Textarea from "@core/Components/Form/Textarea.vue";
import Modal from "@core/Components/Modal.vue";
import InputImage from "@core/Components/Form/InputImage.vue";
import Select from "@core/Components/Select.vue";
import ToggleButton from "@core/Components/Form/ToggleButton.vue";

const props = defineProps(["errors"]);

const {
          seo
      } = toRefs(props);

const sections = reactive({
    general: true,
    schema: false,
    social: false,
    advanced: false
});

const activeSeccion = section => {
    Object.keys(sections).forEach(function (key) {
        sections[key] = false;
    });
    sections[section] = true;
};

const values = reactive({
    slug: null,
    title: null,
    image: {},
    description: null,
    facebook_title: null,
    facebook_description: null,
    twitter_title: null,
    twitter_description: null,
    image_title: null,
    image_alt: null,
    facebook_image_title: null,
    facebook_image_alt: null,
    twitter_image_title: null,
    twitter_image_alt: null
});

const form = reactive({
    alias: '',
    controller: null,
    action: null,
    page_type: "website",
    article_type: "article",
    image: null,
    facebook_image: null,
    twitter_image: null,
    canonical: false,
    robots: [],
    translations: {}
});
const image = ref("");
const selected_locale = ref();
const robots_options = [
    {
        id: "noindex",
        label: "no_index"
    }, {
        id: "nofollow",
        label: "no_follow"
    }, {
        id: "notranslate",
        label: "no_translate"
    }, {
        id: "noimageindex",
        label: "no_image_index"
    }, {
        id: "nosnippet",
        label: "no_snippet"
    }
];
const page_types = [
    {
        id: "website",
        label: "website"
    }, {
        id: "article",
        label: "article"
    }, {
        id: "about_us",
        label: "about_us"
    }, {
        id: "faq",
        label: "faq"
    }, {
        id: "profile",
        label: "profile"
    }, {
        id: "contact",
        label: "contact"
    }, {
        id: "realestate",
        label: "realestate"
    }, {
        id: "medical",
        label: "medical"
    }, {
        id: "checkout",
        label: "checkout"
    }, {
        id: "search_result",
        label: "search_result"
    }
];
const article_types = [
    {
        id: "article",
        label: "article"
    }, {
        id: "blog_entry",
        label: "blog_entry"
    }, {
        id: "social_media",
        label: "social_media"
    }, {
        id: "news",
        label: "news"
    }, {
        id: "advertising",
        label: "advertising"
    }, {
        id: "satiric",
        label: "satiric"
    }, {
        id: "academic",
        label: "academic"
    }, {
        id: "technical",
        label: "technical"
    }, {
        id: "report",
        label: "report"
    }, {
        id: null,
        label: "none"
    }
];

const submit = () => {
    Inertia.post(route("admin.seo.store"), form);
};

onMounted(() => {
    watch(() => form.image, (value, oldValue) => {
        values.image_title = values.image.name;
        values.image_alt = values.image.alt_name;

        if (!form.facebook_image || form.facebook_image === oldValue) {
            form.facebook_image = values.image.url;
            values.facebook_image_title = values.image.name;
            values.facebook_image_alt = values.image.alt_name;
        }

        if (!form.twitter_image || form.twitter_image === oldValue) {
            form.twitter_image = values.image.url;
            values.twitter_image_title = values.image.name;
            values.twitter_image_alt = values.image.alt_name;
        }
    });

    watch(() => values.title, (value, oldValue) => {
        if (!values.facebook_title || values.facebook_title === oldValue) {
            values.facebook_title = value;
        }

        if (!values.twitter_title || values.twitter_title === oldValue) {
            values.twitter_title = value;
        }
    });
});
</script>
<template>
    <!--Title-->
    <div class="flex justify-between my-0 items-center h-14 rounded-lg overflow-hidden mt-4">
        <span class="dark:text-light font-medium text-xl">{{ __("create_title") }}</span>
    </div>
    <form @submit.prevent="submit">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 text-skin-base dark:text-skin-base-dark mb-8">
            <div class="col-span-1">
                <p class="text-sm">{{ __("craate_summary") }}</p>
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
                        <Input v-model="form.alias"
                               :errors="errors"
                               :label="__('Alias')"
                               autofocus
                               name="alias"/>
                    </div>
                </div>
                <div class="rounded-b-lg overflow-hidden">
                    <div class="p-3 bg-gray-200 dark:bg-gray-500 flex justify-end">
                        <button class="btn btn-primary"
                                type="submit">
                            {{ __("save") }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- buttons -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-1 mb-2 sm:mb-0">
            <button :class="{'bg-white dark:bg-gray-600 font-bold': sections.general}"
                    class="sm:mb-0 border sm:border-b-0 border-gray-300 dark:border-gray-700 z-10 px-4 py-2 rounded-lg sm:rounded-b-none"
                    type="button"
                    @click="activeSeccion('general')">
                {{ __("general") }}
            </button>
            <button :class="{'bg-white dark:bg-gray-600': sections.schema}"
                    class="sm:mb-0 border sm:border-b-0 border-gray-300 dark:border-gray-700 z-10 px-4 py-2 rounded-lg sm:rounded-b-none"
                    type="button"
                    @click="activeSeccion('schema')">
                {{ __("schema") }}
            </button>
            <button :class="{'bg-white dark:bg-gray-600': sections.social}"
                    class="sm:mb-0 border sm:border-b-0 border-gray-300 dark:border-gray-700 z-10 px-4 py-2 rounded-lg sm:rounded-b-none"
                    type="button"
                    @click="activeSeccion('social')">
                Social
            </button>
            <button :class="{'bg-white dark:bg-gray-600': sections.advanced}"
                    class="sm:mb-0 border sm:border-b-0 border-gray-300 dark:border-gray-700 z-10 px-4 py-2 rounded-lg sm:rounded-b-none"
                    type="button"
                    @click="activeSeccion('advanced')">
                {{ __("advanced") }}
            </button>
        </div>
        <div class="col-span-2
                        border-b border-l border-r
                        dark:border-gray-600
                        bg-white dark:bg-gray-600
                        test-skin-base dark:text-skin-base-dark
                        border-gray-300 dark:border-gray-700
                        shadow
                        rounded-b-lg">
            <div class="">
                <!-- general -->
                <div v-show="sections.general"
                     class="flex flex-col lg:flex-row divide-y lg:divide-x lg:divide-y-0 divice-gray-300 dark:divide-gray-700">
                    <div class="lg:w-1/2 p-4">
                        <div class="">
                            <Input v-model="form"
                                   v-model:locale="selected_locale"
                                   v-model:value="values.slug"
                                   :errors="errors"
                                   :label="__('slug')"
                                   name="slug"
                                   translation></Input>
                            <div class="mt-2">
                                <a :href="usePage().props.value.default.baseUrl + '/' + values.slug"
                                   class="text-sm"
                                   target="_blank">{{ usePage().props.value.default.baseUrl + "/" + values.slug }}
                                </a>
                            </div>
                            <div class="py-4">
                                <ToggleButton v-model="form.canonical"
                                              :label="__('canonical_url')"/>
                            </div>
                        </div>
                        <div class="mb-4">
                            <Input v-model="form"
                                   v-model:locale="selected_locale"
                                   :errors="errors"
                                   :label="__('keyword')"
                                   name="keyword"
                                   translation></Input>
                        </div>
                        <div class="mb-4">
                            <Input v-model="form"
                                   v-model:locale="selected_locale"
                                   v-model:value="values.title"
                                   :errors="errors"
                                   :label="__('seo_title')"
                                   name="title"
                                   translation></Input>
                        </div>
                        <div class="mb-4">
                            <Textarea v-model="form"
                                      v-model:locale="selected_locale"
                                      v-model:value="values.description"
                                      :autoresize="false"
                                      :errors="errors"
                                      :label="__('description')"
                                      name="description"
                                      rows="4"
                                      translation></Textarea>
                        </div>
                    </div>
                    <div class="lg:w-1/2 p-4">
                        <div class="sm:w-1/2 lg:w-3/4 xl:w-1/2">
                            <InputImage v-model="form.image"
                                        v-model:image="values.image"
                                        :errors="errors"
                                        :label="__('cover_image')"
                                        name="image"/>
                        </div>
                        <div class="mt-4">
                            <Input v-model="form"
                                   v-model:locale="selected_locale"
                                   v-model:value="values.image_title"
                                   :errors="errors"
                                   :label="__('cover_title')"
                                   name="image_title"
                                   translation/>
                        </div>
                        <div class="mt-4">
                            <Textarea v-model="form"
                                      v-model:locale="selected_locale"
                                      v-model:value="values.image_alt"
                                      :autoresize="false"
                                      :errors="errors"
                                      :label="__('cover_alt')"
                                      name="image_alt"
                                      translation/>
                        </div>
                    </div>
                </div>
                <!-- schema -->
                <div v-show="sections.schema"
                     class="flex flex-col lg:flex-row gap-4 p-4">
                    <div class="lg:w-1/2">
                        <div class="mb-4">
                            <Select v-model="form.page_type"
                                    :label="__('page_type')"
                                    :options="page_types"
                                    :translate-label="true"
                                    reduce></Select>
                        </div>
                        <div class="mb-4">
                            <Select v-model="form.article_type"
                                    :label="__('article_type')"
                                    :options="article_types"
                                    :translate-label="true"
                                    reduce></Select>
                        </div>
                    </div>
                    <div class="mb-6 w-1/2"></div>
                </div>
            </div>
            <!-- social -->
            <div v-show="sections.social">
                <div class="grid lg:grid-cols-2 divide-y lg:divide-x lg:divide-y-0 divide-gray-300 dark:divide-gray-700">
                    <h3 class="lg:col-span-2 p-4 bg-gray-300 dark:bg-gray-700">Facebook</h3>
                    <div class="p-4">
                        <div class="mt-4">
                            <Input v-model="form"
                                   v-model:locale="selected_locale"
                                   v-model:value="values.facebook_title"
                                   :errors="errors"
                                   :label="__('facebook_title')"
                                   name="facebook_title"
                                   translation></Input>
                        </div>
                        <div class="mt-4">
                            <Textarea v-model="form"
                                      v-model:locale="selected_locale"
                                      :autoresize="false"
                                      :errors="errors"
                                      :label="__('facebook_description')"
                                      name="facebook_description"
                                      rows="4"
                                      translation></Textarea>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="mb-4">
                            <div class="sm:w-1/2 lg:w-3/4 xl:w-1/2">
                                <InputImage v-model="form.facebook_image"
                                            v-model:title="values.facebook_image_title"
                                            v-model:alt="values.facebook_image_alt"
                                            :label="__('cover_image')"/>
                            </div>
                            <div class="mt-4">
                                <Input v-model="form"
                                       v-model:locale="selected_locale"
                                       v-model:value="values.facebook_image_title"
                                       :errors="errors"
                                       :label="__('cover_title')"
                                       name="facebook_image_title"
                                       translation/>
                            </div>
                            <div class="mt-4">
                                <Textarea v-model="form"
                                          v-model:locale="selected_locale"
                                          v-model:value="values.facebook_image_alt"
                                          :autoresize="false"
                                          :errors="errors"
                                          :label="__('cover_alt')"
                                          name="facebook_image_alt"
                                          translation/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 border-t border-gray-300 dark:border-gray-700 divide-y lg:divide-x lg:divide-y-0 divide-gray-300 dark:divide-gray-700">
                    <h3 class="lg:col-span-2 p-4 bg-gray-300 dark:bg-gray-700">Twitter</h3>
                    <div class="p-4">
                        <div class="mt-4">
                            <Input v-model="form"
                                   v-model:locale="selected_locale"
                                   v-model:value="values.twitter_title"
                                   :errors="errors"
                                   :label="__('twitter_title')"
                                   name="twitter_title"
                                   translation></Input>
                        </div>
                        <div class="mt-4">
                            <Textarea v-model="form"
                                      v-model:locale="selected_locale"
                                      :autoresize="false"
                                      :errors="errors"
                                      :label="__('twitter_description')"
                                      name="twitter_description"
                                      rows="4"
                                      translation></Textarea>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="mb-4">
                            <div class="sm:w-1/2 lg:w-3/4 xl:w-1/2">
                                <InputImage v-model="form.twitter_image"
                                            v-model:title="values.twitter_image_title"
                                            v-model:alt="values.twitter_image_alt"
                                            :label="__('cover_image')"/>
                            </div>
                            <div class="mt-4">
                                <Input v-model="form"
                                       v-model:value="values.twitter_image_title"
                                       v-model:locale="selected_locale"
                                       :errors="errors"
                                       :label="__('cover_title')"
                                       name="twitter_image_title"
                                       translation/>
                            </div>
                            <div class="mt-4">
                                <Textarea v-model="form"
                                        v-model:value="values.twitter_image_alt"
                                          v-model:locale="selected_locale"
                                          :autoresize="false"
                                          :errors="errors"
                                          :label="__('cover_alt')"
                                          name="twitter_image_alt"
                                          translation/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-show="sections.advanced">
                <div class="grid grid-cols-1 gap-4 px-4 py-8">
                    <h3>{{ __("search_engine") }}</h3>
                    <div class="mb-4">
                        <Select v-model="form.robots"
                                :label="__('advanced_robots')"
                                :options="robots_options"
                                :translate-label="true"
                                multi-select
                                reduce></Select>
                    </div>
                </div>
            </div>
            <div class="rounded-b-lg overflow-hidden">
                <div class="p-3 bg-gray-200 dark:bg-gray-500 flex justify-end">
                    <button class="btn btn-primary"
                            type="submit">
                        {{ __("save") }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</template>
<style lang="scss"
       scoped></style>
