<template>
  <div>
    <HeaderComponent></HeaderComponent>
    <DrawerComponent></DrawerComponent>
    <div class="page text-center">
      <h1 class="py-4 text-3xl">Total Nilai Penawaran</h1>
      <div class="flex justify-center">
        <div
          class="rounded-lg border shadow border-gray-300 py-4 bg-white items-center"
        >
          <h1 class="text-center text-3xl">Per Minggu</h1>
          <pie-chart :chartData="pieChartTotalPerMinggu" />
        </div>
        <div
          class="rounded-lg border shadow border-gray-300 py-4 bg-white items-center"
        >
          <h1 class="text-center text-3xl">Per 4 Minggu</h1>
          <pie-chart :chartData="pieChartTotalPer4Minggu" />
        </div>
      </div>
      <h1 class="py-4 text-3xl">Quantity Penawaran</h1>
      <div class="flex justify-center">
        <div
          class="rounded-lg border shadow border-gray-300 py-4 bg-white items-center"
        >
          <h1 class="text-center text-3xl">Per Minggu</h1>
          <pie-chart :chartData="pieChartQtyPerMinggu" />
        </div>
        <div
          class="rounded-lg border shadow border-gray-300 py-4 bg-white items-center"
        >
          <h1 class="text-center text-3xl">Per 4 Minggu</h1>
          <pie-chart :chartData="pieChartQtyPer4Minggu" />
        </div>
      </div>
      <div class="px-4 mt-8">
        <div class="shadow rounded-lg border bg-white">
          <h1 class="text-3xl py-2 mt-6">Summary Internal</h1>
          <div class="py-2 px-4 flex justify-center" style="align-items: start">
            <div class="flex" style="flex-direction: column">
              <div
                v-for="(internalSelect, index) in internalSelects"
                :key="index"
              >
                <div class="flex items-center py-2 px-4">
                  <h1>User Internal</h1>
                  <select
                    :id="'internals-select-' + index"
                    v-model="internalSelect.selectedInternal"
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
                  <button
                    @click="removeSelect(index)"
                    class="delete text-white rounded px-4 py-2"
                  >
                    x
                  </button>
                </div>
              </div>

              <div class="flex items-center py-2 px-4 justify-end">
                <button
                  @click="addSelect"
                  class="bg-green-600 text-white font-bold px-3 py-2 rounded shadow-md"
                >
                  Tambah
                </button>
              </div>
            </div>
            <div class="flex items-center py-2 px-4">
              <h1>Qty List Appear Per User</h1>
              <input
                type="number"
                class="px-4 py-2 shadow rounded"
                min="1"
                value="1"
                v-model="qtyAppear"
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
          <div class="px-4 py-2" v-if="summaryLength > 0">
            <table
              class="min-w-full rounded-lg overflow-hidden mt-4 bg-white shadow-md text-sm "
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
                  v-for="row in summary"
                  :key="row.uri"
                  class="border-t border-gray-200 hover:bg-gray-200"
                >
                  <td class="px-4 py-2 text-left">{{ row.Internal }}</td>

                  <td
                    class="px-4 py-2 text-green-600 font-bold"
                    data-label="Detail"
                  >
                    <a :href="'/internal/' + row.uri" target="_blank">Detail</a>
                  </td>
                  <td class="px-4 py-2 text-left">{{ row.tgl }}</td>
                  <td class="px-4 py-2 text-left">{{ row.Perusahaan }}</td>
                  <td class="px-4 py-2 text-left">{{ row.Total }}</td>
                </tr>
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
import { onMounted, ref, computed } from "vue";
// import { v4 as uuidv4 } from "uuid";
import AuthService from "@/AuthService";

let dashboard = ref([]);
let pieChartTotalPerMinggu = ref({
  labels: [],
  datasets: [
    {
      data: [],
    },
  ],
});
let pieChartTotalPer4Minggu = ref({
  labels: [],
  datasets: [
    {
      data: [],
    },
  ],
});
let pieChartQtyPerMinggu = ref({
  labels: [],
  datasets: [
    {
      data: [],
    },
  ],
});
let pieChartQtyPer4Minggu = ref({
  labels: [],
  datasets: [
    {
      data: [],
    },
  ],
});
const internals = ref([]);
const internalSelects = ref([{ selectedInternal: null }]);
const today = new Date().toISOString().split("T")[0];
const dateFrom = ref(today);
const dateTo = ref(today);
let summary = ref([]);
let summaryLength = computed(() => summary.value.length);
let qtyAppear = 1;
async function fetchSummary() {
  try {
    const formData = new FormData();
    internalSelects.value.forEach((internalSelect) => {
      formData.append(`internalSelects[]`, internalSelect.selectedInternal);
    });
    formData.append("qtyListPerUser", qtyAppear);
    formData.append("dateFrom", dateFrom.value);
    formData.append("dateTo", dateTo.value);
    const response = await AuthService.summary(formData);
    summary.value = response.data;
    console.log(summary);
  } catch (error) {
    console.error("Error:", error);
  }
}
function addSelect() {
  internalSelects.value.push({ selectedInternal: null });
}

// Function to remove a select element
function removeSelect(index) {
  internalSelects.value.splice(index, 1);
}
onMounted(async () => {
  try {
    const response = await AuthService.dashboard();
    dashboard.value = response.data;
    internals.value = response.data.internals;
    if (response.data.totalPerMinggu.length > 0) {
      pieChartTotalPerMinggu.value = {
        labels: response.data.totalPerMinggu.map((entry) => entry.customer),
        datasets: [
          {
            data: response.data.totalPerMinggu.map((entry) => entry.total),
          },
        ],
      };
    }
    if (response.data.totalPer4Minggu.length > 0) {
      pieChartTotalPer4Minggu.value = {
        labels: response.data.totalPer4Minggu.map((entry) => entry.customer),
        datasets: [
          {
            data: response.data.totalPer4Minggu.map((entry) => entry.total),
          },
        ],
      };
    }

    if (response.data.qtyPerMinggu.length > 0) {
      pieChartQtyPerMinggu.value = {
        labels: response.data.qtyPerMinggu.map((entry) => entry.customer),
        datasets: [
          {
            data: response.data.qtyPerMinggu.map((entry) => entry.total),
          },
        ],
      };
    }
    if (response.data.qtyPer4Minggu.length > 0) {
      pieChartQtyPer4Minggu.value = {
        labels: response.data.qtyPer4Minggu.map((entry) => entry.customer),
        datasets: [
          {
            data: response.data.qtyPer4Minggu.map((entry) => entry.total),
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
