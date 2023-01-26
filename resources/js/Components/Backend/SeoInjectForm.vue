<script setup>
import { onBeforeMount, onMounted, reactive, ref, toRefs, watch } from "vue";
import Textarea from "@core/Components/Form/Textarea.vue";
import Input from "@core/Components/Form/Input.vue";
import InputImage from "@core/Components/Form/InputImage.vue";
import Icon from "@core/Components/Heroicon.vue";
import Select from "@core/Components/Select.vue";
import ToggleButton from "@core/Components/ToggleButton.vue";
import { usePage } from "@inertiajs/inertia-vue3";

const props = defineProps({
    modelValue: {
        type: Object,
        default: {}
    },
    form: Object,
    errors: Object,
    titleProperty: String,
    metaDescriptionProperty: String,
    locale: String,
    autocomplete: {
        type: Boolean,
        default: false
    },
    autocompleteImage: String,
    autocompleteImageTitle: String,
    autocompleteImageAlt: String,
    autocompleteTitle: String,
    autocompleteDescription: String,
    schemaPageType: String,
    schemaArticleType: String,
    advancedRobots: Array
});
const {
          modelValue,
          locale,
          form,
          autocomplete,
          autocompleteImage,
          autocompleteImageTitle,
          autocompleteImageAlt,
          autocompleteTitle,
          autocompleteDescription,
          advancedRobots,
          schemaPageType,
          schemaArticleType,
      } = toRefs(props);

const emit = defineEmits(["update:modelValue", "update:locale"]);

const seo = reactive({
    id: null,
    controller: null,
    action: null,
    page_type: !schemaPageType.value ? "website" : schemaPageType.value,
    article_type: !schemaArticleType.value ? "website" : schemaArticleType.value,
    image: null,
    facebook_image: null,
    twitter_image: null,
    canonical: false,
    robots: advancedRobots.value,
    autocomplete: autocomplete.value,
    translations: {}
});

onBeforeMount(() => {
    if (modelValue.value.hasOwnProperty("id")) {
        seo.id = modelValue.value.id;
    }
    Object.keys(modelValue.value).filter(key => key in seo).forEach(key => seo[key] = modelValue.value[key]);
});

