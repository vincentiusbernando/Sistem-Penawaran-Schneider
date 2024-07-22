import { createRouter, createWebHistory } from "vue-router";

// Import your Vue components
import LoginPage from "./Pages/LoginPage.vue";
import RegisterPage from "./Pages/RegisterPage.vue";
import CustomerPage from "./Pages/CustomerPage.vue";
import DashboardPage from "./Pages/DashboardPage.vue";
import DetailPage from "./Pages/DetailPage.vue";
import InternalPage from "./Pages/InternalPage.vue";
import Penawaran_baru from "./Pages/Penawaran_baru.vue";
import ProductPage from "./Pages/ProductPage.vue";
import SDPage from "./Pages/SDPage.vue";
import Product_baru from "./Pages/Product_baru.vue";
import Customer_baru from "./Pages/Customer_baru.vue";
import UploadCustomer from "./Pages/UploadCustomer.vue";
import UploadProduct from "./Pages/UploadProduct.vue";
import UpdateStock from "./Pages/UpdateStock.vue";
// import axios from "axios";
// import { API_URL } from "./constants";
const routes = [
  { path: "/", component: LoginPage },
  { path: "/register", component: RegisterPage },
  {
    path: "/customer",
    component: CustomerPage,
    meta: { requiresCustomerAuth: true },
  },
  {
    path: "/dashboard",
    component: DashboardPage,
    meta: { requiresInternalAuth: true },
  },
  {
    path: "/internal",
    component: InternalPage,
    meta: { requiresInternalAuth: true },
  },
  {
    path: "/internal/:uri",
    component: DetailPage,
    meta: { requiresInternalAuth: true },
  },
  {
    path: "/penawaran_baru",
    component: Penawaran_baru,
    meta: { requiresInternalAuth: true },
  },
  {
    path: "/product",
    component: ProductPage,
    meta: { requiresInternalAuth: true },
  },
  {
    path: "/product/:by/:query",
    component: ProductPage,
    meta: { requiresInternalAuth: true },
  },
  {
    path: "/update_stock",
    component: UpdateStock,
    meta: { requiresInternalAuth: true },
  },
  {
    path: "/product_baru",
    component: Product_baru,
    meta: { requiresInternalAuth: true },
  },
  {
    path: "/upload_product_baru",
    component: UploadProduct,
    meta: { requiresInternalAuth: true },
  },
  {
    path: "/customer_baru",
    component: Customer_baru,
    meta: { requiresInternalAuth: true },
  },
  {
    path: "/upload_customer_baru",
    component: UploadCustomer,
    meta: { requiresInternalAuth: true },
  },
  { path: "/sd", component: SDPage, meta: { requiresInternalAuth: true } },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach(async (to, from, next) => {
  if (to.matched.some((record) => record.meta.requiresInternalAuth)) {
    const sessionToken = localStorage.getItem("sessionToken");
    const role = localStorage.getItem("role");
    if (sessionToken && role == "internal") {
      next();
    } else if (role == "customer") {
      next("/customer");
    } else {
      next("/");
    }
  } else {
    next();
  }
});

router.beforeEach(async (to, from, next) => {
  if (to.matched.some((record) => record.meta.requiresCustomerAuth)) {
    const sessionToken = localStorage.getItem("sessionToken");
    const role = localStorage.getItem("role");
    if (sessionToken && role == "customer") {
      next();
    } else if (role == "internal") {
      next("/internal");
    } else {
      next("/");
    }
  } else {
    next();
  }
});

export default router;
