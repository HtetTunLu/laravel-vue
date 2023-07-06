<template>
    <div class="btn-main">
        <font-awesome-icon
            icon="fa-solid fa-backward"
            bounce
            size="xl"
            class="back"
            @click="back"
        />
    </div>
</template>

<script>
export default {
    name: "Back",
    data: () => {
        return {
            prevRoute: null,
        };
    },
    methods: {
        back() {
            this.$router.go(-1)
        },
    },
};
</script>

<style scoped>
.btn-main {
    float: left;
    margin-top: 30px;
    margin-left: 8%;
}

.back {
    color: rgba(157, 187, 165, 1);
}

.back:hover {
    opacity: 0.4;
    color: black;
    cursor: pointer;
}

.previous {
    background-color: rgba(157, 187, 165, 1);
    color: black;
    font-size: 20px;
}

.round {
    border-radius: 50%;
}
</style>
