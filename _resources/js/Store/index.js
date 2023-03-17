import { createStore } from "vuex";
import frontend from "./modules/frontend";
import backend from "./modules/backend";
import custom from "./modules/custom";

export default createStore({
    modules: {
        backend,
        frontend,
        custom
    }
});
