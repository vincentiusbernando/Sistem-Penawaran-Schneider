<style scoped>
table {
  font-size: 100%;
}
</style>
<template>
  <HeaderComponent></HeaderComponent>
  <DrawerComponent></DrawerComponent>
  <div class="page">
    <div class="px-4 py-2">
      <div class="py-2 justify-between flex">
        <div>
          <a href="/penawaran_baru" v-if="!isSpv">
            <button
              class="bg-green-600 text-white font-bold px-4 py-2 rounded shadow-md"
            >
              Buat Penawaran
            </button>
          </a>
          <a href="/customer_baru" v-if="!isSpv">
            <button
              class="bg-green-600 text-white font-bold px-4 py-2 rounded shadow-md ml-2"
            >
              Tambah Customer
            </button>
          </a>
        </div>
        <button
          class="bg-green-600 text-white font-bold px-4 py-2 rounded items-center flex shadow-md"
          @click="Refresh"
        >
          <i class="bx bx-refresh bigger_icon"></i>
          <p class="ml-2">Refresh</p>
        </button>
      </div>
      <h1 class="text-3xl py-2 font-bold">Search by</h1>
      <div class="flex items-center">
        <label>
          <input
            type="radio"
            v-model="searchOption"
            value="pembuat"
            name="searchOption"
          />
          <span>Pembuat</span>
        </label>
        <label>
          <input
            type="radio"
            v-model="searchOption"
            value="perusahaan"
            name="searchOption"
            class="ml-2"
          />
          <span>Perusahaan</span>
        </label>
      </div>
      <div class="flex w-full mt-4">
        <input
          type="text"
          placeholder="Search..."
          v-model="searchQuery"
          class="px-4 py-2 w-full border border-gray-200 rounded-md"
        />
        <button
          class="bg-green-600 text-white font-bold px-4 py-2 rounded shadow-md"
          @click="fetchData"
        >
          Search
        </button>
      </div>
      <div v-if="data.length>0">

        <table
          class="min-w-full rounded-lg overflow-hidden mt-4 bg-white shadow-md"
        >
          <thead>
            <tr class="bg-green-600 text-white">
              <th style="width: 5%" class="px-4 py-2 text-left">Date</th>
              <th style="width: 2%" class="px-4 py-2 text-left">Team</th>
              <th style="width: 3%" class="px-4 py-2 text-left">Internal</th>
              <th style="width: 3%" class="px-4 py-2 text-left">Customer</th>
              <th style="width: 3%" class="px-4 py-2 text-left">Perusahaan</th>
              <th style="width: 2%" class="px-4 py-2 text-left">Detail</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="row in data"
              :key="row.id"
              class="border-t border-gray-200 hover:bg-gray-200"
            >
              <td class="px-4 py-2" data-label="Date">{{ row.tgl }}</td>
              <td class="px-4 py-2" data-label="Team">{{ row.Team }}</td>
              <td class="px-4 py-2" data-label="Internal">{{ row.Internal }}</td>
              <td class="px-4 py-2" data-label="Customer">{{ row.Customer }}</td>
              <td class="px-4 py-2" data-label="Perusahaan">
                {{ row.Perusahaan }}
              </td>
              <td class="px-4 py-2 text-green-600 font-bold" data-label="Detail">
                <a :href="'/internal/' + row.uri">Detail</a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div v-else>
        <h1 class="text-center mt-4">Data tidak ditemukan</h1>
      </div>
    </div>
  </div>
</template>

<script setup>
import HeaderComponent from "../components/HeaderComponent";
import DrawerComponent from "../components/DrawerComponent";
import { ref } from "vue";
import AuthService from "@/AuthService";
import { useToast } from "vue-toast-notification";
import "vue-toast-notification/dist/theme-sugar.css";

var isSpv = localStorage.getItem("akses") == "spv";
const $toast = useToast();

// Define a reactive variable for data
const data = ref([]);
var searchOption = ref("pembuat");
var searchQuery = ref("");
const fetchData = async () => {
  try {
    var response;
    if (searchQuery.value === "") {
      response = await AuthService.internal();
    } else {
      response = await AuthService.searchPenawaran(
        searchOption.value,
        searchQuery.value
      );
    }
    data.value = response.data;
  } catch (error) {
    console.error("Error fetching data:", error);
  }
};

const Refresh = () => {
  fetchData();
  $toast.success("Refreshed", { position: "top" });
};
fetchData();
</script>
