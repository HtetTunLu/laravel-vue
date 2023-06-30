<template>
    <div class="main">
        <router-link :to="{ name: 'Dashboard' }">Dashboard</router-link> |
        <router-link :to="{ name: 'Test' }">Test</router-link> |
        <router-link :to="{ name: 'Accessories' }">Accessories</router-link>
        <h1>This is Dashboard Page</h1>
    </div>
</template>
<script>
export default {
    name: "Dashboard",
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
