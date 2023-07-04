import { createWebHistory, createRouter } from "vue-router";
import Dashboard from "./pages/Dashboard.vue";
import Accessories from "./pages/accessories/index.vue";
import AcccessoryCreate from "./pages/accessories/create.vue";
import AcccessoryEdit from "./pages/accessories/_id/edit.vue";
import AcccessoryShow from "./pages/accessories/_id/show.vue";
import Test from "./pages/Test.vue";
import Login from "./pages/Login.vue";

const routes = [
    {
        path: "/login",
        name: "Login",
        component: Login,
    },
    {
        path: "/",
        name: "Dashboard",
        component: Dashboard,
    },
    {
        path: "/accessories",
        name: "Accessories",
        component: Accessories,
    },
    {
        path: "/accessories/create",
        name: "AccessoryCreate",
        component: AcccessoryCreate,
    },
    {
        path: "/accessories/:id/edit",
        name: "AccessoryEdit",
        component: AcccessoryEdit,
    },
    {
        path: "/accessories/:id/show",
        name: "AccessoryShow",
        component: AcccessoryShow,
    },
    {
        path: "/test",
        name: "Test",
        component: Test,
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
