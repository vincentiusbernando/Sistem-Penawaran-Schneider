<template>
  <HeaderComponent></HeaderComponent>
  <DrawerComponent></DrawerComponent>
  <div class="page">
    <div class="px-4 py-2">
      <div class="text-white py-2">
        <a href="/penawaran_baru">
          <button class="btn px-4 py-2">Buat Penawaran</button>
        </a>
      </div>
      <table>
        <thead>
          <tr class="bg-gray-200">
            <th class="px-4 py-2">Date</th>
            <th class="px-4 py-2">Team</th>
            <th class="px-4 py-2">Internal</th>
            <th class="px-4 py-2">Customer</th>
            <th class="px-4 py-2">Perusahaan</th>
            <th class="px-4 py-2">Detail</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="row in data" :key="row.id" class="border-gray-200">
            <td class="px-4 py-2" data-label="Date">{{ row.tgl }}</td>
            <td class="px-4 py-2" data-label="Team">
              {{ row.Team }}
            </td>
            <td class="px-4 py-2" data-label="Internal">
              {{ row.Internal }}
            </td>
            <td class="px-4 py-2" data-label="Customer">
              {{ row.Customer }}
            </td>
            <td class="px-4 py-2" data-label="Perusahaan">
              {{ row.Perusahaan }}
            </td>
            <td class="px-4 py-2" data-label="Detail">
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
import { onMounted, ref } from "vue";
import AuthService from "@/AuthService";

// Define a reactive variable for data
const data = ref([]);

onMounted(async () => {
  try {
    const response = await AuthService.internal();
    data.value = response.data;
  } catch (error) {
    console.error("Error fetching data:", error);
  }
});
</script>
