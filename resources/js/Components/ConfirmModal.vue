<script setup>
import {computed, ref} from "vue";

const props = defineProps({
    modelValue: Boolean,
    payload: [Object, Number, String, Boolean],
    overlayBgColor: String,
    title: String,
    message: String,
    confirmButtonText: String,
    cancelButtonText: String,
    closeOverlay: Boolean,
    md: Boolean,
    lg: Boolean,
    xl: Boolean,
    danger: Boolean,
    success: Boolean,
    info: Boolean,
    top: Boolean
});

const emit = defineEmits(["update:modelValue", "update:payload", "confirm", "cancel"]);

const show = computed({
    get(){
        return props.modelValue;
    },set(value){
        emit('update:modelValue', value);
    }
})
const open = () => {
    show.value = true;
};

const close = () => {
    show.value = false;
    emit("cancel");
};
</script>
<template>
    <slot :close="close"
          :open="open">
    </slot>
    <div v-if="show"
         class="inline-block">
        <div :class="overlayBgColor"
             class="fixed z-40 w-screen h-screen inset-0 bg-gray-900 bg-opacity-60"
             @click="closeOverlay ? close() : ''"></div>
        <!-- The dialog -->
        <div id="dialog"
             :class="{'sm:w-6/12':md, 'sm:w-9/12':lg, 'sm:w-11/12':xl, '-translate-y-0 top-8': top}"
             class="text-center fixed z-50 top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[90%] sm:w-96 bg-white dark:bg-gray-600 rounded-md space-y-5 drop-shadow-lg overflow-hidden">
            <slot name="header">
                <div v-if="title"
                     class="px-4 pt-6">
                    <h1 class="text-2xl font-semibold">{{ title }}</h1>
                </div>
            </slot>
            <slot name="message">
                <div v-if="message"
                     class="px-4">
                    <p>{{ message }}</p>
                </div>
            </slot>
            <div :class="{'bg-red-200 dark:bg-red-400': danger, 'bg-blue-200 dark:bg-blue-400': info, 'bg-green-200 dark:bg-green-400': success}"
                 class="flex justify-end bg-gray-200 dark:bg-gray-700">
                <div class="px-6 py-4 flex gap-2">
                    <slot :close="close"
                          name="footer">
                        <!-- This button is used to close the dialog -->
                        <button class="btn btn-neutral"
                                @click="close">
                            {{ cancelButtonText ? cancelButtonText : __('cancel') }}
                        </button>
                        <!-- This button is used to confirm action -->
                        <slot name="confirmButton">
                            <button :class="{
                                        'bg-gray-600 hover:bg-gray-800': !danger && !info && !success,
                                        'btn-danger':danger,
                                        'btn-info':info,
                                        'btn-success':success,
                                    }"
                                    class="btn"
                                    @click.stop="close; emit('confirm', payload)">
                                {{ confirmButtonText ? confirmButtonText : __('confirm') }}
                            </button>
                        </slot>
                    </slot>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped></style>
