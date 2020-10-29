import Vue from "vue";
import History from "./components/History";
import Weather from "./components/Weather";

new Vue({
  el: "#history",
  components: { History },
  template: "<History/>"
});

new Vue({
  el: "#weather",
  components: { Weather },
  template: "<Weather/>"
});