<template>
    <div>
        <canvas :id="`chat${id}`"></canvas>
    </div>
</template>

<script>
import Chart from "chart.js/auto";
export default {
    name: "Pie",
    props: ["id"],
    mounted() {
        const ctx = document.getElementById(`chat${this.id}`);
        new Chart(ctx, {
            type: "doughnut",
            data: {
                labels: ["Red", "Blue", "Yellow"],
                datasets: [
                    {
                        label: "My First Dataset",
                        data: [80, 20, 30],
                        backgroundColor: [
                            "rgb(255, 99, 132)",
                            "rgb(54, 162, 235)",
                            "rgb(255, 205, 86)",
                        ],
                        hoverOffset: 2,
                    },
                ],
            },
            options: {
                cutout: 100,
            },
        });
    },
};
</script>
