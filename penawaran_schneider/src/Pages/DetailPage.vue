<template>
  <HeaderComponent></HeaderComponent>
  <DrawerComponent></DrawerComponent>
  <div class="page">
    <div class="px-4">
      <div class="flex justify-between py-2 items-center">
        <div class="flex items-center">
          <div
            class="flex rounded-lg border shadow border-gray-300 py-4 px-3 bg-white items-center"
          >
            <i class="bx bx-time bigger_icon"></i>
            <h1 class="px-3 font-medium text-gray-900">
              {{ head.tgl }}
            </h1>
          </div>
          <div
            class="flex rounded-lg border shadow border-gray-300 py-4 px-3 ml-4 bg-white items-center"
          >
            <i class="bx bx-user bigger_icon"></i>
            <h1 class="px-3 font-medium text-gray-900">
              {{ head.Customer }} - {{ head.Perusahaan }}
            </h1>
          </div>
          <div
            class="flex rounded-lg border shadow border-gray-300 py-4 px-3 ml-4 bg-white items-center"
          >
            <i class="bx bx-task bigger_icon"></i>
            <h1 class="px-3 font-medium text-gray-900">
              {{ head.Internal }} from {{ head.Team }}
            </h1>
          </div>
        </div>
        <div class="flex">
          <div>
            <button
              class="bg-green-600 text-white font-bold px-4 py-2 rounded items-center flex"
              @click="Refresh"
            >
              <i class="bx bx-refresh bigger_icon"></i>
              <p class="ml-2">Refresh</p>
            </button>
          </div>
          <div v-if="teams_id == head.teams_id || isAdmin">
            <button
              class="bg-green-600 text-white font-bold px-4 py-2 rounded ml-2 items-center flex"
              @click="saveModifiedRows"
            >
              <i class="bx bx-save bigger_icon"></i>
              <p class="ml-2">Save</p>
            </button>
          </div>
        </div>
      </div>
      <div
        class="py-2 flex justify-end"
        v-if="teams_id == head.teams_id || isAdmin"
      >
        <div class="flex items-center">
          <h1>Global Coef :</h1>
          <input
            class="px-3 py-2 border border-gray-300 rounded ml-2"
            type="number"
            step="0.01"
            min="1"
            v-model="global_coef"
          />
          <button
            class="bg-green-600 text-white font-bold px-3 py-2 rounded shadow-md ml-2"
            @click="setCoef"
          >
            Set
          </button>
        </div>
        <button
          class="bg-green-600 text-white font-bold px-3 py-2 rounded shadow-md ml-2"
          @click="addRow"
        >
          Tambah Barang
        </button>
      </div>
      <div style="position: relative">
        <table
          class="min-w-full rounded-lg mt-4 bg-white shadow-md overflow-hidden"
          v-if="tableReady"
        >
          <thead>
            <tr class="bg-green-600 text-white">
              <th
                style="width: 5%"
                class="px-3 py-2"
                v-if="teams_id == head.teams_id || isAdmin"
              ></th>
              <th style="width: 15%" class="px-3 py-2">Ref</th>
              <th style="width: 20%" class="px-3 py-2">Description</th>
              <th style="width: 6%" class="px-3 py-2">Qty</th>
              <th style="width: 8%" class="px-3 py-2">Unit <br />Price</th>
              <th style="width: 8%" class="px-3 py-2">Total<br />Price</th>
              <th style="width: 8%" class="px-3 py-2">Del. Time</th>
              <th style="width: 10%" class="px-3 py-2">Remarks</th>
              <th style="width: 6%" class="px-3 py-2">SD <br />(%)</th>
              <th style="width: 6%" class="px-3 py-2">AD<br />(%)</th>
              <th style="width: 6%" class="px-3 py-2">CD<br />(%)</th>
              <th style="width: 8%" class="px-3 py-2">PL <br />Excel</th>
              <th style="width: 7%" class="px-3 py-2">Coef</th>
              <th style="width: 8%" class="px-3 py-2">
                PL<br />After <br />Coef
              </th>
              <th
                style="width: 8%"
                class="px-3 py-2"
                v-if="teams_id == head.teams_id || isAdmin"
              >
                Action
              </th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(item, index) in items"
              :key="item.id"
              class="border-t border-gray-200"
            >
              <td
                class="text-center"
                v-if="teams_id == head.teams_id || isAdmin"
              >
                <button
                  class="bg-green-600 text-white rounded px-3"
                  @click="addRowBetween(index + 1)"
                >
                  +
                </button>
              </td>
              <td data-label="Ref">
                <div class="autocomplete">
                  <input
                    :id="'searchInput-' + item.id"
                    type="text"
                    v-model="searchTextArray[index].text"
                    @input="search(index)"
                    placeholder="Search..."
                    class="px-3 py-2 border border-gray-300 rounded w-full"
                    :disabled="teams_id != head.teams_id && !isAdmin"
                  />
                  <div
                    :id="'searchResults-' + item.id"
                    v-if="
                      searchResults[index] &&
                      searchResults[index].results.length > 0
                    "
                    class="autocomplete-items bg-white border border-gray-300 rounded mt-2"
                  >
                    <div
                      v-for="result in searchResults[index].results"
                      :key="result.id"
                      class="autocomplete-item px-3 py-2 hover-text hover:bg-gray-200"
                      @click="selectItem(result, index)"
                    >
                      {{ result.ref }}
                    </div>
                  </div>
                </div>
              </td>
              <td class="px-3 py-2" data-label="Description">
                {{ item.description }}
              </td>
              <td data-label="Quantity">
                <input
                  class="px-3 py-2 border border-gray-300 rounded w-full"
                  type="number"
                  step="1"
                  min="1"
                  v-model="item.quantity"
                  @input="updateStock(item)"
                  :disabled="teams_id != head.teams_id && !isAdmin"
                />
              </td>
              <td class="px-3 py-2 text-end" data-label="Unit Price">
                {{ formatRupiah(item.unitPrice) }}
              </td>
              <td class="px-3 py-2 text-end" data-label="Total Price">
                {{ formatRupiah(item.totalPrice) }}
              </td>
              <td data-label="Delivery Time">
                <input
                  class="px-3 py-2 border border-gray-300 rounded w-full"
                  v-model="item.deliveryTime"
                  :disabled="teams_id != head.teams_id && !isAdmin"
                />
              </td>
              <td data-label="Remarks">
                <!-- <input
                  class="px-3 py-2 border border-gray-300 rounded w-full"
                  v-model="item.remarks"
                  :disabled="teams_id != head.teams_id && !isAdmin"
                /> -->
                <textarea
                  name=""
                  id=""
                  class="px-3 py-2 border border-gray-300 rounded w-full"
                  style="height: 100%"
                  v-model="item.remarks"
                  :disabled="teams_id != head.teams_id && !isAdmin"
                ></textarea>
              </td>
              <td data-label="Standard Discount">
                <input
                  class="px-3 py-2 border border-gray-300 rounded w-full"
                  type="number"
                  step="0.1"
                  min="0"
                  v-model="item.standardDiscount"
                  @input="updateRow(item)"
                  :disabled="teams_id != head.teams_id && !isAdmin"
                />
              </td>
              <td data-label="Additional Discount">
                <input
                  class="px-3 py-2 border border-gray-300 rounded w-full"
                  type="number"
                  step="0.1"
                  min="0"
                  v-model="item.additionalDiscount"
                  @input="updateRow(item)"
                  :disabled="teams_id != head.teams_id && !isAdmin"
                />
              </td>
              <td data-label="Cash Discount">
                <input
                  class="px-3 py-2 border border-gray-300 rounded w-full"
                  type="number"
                  step="0.1"
                  min="0"
                  v-model="item.cashDiscount"
                  @input="updateRow(item)"
                  :disabled="teams_id != head.teams_id && !isAdmin"
                />
              </td>
              <td class="px-3 py-2 text-end" data-label="PL Excel">
                {{ formatRupiah(item.plExcel) }}
              </td>
              <td data-label="Coef">
                <input
                  class="px-3 py-2 border border-gray-300 rounded w-full"
                  type="number"
                  step="0.01"
                  min="0"
                  v-model="item.coef"
                  @input="updateRow(item)"
                  :disabled="teams_id != head.teams_id && !isAdmin"
                />
              </td>
              <td class="px-3 py-2 text-end" data-label="PL After Coef">
                {{ formatRupiah(item.plAfterCoef) }}
              </td>
              <td
                class="text-center"
                v-if="teams_id == head.teams_id || isAdmin"
              >
                <button
                  class="delete text-white rounded px-3 py-1"
                  @click="deleteRow(item.id)"
                >
                  Delete
                </button>
              </td>
            </tr>

            <tr>
              <td></td>
              <td class="px-3 py-2">Sub Total:</td>
              <td></td>
              <td></td>
              <td></td>
              <td class="px-3 py-2 text-end">{{ formatRupiah(subTotal) }}</td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td></td>
              <td class="px-3 py-2">PPN :</td>
              <td></td>
              <td></td>
              <td></td>
              <td class="px-3 py-2 text-end">
                {{ formatRupiah(subTotal * 0.11) }}
              </td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td></td>
              <td class="px-3 py-2">Total :</td>
              <td></td>
              <td></td>
              <td></td>
              <td class="px-3 py-2 text-end">{{ formatRupiah(grandTotal) }}</td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
          </tbody>
        </table>
      </div>
      <!-- <div class="py-2 text-2xl">
        <br />
        <div class="flex justify-between">
          <h1>Sub Total:</h1>
          <h1>{{ formatRupiah(subTotal) }}</h1>
        </div>
        <br />
        <div class="flex justify-between">
          <h1>PPN :</h1>
          <h1>{{ formatRupiah(subTotal * 0.11) }}</h1>
        </div>
        <br />
        <div class="flex justify-between">
          <h1>Total:</h1>
          <h1>{{ formatRupiah(grandTotal) }}</h1>
        </div>
      </div> -->
    </div>
  </div>
