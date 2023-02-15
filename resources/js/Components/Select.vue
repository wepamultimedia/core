<script setup>
import { onBeforeMount, ref, toRefs, watch } from "vue";
import Icon from "@/Core/Components/Heroicon.vue";

const props = defineProps({
    options: Array,
    placeholder: String,
    multiSelect: Boolean,
    translateLabel: Boolean,
    modelValue: [String, Number, Object, Array],
    errors: {
        type: Object,
        default: {}
    },
    reduce: {
        type: Boolean,
        default: true
    },
    searcheable: Boolean,
    debug: Boolean,
    closeOnSelect: {
        type: Boolean,
        default: true
    },
    label: String,
    optionLabel: {
        type: String,
        default: "label"
    }
});
const emit = defineEmits(["update:modelValue"]);

const {
          optionLabel,
          height,
          options,
          modelValue,
          reduce,
          multiSelect
      } = toRefs(props);

const open = ref(false);
const search = ref("");
const optionsFiltered = ref(options.value);
const selectedOption = ref({});

const buildSelect = () => {
    if (multiSelect.value) {
        selectedOption.value = [];
        if (reduce.value && typeof modelValue.value === "object" && modelValue.value !== null) {
            modelValue.value.map(selected => {
                const result = options.value.find(option => option.id === selected);
                if (result) {
                    selectOption(result);
                }
            });
        } else if (!reduce.value && typeof modelValue.value === "object" && modelValue.value !== null) {
            modelValue.value.map(selected => {
                const result = options.value.find(option => option.id === selected.id);
                if (result) {
                    selectOption(result);
                }
            });
        }
    } else {
        if (reduce.value && typeof (modelValue.value === "number" || modelValue.value === "string")) {
            selectOption(options.value.find(option => option.id === modelValue.value));
        } else if (typeof modelValue.value === "object" && modelValue.value !== null && modelValue.value.hasOwnProperty("id")) {
            selectOption(options.value.find(option => option.id === modelValue.value.id));
        }
    }
}

watch(search, value => {
    if (value !== "" || value) {
        const re = new RegExp(value, "i");
        optionsFiltered.value = options.value.filter(option => re.test(option[optionLabel.value]));
    } else {
        optionsFiltered.value = options.value;
    }
});

onBeforeMount(() => {
    buildSelect();
});

const selectOption = option => {
    if (options.value.length > 0) {
        if (multiSelect.value) {
            if (option && typeof option === "object") {
                if (selectedOption.value.find(so => so.id === option.id)) {
                    selectedOption.value.splice(selectedOption.value.indexOf(selectedOption.value.find(so => so.id === option.id)), 1);
                    if (reduce.value) {
                        emit("update:modelValue", selectedOption.value.map(option => option.id));
                    } else {
                        emit("update:modelValue", selectedOption);
                    }
                } else {
                    selectedOption.value.push(option);
                    if (reduce.value) {
                        emit("update:modelValue", selectedOption.value.map(option => option.id));
                    } else {
                        emit("update:modelValue", selectedOption.value);
                    }
                }
            } else {
                emit("update:modelValue", null);
            }
        } else {
            if (selectedOption.value !== null && selectedOption.value.hasOwnProperty("id") && selectedOption.value.id === option.id) {
                selectedOption.value = {};
                if (reduce.value) {
                    emit("update:modelValue", null);
                } else {
                    emit("update:modelValue", null);
                }
            } else {
                selectedOption.value = option ? option : {};
                if (reduce.value) {
                    if (typeof option === "object" && option !== null && option.hasOwnProperty("id")) {
                        emit("update:modelValue", option.id);
                    } else {
                        emit("update:modelValue", null);
                    }
                } else {
                    emit("update:modelValue", selectedOption.value);
                }
            }
        }
    }
}
const openClick = () => {
    let oldValue = open.value;
    document.body.click();
    open.value = !oldValue;
}

document.body.addEventListener("click", () => {
    open.value = false;
});

