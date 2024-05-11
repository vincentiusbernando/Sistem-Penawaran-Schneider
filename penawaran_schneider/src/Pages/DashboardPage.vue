<template>
  <HeaderComponent></HeaderComponent>
  <DrawerComponent></DrawerComponent>
  <div class="page">
    <div class=""></div>
  </div>
</template>
<script>
import HeaderComponent from "../components/HeaderComponent";
import DrawerComponent from "../components/DrawerComponent";
export default {
  name: "DashboardPage",
  components: {
    HeaderComponent,
    DrawerComponent,
  },
};
</script>
