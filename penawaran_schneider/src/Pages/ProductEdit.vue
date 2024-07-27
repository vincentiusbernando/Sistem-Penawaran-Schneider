<template>
  <HeaderComponent />
  <DrawerComponent />

  <div class="page">
    <div class="px-4">
      <div class="flex justify-between items-center">
        <h1 class="text-3xl py-2 font-bold">Edit Produk</h1>
      </div>
      <form @submit.prevent="submitForm">
        <div class="mb-4">
          <label for="ref" class="block text-gray-700">Reference</label>
          <input
            type="text"
            id="ref"
            v-model="product.ref"
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
            v-model="product.description"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          />
        </div>
        <div class="mb-4">
          <label for="price" class="block text-gray-700">Price</label>
          <input
            type="number"
            id="price"
            v-model="product.price"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            step="100"
          />
        </div>
        <div class="mb-4">
          <label for="bu" class="block text-gray-700">BU</label>
          <input
            type="text"
            id="bu"
            v-model="product.bu"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          />
        </div>
        <div class="mb-4">
          <label for="activity" class="block text-gray-700">Activity</label>
          <input
            type="text"
            id="activity"
            v-model="product.activity"
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
            v-model="product.standard_discounts_mpg"
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
            v-model="product.type"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          />
        </div>
        <div class="mb-4">
          <label for="sub_type" class="block text-gray-700">Sub Type</label>
          <input
            type="text"
            id="sub_type"
            v-model="product.sub_type"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          />
        </div>
        <div class="mb-4">
          <label for="time" class="block text-gray-700">Time</label>
          <input
            type="text"
            id="time"
            v-model="product.time"
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
            v-model="product.activity_detail"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          />
        </div>
        <div class="mb-4">
          <label for="stock" class="block text-gray-700">Stock</label>
          <input
            type="number"
            id="stock"
            v-model="product.stock"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          />
        </div>
        <div class="flex justify-end py-8">
          <button
            type="submit"
            class="bg-green-600 text-white font-bold px-4 py-2 rounded shadow-md"
          >
            Edit Produk
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
import { useRoute } from "vue-router";
if (localStorage.getItem("akses") != "admin") {
  window.location.href = `/product`;
}

const $toast = useToast();
const route = useRoute();
let sd = ref([]);
let product = ref({});
onMounted(async () => {
  try {
    const response = await AuthService.standardDiscount();
    sd.value = response.data;
    const response2 = await AuthService.product_detail(route.params.id);
    product.value = response2.data;
    console.log(product.value);
  } catch (error) {
    console.error("Error fetching data:", error);
  }
});

const submitForm = () => {
  const formData = {
    id: route.params.id,
    ref: product.value.ref,
    description: product.value.description,
    price: product.value.price,
    bu: product.value.bu,
    activity: product.value.activity,
    standard_discounts_mpg: product.value.standard_discounts_mpg,
    type: product.value.type,
    sub_type: product.value.sub_type,
    time: product.value.time,
    activity_detail: product.value.activity_detail,
    stock: product.value.stock,
  };

  editProduct(formData);
};

const editProduct = async (formData) => {
  try {
    const response = await AuthService.editProduct(formData);
    const { result, message } = response.data;
    if (result === "success") {
      window.history.back();
    } else {
      $toast.error(message, { position: "top" });
    }
  } catch (error) {
    console.error("Error edit product:", error);
  }
};
</script>