</template>

<script setup>
import HeaderComponent from "../components/HeaderComponent";
import DrawerComponent from "../components/DrawerComponent";
import { reactive, ref } from "vue";
import { v4 as uuidv4 } from "uuid";
import AuthService from "@/AuthService";
import { useToast } from "vue-toast-notification";
import "vue-toast-notification/dist/theme-sugar.css";
import { onMounted } from "vue";
import { useRoute } from "vue-router";

const teams_id = localStorage.getItem("teams_id");
const isAdmin = localStorage.getItem("akses") == "admin";
const head = ref({});
var data;
const route = useRoute();
const searchTextArray = ref([]);
const searchResults = ref([]);
var standard_discount = 0;
var additional_discount = 0;
var subTotal = 0;
var grandTotal = 0;
var global_coef = 1;
const $toast = useToast();
const items = reactive([]);
var tableReady = ref(false);
const fetchData = async () => {
  try {
    const response = await AuthService.detailPenawaranInternal(
      route.params.uri
    );
    head.value = response.data.head[0];
    standard_discount = head.value.standard_discount;
    additional_discount = head.value.additional_discount;

    data = response.data.data;
    tableReady.value = false;
    items.splice(0, items.length);
    searchTextArray.value.splice(0, searchTextArray.value.length);
    searchResults.value.splice(0, searchResults.value.length);
    for (const item of data) {
      searchTextArray.value.push({ id: uuidv4(), text: item.ref });
      searchResults.value.push({ id: uuidv4(), results: [] });

      const response2 = await AuthService.getStock(item.id);
      var stock = response2.data;
      items.push({
        id: uuidv4(),
        dbID: item.id,
        ref: item.ref,
        description: item.description,
        quantity: item.quantity,
        deliveryTime: item.delivery_time,
        remarks: item.remarks,
        standardDiscount: item.standard_discount,
        additionalDiscount: item.additional_discount,
        cashDiscount: item.cash_discount,
        coef: item.coef,
        plExcel: item.price_list_excl,
        plAfterCoef: item.pl_aft_coef,
        unitPrice: item.unit_price,
        totalPrice: item.total_price,
        stock: stock,
      });
    }
    tableReady.value = true;
    updateGrandTotal();
  } catch (error) {
    console.error("Error fetching data:", error);
  }
};
fetchData();
onMounted(() => {
  console.log(data);
});
const Refresh = () => {
  fetchData();
};

