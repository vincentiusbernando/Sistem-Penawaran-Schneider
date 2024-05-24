<template>
  <nav class="sidebar close hoverable shadow">
    <div class="menu_content">
      <ul class="menu_items">
        <!-- duplicate or remove this li tag if you want to add or remove navlink with submenu -->
        <!-- start -->
        <a href="/internal" class="nav_link submenu_item">
          <span class="navlink_icon">
            <i class="bx bx-home-alt"></i>
          </span>
          <span class="navlink">Internal</span>
        </a>
        <!-- end -->

        <!-- duplicate this li tag if you want to add or remove  navlink with submenu -->
        <!-- start -->
        <a href="/dashboard" class="nav_link submenu_item">
          <span class="navlink_icon">
            <i class="bx bxs-dashboard"></i>
          </span>
          <span class="navlink">Dashboard</span>
        </a>
        <!-- end -->
      </ul>

      <ul class="menu_items">
        <li class="item">
          <a href="/product" class="nav_link">
            <span class="navlink_icon">
              <i class="bx bxl-product-hunt"></i>
            </span>
            <span class="navlink">Product</span>
          </a>
        </li>
        <!-- End -->

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
  mounted() {
    Drawer.init();
  },
  methods: {
    logout() {
      localStorage.removeItem("sessionToken");
      localStorage.removeItem("role");
      window.location.href = "/";
    },
  },
};
</script>
