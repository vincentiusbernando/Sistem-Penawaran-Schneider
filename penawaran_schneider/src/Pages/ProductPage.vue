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
      <form :action="formAction" method="GET" @submit.prevent="submitForm">
        <div class="flex justify-between items-center">
          <div>
            <h1 class="text-3xl py-2 font-bold">Search by</h1>
            <div class="flex items-center">
              <label>
                <input
                  type="radio"
                  v-model="searchOption"
                  value="ref"
                  name="searchOption"
                  class="ml-2"
                />
                <span>Ref</span>
              </label>
              <label>
                <input
                  type="radio"
                  v-model="searchOption"
                  value="desc"
                  name="searchOption"
                  class="ml-2"
                />
                <span>Description</span>
              </label>
            </div>
          </div>
          <div class="flex">
            <router-link to="/product_baru">
              <button
                class="bg-green-600 text-white font-bold px-4 py-2 rounded shadow-md"
                router
              >
                Tambah Produk
              </button>
            </router-link>
            <router-link to="/update_stock">
              <button
                class="ml-2 bg-green-600 text-white font-bold px-4 py-2 rounded shadow-md"
                router
              >
                Update Stock
              </button>
            </router-link>
          </div>
        </div>
        <div class="flex w-full mt-4">
          <input
            type="text"
            placeholder="Search Product..."
            v-model="searchQuery"
            class="px-4 py-2 w-full border border-gray-200 rounded-md"
          />
          <a href="/">
            <button
              class="bg-green-600 text-white font-bold px-4 py-2 rounded shadow-md"
            >
              Search
            </button>
          </a>
        </div>
      </form>
      <br />
      <table class="min-w-full rounded-lg overflow-hidden bg-white shadow-md">
        <thead>
          <tr class="bg-green-600 text-white">
            <th style="width: 2%" class="px-4 py-2">ID</th>
            <th style="width: 5%" class="px-4 py-2">Ref</th>
            <th style="width: 10%" class="px-4 py-2">Description</th>
            <th style="width: 4%" class="px-4 py-2">Price</th>
            <th style="width: 3%" class="px-4 py-2">BU</th>
            <th style="width: 4%" class="px-4 py-2">Activity</th>
            <th style="width: 3%" class="px-4 py-2">MPG</th>
            <th style="width: 3%" class="px-4 py-2">Type</th>
            <th style="width: 4%" class="px-4 py-2">Sub-type</th>
            <th style="width: 3%" class="px-4 py-2">Time</th>
            <th style="width: 5%" class="px-4 py-2">Activity Detail</th>
            <th style="width: 4%" class="px-4 py-2">Stock</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="row in data"
            :key="row.id"
            class="border-gray-200 hover:bg-gray-100"
          >
            <td class="px-4 py-2" data-label="ID">{{ row.id }}</td>
            <td class="px-4 py-2" data-label="Ref">
              {{ row.ref }}
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
import { useRoute } from "vue-router";
import AuthService from "@/AuthService";
const data = ref([]);
const route = useRoute();
const searchOption = ref("ref");
const searchQuery = ref("");
onMounted(async () => {
  try {
    var response;
    if (route.params.by && route.params.query) {
      response = await AuthService.searchProduct(
        route.params.by,
        route.params.query
      );
      searchOption.value=route.params.by;
      searchQuery.value=route.params.query;
    } else {
      response = await AuthService.product();
    }
    data.value = response.data;
    console.log(data.value);
  } catch (error) {
    console.error("Error fetching data:", error);
  }
});

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
