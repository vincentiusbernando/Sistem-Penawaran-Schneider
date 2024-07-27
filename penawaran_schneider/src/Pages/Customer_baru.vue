<template>
  <HeaderComponent />
  <DrawerComponent />

  <div class="page">
    <div class="px-4">
      <div class="py-2 justify-between flex items-center">
        <h1 class="text-3xl py-2 font-bold">Tambah Customer</h1>
        <div class="flex">
          <a href="/upload_customer_baru">
            <button
              type="submit"
              class="bg-green-600 text-white font-bold px-4 py-2 rounded shadow-md"
            >
              Upload Customer
            </button>
          </a>
        </div>
      </div>
      <form @submit.prevent="submitFormCustomer">
        <div class="mb-4">
          <label for="nama_user" class="block text-gray-700">Nama</label>
          <input
            type="text"
            id="nama_user"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          />
        </div>
        <div class="mb-4">
          <label for="email" class="block text-gray-700">Email</label>
          <input
            type="text"
            id="email"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          />
        </div>
        <div class="mb-4">
          <label for="handphone" class="block text-gray-700">Handphone</label>
          <input
            type="text"
            id="handphone"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          />
        </div>
        <!-- <div class="mb-4">
          <label for="password" class="block text-gray-700">Password</label>
          <input
            type="password"
            id="password"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          />
        </div>
        <div class="mb-4">
          <label for="confirm_password" class="block text-gray-700"
            >Confirm Password</label
          >
          <input
            type="password"
            id="confirm_password"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          />
        </div> -->
        <div class="mb-4">
          <label for="perusahaans_id" class="block text-gray-700"
            >Perusahaan</label
          >
          <div>
            <input
              id="searchInput"
              type="text"
              v-model="searchQuery"
              @input="search"
              placeholder="Search..."
              class="px-3 py-2 border border-gray-300 rounded w-full"
            />
            <div
              id="searchResults"
              v-if="showDropdown && filteredPerusahaan.length > 0 && searchQuery.length > 0"
              class="autocomplete-items bg-white border border-gray-300 rounded mt-2"
            >
              <div
                v-for="option in filteredPerusahaan"
                :key="option.id"
                class="autocomplete-item px-3 py-2 hover-text hover:bg-gray-200"
                @click="selectItem(option)"
              >
                {{ option.nama }}
              </div>
            </div>
          </div>
        </div>
        <div class="flex justify-end py-8">
          <button
            type="submit"
            class="bg-green-600 text-white font-bold px-4 py-2 rounded shadow-md"
          >
            Tambah Customer
          </button>
        </div>
      </form>
      <h1 class="text-3xl font-bold">Tambah Perusahaan</h1>
      <form @submit.prevent="submitFormPerusahaan">
        <div class="mb-4">
          <label for="nama_perusahaan" class="block text-gray-700">Nama</label>
          <input
            type="text"
            id="nama_perusahaan"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          />
        </div>
        <div class="mb-4">
          <label for="sd" class="block text-gray-700">Standard Discount</label>
          <input
            type="number"
            id="sd"
            max="100"
            min="0"
            value="0"
            step="0.1"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          />
        </div>
        <div class="mb-4">
          <label for="ad" class="block text-gray-700"
            >Additional Discount</label
          >
          <input
            type="number"
            id="ad"
            max="100"
            min="0"
            value="0"
            step="0.1"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          />
        </div>
        <div class="flex justify-end py-8">
          <button
            type="submit"
            class="bg-green-600 text-white font-bold px-4 py-2 rounded shadow-md"
          >
            Tambah Perusahaan
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
<script setup>
import AuthService from "@/AuthService";
import { ref, onMounted, computed } from "vue";
import HeaderComponent from "@/components/HeaderComponent.vue";
import DrawerComponent from "@/components/DrawerComponent.vue";
import { useToast } from "vue-toast-notification";
import "vue-toast-notification/dist/theme-sugar.css";
if (localStorage.getItem("akses") == "spv") {
  window.location.href = `/internal`;
}
const $toast = useToast();

let perusahaan = ref([]);

onMounted(async () => {
  try {
    const response = await AuthService.perusahaan();
    perusahaan.value = response.data;
  } catch (error) {
    console.error("Error fetching data:", error);
  }
});
const searchQuery = ref("");
const selectedPerusahaan = ref(null);
const showDropdown = ref(true);

const filteredPerusahaan = computed(() => {
  const query = searchQuery.value.toLowerCase();
  return perusahaan.value.filter((option) =>
    option.nama.toLowerCase().includes(query)
  );
});

const search = () => {
  showDropdown.value = true;
};

const selectItem = (option) => {
  selectedPerusahaan.value = option;
  searchQuery.value = option.nama;
  showDropdown.value = false;
};
const submitFormCustomer = () => {
  // let password = document.getElementById("password").value;
  // let confirm = document.getElementById("confirm_password").value;
  // if (password === confirm) {
  const formData = {
    nama: document.getElementById("nama_user").value,
    email: document.getElementById("email").value,
    handphone: document.getElementById("handphone").value,
    // password: document.getElementById("password").value,
    perusahaans_id: document.getElementById("perusahaans_id").value,
  };

  submitCustomer(formData);
  // } else {
  //   $toast.error("Konfirmasi Password Berbeda", { position: "top" });
  // }
};

const submitCustomer = async (formData) => {
  try {
    const response = await AuthService.submitCustomer(formData);
    const { result, message } = response.data;
    if (result === "success") {
      window.history.back();
    } else {
      $toast.error(message, { position: "top" });
    }
  } catch (error) {
    console.error("Error:", error);
  }
};

const submitFormPerusahaan = () => {
  const formData = {
    nama: document.getElementById("nama_perusahaan").value,
    sd: document.getElementById("sd").value,
    ad: document.getElementById("ad").value,
  };
  submitPerusahaan(formData);
};

const submitPerusahaan = async (formData) => {
  try {
    const response = await AuthService.submitPerusahaan(formData);
    const { result, message } = response.data;
    if (result === "success") {
      window.location.href = "/customer_baru";
    } else {
      $toast.error(message, { position: "top" });
    }
  } catch (error) {
    console.error("Error:", error);
  }
};
</script>