</script>
<template>
    <div class="font-medium relative"
         v-bind="$attrs">
        <label v-if="label"
               class="text-sm"
               @click.stop="open = !open">
            {{ label }}
        </label>
        <div class="mt-1 bg-white dark:bg-gray-600 w-full p-2 flex justify-between items-center border rounded-lg border-gray-300 dark:border-gray-700 min-w-max cursor-pointer"
             @click.stop="openClick()">
            <div v-if="multiSelect"
                 class="flex flex-wrap gap-1">
                <span v-if="selectedOption.length < 1">{{ placeholder }}</span>
                <span v-for="so in selectedOption"
                      :key="so.id"
                      class="border border-gray-300 dark:border-gray-600 bg-gray-200 dark:bg-gray-700 px-2 py-1 justify-between rounded flex items-center gap-1">
                    <span v-if="translateLabel">{{ __(so[optionLabel]) }}</span>
                    <span v-else>{{ so[optionLabel] }}</span>
                    <Icon class="fill-gray-600 dark:fill-gray-800 min-w-max cursor-pointer"
                          icon="x"
                          size="2"
                          @click.stop="selectOption(so)"/>
                </span>
            </div>
            <span v-else>
                {{
                    selectedOption.hasOwnProperty("id") ? translateLabel ? __(selectedOption[optionLabel])
                                                                         : selectedOption[optionLabel] : placeholder
                }}
            </span>
            <svg :class="{'rotate-180': open}"
                 class="fill-gray-600 dark:fill-white min-w-max"
                 fill="none"
                 height="20"
                 viewBox="0 0 20 20"
                 width="20"
                 xmlns="http://www.w3.org/2000/svg">
                <path clip-rule="evenodd"
                      d="M5.29289 7.29289C5.68342 6.90237 6.31658 6.90237 6.70711 7.29289L10 10.5858L13.2929 7.29289C13.6834 6.90237 14.3166 6.90237 14.7071 7.29289C15.0976 7.68342 15.0976 8.31658 14.7071 8.70711L10.7071 12.7071C10.3166 13.0976 9.68342 13.0976 9.29289 12.7071L5.29289 8.70711C4.90237 8.31658 4.90237 7.68342 5.29289 7.29289Z"
                      fill-rule="evenodd"/>
            </svg>
        </div>
        <div :class="{'max-h-60 shadow border': open, 'max-h-0': !open}"
             class="w-full absolute bg-white dark:bg-gray-600 mt-2 rounded-lg overflow-hidden overflow-y-auto z-50 min-w-[200px]"
             v-bind="$attrs">
            <div v-if="searcheable"
                 class="flex items-center px-2 sticky top-0 bg-white">
                <svg class="fill-gray-600"
                     fill="none"
                     height="20"
                     viewBox="0 0 20 20"
                     width="20"
                     xmlns="http://www.w3.org/2000/svg">
                    <path clip-rule="evenodd"
                          d="M8 4C5.79086 4 4 5.79086 4 8C4 10.2091 5.79086 12 8 12C10.2091 12 12 10.2091 12 8C12 5.79086 10.2091 4 8 4ZM2 8C2 4.68629 4.68629 2 8 2C11.3137 2 14 4.68629 14 8C14 9.29583 13.5892 10.4957 12.8907 11.4765L17.7071 16.2929C18.0976 16.6834 18.0976 17.3166 17.7071 17.7071C17.3166 18.0976 16.6834 18.0976 16.2929 17.7071L11.4765 12.8907C10.4957 13.5892 9.29583 14 8 14C4.68629 14 2 11.3137 2 8Z"
                          fill-rule="evenodd"/>
                </svg>
                <input v-model="search"
                       :placeholder="__('search')"
                       class="w-full border-transparent focus:border-transparent focus:ring-0"
                       type="text">
            </div>
            <ul>
                <li v-for="option in optionsFiltered"
                    :class="{'bg-gray-200 dark:bg-gray-800': (selectedOption && option.id === selectedOption.id) || (multiSelect && selectedOption.length && selectedOption.find(o => o.id === option.id))}"
                    class="flex items-center justify-between hover:bg-skin-primary hover:text-skin-inverted p-2 text-sm cursor-pointer fill-gray-600 hover:fill-white"
                    @click.stop="selectOption(option); closeOnSelect ? open = false : null; search = ''">
                    <slot name="option"
                          v-bind="{option}">
                        <span v-if="translateLabel">{{ __(option[optionLabel]) }}</span>
                        <span v-else="translateLabel">{{ option[optionLabel] }}</span>
                    </slot>
                    <Icon v-if="(selectedOption && option.id === selectedOption.id) || (multiSelect && selectedOption.length && selectedOption.find(o => o.id === option.id))"
                          class="fill-inherit"
                          icon="check"/>
                </li>
            </ul>
        </div>
    </div>
    <div v-if="errors[$attrs.name]"
         class="text-red-300 text-sm mt-1">* {{ errors[$attrs.name] }}
    </div>
    <pre v-if="debug">{{ modelValue }}</pre>
</template>
<style scoped></style>