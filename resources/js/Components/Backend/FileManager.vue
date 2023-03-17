<script setup>
import { onMounted, reactive, ref, watch } from "vue";
import _ from "lodash";
import { __ } from "@/Vendor/Core/Mixins/translations";
import axios from "axios";
import Pagination from "@/Vendor/Core/Components/Pagination.vue";
import Flap from "@/Vendor/Core/Components/Flap.vue";
import Input from "@/Vendor/Core/Components/Form/Input.vue";
import ToggleButton from "@/Vendor/Core/Components/Form/ToggleButton.vue";
import Textarea from "@/Vendor/Core/Components/Form/Textarea.vue";
import { useStore } from "vuex";

const props = defineProps({
    errors: String,
    contentClasses: String,
    toolbarClasses: String,
    breadcrumbClasses: String,
    modelValue: String
});

const emit = defineEmits(["update:modelValue", "change"]);

const errors = ref();
const files = ref();
const breadcrumb = ref();
const currentParentId = ref(null);
const currentPage = ref(null);
const searchInput = ref();
const search = _.throttle(value => {
    if (value.length > 0) {
        getFiles(currentParentId.value, null, value);
    } else {
        getFiles(currentParentId.value);
    }
}, 100);
const mimeTypes = ref();
const loading = ref(false);
const store = useStore();
// File
const fileInput = ref({});
const isImage = () => {
    if (file.form.file) {
        let fileType = fileInput.value?.files[0]?.type;
        return (fileType?.indexOf("jpeg") !== -1 || fileType?.indexOf("jpg") !== -1 || fileType?.indexOf("png") !== -1);
    }

    return false;
};
const file = reactive({
    flap: false,
    flapMd: true,
    flapLG: false,
    flapSizeToggle: () => {
        file.flapMd = !file.flapMd;
        file.flapLG = !file.flapLG;
    },
    create: false,
    update: false,
    delete: false,
    confirmDeleteInput: "",
    originalSize: false,
    form: {
        id: null,
        name: "",
        altName: "",
        description: "",
        file: "",
        maxSize: 800
    },
    selected: null,
    copyLink: () => {
        let link = `${file.selected.url}`;
        const el = document.createElement("textarea");
        el.value = link;
        el.setAttribute("readonly", "");
        el.style.position = "absolute";
        el.style.left = "-9999px";
        document.body.appendChild(el);
        const selected = document.getSelection().rangeCount > 0 ? document.getSelection().getRangeAt(0) : false;
        el.select();
        document.execCommand("copy");
        document.body.removeChild(el);
        if (selected) {
            document.getSelection().removeAllRanges();
            document.getSelection().addRange(selected);
        }

        //navigator.clipboard.writeText(link);
        store.dispatch("backend/addAlert", {
            type: "info",
            title: `${__("link_copied")}`,
            message: `${link}`
        });
    },
    createSubmit: () => {
        if (!loading.value) {
            const formData = new FormData();
            formData.append("parent_id", currentParentId.value ? currentParentId.value : "");
            formData.append("name", file.form.name);
            formData.append("alt_name", file.form.altName);
            formData.append("description", file.form.description);
            formData.append("file", fileInput.value.files[0]);
            formData.append("max_size", file.originalSize ? 0 : file.form.maxSize);
            loading.value = true;
            axios.post(route("api.v1.filenamager.file.store", {
                parentId: currentParentId.value,
                page: files.value.current_page
            }), formData).then(response => {
                refresh(response.data);
                resetFileFlap();
                loading.value = false;
            }).catch(error => {
                errors.value = error.response.data.errors;
                loading.value = false;
            });
        }
    },
    updateSubmit: () => {
        loading.value = true;
        axios.put(route("api.v1.filenamager.file.update", {
            file: file.form.id,
            page: files.value.current_page
        }), {
            "id": file.form.id,
            "parent_id": currentParentId.value,
            "name": file.form.name,
            "alt_name": file.form.altName,
            "description": file.form.description
        }).then(response => {
            refresh(response.data);
            resetFileFlap();
            loading.value = false;
        }).catch(error => {
            errors.value = error.response.data.errors;
            loading.value = false;
        });
    },
    deleteSubmit: () => {
        loading.value = true;
        axios.delete(route("api.v1.filenamager.file.destroy", {file: file.form.id})).then(response => {
            getFiles(currentParentId.value);
            resetFileFlap();
            loading.value = false;
        }).catch(error => {
            errors.value = error.response.data.errors;
            loading.value = false;
        });
    }
});
const selectFile = selectedFile => {
    emit("update:modelValue", selectedFile.url);
    emit("change", selectedFile);
};
const updateFile = selectedFile => {
    file.flap = true;
    file.create = false;
    file.update = true;
    file.selected = selectedFile;
    file.form.id = selectedFile.id;
    file.form.name = selectedFile.name;
    file.form.altName = selectedFile.alt_name;
    file.form.description = selectedFile.description;
};
const createFile = () => {
    file.flap = true;
    file.create = true;
    file.update = false;
};
const resetFileFlap = () => {
    file.flap = false;
    file.flapMd = true;
    file.flapLG = false;
    file.create = false;
    file.update = false;
    file.delete = false;
    file.selectedFile = null;
    file.form.id = "";
    file.form.name = "";
    file.form.file = "";
    file.form.altName = "";
    file.form.description = "";
    file.form.maxSize = 800;
    file.originalSize = false;
    file.confirmDeleteInput = "";
    fileInput.value = null;
};

