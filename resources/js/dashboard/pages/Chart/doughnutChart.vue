<template>
  <div>
    <canvas id="doughnutChart"></canvas>
  </div>
</template>

<script>
import Chart from "chart.js/auto";
export default {
  setup() {
    return {};
  },
  mounted() {
    const ctx = document.getElementById("doughnutChart");

    new Chart(ctx, {
      type: "doughnut",
      data: {
        labels: ["Red", "Blue"],
        datasets: [
          {
            label: "My First Dataset",
            data: [300, 100],
            backgroundColor: [
              "rgb(255, 99, 132)",
              "rgb(255, 205, 86)",
            ],
            hoverOffset: 4,
          },
          
        ],
      },
      options: {
        scales: {
          y: {
            beginAtZero: true,
          },
        },
      },
    });
  },
};
</script>

<style></style>