const sections = reactive({
    general: true,
    schema: false,
    social: false,
    advanced: false
});
const showSeoAnalysis = ref(false);
const showReadability = ref(false);
const selectedLocale = ref();
const autocompleteValues = reactive({
    image: seo.autocomplete ? autocompleteImage.value ? autocompleteImage.value : null : null,
    imageTitle: seo.autocomplete ? autocompleteImageTitle.value ? autocompleteImageTitle.value : null : null,
    imageAlt: seo.autocomplete ? autocompleteImageAlt.value ? autocompleteImageAlt.value : null : null,
    title: seo.autocomplete ? autocompleteTitle.value ? autocompleteTitle.value : null : null,
    description: seo.autocomplete ? autocompleteDescription.value ? autocompleteDescription.value : null : null,
    slug: null
});
const activeSeccion = section => {
    Object.keys(sections).forEach(function (key) {
        sections[key] = false;
    });
    sections[section] = true;
};
const generateSlug = text => {
    if (text !== "" && text !== null) {
        axios.get(route("api.seo.slug", {text}), {}, {
            withCredentials: true,
            headers: {
                "Authorization": "Bearer " + usePage().props.value.access_token
            }
        }).then(response => {
            if (response.status === 200) {
                autocompleteValues.slug = response.data;
            }
        });
    }
};
const robotsOptions = [
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
const pageTypes = [
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
const articleTypes = [
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

onMounted(() => {
    watch(seo, value => {
        emit("update:modelValue", value);
    });
    watch(locale, value => {
        if (selectedLocale.value !== value) {
            selectedLocale.value = value;
        }
    });
    watch(selectedLocale, value => {
        if (selectedLocale.value !== locale.value) {
            emit("update:locale", value);
        }
    });
    watch(autocompleteImage, value => {
        if (seo.autocomplete) {
            seo.image = value;
            seo.facebook_image = value;
            seo.twitter_image = value;
        }
    });
    watch(autocompleteImageTitle, value => {
        if (seo.autocomplete) {
            autocompleteValues.imageTitle = value;
        }
    });
    watch(autocompleteImageAlt, value => {
        if (seo.autocomplete) {
            autocompleteValues.imageAlt = value;
        }
    });
    watch(autocompleteTitle, value => {
        if (seo.autocomplete) {
            autocompleteValues.title = value;
            generateSlug(value);
        }
    });
    watch(autocompleteDescription, value => {
        if (seo.autocomplete) {
            autocompleteValues.description = value;
        }
    });
});
</script>
<template>
    <!-- buttons -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-1 mb-2 sm:mb-0">
        <button :class="{'bg-white dark:bg-gray-600 font-bold': sections.general}"
                class="sm:mb-0 border sm:border-b-0 border-gray-300 dark:border-gray-700 z-10 px-4 py-2 rounded sm:rounded-b-none"
                type="button"
                @click="activeSeccion('general')">
            {{ __("general") }}
        </button>
        <button :class="{'bg-white dark:bg-gray-600': sections.schema}"
                class="sm:mb-0 border sm:border-b-0 border-gray-300 dark:border-gray-700 z-10 px-4 py-2 rounded sm:rounded-b-none"
                type="button"
                @click="activeSeccion('schema')">
            {{ __("schema") }}
        </button>
        <button :class="{'bg-white dark:bg-gray-600': sections.social}"
                class="sm:mb-0 border sm:border-b-0 border-gray-300 dark:border-gray-700 z-10 px-4 py-2 rounded sm:rounded-b-none"
                type="button"
                @click="activeSeccion('social')">
            Social
        </button>
        <button :class="{'bg-white dark:bg-gray-600': sections.advanced}"
                class="sm:mb-0 border sm:border-b-0 border-gray-300 dark:border-gray-700 z-10 px-4 py-2 rounded sm:rounded-b-none"
                type="button"
                @click="activeSeccion('advanced')">
            {{ __("advanced") }}
        </button>
    </div>
    <div class="bg-white dark:bg-gray-600 border border-t-0 dark:border-gray-700 rounded-lg sm:rounded-t-none overflow-y-auto overflow-x-hidden">
        <div v-if="autocomplete"
             class="p-4 border-b border-gray-300 dark:border-gray-700">
            <ToggleButton v-model="seo.autocomplete"
                          :label="__('autocomplete')"/>
        </div>
        <!-- general -->
        <div v-show="sections.general"
             class="flex flex-col lg:flex-row divide-y lg:divide-x lg:divide-y-0 divice-gray-300 dark:divide-gray-700">
            <div class="lg:w-1/2 p-4">
                <div class="">
                    <Input v-model="seo"
                           v-model:locale="selectedLocale"
                           v-model:value="autocompleteValues.slug"
                           :errors="errors.seo"
                           :label="__('slug')"
                           name="slug"
                           translation></Input>
                    <div class="mt-2">
                        <a :href="usePage().props.value.baseUrl + '/' + autocompleteValues.slug"
                           class="text-sm"
                           target="_blank">{{ usePage().props.value.baseUrl + "/" + autocompleteValues.slug }}
                        </a>
                    </div>
                    <div class="py-4">
                        <ToggleButton v-model="seo.canonical"
                                      :label="__('canonical_url')"/>
                    </div>
                </div>
                <div class="mb-4">
                    <Input v-model="seo"
                           v-model:locale="selectedLocale"
                           :errors="errors.seo"
                           :label="__('keyword')"
                           name="keyword"
                           translation></Input>
                </div>
                <div class="mb-4">
                    <Input v-model="seo"
                           v-model:locale="selectedLocale"
                           v-model:value="autocompleteValues.title"
                           :errors="errors.seo"
                           :label="__('seo_title')"
                           name="title"
                           translation></Input>
                </div>
                <div class="mb-4">
                    <Textarea v-model="seo"
                              v-model:locale="selectedLocale"
                              v-model:value="autocompleteValues.description"
                              :autoresize="false"
                              :errors="errors.seo"
                              :label="__('description')"
                              name="description"
                              rows="4"
                              translation></Textarea>
                </div>
            </div>
            <div class="lg:w-1/2 p-4">
                <div class="sm:w-1/2 lg:w-3/4 xl:w-1/2">
                    <InputImage v-model="seo.image"
                                :errors="errors.seo"
                                :label="__('cover_image')"
                                name="image"/>
                </div>
                <div class="mt-4">
                    <Input v-model="seo"
                           v-model:locale="selectedLocale"
                           v-model:value="autocompleteValues.imageTitle"
                           :errors="errors"
                           :label="__('cover_title')"
                           name="facebook_image_title"
                           translation/>
                </div>
                <div class="mt-4">
                    <Textarea v-model="seo"
                              v-model:locale="selectedLocale"
                              v-model:value="autocompleteValues.imageAlt"
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
                    <Select v-model="seo.page_type"
                            :label="__('page_type')"
                            :options="pageTypes"
                            :translate-label="true"
                            reduce></Select>
                </div>
                <div class="mb-4">
                    <Select v-model="seo.article_type"
                            :label="__('article_type')"
                            :options="articleTypes"
                            :translate-label="true"
                            reduce></Select>
                </div>
            </div>
            <div class="mb-6 w-1/2"></div>
        </div>
        <!-- social -->
        <div v-show="sections.social">
            <div class="grid lg:grid-cols-2 divide-y lg:divide-x lg:divide-y-0 divide-gray-300 dark:divide-gray-700">
                <h3 class="lg:col-span-2 p-4 bg-gray-700">Facebook</h3>
                <div class="p-4">
                    <div class="mt-4">
                        <Input v-model="seo"
                               v-model:locale="selectedLocale"
                               v-model:value="autocompleteValues.title"
                               :errors="errors.seo"
                               :label="__('facebook_title')"
                               name="facebook_title"
                               translation></Input>
                    </div>
                    <div class="mt-4">
                        <Textarea v-model="seo"
                                  v-model:locale="selectedLocale"
                                  v-model:value="autocompleteValues.description"
                                  :autoresize="false"
                                  :errors="errors.seo"
                                  :label="__('facebook_description')"
                                  name="facebook_description"
                                  rows="4"
                                  translation></Textarea>
                    </div>
                </div>
                <div class="p-4">
                    <div class="mb-4">
                        <div class="sm:w-1/2 lg:w-3/4 xl:w-1/2">
                            <InputImage v-model="seo.facebook_image"
                                        :label="__('cover_image')"/>
                        </div>
                        <div class="mt-4">
                            <Input v-model="seo"
                                   v-model:locale="selectedLocale"
                                   v-model:value="autocompleteValues.imageTitle"
                                   :errors="errors"
                                   :label="__('cover_title')"
                                   name="facebook_image_title"
                                   translation/>
                        </div>
                        <div class="mt-4">
                            <Textarea v-model="seo"
                                      v-model:locale="selectedLocale"
                                      v-model:value="autocompleteValues.imageAlt"
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
                <h3 class="lg:col-span-2 p-4 bg-gray-700">Twitter</h3>
                <div class="p-4">
                    <div class="mt-4">
                        <Input v-model="seo"
                               v-model:locale="selectedLocale"
                               v-model:value="autocompleteValues.title"
                               :errors="errors.seo"
                               :label="__('twitter_title')"
                               name="twitter_title"
                               translation></Input>
                    </div>
                    <div class="mt-4">
                        <Textarea v-model="seo"
                                  v-model:locale="selectedLocale"
                                  v-model:value="autocompleteValues.description"
                                  :autoresize="false"
                                  :errors="errors.seo"
                                  :label="__('twitter_description')"
                                  name="twitter_description"
                                  rows="4"
                                  translation></Textarea>
                    </div>
                </div>
                <div class="p-4">
                    <div class="mb-4">
                        <div class="sm:w-1/2 lg:w-3/4 xl:w-1/2">
                            <InputImage v-model="seo.twitter_image"
                                        :label="__('cover_image')"/>
                        </div>
                        <div class="mt-4">
                            <Input v-model="seo"
                                   v-model:locale="selectedLocale"
                                   v-model:value="autocompleteValues.imageTitle"
                                   :errors="errors"
                                   :label="__('cover_title')"
                                   name="twitter_image_title"
                                   translation/>
                        </div>
                        <div class="mt-4">
                            <Textarea v-model="seo"
                                      v-model:locale="selectedLocale"
                                      v-model:value="autocompleteValues.imageAlt"
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
        <!-- advanced -->
        <div v-show="sections.advanced">
            <div class="grid grid-cols-1 gap-4 px-4 py-8">
                <h3>{{ __("search_engine") }}</h3>
                <div class="mb-4">
                    <Select v-model="seo.robots"
                            :label="__('advanced_robots')"
                            :options="robotsOptions"
                            :translate-label="true"
                            multi-select
                            reduce></Select>
                </div>
            </div>
        </div>
        <div class="border-t dark:border-gray-700">
            <div class="flex items-center justify-between cursor-pointer bg-gray-300 dark:bg-gray-700 p-4"
                 @click="showSeoAnalysis = !showSeoAnalysis">
                <h3>{{ __("seo_analysis") }}</h3>
                <Icon :icon="!showSeoAnalysis ? 'chevron-up' : 'chevron-down'"
                      class="dark:fill-white"></Icon>
            </div>
            <div v-if="showSeoAnalysis"
                 class="p-4">...
            </div>
        </div>
        <div class="border-t dark:border-gray-700 ">
            <div class="flex items-center justify-between cursor-pointer bg-gray-300 dark:bg-gray-700 p-4"
                 @click="showReadability = !showReadability">
                <h3>{{ __("readability") }}</h3>
                <Icon :icon="!showReadability ? 'chevron-up' : 'chevron-down'"
                      class="dark:fill-white"></Icon>
            </div>
            <div v-if="showReadability"
                 class="p-4">...
            </div>
        </div>
    </div>
</template>
<style scoped></style>
