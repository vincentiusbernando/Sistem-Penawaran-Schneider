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
      <div class="px-4 mt-8">
        <div class="shadow rounded-lg border bg-white">
          <h1 class="text-3xl py-2 mt-6">Summary Internal</h1>
          <div class="py-2 px-4 flex justify-center" style="align-items: start;">
            <div class="flex items-center py-2 px-4">
              <h1>User Internal</h1>
              <select
                id="internals-select"
                v-model="selectedInternal"
                class="px-4 py-2 shadow rounded"
              >
                <option
                  v-for="internal in internals"
                  :key="internal.id"
                  :value="internal.id"
                >
                  {{ internal.nama }}
                </option>
              </select>
            </div>
            <div class="flex items-center py-2 px-4">
              <h1>Qty List Appear Per User</h1>
              <input
                type="number"
                class="px-4 py-2 shadow rounded"
                min="1"
                value="1"
              />
            </div>
            <div class="text-left">
              <div class="flex justify-between py-2">
                <label for="date-from" class="py-2">From</label>
                <input
                  type="date"
                  id="date-from"
                  v-model="dateFrom"
                  class="py-2 px-4 shadow rounded"
                />
              </div>
              <div class="flex justify-between py-2">
                <label for="date-to" class="py-2">To</label>
                <input
                  type="date"
                  id="date-to"
                  v-model="dateTo"
                  class="py-2 px-4 shadow rounded"
                />
              </div>
            </div>
            <div class="text-white py-2 flex justify-end">
              <button
                class="bg-green-600 text-white font-bold px-3 py-2 rounded shadow-md"
                @click="fetchSummary"
              >
                Search
              </button>
            </div>
          </div>
          <div class="px-4 py-2">
            <table
              class="min-w-full rounded-lg overflow-hidden mt-4 bg-white shadow-md"
              v-if="summary"
            >
              <thead>
                <tr class="bg-green-600 text-white">
                  <th class="px-4 py-2 text-left">Name</th>
                  <th class="px-4 py-2 text-left">Link Offer</th>
                  <th class="px-4 py-2 text-left">Date Offer</th>
                  <th class="px-4 py-2 text-left">Company Name</th>
                  <th class="px-4 py-2 text-left">Total Offer</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="row in data"
                  :key="row.id"
                  class="border-t border-gray-200 hover:bg-gray-200"
                ></tr>
              </tbody>
            </table>
          </div>
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
const internals = ref([]);
const selectedInternal = ref(null);
const today = new Date().toISOString().split("T")[0];
const dateFrom = ref(today);
const dateTo = ref(today);
let summary = null;
async function fetchSummary() {
  try {
    const formData = new FormData();
    const response = await AuthService.summary(formData);
    summary = response.data.summary;
    console.log(response);
  } catch (error) {
    console.error("Error:", error);
  }
}
onMounted(async () => {
  try {
    const response = await AuthService.dashboard();
    dashboard.value = response.data;
    internals.value = response.data.internals;
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
            data: response.data.currentWeekQuantity.map((entry) => entry.total),
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
