import Vue from "vue";
import Buefy from "buefy";
import "@/../css/app.scss";
// import "buefy/dist/buefy.css";
import App from "@/components/App.vue";
import router from "@/router";

Vue.config.productionTip = false;
Vue.use(Buefy);

new Vue({
    router,
    render: (h) => h(App),
}).$mount("#app-root");
