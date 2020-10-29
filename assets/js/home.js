import Vue from "vue";
import App from "./components/App";
import * as VueGoogleMaps from "vue2-google-maps";

Vue.use(VueGoogleMaps, {
    load: {
      key: "AIzaSyCdgTcZurqwDgi-Yi4YH1GawdjGZrYE-R4",
      libraries: "places" 
    }
});

new Vue({
    el: "#app",
    components: { App },
    template: "<App/>"
});