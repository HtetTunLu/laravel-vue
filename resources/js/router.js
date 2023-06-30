import { createWebHistory, createRouter } from "vue-router";
import Dashboard from "./pages/Dashboard.vue";
import Accessories from "./pages/Accessories.vue";
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
