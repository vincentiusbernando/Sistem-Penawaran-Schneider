import axios from "axios";
// axios.defaults.baseURL = "https://penawaranschneiderapi.rfqbstg.com";
axios.defaults.baseURL = "http://127.0.0.1:8000";
axios.defaults.headers.common["Authorization"] =
  "Bearer " + localStorage.getItem("sessionToken");

export default {
  loginInternal(credentials) {
    return axios.post("/api/login_internal", credentials);
  },
  loginCustomer(credentials) {
    return axios.post("/api/login_customer", credentials);
  },
  internal() {
    return axios.get("/api/internal");
  },
  dashboard() {
    return axios.post("/api/dashboard");
  },
  product() {
    return axios.get("/api/product");
  },
  submitProduct(formData) {
    return axios.post("/api/buat_product", formData);
  },
  standardDiscount() {
    return axios.get("/api/sd");
  },
  searchCustomer(query) {
    return axios.get("/api/search_customer?search=" + query);
  },
  searchProductRef(query) {
    return axios.get("/api/search_product?search=" + query);
  },
  detailPenawaranInternal(uri) {
    return axios.get("/api/internal/" + uri);
  },
  submitPenawaran(formData) {
    return axios.post("/api/buat_penawaran", formData);
  },
  summary(formData) {
    return axios.post("/api/summary", formData);
  },
  updatePenawaran(id_penawaran, jsonData) {
    return axios.post("/api/update_penawaran", {
      jsonData: jsonData,
      id_penawaran: id_penawaran,
    });
  },
};
