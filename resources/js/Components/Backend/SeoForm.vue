<script setup>
import Input from "@core/Components/Form/Input.vue";
import InputImage from "@core/Components/Form/InputImage.vue";
import Textarea from "@core/Components/Form/Textarea.vue";
import ToggleButton from "@core/Components/Form/ToggleButton.vue";
import Icon from "@core/Components/Heroicon.vue";
import Select from "@core/Components/Select.vue";
import {usePage} from "@inertiajs/vue3";
import axios from "axios";
import {computed, onBeforeMount, onMounted, reactive, ref, toRefs, watch} from "vue";
import {useStore} from "vuex";

const props = defineProps({
    seo: Object,
    locale: String,
    autocomplete: Boolean,
    image: String,
    imageUrl: String,
    imageTitle: String,
    imageAlt: String,
    title: String,
    description: String,
    pageType: String,
    articleType: String,
    changeFreq: String,
    priority: Number,
    robots: Array,
    slugPrefix: Object,
});
const {
    seo,
    locale,
    autocomplete,
    image,
    imageUrl,
    imageTitle,
    imageAlt,
    title,
    description,
    robots,
    pageType,
    articleType
} = toRefs(props);
const emit = defineEmits(["update:locale", "update:seo"]);
const form = reactive({
    id: null,
    controller: null,
    alias: null,
    action: null,
    page_type: props.pageType || "website",
    article_type: props.articleType || "website",
    image: null,
    facebook_image: null,
    twitter_image: null,
    canonical: false,
    robots: robots.value,
    autocomplete: autocomplete.value,
    change_freq: props.changeFreq || "never",
    priority: props.priority || 0.5,
    translations: {}
});
const sections = reactive({
    general: true,
    schema: false,
    social: false,
    advanced: false
});
const showSeoAnalysis = ref(false);
const showReadability = ref(false);
const values = reactive({
    title: "",
    facebook_title: "",
    twitter_title: "",
    description: "",
    facebook_description: "",
    twitter_description: "",
    slug: "",
    image: "",
    image_title: "",
    image_alt: "",
    facebook_image: "",
    facebook_image_title: "",
    facebook_image_alt: "",
    twitter_image: "",
    twitter_image_title: "",
    twitter_image_alt: ""
});
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
const site_seo = ref({
    title: null,
    description: null,
    image: null,
    image_alt: null,
    slug: null
});
const googleLimits = {title: {min: 40, max: 60}, description: {min: 140, max: 160}};
const errors = computed(() => usePage().props.errors.seo);

const formattedSlugWithUrl = computed(() => {
    let slug = [usePage().props.default.baseUrl];

    if (usePage().props.default.locales.length > 1 &&
        (
            (form.alias === "home" && useStore().getters["backend/formLocale"] !== usePage().props.default.defaultLocale)
            || (usePage().props.default.locales.length > 1 && form.alias !== "home")
        )) {
        let formLocale = useStore().getters["backend/formLocale"];
        slug.push(formLocale);
    }

    if (props.slugPrefix) {
        slug.push(formatSlug(props.slugPrefix[useStore().getters["backend/formLocale"]]));
    }

    if (values.slug) {
        slug.push(values.slug);
    }

    return slug.join("/");
});

function activeSeccion(section) {
    Object.keys(sections).forEach(function (key) {
        sections[key] = false;
    });
    sections[section] = true;
}

function formatSlug(text) {
    return text
        .toString()
        .normalize("NFKD")
        .replace(/[\u0300-\u036f]/g, "")
        .toLowerCase()
        .trim()
        .replace(/\s+/g, "-")
        .replace(/[^\w-]+/g, "")
        .replace(/\_/g, "-")
        .replace(/--+/g, "-")
        .replace(/\-$/g, "");
}

onBeforeMount(() => {
    Object.keys(seo.value).forEach(key => {
        if (key === "translations") {
            form[key] = Array.isArray(seo.value[key]) ? {} : seo.value[key];
        } else {
            form[key] = seo.value[key];
        }
    });
    Object.keys(seo.value).filter(key => key in values).forEach(key => values[key] = seo.value[key]);
});

