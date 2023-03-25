<script setup>
import { onMounted } from "vue";

// [{position: {lat: xxx, lng: xxx}, label: xxxx, title: xxxx}] or {position: {lat: xxx, lng: xxx}, label: xxxx, title: xxxx}
// {lat: xxx, lng: xxx}
const props = defineProps({
    apiKey: {
        type: String,
        required: true
    },
    markers: {
        type: [Object, Array]
    },
    center: {
        type: Object
    },
    zoom: {
        type: [String, Number],
        default: 7
    },
    image: {
        type: String,
        default: ""
    }
});

// Google Maps
const center = {
    lat: 28.4872623,
    lng: -16.3179302
};

const mapOptions = {
    center,
    zoom: 15
};

const loadGoogleMap = async () => {
    if (typeof google !== "undefined") {
        window.buildMap();
    } else {
        const script = document.createElement("script");
        script.src = `https://maps.googleapis.com/maps/api/js?key=${props.apiKey}&sensor=false&callback=window.buildMap`;
        document.body.appendChild(script);
    }
};

onMounted(() => {
    window.buildMap = () => {
        let map = new google.maps.Map(document.getElementById("google-map"), mapOptions);

        console.log(props.markers instanceof Array, " - hola mundo");

        if (props.markers && props.markers instanceof Array) {
            props.markers.forEach(m => {
                const markerOptions = {
                    position: m.position,
                    map: map,
                    label: m.label,
                    title: m.title
                };

                new google.maps.Marker(markerOptions);
            });
        } else if (props.markers) {
            const markerOptions = {
                position: props.markers.position,
                map: map,
                label: props.markers.label,
                title: props.markers.title
            };

            new google.maps.Marker(markerOptions);
        }
    };

    if(!props.image){
        loadGoogleMap();
    }
});
</script>
<template>
    <div id="google-map"
         :style="`background-image: url('${props.image}')`"
         class="bg-cover bg-center cursor-pointer" @click="props.image ? loadGoogleMap() : ''"></div>
</template>
<style scoped></style>
