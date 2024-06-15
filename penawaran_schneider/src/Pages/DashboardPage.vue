<template>
  <div>
    <HeaderComponent></HeaderComponent>
    <DrawerComponent></DrawerComponent>
    <div class="page text-center">
      <h1 class="py-4 text-3xl">Total Nilai Penawaran 4 Minggu Terakhir</h1>
      <div class="flex justify-center">
        <div
          class="rounded-lg border shadow border-gray-300 py-4 px-3 bg-white items-center"
        >
          <h1 class="text-center">3 Minggu Lalu</h1>
          <pie-chart :chartData="pieChartlastThreeWeeksTotalPrice" />
        </div>
        <div
          class="rounded-lg border shadow border-gray-300 py-4 px-3 bg-white items-center"
        >
          <h1 class="text-center">2 Minggu Lalu</h1>
          <pie-chart :chartData="pieChartlastTwoWeeksTotalPrice" />
        </div>
        <div
          class="rounded-lg border shadow border-gray-300 py-4 px-3 bg-white items-center"
        >
          <h1 class="text-center">1 Minggu Lalu</h1>
          <pie-chart :chartData="pieChartlastWeekTotalPrice" />
        </div>
        <div
          class="rounded-lg border shadow border-gray-300 py-4 px-3 bg-white items-center"
        >
          <h1 class="text-center">Minggu Ini</h1>
          <pie-chart :chartData="pieChartcurrentWeekTotalPrice" />
        </div>
      </div>
      <h1 class="py-4 text-3xl">Quantity Penawaran 4 Minggu Terakhir</h1>
      <div class="flex justify-center">
        <div
          class="rounded-lg border shadow border-gray-300 py-4 px-3 bg-white items-center"
        >
          <h1 class="text-center">3 Minggu Lalu</h1>
          <pie-chart :chartData="pieChartlastThreeWeeksQuantity" />
        </div>
        <div
          class="rounded-lg border shadow border-gray-300 py-4 px-3 bg-white items-center"
        >
          <h1 class="text-center">2 Minggu Lalu</h1>
          <pie-chart :chartData="pieChartlastTwoWeeksQuantity" />
        </div>
        <div
          class="rounded-lg border shadow border-gray-300 py-4 px-3 bg-white items-center"
        >
          <h1 class="text-center">1 Minggu Lalu</h1>
          <pie-chart :chartData="pieChartlastWeekQuantity" />
        </div>
        <div
          class="rounded-lg border shadow border-gray-300 py-4 px-3 bg-white items-center"
        >
          <h1 class="text-center">Minggu Ini</h1>
          <pie-chart :chartData="pieChartcurrentWeekQuantity" />
        </div>
      </div>
      <h1 class="py-4 text-3xl">1 Bulan Terakhir</h1>
      <div class="flex justify-center">
        <div
          class="rounded-lg border shadow border-gray-300 py-4 px-3 bg-white items-center"
        >
          <h1 class="text-center">Total Price</h1>
          <pie-chart :chartData="pieChartlastMonthTotalPrice" />
        </div>
        <div
          class="rounded-lg border shadow border-gray-300 py-4 px-3 bg-white items-center"
        >
          <h1 class="text-center">Quantity</h1>
          <pie-chart :chartData="pieChartlastMonthQuantity" />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import HeaderComponent from "../components/HeaderComponent.vue";
import DrawerComponent from "../components/DrawerComponent.vue";
import PieChart from "../components/PieChart.vue";
import { onMounted, ref } from "vue";
import AuthService from "@/AuthService";

let dashboard = ref([]);
let pieChartcurrentWeekTotalPrice = ref({
  labels: [],
  datasets: [
    {
      data: [],
    },
  ],
});
let pieChartlastWeekTotalPrice = ref({
  labels: [],
  datasets: [
    {
      data: [],
    },
  ],
});
let pieChartlastTwoWeeksTotalPrice = ref({
  labels: [],
  datasets: [
    {
      data: [],
    },
  ],
});
let pieChartlastThreeWeeksTotalPrice = ref({
  labels: [],
  datasets: [
    {
      data: [],
    },
  ],
});

