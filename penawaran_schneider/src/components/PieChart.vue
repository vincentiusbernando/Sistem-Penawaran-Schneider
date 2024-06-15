<template>
  <div class="chart-container">
    <canvas ref="pieChart" class="pie-chart"></canvas>
  </div>
</template>

<script>
import { Chart } from "chart.js/auto";

export default {
  props: {
    chartData: {
      type: Object,
      required: true,
    },
  },
  mounted() {
    this.renderPieChart();
  },
  watch: {
    chartData: {
      handler() {
        // Destroy the existing chart before rendering a new one
        if (this.pieChart) {
          this.pieChart.destroy();
        }
        this.renderPieChart();
      },
      deep: true, // Watch for changes in nested properties of chartData
    },
  },
  methods: {
    renderPieChart() {
      this.pieChart = new Chart(this.$refs.pieChart, {
        type: "pie",
        data: this.chartData,
        options: {
          responsive: true,
          maintainAspectRatio: false,
          legend: {
            position: "right",
          },
        },
      });
    },
  },
};
</script>
<style scoped>
.chart-container {
  width: 18rem;
  height: 18rem;
}

.pie-chart {
  width: 100%;
  height: 100%;
}
</style>
