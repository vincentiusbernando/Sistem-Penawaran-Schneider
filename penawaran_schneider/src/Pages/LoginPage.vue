<template>
  <div
    class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8"
  >
    <div class="max-w-md w-full space-y-8">
      <div>
        <img src="../assets/logo.png" alt="Logo" class="mx-auto h-12 w-auto" />
      </div>
      <div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
          Log in
        </h2>
      </div>
      <form class="mt-8 space-y-6" @submit.prevent="submitForm">
        <div class="error-container py-2" v-if="error">
          <h1 class="text-center error">Gagal Login</h1>
        </div>
        <div class="rounded-md shadow-sm -space-y-px">
          <div>
            <label for="username" class="sr-only">Username</label>
            <input
              id="username"
              name="username"
              type="text"
              autocomplete="username"
              required
              v-model="formData.username"
              class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-green-500 focus:border-green-500 focus:z-10 sm:text-sm"
              placeholder="Username"
            />
          </div>
          <div>
            <label for="password" class="sr-only">Password</label>
            <input
              id="password"
              name="password"
              type="password"
              autocomplete="current-password"
              required
              v-model="formData.password"
              class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-green-500 focus:border-green-500 focus:z-10 sm:text-sm"
              placeholder="Password"
            />
          </div>
          <div class="flex justify-center">
            <div class="px-4 py-2">
              <input
                type="radio"
                id="internal"
                name="role"
                value="internal"
                v-model="formData.role"
              />
              <label for="internal">Internal</label>
            </div>
            <div class="px-4 py-2">
              <input
                type="radio"
                id="customer"
                name="role"
                value="customer"
                v-model="formData.role"
              />
              <label for="customer">Customer</label>
            </div>
          </div>
        </div>
        <div>
          <button
            type="submit"
            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
          >
            Log in
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import AuthService from "@/AuthService";
export default {
  data() {
    return {
      formData: {
        username: "",
        password: "",
        role: "internal",
      },
      token: "",
      error: false,
    };
  },
  methods: {
    async submitForm() {
      try {
        var response;
        if (this.formData.role === "internal") {
          response = await AuthService.loginInternal(this.formData);
          console.log("tunggu")
          console.log(response)
          if (response.data.success) {
            localStorage.setItem("sessionToken", response.data.token);
            localStorage.setItem("role", "internal");
            window.location.href = response.data.redirectUrl;
          } else {
            this.error = true;
          }
        } else {
          response = await AuthService.loginCustomer(this.formData);
          if (response.data.success) {
            localStorage.setItem("sessionToken", response.data.token);
            localStorage.setItem("role", "customer");
            window.location.href = response.data.redirectUrl;
          } else {
            this.error = true;
          }
        }
      } catch (error) {
        console.error("Error logging in:", error);
      }
    },
  },
};
</script>