// Folder
const folder = reactive({
    flap: false,
    create: false,
    update: false,
    delete: false,
    confirmDeleteInput: "",
    form: {
        id: null,
        parent_id: currentParentId.value,
        name: ""
    },
    createSubmit: () => {
        loading.value = true;
        folder.form.parent_id = currentParentId;
        axios.post(route("api.v1.filenamager.folder.store", {parentId: currentParentId.value}), folder.form).then(response => {
            refresh(response.data);
            resetFolderFlap();
            loading.value = false;
        }).catch(error => {
            errors.value = error.response.data.errors;
            loading.value = false;
        });
    },
    updateSubmit: () => {
        loading.value = true;
        axios.put(route("api.v1.filenamager.folder.update", {
            file: folder.form.id,
            parentId: currentParentId.value,
            page: files.value.current_page
        }), folder.form).then(response => {
            refresh(response.data);
            resetFolderFlap();
            loading.value = false;
        }).catch(error => {
            errors.value = error.response.data.errors;
            loading.value = false;
        });
    },
    deleteSubmit: () => {
        loading.value = true;
        axios.delete(route("api.v1.filenamager.file.destroy", {
            file: folder.form.id,
            parentId: currentParentId.value,
            page: files.value.current_page
        })).then(response => {
            getFiles(currentParentId.value, currentPage.value);
            resetFolderFlap();
            loading.value = false;
        }).catch(error => {
            errors.value = error.response.data.errors;
            loading.value = false;
        });
    },
    open: file => getFiles(file.id)
});
const createFolder = () => {
    folder.flap = true;
    folder.create = true;
    folder.update = false;
};
const updateFolder = selectedFolder => {
    folder.form.id = selectedFolder.id;
    folder.form.name = selectedFolder.name;
    folder.flap = true;
    folder.create = false;
    folder.update = true;
};
const resetFolderFlap = () => {
    folder.flap = false;
    folder.create = false;
    folder.update = false;
    folder.delete = false;
    folder.form.id = "";
    folder.form.name = "";
    folder.confirmDeleteInput = "";
};


// Files
const refresh = data => {
    files.value = data.files;
    breadcrumb.value = data.breadcrumb;
    currentPage.value = data.current_page;
    if (data.parentId) {
        currentParentId.value = data.parentId;
    }
};
const getFiles = (parentId = null, page = null, search = null) => {
    loading.value = true;
    axios.get(route("api.v1.filenamager.index", {
        parentId,
        page,
        search
    })).then(response => {
        currentParentId.value = parentId;
        refresh(response.data);
        loading.value = false;
    }).catch(error => {
        errors.value = error.response.data.errors;
        loading.value = false;
    });
};
const getMimeTypes = () => {
    axios.get(route("api.v1.filenamager.mime_types")).then(response => {
        mimeTypes.value = response.data;
        loading.value = false;
    }).catch(error => {
        errors.value = error.response.data.errors;
        loading.value = false;
    });
};

const clicks = reactive({
    click: 0,
    timer: null
});
const onClick = (callbackClick, callbackDbClick) => {
    clicks.click++;
    if (clicks.click === 1) {
        clicks.timer = setTimeout(() => {
            clearTimeout(clicks.timer);
            callbackClick();
            clicks.click = 0;
        }, 300);
    } else {
        clearTimeout(clicks.timer);
        clicks.timer = null;
        clicks.click = 0;
        callbackDbClick();
    }
};
const message = message => {
    console.log(message);
};

watch(searchInput, value => {
    search(value);
});

