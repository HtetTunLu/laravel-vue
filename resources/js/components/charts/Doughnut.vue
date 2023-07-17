<template>
    <div class="chat">
        <span class="title">{{ title }}</span>
        <canvas :id="`chat${id}`"></canvas>
    </div>
</template>

<script>
import Chart from "chart.js/auto";
export default {
    name: "Pie",
    props: ["id", "title", "labels", 'data'],
    mounted() {
        const ctx = document.getElementById(`chat${this.id}`);
        new Chart(ctx, {
            type: "doughnut",
            data: {
                labels: this.labels,
                datasets: [
                    {
                        label: "Counts",
                        data: this.data,
                        backgroundColor: [
                            "#adadad",
                            "#c2c0c0",
                            "#e6e8e7",
                            "white",
                        ],
                        hoverOffset: 2,
                    },
                ],
            },
            options: {
                cutout: 95,
            },
        });
    },
};
</script>

<style scoped>
.chat {
    position: relative;
}
.title {
    position: absolute;
    top: 53%;
    left: 35%;
    text-align: center;
    font-size: 18px;
    font-weight: bold;
    color: #888888;
}
</style>
