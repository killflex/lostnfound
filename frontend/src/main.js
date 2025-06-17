import { createApp } from "vue";
import { createPinia } from "pinia";
import "./style.css";
import App from "./App.vue";
import router from "./router";

const app = createApp(App, {
  title: "Lost & Found",
});
const pinia = createPinia();

app.use(router).use(pinia);

app.mount("#app");
