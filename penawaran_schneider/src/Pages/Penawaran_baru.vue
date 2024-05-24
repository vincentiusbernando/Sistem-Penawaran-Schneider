<style>
.coladdRowBetween {
  width: 3%;
}
.colRef {
  /* width: 2%; */
}
.colDescription {
  width: 10%;
}
.colQuantity {
  width: 6%;
}
.colDeliveryTime {
}
.colRemarks {
  width: 10%;
}
.colSD {
}
.colAD {
}
.colCD {
}
.colCoef {
  width: 5%;
}
.colPriceExcel {
}
.colPriceAfterCoef {
}
.colUnitPrice {
}
.colTotalPrice {
}
.colAction {
  width: 6%;
}
</style>
<template>
  <HeaderComponent></HeaderComponent>
  <DrawerComponent></DrawerComponent>
  <div class="page">
  <div class="px-4 py-2">
    <div class="autocomplete mb-4">
      <input
        type="text"
        v-model="searchTextExternal"
        @input="searchExternal"
        placeholder="Search Customer..."
        class="px-4 py-2 border border-gray-300 rounded w-full"
      />
      <div
        v-if="searchResultsExternal.length > 0"
        class="autocomplete-items bg-white border border-gray-300 rounded mt-2"
      >
        <div
          v-for="result in searchResultsExternal"
          :key="result.id"
          class="autocomplete-item px-4 py-2 cursor-pointer hover:bg-gray-200 hover-text"
          @click="selectItemExternal(result)"
        >
          {{ result.customer_perusahaan }}
        </div>
      </div>
    </div>
    <div class="text-white py-2 flex justify-end">
      <button class="bg-green-600 text-white font-bold px-4 py-2 rounded" @click="addRow">
        Tambah Barang
      </button>
    </div>
    <table class="min-w-full border border-gray-200 rounded-lg overflow-hidden">
      <thead>
        <tr class="bg-green-600 text-white">
          <th class="px-4 py-2 coladdRowBetween"></th>
          <th class="px-4 py-2">Ref</th>
          <th class="px-4 py-2">Description</th>
          <th class="px-4 py-2">Quantity</th>
          <th class="px-4 py-2">Unit Price</th>
          <th class="px-4 py-2">Total Price</th>
          <th class="px-4 py-2">Delivery Time</th>
          <th class="px-4 py-2">Remarks</th>
          <th class="px-4 py-2">Standard Discount (%)</th>
          <th class="px-4 py-2">Additional Discount (%)</th>
          <th class="px-4 py-2">Cash Discount (%)</th>
          <th class="px-4 py-2">Coef</th>
          <th class="px-4 py-2">PL Excel</th>
          <th class="px-4 py-2">PL After Coef</th>
          <th class="px-4 py-2">Action</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="(item, index) in items"
          :key="item.id"
          class="border-t border-gray-200 hover:bg-gray-100"
        >
          <td class="text-center">
            <button
              class="bg-green-600 text-white rounded px-4 py-2"
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
                class="px-4 py-2 border border-gray-300 rounded w-full"
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
                  class="autocomplete-item px-4 py-2 hover-text hover:bg-gray-200"
                  @click="selectItem(result, index)"
                >
                  {{ result.ref }}
                </div>
              </div>
            </div>
          </td>
          <td class="px-4 py-2" data-label="Description">{{ item.description }}</td>
          <td data-label="Quantity">
            <input
              class="px-4 py-2 border border-gray-300 rounded w-full"
              type="number"
              step="1"
              min="1"
              v-model="item.quantity"
              @input="updateRow(item)"
            />
          </td>
          <td class="px-4 py-2" data-label="Unit Price">{{ item.unitPrice }}</td>
          <td class="px-4 py-2" data-label="Total Price">{{ item.totalPrice }}</td>
          <td data-label="Delivery Time">
            <input
              class="px-4 py-2 border border-gray-300 rounded w-full"
              v-model="item.deliveryTime"
            />
          </td>
          <td data-label="Remarks">
            <input
              class="px-4 py-2 border border-gray-300 rounded w-full"
              v-model="item.remarks"
            />
          </td>
          <td data-label="Standard Discount">
            <input
              class="px-4 py-2 border border-gray-300 rounded w-full"
              type="number"
              step="0.1"
              min="0"
              v-model="item.standardDiscount"
              @input="updateRow(item)"
            />
          </td>
          <td data-label="Additional Discount">
            <input
              class="px-4 py-2 border border-gray-300 rounded w-full"
              type="number"
              step="0.1"
              min="0"
              v-model="item.additionalDiscount"
              @input="updateRow(item)"
            />
          </td>
          <td data-label="Cash Discount">
            <input
              class="px-4 py-2 border border-gray-300 rounded w-full"
              type="number"
              step="0.1"
              min="0"
              v-model="item.cashDiscount"
              @input="updateRow(item)"
            />
          </td>
          <td data-label="Coef">
            <input
              class="px-4 py-2 border border-gray-300 rounded w-full"
              type="number"
              step="0.1"
              min="0"
              v-model="item.coef"
              @input="updateRow(item)"
            />
          </td>
          <td class="px-4 py-2" data-label="PL Excel">{{ item.plExcel }}</td>
          <td data-label="PL After Coef">
            <input
              class="px-4 py-2 border border-gray-300 rounded w-full"
              v-model="item.plAfterCoef"
              readonly
            />
          </td>
          <td class="text-center">
            <button
              class="delete text-white rounded px-4 py-1"
              @click="deleteRow(item.id)"
            >
              Delete
            </button>
          </td>
        </tr>
      </tbody>
    </table>
    <div class="py-2 text-3xl flex justify-end">
      <h1>Total: Rp. {{ grandTotal }}</h1>
    </div>
    <div class="text-white py-2 flex justify-end">
      <button class="bg-green-600 text-white font-bold px-4 py-2 rounded" @click="submitPenawaran">
        Buat Penawaran
      </button>
    </div>
  </div>
