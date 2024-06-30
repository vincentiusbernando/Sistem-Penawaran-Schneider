<template>
  <nav class="sidebar close hoverable shadow">
    <div class="menu_content">
      <ul class="menu_items">
        <!-- Internal Link -->
        <li class="item">
          <a href="/internal" class="nav_link submenu_item">
            <span class="navlink_icon">
              <i class="bx bx-home-alt"></i>
            </span>
            <span class="navlink">Internal</span>
          </a>
        </li>

        <!-- Dashboard Link (conditionally rendered for admin role) -->
        <li class="item" v-if="isAdmin">
          <a href="/dashboard" class="nav_link submenu_item">
            <span class="navlink_icon">
              <i class="bx bxs-dashboard"></i>
            </span>
            <span class="navlink">Dashboard</span>
          </a>
        </li>
      </ul>

      <!-- Other Menu Items -->
      <ul class="menu_items">
        <li class="item">
          <a href="/product" class="nav_link">
            <span class="navlink_icon">
              <i class="bx bxl-product-hunt"></i>
            </span>
            <span class="navlink">Product</span>
          </a>
        </li>
        <li class="item">
          <a href="/sd" class="nav_link">
            <span class="navlink_icon">
              <i class="bx bxs-discount"></i>
            </span>
            <span class="navlink">Standard Discount</span>
          </a>
        </li>
        <li class="item">
          <a href="#" class="nav_link">
            <span class="navlink_icon">
              <i class="bx bx-cog"></i>
            </span>
            <span class="navlink">Setting</span>
          </a>
        </li>
        <li class="item">
          <a href="#" class="nav_link" @click="logout">
            <span class="navlink_icon">
              <i class="bx bx-log-out"></i>
            </span>
            <span class="navlink">Log Out</span>
          </a>
        </li>
      </ul>

      <!-- Sidebar Open / Close -->
      <div class="expand_sidebar"></div>
      <div class="collapse_sidebar"></div>
    </div>
  </nav>
</template>

<script>
import Drawer from "./Drawer";

export default {
  name: "DrawerComponent",
  data() {
    return {
      isAdmin: localStorage.getItem("akses") == "admin",
    };
  },
  mounted() {
    Drawer.init();
  },
  methods: {
    logout() {
      localStorage.removeItem("sessionToken");
      localStorage.removeItem("nama");
      localStorage.removeItem("role");
      localStorage.removeItem("akses");
      this.$router.push("/"); // Use Vue Router for navigation
    },
  },
};
</script>
