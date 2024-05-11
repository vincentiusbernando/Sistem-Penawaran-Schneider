<template>
  <HeaderComponent></HeaderComponent>
  <DrawerComponent></DrawerComponent>
  <div class="page">
    <div class="px-4 py-2">
      <table>
        <thead>
          <tr class="bg-gray-200">
            <th class="px-4 py-2">MPG</th>
            <th class="px-4 py-2">Activity</th>
            <th class="px-4 py-2">Description</th>
            <th class="px-4 py-2">Basic Discount</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="row in data" :key="row.id" class="border-gray-200">
            <td class="px-4 py-2" data-label="MPG">{{ row.mpg }}</td>
            <td class="px-4 py-2" data-label="Activity">{{ row.activity }}</td>
            <td class="px-4 py-2" data-label="Description">
              {{ row.description }}
            </td>
            <td class="px-4 py-2" data-label="Basic Discount">
              {{ row.basic_disc }}%
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import AuthService from "@/AuthService";

import HeaderComponent from "../components/HeaderComponent";
import DrawerComponent from "../components/DrawerComponent";
const data = ref([]);

onMounted(async () => {
  try {
    const response = await AuthService.standardDiscount(); // Add await here
    data.value = response.data;
    console.log(data.value); // Make sure data is fetched, use data.value
  } catch (error) {
    console.error("Error fetching data:", error);
  }
});
</script>
