<template>
  <div class="chart-container">
    <canvas ref="pieChart" class="pie-chart"></canvas>
  </div>
</template>

<script>
import { Chart } from "chart.js/auto";
import ChartDataLabels from "chartjs-plugin-datalabels";

Chart.register(ChartDataLabels);

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
          plugins: {
            legend: {
              position: "right",
            },
            datalabels: {
              color: "#fff",
              anchor: "end",
              align: "start",
              offset: 10,
              borderWidth: 2,
              borderColor: "#fff",
              backgroundColor: (context) => {
                return context.dataset.backgroundColor;
              },
              formatter: (value, context) => {
                let sum = 0;
                let dataArr = context.chart.data.datasets[0].data;
                dataArr.map(data => {
                  sum += data;
                });
                let percentage = ((value / sum) * 100).toFixed(2) + '%';
                return percentage;
              },
            },
          },
        },
      });
    },
  },
};
</script>

<style scoped>
.chart-container {
  width: 40rem;
  height: 15rem;
}

.pie-chart {
  width: 90%;
  height: 90%;
}
</style>
