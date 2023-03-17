import _ from "lodash";
import { breakpointsTailwind, useBreakpoints } from "@vueuse/core";
import { onMounted, onUnmounted, ref } from "vue";

const breakpoints = useBreakpoints(breakpointsTailwind);

const sizes = ["sm", "md", "lg", "xl", "2xl"];

const screen = ref({
    size: "xs",
    isGreater: {
        sm: false,
        md: false,
        lg: false,
        xl: false,
        "2xl": false
    },
    isSmaller: {
        sm: false,
        md: false,
        lg: false,
        xl: false,
        "2xl": false
    }
});

function reset() {
    screen.value.size = "xs";

    sizes.forEach(size => {
        screen.value.isGreater[size] = false
        screen.value.isSmaller[size] = false
    })
}

export const isGreater = size => {
    return screen.value.isGreater[size];
};

export const isSmaller = size => {
    return screen.value.isSmaller[size];
};

export const screenSize = () => {
    return screen.value.size;
};


export const calculate = _.throttle(() => {
    reset();
    for (let i = 0; i < sizes.length; i ++){
        if (breakpoints.isGreater(sizes[i])) {
            screen.value.size = sizes[i];
            screen.value.isGreater[sizes[i]] = true;
        }

        if(breakpoints.isSmaller(sizes[i])){
            screen.value.isSmaller[sizes[i]] = true;
        }
    }
}, 500)

calculate();

export const breakpointsListeners = () => {
    onMounted(() => {
        window.addEventListener("resize", calculate);
    })
    onUnmounted(() => {
        window.removeEventListener("resize", calculate);
    })
}