onMounted(() => {
    watch(image, (value, oldValue) => {
        if (form.autocomplete) {
            let old = oldValue === null ? "" : oldValue;

            if(!values.image_url || values.image_url === old){
                values.image = value;
            }
        }
    });

    watch(imageTitle, (value, oldValue) => {
        if (form.autocomplete) {
            values.image_title = value;
            let old = oldValue === null ? "" : oldValue;

            if (!values.facebook_image_title || values.facebook_image_title === old) {
                values.facebook_image_title = value;
            }

            if (!values.twitter_image_title || values.twitter_image_title === old) {
                values.twitter_image_title = value;
            }
        }
    });

    watch(imageAlt, (value, oldValue) => {
        if (form.autocomplete) {
            values.image_alt = value;
            let old = oldValue === null ? "" : oldValue;

            if (!values.facebook_image_alt || values.facebook_image_alt === old) {
                values.facebook_image_alt = value;
            }

            if (!values.twitter_image_alt || values.twitter_image_alt === old) {
                values.twitter_image_alt = value;
            }
        }
    });

    watch(() => values.image, (value, oldValue) => {
        form.image = value;
        let old = oldValue === null ? "" : oldValue;
        //values.image_title = values.image.name;
        //values.image_alt = values.image.alt_name;
        //values.image_url = values.image.url;

        if (!form.facebook_image_url || form.facebook_image_url === old) {
            form.facebook_image = value;
            values.facebook_image_title = values.image_title;
            values.facebook_image_alt = values.image_alt;
            values.facebook_image = value;
        }

        if (!form.twitter_image_url || form.twitter_image_url === old) {
            form.twitter_image = value;
            values.twitter_image_title = values.image_title;
            values.twitter_image_alt = values.image_alt;
            values.twitter_image = value;
        }
    });

    watch(title, (value, oldValue) => {
        let old = oldValue === null ? "" : oldValue;

        if (form.autocomplete) {
            if ((values.title === null || values.title !== "") || old === values.title) {
                values.title = value;
                values.slug = value;
            }
        }
    });

    watch(description, (value, oldValue) => {
        let old = oldValue === null ? "" : oldValue;
        if (form.autocomplete) {
            if ((values.description === null || values.description !== "") || old === values.description) {
                values.description = value;
            }
        }
    });

    watch(() => values.title, (value, oldValue) => {
        let old = oldValue === null ? "" : oldValue;
        values.facebook_title = values.facebook_title === null ? "" : values.facebook_title;
        if (values.facebook_title === old) {
            values.facebook_title = value;
        }

        values.twitter_title = values.twitter_title === null ? "" : values.twitter_title;
        if (values.twitter_title === old) {
            values.twitter_title = value;
        }
    });

    watch(() => values.description, (value, oldValue) => {
        let old = oldValue === null ? "" : oldValue;
        values.facebook_description = values.facebook_description === null ? "" : values.facebook_description;
        if (values.facebook_description === old) {
            values.facebook_description = value;
        }
        values.twitter_description = values.twitter_description === null ? "" : values.twitter_description;
        if (values.twitter_description === old) {
            values.twitter_description = value;
        }
    });

    watch(() => values.slug, value => {
        values.slug = formatSlug(value);
    });

    watch(form, value => {
        emit("update:seo", value);
    });

    axios.get(route("api.v1.seo.by_alias", {alias: "home"})).then(response => {
        site_seo.value = {...response.data.data};
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
    <div class="bg-white dark:bg-gray-600 border border-t-0 border-gray-300 dark:border-gray-700 rounded-lg sm:rounded-t-none">
        <div class="p-6 border-b border-gray-300 dark:border-gray-700">
            <ToggleButton v-model="form.autocomplete"
                          :label="__('autocomplete')"
            />
        </div>
        <!-- general -->
        <div v-show="sections.general"
             class="grid divide-y divice-gray-300 dark:divide-gray-700">
            <div class="grid lg:grid-cols-2 divide-y lg:divide-x lg:divide-y-0 divice-gray-300 dark:divide-gray-700">
                <div class="p-6">
                    <div class="mb-8">
                        <Input v-model="form"
                               v-model:value="values.slug"
                               :errors="errors"
                               :label="__('slug')"
                               name="slug"
                               translation></Input>
                        <div class="mt-2">
                            <a :href="usePage().props.default.baseUrl + '/' + values.slug"
                               class="text-sm"
                               target="_blank">{{ formattedSlugWithUrl }}
                            </a>
                        </div>
                        <div class="py-4">
                            <ToggleButton v-model="form.canonical"
                                          :label="__('canonical_url')"/>
                        </div>
                    </div>

                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <Input v-model="form"
                               :errors="errors"
                               :label="__('keyword')"
                               name="keyword"
                               translation></Input>
                    </div>
                    <div class="mb-4">
                        <Input v-model="form"
                               v-model:value="values.title"
                               :errors="errors"
                               :label="__('seo_title')"
                               :limit="[googleLimits.title.min,googleLimits.title.max]"
                               limit-label
                               name="title"
                               translation></Input>
                    </div>
                    <div class="mb-4">
                    <Textarea v-model="form"
                              v-model:value="values.description"
                              :autoresize="false"
                              :errors="errors"
                              :label="__('description')"
                              :limit="[googleLimits.description.min,googleLimits.description.max]"
                              limit-label
                              name="description"
                              rows="4"
                              translation></Textarea>
                    </div>
                    <div>
                        <div class="sm:w-1/2 lg:w-3/4 xl:w-1/2 mb-4">
                            <InputImage v-model:alt_name="values.image_alt"
                                        v-model="form.image"
                                        v-model:title="values.image_title"
                                        v-model:url="values.image"
                                        :errors="errors"
                                        :label="__('cover_image')"
                                        name="image"/>
                        </div>
                        <div>
                            <Input v-model="form"
                                   v-model:value="values.image_title"
                                   :errors="errors"
                                   :label="__('cover_title')"
                                   class="mb-4"
                                   name="facebook_image_title"
                                   translation/>
                            <Textarea v-model="form"
                                      v-model:value="values.image_alt"
                                      :autoresize="false"
                                      :errors="errors"
                                      :label="__('cover_alt')"
                                      name="image_alt"
                                      translation/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- schema -->
        <div v-show="sections.schema"
             class="flex flex-col lg:flex-row gap-4 p-6">
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
        <!-- social -->
        <div v-show="sections.social">
            <div class="grid lg:grid-cols-2 divide-y  lg:divide-y-0 divide-gray-300 dark:divide-gray-700">
                <div class="p-6 grid gap-6">
                    <h3 class="lg:col-span-full lg:border-b border-gray-300 dark:border-gray-700">Facebook</h3>
                    <div>
                        <Input v-model="form"
                               v-model:value="values.facebook_title"
                               :errors="errors"
                               :label="__('facebook_title')"
                               name="facebook_title"
                               translation></Input>
                    </div>
                    <div>
                        <Textarea v-model="form"
                                  v-model:value="values.facebook_description"
                                  :autoresize="false"
                                  :errors="errors"
                                  :label="__('facebook_description')"
                                  name="facebook_description"
                                  rows="4"
                                  translation></Textarea>
                    </div>
                    <div class="grid gap-6">
                        <div class="sm:w-1/2 lg:w-3/4 xl:w-1/2">
                            <InputImage v-model:alt="values.facebook_image_alt"
                                        v-model="form.facebook_image"
                                        v-model:title="values.facebook_image_title"
                                        v-model:url="values.facebook_image"
                                        :label="__('cover_image')"/>
                        </div>
                        <div>
                            <Input v-model="form"
                                   v-model:value="values.facebook_image_title"
                                   :errors="errors"
                                   :label="__('cover_title')"
                                   name="facebook_image_title"
                                   translation/>
                        </div>
                        <div>
                            <Textarea v-model="form"
                                      v-model:value="values.facebook_image_alt"
                                      :autoresize="false"
                                      :errors="errors"
                                      :label="__('cover_alt')"
                                      name="facebook_image_alt"
                                      translation/>
                        </div>
                    </div>
                </div>
                <div class="p-6 grid gap-6">
                    <h3 class="lg:col-span-full lg:border-b border-gray-300 dark:border-gray-700">Twitter</h3>
                    <div>
                        <Input v-model="form"
                               v-model:value="values.twitter_title"
                               :errors="errors"
                               :label="__('twitter_title')"
                               name="twitter_title"
                               translation></Input>
                    </div>
                    <div>
                        <Textarea v-model="form"
                                  v-model:value="values.twitter_description"
                                  :autoresize="false"
                                  :errors="errors"
                                  :label="__('twitter_description')"
                                  name="twitter_description"
                                  rows="4"
                                  translation></Textarea>
                    </div>
                    <div class="grid gap-6">
                        <div class="sm:w-1/2 lg:w-3/4 xl:w-1/2">
                            <InputImage v-model:alt="values.twitter_image_alt"
                                        v-model="form.twitter_image"
                                        v-model:title="values.twitter_image_title"
                                        v-model:url="values.twitter_image"
                                        :label="__('cover_image')"/>
                        </div>
                        <div class="">
                            <Input v-model="form"
                                   v-model:value="values.twitter_image_title"
                                   :errors="errors"
                                   :label="__('cover_title')"
                                   name="twitter_image_title"
                                   translation/>
                        </div>
                        <div class="">
                            <Textarea v-model="form"
                                      v-model:value="values.twitter_image_alt"
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
            <div class="grid grid-cols-1 gap-4 p-6">
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
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-6">
                <h3 class="col-span-full">{{ __("sitemap") }}</h3>
                <div class="mb-4">
                    <Input v-model="form"
                           :errors="errors"
                           :label="__('priority')"
                           name="priority"></Input>
                </div>
                <div class="mb-4">
                    <Select v-model="form.change_freq"
                            :errors="errors"
                            :label="__('change_freq')"
                            :options="[
                                {id: 'always', label: __('always')},
                                {id: 'hourly', label: __('hourly')},
                                {id: 'daily', label: __('daily')},
                                {id: 'weekly', label: __('weekly')},
                                {id: 'monthly', label: __('monthly')},
                                {id: 'yearly', label: __('yearly')},
                                {id: 'never', label: __('never')}]"
                            name="change_freq"></Select>
                </div>
            </div>
        </div>
        <!-- google preview -->
        <div class="border-t dark:border-gray-700">
            <div class="flex items-center justify-between cursor-pointer bg-gray-300 dark:bg-gray-700 p-4"
                 @click="showSeoAnalysis = !showSeoAnalysis">
                <h3>{{ __("preview_google") }}</h3>
                <Icon :icon="!showSeoAnalysis ? 'chevron-up' : 'chevron-down'"
                      class="dark:fill-white"></Icon>
            </div>
            <div v-if="showSeoAnalysis"
                 class="p-4">
                <div>
                    <!-- desktop -->
                    <h3>{{ __("desktop") }}</h3>
                    <div class="max-w-[600px]">
                        <div class="mb-[12px] flex items-center overflow-ellipsis pt-[1px] leading-[16px] text-[14px]">
                            <div class="w-[28px] h-[28px] flex items-center justify-center mr-[12px]">
                                <img :src="`${$page.props.default.storageUrl}/icons/android-icon-48x48.png`"
                                     alt=""
                                     class="w-[18px] h-[18px]">
                            </div>
                            <div class="flex-auto">
                                <div class="text-[14px] font-arial">{{ site_seo.title }}</div>
                                <div class="text-[12px] font-arial min-w-max">{{ $page.props.default.baseUrl }}
                                    <svg v-if="values.slug"
                                         aria-hidden="true"
                                         class="w-2.5 h-2.5 inline-block"
                                         fill="none"
                                         stroke="currentColor"
                                         stroke-width="1.5"
                                         viewBox="0 0 24 24"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8.25 4.5l7.5 7.5-7.5 7.5"
                                              stroke-linecap="round"
                                              stroke-linejoin="round"></path>
                                    </svg>
                                    {{ values.slug }}
                                    <svg aria-hidden="true"
                                         class="inline-block w-5 h-5 ml-2"
                                         fill="none"
                                         stroke="currentColor"
                                         stroke-width="1.5"
                                         viewBox="0 0 24 24"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z"
                                              stroke-linecap="round"
                                              stroke-linejoin="round"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="font-arial text-[20px] text-[#1a0dab] dark:text-[#8ab4f8]">{{
                                    values.title?.length > googleLimits.title.max
                                        ? values.title.substring(0, googleLimits.title.max - 1) + "..."
                                        : values.title
                                }}
                            </div>
                            <div class="font-arial leading-[1.58rem] text-[14px]">{{
                                    values.description?.length > googleLimits.description.max
                                        ? values.description.substring(0, googleLimits.description.max - 1) + "..."
                                        : values.description
                                }}
                            </div>
                        </div>
                    </div>
                    <!-- mobile -->
                    <h3 class="mt-10">{{ __("mobile") }}</h3>
                    <div class="max-w-[400px]">
                        <div class="mb-[12px] flex items-center overflow-ellipsis pt-[1px] leading-[16px] text-[14px]">
                            <div class="w-[28px] h-[28px] flex items-center justify-center mr-[12px]">
                                <img :src="`${$page.props.default.storageUrl}/icons/android-icon-48x48.png`"
                                     alt=""
                                     class="w-[18px] h-[18px]">
                            </div>
                            <div class="flex-auto">
                                <div class="text-[14px] font-arial">{{ site_seo.title }}</div>
                                <div class="text-[12px] font-arial min-w-max">
                                    {{ $page.props.default.baseUrl }}
                                    <svg v-if="values.slug"
                                         aria-hidden="true"
                                         class="w-2.5 h-2.5 inline-block"
                                         fill="none"
                                         stroke="currentColor"
                                         stroke-width="1.5"
                                         viewBox="0 0 24 24"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8.25 4.5l7.5 7.5-7.5 7.5"
                                              stroke-linecap="round"
                                              stroke-linejoin="round"></path>
                                    </svg>
                                    {{ values.slug }}
                                    <svg aria-hidden="true"
                                         class="inline-block w-5 h-5 ml-2"
                                         fill="none"
                                         stroke="currentColor"
                                         stroke-width="1.5"
                                         viewBox="0 0 24 24"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z"
                                              stroke-linecap="round"
                                              stroke-linejoin="round"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="font-arial text-[20px] text-[#1a0dab] dark:text-[#8ab4f8]">
                                {{
                                    values.title?.length > googleLimits.title.max
                                        ? values.title.substring(0, googleLimits.title.max - 1) + "..."
                                        : values.title
                                }}
                            </div>
                            <div class="font-arial leading-[1.58rem] text-[14px]">{{
                                    values.description?.length > googleLimits.description.max
                                        ? values.description.substring(0, googleLimits.description.max - 1) + "..."
                                        : values.description
                                }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--        <div class="border-t dark:border-gray-700">-->
        <!--            <div class="flex items-center justify-between cursor-pointer bg-gray-300 dark:bg-gray-700 p-4"-->
        <!--                 @click="showSeoAnalysis = !showSeoAnalysis">-->
        <!--                <h3>{{ __("seo_analysis") }}</h3>-->
        <!--                <Icon :icon="!showSeoAnalysis ? 'chevron-up' : 'chevron-down'"-->
        <!--                      class="dark:fill-white"></Icon>-->
        <!--            </div>-->
        <!--            <div v-if="showSeoAnalysis"-->
        <!--                 class="p-4">...-->
        <!--            </div>-->
        <!--        </div>-->
        <!--        <div class="border-t dark:border-gray-700 rounded-b-lg">-->
        <!--            <div class="flex items-center justify-between cursor-pointer bg-gray-300 dark:bg-gray-700 p-4 rounded-b-lg"-->
        <!--                 @click="showReadability = !showReadability">-->
        <!--                <h3>{{ __("readability") }}</h3>-->
        <!--                <Icon :icon="!showReadability ? 'chevron-up' : 'chevron-down'"-->
        <!--                      class="dark:fill-white"></Icon>-->
        <!--            </div>-->
        <!--            <div v-if="showReadability"-->
        <!--                 class="p-4">...-->
        <!--            </div>-->
        <!--        </div>-->
    </div>
</template>
<style scoped></style>
