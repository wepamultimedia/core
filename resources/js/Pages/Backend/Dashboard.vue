<script>
import MainLayout from "@pages/Core/Backend/Layouts/MainLayout/MainLayout.vue";

export default {
    layout: (h, page) => h(MainLayout, {title: "dashboard"}, () => page)
};
</script>
<script setup>
import { useStore } from "vuex";

const store = useStore();
function addAlert(){
    store.dispatch("addAlert", {
        show: true,
        message: "Hola mundo",
        type: "info"
    });
}
</script>
<template>
    <button @click="addAlert()">Add alert</button>
</template>
