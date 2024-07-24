<template>
  <HeaderComponent />
  <DrawerComponent />

  <div class="page">
    <div class="px-4">
      <div class="flex justify-between items-center">
        <h1 class="text-3xl py-2 font-bold">Tambah Produk</h1>
        <router-link to="/upload_product_baru">
          <button
            class="bg-green-600 text-white font-bold px-4 py-2 rounded shadow-md"
            router
          >
            Upload Produk
          </button>
        </router-link>
      </div>
      <form @submit.prevent="submitForm">
        <div class="mb-4">
          <label for="ref" class="block text-gray-700">Reference</label>
          <input
            type="text"
            id="ref"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          />
        </div>
        <div class="mb-4">
          <label for="description" class="block text-gray-700"
            >Description</label
          >
          <input
            type="text"
            id="description"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          />
        </div>
        <div class="mb-4">
          <label for="price" class="block text-gray-700">Price</label>
          <input
            type="number"
            id="price"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            step="100"
          />
        </div>
        <div class="mb-4">
          <label for="bu" class="block text-gray-700">BU</label>
          <input
            type="text"
            id="bu"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          />
        </div>
        <div class="mb-4">
          <label for="activity" class="block text-gray-700">Activity</label>
          <input
            type="text"
            id="activity"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          />
        </div>
        <div class="mb-4">
          <label for="standard_discounts_mpg" class="block text-gray-700"
            >Standard Discounts MPG</label
          >
          <select
            id="standard_discounts_mpg"
            class="shadow rounded w-full py-2 px-3 text-gray-700 overflow-auto"
            size="1"
          >
            <option v-for="option in sd" :key="option.mpg" :value="option.mpg">
              {{ option.mpg }} - {{ option.basic_disc }}%
            </option>
          </select>
        </div>
        <div class="mb-4">
          <label for="type" class="block text-gray-700">Type</label>
          <input
            type="text"
            id="type"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          />
        </div>
        <div class="mb-4">
          <label for="sub_type" class="block text-gray-700">Sub Type</label>
          <input
            type="text"
            id="sub_type"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          />
        </div>
        <div class="mb-4">
          <label for="time" class="block text-gray-700">Time</label>
          <input
            type="text"
            id="time"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          />
        </div>
        <div class="mb-4">
          <label for="activity_detail" class="block text-gray-700"
            >Activity Detail</label
          >
          <input
            type="text"
            id="activity_detail"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          />
        </div>
        <div class="mb-4">
          <label for="stock" class="block text-gray-700">Stock</label>
          <input
            type="number"
            id="stock"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          />
        </div>
        <div class="flex justify-end py-8">
          <button
            type="submit"
            class="bg-green-600 text-white font-bold px-4 py-2 rounded shadow-md"
          >
            Tambah Produk
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
<script setup>
import AuthService from "@/AuthService";
import { ref, onMounted } from "vue";
import HeaderComponent from "@/components/HeaderComponent.vue";
import DrawerComponent from "@/components/DrawerComponent.vue";
import { useToast } from "vue-toast-notification";
import "vue-toast-notification/dist/theme-sugar.css";
if (localStorage.getItem("akses") != "admin"){
  window.location.href = `/product`;
}

const $toast = useToast();

let sd = ref([]);

onMounted(async () => {
  try {
    const response = await AuthService.standardDiscount();
    sd.value = response.data;
  } catch (error) {
    console.error("Error fetching data:", error);
  }
});

const submitForm = () => {
  const formData = {
    ref: document.getElementById("ref").value,
    description: document.getElementById("description").value,
    price: document.getElementById("price").value,
    bu: document.getElementById("bu").value,
    activity: document.getElementById("activity").value,
    standard_discounts_mpg: document.getElementById("standard_discounts_mpg")
      .value,
    type: document.getElementById("type").value,
    sub_type: document.getElementById("sub_type").value,
    time: document.getElementById("time").value,
    activity_detail: document.getElementById("activity_detail").value,
    stock: document.getElementById("stock").value,
  };

  submitProduct(formData);
};

const submitProduct = async (formData) => {
  try {
    const response = await AuthService.submitProduct(formData);
    const { result, message } = response.data;
    if (result === "success") {
      window.history.back();
    } else {
      $toast.error(message, { position: "top" });
    }
  } catch (error) {
    console.error("Error creating product:", error);
  }
};
</script>