items.forEach(() => {
  searchTextArray.value.push({ id: uuidv4(), text: "" });
  searchResults.value.push({ id: uuidv4(), results: [] });
});

async function search(index) {
  if (!searchTextArray.value[index].text) {
    searchTextArray.value[index].text = "";
  }

  if (searchTextArray.value[index].text.length > 0) {
    try {
      const response = await AuthService.searchProductRef(
        searchTextArray.value[index].text
      );
      const data = await response.data;
      searchResults.value[index].results = data;
    } catch (error) {
      console.error("Error fetching data:", error);
    }
  } else {
    searchResults.value[index].results = [];
  }
}

async function selectItem(item, index) {
  searchTextArray.value[index].text = item.ref;
  searchResults.value[index].results = [];
  try {
    const response = await AuthService.searchProductRef(
      searchTextArray.value[index].text
    );
    const data = await response.data;
    items[index].ref = data[0].ref;
    items[index].description = data[0].description;
    items[index].plExcel = data[0].price;
    items[index].dbID = data[0].id;

    const response2 = await AuthService.getStock(data[0].id);
    var stock = response2.data;
    items[index].stock = stock;

    updateStock(items[index]);
  } catch (error) {
    console.error("Error fetching data:", error);
  }
}

async function updateStock(item) {
  console.log(item.stock);
  if (item.stock) {
    if (item.stock > item.quantity) {
      item.remarks = "ready";
    } else {
      item.remarks = "ready qty stock";
    }
  }
  updateRow(item);
}
function updateRow(item) {
  if (item.plExcel !== "" && item.coef !== "") {
    item.plAfterCoef = parseInt(item.plExcel * item.coef);
    item.unitPrice = parseInt(
      (item.plAfterCoef *
        (100 -
          item.standardDiscount -
          item.additionalDiscount -
          item.cashDiscount)) /
        100
    );
    item.totalPrice = item.unitPrice * item.quantity;
    updateGrandTotal();
  } else {
    item.plAfterCoef = 0;
  }
}
function updateGrandTotal() {
  subTotal = 0;
  for (let index = 0; index < items.length; index++) {
    subTotal += items[index].totalPrice;
  }
  grandTotal = subTotal * 1.11;
}

