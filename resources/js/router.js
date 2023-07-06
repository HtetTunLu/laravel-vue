import { createWebHistory, createRouter } from "vue-router";
import Dashboard from "./pages/Dashboard.vue";
import Accessories from "./pages/accessories/index.vue";
import AccessoryCreate from "./pages/accessories/create.vue";
import AccessoryEdit from "./pages/accessories/_id/edit.vue";
import AccessoryShow from "./pages/accessories/_id/show.vue";
import Teams from "./pages/teams/index.vue";
import TeamCreate from "./pages/teams/create.vue";
import TeamEdit from "./pages/teams/_id/edit.vue";
import TeamShow from "./pages/teams/_id/show.vue";
import Test from "./pages/Test.vue";
import Login from "./pages/Login.vue";
import store from "./store";

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
        component: AccessoryCreate,
    },
    {
        path: "/accessories/:id/edit",
        name: "AccessoryEdit",
        component: AccessoryEdit,
    },
    {
        path: "/accessories/:id/show",
        name: "AccessoryShow",
        component: AccessoryShow,
    },
    {
        path: "/teams",
        name: "Teams",
        component: Teams,
    },
    {
        path: "/teams/create",
        name: "TeamCreate",
        component: TeamCreate,
    },
    {
        path: "/teams/:id/edit",
        name: "TeamEdit",
        component: TeamEdit,
    },
    {
        path: "/teams/:id/show",
        name: "TeamShow",
        component: TeamShow,
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

/**
 * This is to handle and check authentication for routing.
 */
router.beforeEach((to, from, next) => {
    const isLoggedIn = store.state.token !== null;
    if (!isLoggedIn && to.name !== "Login") {
        return next("/login");
    } else if (isLoggedIn && to.name === "Login"){
        return next("/")
    } next();
});

export default router;