let pieChartcurrentWeekQuantity = ref({
  labels: [],
  datasets: [
    {
      data: [],
    },
  ],
});
let pieChartlastWeekQuantity = ref({
  labels: [],
  datasets: [
    {
      data: [],
    },
  ],
});
let pieChartlastTwoWeeksQuantity = ref({
  labels: [],
  datasets: [
    {
      data: [],
    },
  ],
});
let pieChartlastThreeWeeksQuantity = ref({
  labels: [],
  datasets: [
    {
      data: [],
    },
  ],
});
let pieChartlastMonthTotalPrice = ref({
  labels: [],
  datasets: [
    {
      data: [],
    },
  ],
});
let pieChartlastMonthQuantity = ref({
  labels: [],
  datasets: [
    {
      data: [],
    },
  ],
});
onMounted(async () => {
  try {
    const response = await AuthService.dashboard();
    dashboard.value = response.data;
    if (response.data.lastThreeWeeksTotalPrice.length > 0) {
      pieChartlastThreeWeeksTotalPrice.value = {
        labels: response.data.lastThreeWeeksTotalPrice.map(
          (entry) => entry.customer
        ),
        datasets: [
          {
            data: response.data.lastThreeWeeksTotalPrice.map(
              (entry) => entry.total
            ),
          },
        ],
      };
    }
    if (response.data.lastThreeWeeksQuantity.length > 0) {
      pieChartlastThreeWeeksQuantity.value = {
        labels: response.data.lastThreeWeeksQuantity.map(
          (entry) => entry.customer
        ),
        datasets: [
          {
            data: response.data.lastThreeWeeksQuantity.map(
              (entry) => entry.total
            ),
          },
        ],
      };
    }

    if (response.data.lastTwoWeeksTotalPrice.length > 0) {
      pieChartlastTwoWeeksTotalPrice.value = {
        labels: response.data.lastTwoWeeksTotalPrice.map(
          (entry) => entry.customer
        ),
        datasets: [
          {
            data: response.data.lastTwoWeeksTotalPrice.map(
              (entry) => entry.total
            ),
          },
        ],
      };
    }

    if (response.data.lastTwoWeeksQuantity.length > 0) {
      pieChartlastTwoWeeksQuantity.value = {
        labels: response.data.lastTwoWeeksQuantity.map(
          (entry) => entry.customer
        ),
        datasets: [
          {
            data: response.data.lastTwoWeeksQuantity.map(
              (entry) => entry.total
            ),
          },
        ],
      };
    }

    if (response.data.lastWeekTotalPrice.length > 0) {
      pieChartlastWeekTotalPrice.value = {
        labels: response.data.lastWeekTotalPrice.map((entry) => entry.customer),
        datasets: [
          {
            data: response.data.lastWeekTotalPrice.map((entry) => entry.total),
          },
        ],
      };
    }
    if (response.data.lastWeekQuantity.length > 0) {
      pieChartlastWeekQuantity.value = {
        labels: response.data.lastWeekQuantity.map((entry) => entry.customer),
        datasets: [
          {
            data: response.data.lastWeekQuantity.map((entry) => entry.total),
          },
        ],
      };
    }
    if (response.data.currentWeekTotalPrice.length > 0) {
      pieChartcurrentWeekTotalPrice.value = {
        labels: response.data.currentWeekTotalPrice.map(
          (entry) => entry.customer
        ),
        datasets: [
          {
            data: response.data.currentWeekTotalPrice.map(
              (entry) => entry.total
            ),
          },
        ],
      };
    }
    if (response.data.currentWeekQuantity.length > 0) {
      pieChartcurrentWeekQuantity.value = {
        labels: response.data.currentWeekQuantity.map(
          (entry) => entry.customer
        ),
        datasets: [
          {
            data: response.data.currentWeekQuantity.map(
              (entry) => entry.total
            ),
          },
        ],
      };
    }
    if (response.data.lastMonthTotalPrice.length > 0) {
      pieChartlastMonthTotalPrice.value = {
        labels: response.data.lastMonthTotalPrice.map(
          (entry) => entry.customer
        ),
        datasets: [
          {
            data: response.data.lastMonthTotalPrice.map((entry) => entry.total),
          },
        ],
      };
    }
    if (response.data.lastMonthQuantity.length > 0) {
      pieChartlastMonthQuantity.value = {
        labels: response.data.lastMonthQuantity.map((entry) => entry.customer),
        datasets: [
          {
            data: response.data.lastMonthQuantity.map((entry) => entry.total),
          },
        ],
      };
    }
  } catch (error) {
    console.error("Error fetching data:", error);
  }
});
</script>
<style scoped>
div {
  gap: 1.5rem;
  flex-wrap: nowrap;
}
</style>
