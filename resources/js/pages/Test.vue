<template>
    <router-link :to="{ name: 'Dashboard' }">Dashboard</router-link> |
    <router-link :to="{ name: 'Test' }">Test</router-link>
    <h1>This is Test Page</h1>
</template>
<script>
export default {
    name: "Test",
};
</script>
