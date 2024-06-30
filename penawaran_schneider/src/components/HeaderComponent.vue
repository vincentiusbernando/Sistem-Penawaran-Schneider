<template>
  <nav class="navbar">
    <i class="bx bx-menu" id="sidebarOpen"></i>
    <!-- <div>
      <img src="../assets/logo.png" alt="Logo" style="width: 8rem" />
    </div> -->

    <div class="navbar_content">
      <h1 class="text-white">{{ nama }}</h1> <!-- Bind to the nama data property -->
    </div>
  </nav>
</template>

<script>
export default {
  name: "HeaderComponent",
  data() {
    return {
      nama: localStorage.getItem("nama") || "Default Name", // Access localStorage in data
    };
  },
};
</script>

<style>
/* Your component styles */
</style>
