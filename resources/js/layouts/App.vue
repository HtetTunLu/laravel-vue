<template>
    <div>
        <div>
            <router-link :to="{ name: 'Dashboard' }">Dashboard</router-link> |
            <router-link :to="{ name: 'Test' }">Test</router-link>
        </div>
        <router-view></router-view>
    </div>
</template>

<script></script>