function setCoef() {
  for (let index = 0; index < items.length; index++) {
    items[index].coef = global_coef;
  }
}

function addRow() {
  items.push({
    id: uuidv4(),
    dbID: 0,
    ref: "",
    description: "",
    quantity: "1",
    deliveryTime: "",
    remarks: "",
    standardDiscount: standard_discount,
    additionalDiscount: additional_discount,
    cashDiscount: "0",
    coef: 1,
    plExcel: 0,
    plAfterCoef: 0,
    unitPrice: "",
    totalPrice: "",
    stock: null,
  });
  searchTextArray.value.push({ id: uuidv4(), text: "" });
  searchResults.value.push({ id: uuidv4(), results: [] });
}
function addRowBetween(index) {
  items.splice(index, 0, {
    id: uuidv4(),
    dbID: 0,
    ref: "",
    description: "",
    quantity: "1",
    deliveryTime: "",
    remarks: "",
    standardDiscount: standard_discount,
    additionalDiscount: additional_discount,
    cashDiscount: 0,
    coef: 1,
    plExcel: 0,
    plAfterCoef: 0,
    unitPrice: "",
    totalPrice: "",
    stock: null,
  });
  searchTextArray.value.splice(index, 0, { id: uuidv4(), text: "" });
  searchResults.value.splice(index, 0, { id: uuidv4(), results: [] });
}

function deleteRow(id) {
  const index = items.findIndex((item) => item.id === id);
  if (index !== -1) {
    items.splice(index, 1);
    searchTextArray.value.splice(index, 1);
    searchResults.value.splice(index, 1);
    updateGrandTotal();
  }
}
async function saveModifiedRows() {
  try {
    if (items.length > 0) {
      const formData = new FormData();
      items.forEach((item) => {
        formData.append("products[]", JSON.stringify(item));
        if (item.dbID == 0) {
          throw new Error("Terdapat Kesalahan pada Produk yang Ditambahkan");
        }
      });
      formData.append("penawarans_id", head.value.id);
      await AuthService.updatePenawaran(formData);
      $toast.success("Saved", { position: "top" });
      Refresh();
    } else {
      throw new Error("Isi Produk Dalam Penawaran Terlebih Dahulu");
    }
  } catch (error) {
    console.error("Error submitting penawaran:", error);
    $toast.error(error, { position: "top" });
  }
}
function formatRupiah(value) {
  if (!value) return "Rp 0";
  value = Math.floor(value);
  const numberString = value.toString().replace(/[^,\d]/g, "");
  const split = numberString.split(",");
  const sisa = split[0].length % 3;
  let rupiah = split[0].substr(0, sisa);
  const ribuan = split[0].substr(sisa).match(/\d{3}/gi);

  if (ribuan) {
    const separator = sisa ? "." : "";
    rupiah += separator + ribuan.join(".");
  }

  rupiah = split[1] !== undefined ? rupiah + "," + split[1] : rupiah;
  return "Rp " + rupiah;
}
</script>
