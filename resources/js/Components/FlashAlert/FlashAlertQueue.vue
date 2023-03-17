<script setup>
import { computed, ref, toRefs } from "vue";
import Alert from "@/Vendor/Core/Components/FlashAlert/Partials/Alert.vue";
import { useStore } from "vuex";

const store = useStore();

const props = defineProps({
    position: {
        type: String,
        default: 'bottom-center',
        validator(value){
            return ["top-right", "top-center", "bottom-center", "bottom-right"].includes(value);
        }
    },
    timeout: {
        type: Number,
        default: 5000
    }
});

const alerts = computed(() => store.getters["backend/alerts"]);

const {timeout, position} = toRefs(props);
</script>
<template>
    <div class="grid z-50 w-[90%] md:w-1/2 xl:w-2/5 fixed"
        :class="[
            {'left-1/2 -translate-x-1/2 bottom-5' : position === 'bottom-center'},
            {'right-5 bottom-5' : position === 'bottom-right'},
            {'right-5 top-5' : position === 'top-right'},
        ]">
        <div v-for="alert in alerts">
            <transition-group name="alert">
                <Alert v-if="alert.show"
                       :key="alert.id"
                       :alert="alert"
                       :timeout="timeout"/>
            </transition-group>
        </div>
    </div>
</template>
<style scoped>
.alert-enter-from, .alert-leave-to {
    @apply opacity-0 translate-x-full
}

.alert-enter-to, .alert-leave-from {
    @apply opacity-100 translate-x-0
}

.alert-enter-active, .alert-leave-active {
    @apply transition-all ease-in-out duration-500
}
</style>
