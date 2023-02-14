<script setup>
import { toRefs } from "vue";
import { useStore } from "vuex";

const props = defineProps({
    alert: Object,
    timeout: {
        type: Number,
        default: 10000
    }
});

const emit = defineEmits(["close"]);

const store = useStore();
const {timeout, alert} = toRefs(props);

let showTimeout = null;
let closed = false;

function startTimeout() {
    showTimeout = setTimeout(() => {
        if(!closed){
            store.dispatch("removeAlert", alert.value);
        }
    }, timeout.value);
}

function mouseOver() {
    clearTimeout(showTimeout);
}

function mouseLeave() {
    startTimeout();
}

function close() {
    closed = true;
    clearTimeout(showTimeout);
    store.dispatch("removeAlert", alert.value);
}

startTimeout();
</script>
<template>
    <div :class="[
             {'bg-red-500': alert.type === 'error'},
             {'bg-blue-500': alert.type === 'info'},
             {'bg-orange-500': alert.type === 'warning'},
             {'bg-green-500': alert.type === 'success'},
         ]"
         class="px-4 py-4 rounded-lg bg-opacity-90 flex justify-between items-start gap-2 shadow-lg mb-2"
         @mouseleave="mouseLeave()"
         @mouseover="mouseOver()">
        <div class="w-full">
            <slot>
                <div v-if="typeof alert.message === 'string'">
                    {{ alert.message }}
                </div>
                <ul v-else class="list-disc pl-4">
                       <li v-for="(message, key) in alert.message">
                           <span v-if="typeof message === 'string'">{{ message }}</span>
                           <template v-else>
                           <span>{{ key.toUpperCase() }}</span>
                               <ul class="list-disc pl-4">
                                   <li v-for="m in message">{{ m }}</li>
                               </ul>
                           </template>
                       </li>
                </ul>
            </slot>
        </div>
        <button class="cursor-pointer"
                @click="close()">
            <svg aria-hidden="true"
                 class="w-5 h-5"
                 fill="currentColor"
                 viewBox="0 0 20 20"
                 xmlns="http://www.w3.org/2000/svg">
                <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"></path>
            </svg>
        </button>
    </div>
</template>
<style scoped>

</style>
