<template>
  <HeaderComponent></HeaderComponent>
  <DrawerComponent></DrawerComponent>
  <div class="page">
    <div class="px-4 py-2">
      <div class="py-2 justify-between flex">
        <a href="/penawaran_baru">
          <button class="bg-green-600 text-white font-bold px-4 py-2 rounded">
            Buat Penawaran
          </button>
        </a>
        <button
          class="bg-green-600 text-white font-bold px-4 py-2 rounded items-center flex"
          @click="fetchData"
        >
          <i class="bx bx-refresh bigger_icon"></i>
          <p class="ml-2">Refresh</p>
        </button>
      </div>
      <table
        class="min-w-full rounded-lg overflow-hidden mt-4 bg-white shadow-md"
      >
        <thead>
          <tr class="bg-green-600 text-white">
            <th class="px-4 py-2 text-left">Date</th>
            <th class="px-4 py-2 text-left">Team</th>
            <th class="px-4 py-2 text-left">Internal</th>
            <th class="px-4 py-2 text-left">Customer</th>
            <th class="px-4 py-2 text-left">Perusahaan</th>
            <th class="px-4 py-2 text-left">Detail</th>
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
  </div>
</template>

<script setup>
import HeaderComponent from "../components/HeaderComponent";
import DrawerComponent from "../components/DrawerComponent";
import { ref } from "vue";
import AuthService from "@/AuthService";

// Define a reactive variable for data
const data = ref([]);

const fetchData = async () => {
  try {
    const response = await AuthService.internal();
    data.value = response.data;
  } catch (error) {
    console.error("Error fetching data:", error);
  }
};

fetchData();
</script>
