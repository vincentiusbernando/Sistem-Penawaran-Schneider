import axios from "axios";
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
  product() {
    return axios.get("/api/product");
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
  updatePenawaran(id_penawaran, jsonData) {
    return axios.post("/api/update_penawaran", {
      jsonData: jsonData,
      id_penawaran: id_penawaran,
    });
  },
};
