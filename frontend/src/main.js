import { createApp } from "vue";
import { createPinia } from "pinia";

import App from "./App.vue";
import router from "./router";

const app = createApp(App, {
  title: "TickTrack",
});
const pinia = createPinia();

app.use(router).use(pinia);

app.mount("#app");
