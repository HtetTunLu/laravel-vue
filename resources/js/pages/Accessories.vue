<template>
    <div class="main">
        <router-link :to="{ name: 'Dashboard' }">Dashboard</router-link> |
        <router-link :to="{ name: 'Test' }">Test</router-link>
        <h1>This is Accessories Page</h1>
    </div>
</template>
<script>
import axios from "axios";
export default {
    name: "Dashboard",
    created() {
        const accessToken = this.$store.state.token
        axios.defaults.headers = {
            Accept: "application/json",
            Authorization: `Bearer ${accessToken}`,
        };
        axios
            .get("api/accessories")
            .then((response) => {
                console.log(response);
            })
            .catch((error) => {
                console.log(error);
            });
    },
};
</script>

<style scoped>
.main {
    height: calc(100vh - 1px);
    background: rgb(235, 245, 255);
    background: linear-gradient(
        90deg,
        rgba(235, 245, 255, 1) 0%,
        rgba(221, 231, 231, 1) 51%,
        rgba(253, 255, 244, 1) 100%
    );
}
h1 {
    color: #615f5f;
    text-align: center;
}
</style>