</div>
</template>

<script setup>
import HeaderComponent from "../components/HeaderComponent";
import DrawerComponent from "../components/DrawerComponent";
import { reactive, ref } from "vue";
import { v4 as uuidv4 } from "uuid";
import AuthService from "@/AuthService";

const searchTextArray = ref([]);
const searchResults = ref([]);
var customer_id;
var standard_discount = 0;
var additional_discount = 0;
var grandTotal = 0;
const items = reactive([
  {
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
    coef: "1",
    plExcel: "0",
    plAfterCoef: "0",
    unitPrice: "",
    totalPrice: "",
  },
]);

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
    console.log(data);
    items[index].ref = data[0].ref;
    items[index].description = data[0].description;
    items[index].plExcel = data[0].price;
    items[index].dbID = data[0].id;
    updateRow(items[index]);
  } catch (error) {
    console.error("Error fetching data:", error);
  }
}
//customer
const searchTextExternal = ref("");
const searchResultsExternal = ref([]);

async function searchExternal() {
  if (searchTextExternal.value.length > 0) {
    try {
      const response = await AuthService.searchCustomer(
        searchTextExternal.value
      );
      searchResultsExternal.value = response.data;
    } catch (error) {
      console.error("Error fetching external data:", error);
    }
  } else {
    searchResultsExternal.value = [];
  }
}

function selectItemExternal(item) {
  searchTextExternal.value = item.customer_perusahaan;
  additional_discount = item.additional_discount;
  standard_discount = item.standard_discount;
  customer_id = item.customer_id;
  items.forEach((item) => {
    item.standardDiscount = standard_discount;
    item.additionalDiscount = additional_discount;
  });
  searchResultsExternal.value = [];
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
  grandTotal = 0;
  for (let index = 0; index < items.length; index++) {
    grandTotal += items[index].totalPrice;
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
async function submitPenawaran() {
  try {
    const formData = new FormData();
    items.forEach((item) => {
      formData.append("products[]", JSON.stringify(item));
    });
    formData.append("customer_id", customer_id);
    const response = await AuthService.submitPenawaran(formData);
    console.log(response);
    window.location.href = "/internal";
  } catch (error) {
    console.error("Error submitting penawaran:", error);
  }
}
</script>
