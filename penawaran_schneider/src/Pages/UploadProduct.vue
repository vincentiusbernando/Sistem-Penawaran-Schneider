<template>
  <HeaderComponent />
  <DrawerComponent />

  <div class="page">
    <div class="px-4">
      <h1 class="text-3xl py-2 font-bold">Upload Product</h1>
      <table
        class="min-w-full rounded-lg overflow-hidden mt-4 bg-white shadow-md"
      >
        <thead>
          <tr class="bg-green-600 text-white">
            <th class="px-4 py-2 text-left">Material Reference</th>
            <th class="px-4 py-2 text-left">Description</th>
            <th class="px-4 py-2 text-left">Price</th>
            <th class="px-4 py-2 text-left">BU</th>
            <th class="px-4 py-2 text-left">Activity</th>
            <th class="px-4 py-2 text-left">MPG</th>
            <th class="px-4 py-2 text-left">Type</th>
            <th class="px-4 py-2 text-left">Sub-Type</th>
            <th class="px-4 py-2 text-left">TimeStamp</th>
            <th class="px-4 py-2 text-left">ActivityDetail</th>
          </tr>
        </thead>
        <tbody>
          <tr class="border-t border-gray-200 hover:bg-gray-200">
            <td class="px-4 py-2">...</td>
            <td class="px-4 py-2">...</td>
            <td class="px-4 py-2">...</td>
            <td class="px-4 py-2">...</td>
            <td class="px-4 py-2">...</td>
            <td class="px-4 py-2">...</td>
            <td class="px-4 py-2">...</td>
            <td class="px-4 py-2">...</td>
            <td class="px-4 py-2">...</td>
            <td class="px-4 py-2">...</td>
          </tr>
        </tbody>
      </table>
      <form @submit.prevent="submitForm">
        <div class="mb-4">
          <input
            type="file"
            id="file"
            class="shadow appearance-none border rounded w-full py-2 px-3 mt-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            @change="handleFileChange"
          />
        </div>

        <div class="flex justify-end py-8">
          <button
            type="submit"
            class="bg-green-600 text-white font-bold px-4 py-2 rounded shadow-md"
          >
            Upload
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import HeaderComponent from "@/components/HeaderComponent.vue";
import DrawerComponent from "@/components/DrawerComponent.vue";
import { ref } from "vue";
import AuthService from "@/AuthService";

import { useToast } from "vue-toast-notification";
const $toast = useToast();
if (localStorage.getItem("akses") != "admin") {
  window.location.href = `/product`;
}
const selectedFile = ref(null);

const handleFileChange = (event) => {
  selectedFile.value = event.target.files[0];
};

const submitForm = async () => {
  if (!selectedFile.value) {
    alert("Please select a file first.");
    return;
  }

  const formData = new FormData();
  formData.append("file", selectedFile.value);

  try {
    const response = await AuthService.uploadProduct(formData);
    if (response.data.result === "success") {
      window.history.back();
    } else {
      $toast.error(response.data.message, { position: "top" });
    }
  } catch (error) {
    console.error("Error uploading file:", error);
  }
};
</script>
