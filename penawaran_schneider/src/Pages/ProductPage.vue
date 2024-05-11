<template>
  <HeaderComponent></HeaderComponent>
  <DrawerComponent></DrawerComponent>
  <div class="page">
    <div class="px-4 py-2">
      <form :action="formAction" method="GET" @submit.prevent="submitForm">
        <h1 class="text-3xl py-2">Search by</h1>
        <label>
          <input
            type="radio"
            v-model="searchOption"
            value="ref"
            name="searchOption"
          />
          Ref
        </label>
        <label>
          <input
            type="radio"
            v-model="searchOption"
            value="desc"
            name="searchOption"
          />
          Description
        </label>
        <br />
        <input
          type="text"
          placeholder="Search Product..."
          v-model="searchQuery"
          class="px-4 py-2"
          style="margin-top: 0.8rem; width: 30rem; margin-right: 0.5rem"
        />
        <input type="submit" value="Search" class="btn py-2 px-4" />
      </form>
      <br />
      <table>
        <thead>
          <tr class="bg-gray-200">
            <th class="px-4 py-2">ID</th>
            <th class="px-4 py-2">Ref</th>
            <th class="px-4 py-2">Material</th>
            <th class="px-4 py-2">Description</th>
            <th class="px-4 py-2">Price</th>
            <th class="px-4 py-2">BU</th>
            <th class="px-4 py-2">Activity</th>
            <th class="px-4 py-2">MPG</th>
            <th class="px-4 py-2">Type</th>
            <th class="px-4 py-2">Sub-type</th>
            <th class="px-4 py-2">Time</th>
            <th class="px-4 py-2">Activity Detail</th>
            <th class="px-4 py-2">Stock</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="row in data" :key="row.id" class="border-gray-200">
            <td class="px-4 py-2" data-label="ID">{{ row.id }}</td>
            <td class="px-4 py-2" data-label="Ref">
              {{ row.ref }}
            </td>
            <td class="px-4 py-2" data-label="Material">
              {{ row.material }}
            </td>
            <td class="px-4 py-2" data-label="Description">
              {{ row.description }}
            </td>
            <td class="px-4 py-2" data-label="Price">
              {{ row.price }}
            </td>
            <td class="px-4 py-2" data-label="BU">{{ row.bu }}</td>
            <td class="px-4 py-2" data-label="Activity">
              {{ row.activity }}
            </td>
            <td class="px-4 py-2" data-label="MPG">
              {{ row.standard_discounts_mpg }}
            </td>
            <td class="px-4 py-2" data-label="Type">
              {{ row.type }}
            </td>
            <td class="px-4 py-2" data-label="Sub-type">
              {{ row.sub_type }}
            </td>
            <td class="px-4 py-2" data-label="Time">
              {{ row.time }}
            </td>
            <td class="px-4 py-2" data-label="Activity Detail">
              {{ row.activity_detail }}
            </td>
            <td class="px-4 py-2" data-label="Stock">
              {{ row.stock }}
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
const data = ref([]);

onMounted(async () => {
  try {
    const response = await AuthService.product(); // Add await here
    data.value = response.data;
    console.log(data.value); // Make sure data is fetched, use data.value
  } catch (error) {
    console.error("Error fetching data:", error);
  }
});
const searchOption = ref("ref");
const searchQuery = ref("");

const submitForm = () => {
  const radioButtonSelect = searchOption.value;
  const query = searchQuery.value;
  // Construct the form action path
  if (query.length == 0) {
    window.location.href = `/product`;
  } else {
    window.location.href = `/product/${radioButtonSelect}/${query}`;
  }
};

// Computed property to construct the form action path
const formAction = () => {
  const radioButtonSelect = searchOption.value;
  return `/product/${radioButtonSelect}/`;
};
</script>