onMounted(() => {
    getFiles();
    getMimeTypes();
});
</script>
<template>
    <!-- toolbar -->
    <div :class="toolbarClasses"
         class="mb-4">
        <div class="grid lg:grid-cols-2">
            <!-- search input -->
            <div>
                <input v-model="searchInput"
                       :placeholder="__('search')"
                       class="w-full text-sm dark:bg-inherit border-gray-200 dark:border-gray-600 text-skin-base  focus:border-gray-200 focus:ring focus:ring-gray-200 focus:ring-opacity-50 rounded-md shadow-sm"
                       type="text">
            </div>
            <!-- / search input -->
            <!-- main action buttons -->
            <div class="flex flex-col sm:flex-row justify-end items-center gap-2 lg:mt-0 mt-4">
                <button class="btn btn-info text-sm w-full sm:w-auto justify-center"
                        type="button"
                        @click="createFile()">
                    {{ __("upload_file") }}
                </button>
                <button class="btn btn-success text-sm w-full sm:w-auto justify-center"
                        type="button"
                        @click="createFolder()">
                    {{ __("create_folder") }}
                </button>
            </div>
            <!-- / main action buttons -->
        </div>
    </div>
    <!-- / toolbar -->
    <!-- breadcrumb -->
    <div v-if="breadcrumb && breadcrumb.length > 1"
         :class="breadcrumbClasses"
         class="border-b border-gray-200 dark:border-gray-600">
        <span v-for="(bc, index) in breadcrumb">
            <span class="text-sm">
                <span :class="{'font-bold': index === breadcrumb.length -1}"
                      class="cursor-pointer"
                      @click="getFiles(bc.id ? bc.id : null)">
                    {{ bc.name }}
                </span>
                <span v-if="index < breadcrumb.length - 1"
                      class="px-1">/
                </span>
            </span>
        </span>
    </div>
    <!-- / breadcrumb -->
    <!-- content -->
    <div v-if="loading"
         :class="contentClasses"
         class="flex items-center gap-2">
        <svg class="w-5 h-5 text-white animate-spin"
             fill="none"
             viewBox="0 0 24 24"
             xmlns="http://www.w3.org/2000/svg">
            <circle class="opacity-25"
                    cx="12"
                    cy="12"
                    r="10"
                    stroke="currentColor"
                    stroke-width="4"></circle>
            <path class="opacity-75"
                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                  fill="currentColor"></path>
        </svg>
        {{ __("loading") }}...
    </div>
    <div v-else
         :class="contentClasses">
        <div v-if="files && !files.data.length"
             class="text-center">{{ __("folder_empty") }}
        </div>
        <div v-else
             class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-7 2xl:grid-cols-8 gap-6">
            <div v-for="file in files.data"
                 v-if="files"
                 :key="file.id"
                 class="cursor-pointer">
                <div v-if="file.type.icon === 'image'">
                    <div class="rounded-lg overflow-hidden"
                         @click="onClick(() => updateFile(file), () => selectFile(file))">
                        <img :alt="file.alt_name"
                             :src="file.url"
                             class="object-cover aspect-square">
                    </div>
                    <p class="text-sm font-bold mt-2 text-center">{{ file.name }}.{{ file.type.extension }}</p>
                </div>
                <div v-else
                     class="w-full"
                     @click="file.type.icon === 'folder' ? onClick(() => updateFolder(file), () => getFiles(file.id)) : updateFile(file)">
                    <inline-svg :src="'/vendor/core/icons/file-types/' + file.type.icon + '.svg'"
                                class="w-full h-auto"></inline-svg>
                    <p class="text-sm font-bold mt-2 text-center">{{ file.name }}
                        <span v-if="file.type.extension !== '.'">.{{ file.type.extension }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <!-- / content -->
    <!-- pagination -->
    <div v-if="files && files.links.length > 3"
         class="p-6 flex justify-center">
        <Pagination :callback="page => getFiles(currentParentId, page)"
                    :links="files.links"></Pagination>
    </div>
    <!-- / pagination -->
    <!-- folder flap -->
    <Flap v-model="folder.flap"
          :on-close="resetFolderFlap"
          close-background>
        <!-- create folder -->
        <form v-if="folder.create"
              @submit.prevent="folder.createSubmit()">
            <h2 class="pb-4">{{ __("create_folder") }}</h2>
            <div class="flex flex-col gap-4">
                <Input v-model="folder.form.name"
                       :errors="errors"
                       :label="__('name')"
                       autofocus
                       name="name"/>
                <button :disabled="loading"
                        class="btn btn-success flex justify-center"
                        type="submit">
                    <span v-if="!loading">{{ __("save") }}</span>
                    <span v-else>
                        <svg aria-hidden="true"
                             class="animate-spin w-5 h-5 "
                             fill="none"
                             stroke="currentColor"
                             stroke-width="1.5"
                             viewBox="0 0 24 24"
                             xmlns="http://www.w3.org/2000/svg">
                            <path d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"
                                  stroke-linecap="round"
                                  stroke-linejoin="round"></path>
                        </svg>
                    </span>
                </button>
                <button class="btn btn-secondary justify-center"
                        @click.prevent="resetFolderFlap()">{{ __("close") }}
                </button>
            </div>
        </form>
        <!-- /create folder -->
        <!-- show update folder -->
        <form v-if="folder.update"
              @submit.prevent="folder.updateSubmit()">
            <h2 class="pb-4">{{ __("update_folder") }}</h2>
            <div class="flex flex-col gap-4">
                <Input v-model="folder.form.name"
                       :errors="errors"
                       :label="__('name')"
                       autofocus
                       name="name"/>
                <button :disabled="loading"
                        class="btn btn-success flex justify-center"
                        type="submit">
                    <span v-if="!loading">{{ __("save") }}</span>
                    <span v-else>
                        <svg aria-hidden="true"
                             class="animate-spin w-5 h-5 "
                             fill="none"
                             stroke="currentColor"
                             stroke-width="1.5"
                             viewBox="0 0 24 24"
                             xmlns="http://www.w3.org/2000/svg">
                            <path d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"
                                  stroke-linecap="round"
                                  stroke-linejoin="round"></path>
                        </svg>
                    </span>
                </button>
                <button class="btn btn-info justify-center"
                        type="button"
                        @click="getFiles(folder.form.id); resetFolderFlap()">{{ __("open") }}
                </button>
                <button class="btn btn-secondary justify-center"
                        @click.prevent="resetFolderFlap">{{ __("close") }}
                </button>
                <button class="justify-center"
                        type="button"
                        @click="folder.delete = true">{{ __("delete") }}
                </button>
            </div>
        </form>
        <div v-if="folder.delete"
             class="mt-8">
            <hr class="my-4 border-gray-200 dark:border-gray-700">
            <div class="my-4">
                <Input v-model="folder.confirmDeleteInput"
                       :label="__('type_folder_name')"
                       autofocus
                       name="name"/>
            </div>
            <button :disabled="(folder.form.name.toLowerCase() !== folder.confirmDeleteInput.toLocaleLowerCase() || loading)"
                    class="btn btn-danger justify-center"
                    type="button"
                    @click="folder.deleteSubmit()">
                <span v-if="!loading">{{ __("delete") }}</span>
                <span v-else>
                    <svg aria-hidden="true"
                         class="animate-spin w-5 h-5 "
                         fill="none"
                         stroke="currentColor"
                         stroke-width="1.5"
                         viewBox="0 0 24 24"
                         xmlns="http://www.w3.org/2000/svg">
                        <path d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"
                              stroke-linecap="round"
                              stroke-linejoin="round"></path>
                    </svg>
                </span>
            </button>
        </div>
        <!-- / show folder -->
    </Flap>
    <!-- / folder flap -->
    <!-- file flap -->
    <Flap v-model="file.flap"
          :lg="file.flapLG"
          :md="file.flapMd"
          :on-close="resetFileFlap"
          close-background>
        <div v-if="file.create"
             class="flex flex-col gap-4">
            <h2 class="pb-4">{{ __("upload_file") }}</h2>
            <Input v-if="file.form.file"
                   v-model="file.form.name"
                   :errors="errors"
                   :label="__('name')"
                   :legend="__('image_name_legend')"
                   autofocus
                   name="name"
                   required/>
            <div v-if="isImage()">
                <Textarea v-model="file.form.altName"
                          :errors="errors"
                          :label="__('alt_name')"
                          :legend="__('image_alt_name_legend')"
                          name="alt_name"
                          required></Textarea>
            </div>
            <div v-if="isImage()">
                <Textarea v-model="file.form.description"
                          :errors="errors"
                          :label="__('description')"
                          :legend="__('image_description_legend')"
                          name="description"></Textarea>
            </div>
            <input id="file"
                   ref="fileInput"
                   :accept="mimeTypes"
                   class="file:mr-4 file:py-2 file:px-4 mb-4
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
                   @change="file.form.file = $event.target.files[0].name"/>
            <div v-if="isImage()">
                <div class="mb-4">
                    <ToggleButton v-model="file.originalSize"
                                  :label="__('original_size')"></ToggleButton>
                </div>
                <Input v-model="file.form.maxSize"
                       :disabled="file.originalSize"
                       :errors="errors"
                       :label="__('max_size')"
                       :legend="__('max_size_legend')"
                       name="max_size"
                       required
                       type="number"/>
            </div>
            <button v-if="file.form.file !== ''"
                    :disabled="loading"
                    class="btn btn-success justify-center flex items-center"
                    type="button"
                    @click.prevent="file.createSubmit()">
                <span v-if="!loading">{{ __("upload") }}</span>
                <span v-else>
                    <svg aria-hidden="true"
                         class="animate-spin w-5 h-5 "
                         fill="none"
                         stroke="currentColor"
                         stroke-width="1.5"
                         viewBox="0 0 24 24"
                         xmlns="http://www.w3.org/2000/svg">
                        <path d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"
                              stroke-linecap="round"
                              stroke-linejoin="round"></path>
                    </svg>
                </span>
            </button>
            <button class="btn btn-secondary justify-center"
                    @click.prevent="resetFileFlap()">{{ __("close") }}
            </button>
        </div>
        <div v-if="file.update"
             class="flex flex-col gap-4">
            <h2 class="pb-4">{{ __("update_file") }}</h2>
            <div v-if="file.selected.type.icon === 'image'"
                 class="rounded-lg overflow-hidden">
                <img :alt="file.selected.alt_name"
                     :src="file.selected.url"
                     @click="file.flapSizeToggle()">
            </div>
            <inline-svg v-else
                        :src="'/vendor/core/icons/file-types/' + file.selected.type.icon + '.svg'"
                        class="w-1/2 h-auto mx-auto"></inline-svg>
            <Input v-model="file.form.name"
                   :errors="errors"
                   :label="__('name')"
                   :legend="__('image_name_legend')"
                   autofocus
                   name="name"
                   required/>
            <div v-if="file.selected.type.extension === 'jpg' || file.selected.type.extension === 'jpeg' || file.selected.type.extension === 'png'">
                <Textarea v-model="file.form.altName"
                          :errors="errors"
                          :label="__('alt_name')"
                          :legend="__('image_alt_name_legend')"
                          name="alt_name"
                          required></Textarea>
            </div>
            <div v-if="file.selected.type.extension === 'jpg' || file.selected.type.extension === 'jpeg' || file.selected.type.extension === 'png'">
                <Textarea v-model="file.form.description"
                          :errors="errors"
                          :label="__('description')"
                          :legend="__('image_description_legend')"
                          name="description"></Textarea>
            </div>
            <button :disabled="loading"
                    class="btn btn-success justify-center flex items-center"
                    type="button"
                    @click.prevent="file.updateSubmit()">
                <span v-if="!loading">{{ __("save") }}</span>
                <span v-else>
                    <svg aria-hidden="true"
                         class="animate-spin w-5 h-5 "
                         fill="none"
                         stroke="currentColor"
                         stroke-width="1.5"
                         viewBox="0 0 24 24"
                         xmlns="http://www.w3.org/2000/svg">
                        <path d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"
                              stroke-linecap="round"
                              stroke-linejoin="round"></path>
                    </svg>
                </span>
            </button>
            <button class="btn btn-info justify-center"
                    type="button"
                    @click.prevent="file.copyLink()">{{ __("copy_link") }}
            </button>
            <button class="btn btn-secondary justify-center"
                    @click.prevent="resetFileFlap()">{{ __("close") }}
            </button>
            <button class="justify-center"
                    type="button"
                    @click="file.delete = true">{{ __("delete") }}
            </button>
        </div>
        <div v-if="file.delete"
             class="mt-8">
            <hr class="my-4 border-gray-200 dark:border-gray-700">
            <div class="my-4">
                <Input v-model="file.confirmDeleteInput"
                       :label="__('type_file_name')"
                       autofocus
                       name="name"/>
            </div>
            <button :disabled="(file.form.name.toLowerCase() !== file.confirmDeleteInput.toLocaleLowerCase() || loading)"
                    class="btn btn-danger justify-center"
                    type="button"
                    @click="file.deleteSubmit()">
                <span v-if="!loading">{{ __("delete") }}</span>
                <span v-else>
                    <svg aria-hidden="true"
                         class="animate-spin w-5 h-5 "
                         fill="none"
                         stroke="currentColor"
                         stroke-width="1.5"
                         viewBox="0 0 24 24"
                         xmlns="http://www.w3.org/2000/svg">
                        <path d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"
                              stroke-linecap="round"
                              stroke-linejoin="round"></path>
                    </svg>
                </span>
            </button>
        </div>
    </Flap>
    <!-- / file flap -->
</template>
<style scoped></style>
