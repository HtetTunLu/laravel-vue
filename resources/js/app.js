import "./bootstrap";
import { createApp } from "vue";
import router from "./router.js";
import App from "./layouts/App.vue";



createApp(App)
    .use(router)
    .mount("#app")
// const app = createApp({
//     components: {
//         App,
//     },
// });
// app.use(router).mount("#app");


