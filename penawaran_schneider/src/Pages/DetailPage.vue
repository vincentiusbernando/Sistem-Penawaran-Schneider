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
          <div>
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
      <table
        class="min-w-full rounded-lg overflow-hidden mt-4 bg-white shadow-md"
      >
        <thead>
          <tr class="bg-green-600 text-white">
            <th style="width: 5%" class="px-4 py-2">Ref</th>
            <th style="width: 10%" class="px-4 py-2">Description</th>
            <th style="width: 5%" class="px-4 py-2">Quantity</th>
            <th style="width: 5%" class="px-4 py-2">Unit Price</th>
            <th style="width: 5%" class="px-4 py-2">Total Price</th>
            <th style="width: 5%" class="px-4 py-2">Delivery Time</th>
            <th style="width: 7%" class="px-4 py-2">Remarks</th>
            <th style="width: 5%" class="px-4 py-2">Standard Discount</th>
            <th style="width: 6%" class="px-4 py-2">Additional Discount</th>
            <th style="width: 5%" class="px-4 py-2">Cash Discount</th>
            <th style="width: 4%" class="px-4 py-2">Coef</th>
            <th style="width: 4%" class="px-4 py-2">PL Excel</th>
            <th style="width: 4%" class="px-4 py-2">PL After Coef</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(row, index) in data"
            :key="row.id"
            class="border-t border-gray-200 hover:bg-gray-200"
          >
            <td class="px-4 py-2" data-label="Ref">
              {{ row.ref }}
            </td>
            <td class="px-4 py-2" data-label="Description">
              {{ row.description }}
            </td>
            <td class="px-4 py-2" data-label="Quantity">
              {{ row.quantity }}
            </td>
            <td class="px-4 py-2" data-label="Unit Price">
              {{ row.unit_price }}
            </td>
            <td class="px-4 py-2" data-label="Total Price">
              {{ row.total_price }}
            </td>
            <td data-label="Delivery Time">
              <input
                class="px-3 py-2"
                v-model="row.delivery_time"
                @input="markModified(row.id, index, 'delivery_time')"
              />
            </td>
            <td data-label="Remarks">
              <input
                class="px-3 py-2"
                v-model="row.remarks"
                @input="markModified(row.id, index, 'remarks')"
              />
            </td>
            <td class="px-4 py-2" data-label="Standard Discount">
              {{ row.standard_discount }}%
            </td>
            <td class="px-4 py-2" data-label="Additional Discount">
              {{ row.additional_discount }}%
            </td>
            <td class="px-4 py-2" data-label="Cash Discount">
              {{ row.cash_discount }}%
            </td>
            <td class="px-4 py-2" data-label="Coef">
              {{ row.coef }}
            </td>
            <td class="px-4 py-2" data-label="PL Excel">
              {{ row.price_list_excl }}
            </td>
            <td class="px-4 py-2" data-label="PL After Coef">
              {{ row.pl_aft_coef }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import HeaderComponent from "../components/HeaderComponent";
import DrawerComponent from "../components/DrawerComponent";
import { ref, computed } from "vue";
import { useRoute } from "vue-router";
import AuthService from "@/AuthService";
import { useToast } from "vue-toast-notification";
import "vue-toast-notification/dist/theme-sugar.css";

const $toast = useToast();
const data = ref([]);
const head = ref({});
const modifiedRows = ref([]);
const route = useRoute();

const fetchData = async () => {
  try {
    const response = await AuthService.detailPenawaranInternal(
      route.params.uri
    );
    data.value = response.data.data;
    head.value = response.data.head[0];
    console.log(data.value[0].id);
  } catch (error) {
    console.error("Error fetching data:", error);
  }
};
fetchData();
const Refresh=()=>{
  fetchData();
  $toast.success("Refreshed", { position: "top" });
};

const markModified = (id, rowIndex, fieldName) => {
  const modifiedEntry = {
    row: id,
    field: fieldName,
    value: data.value[rowIndex][fieldName],
  };
  const existingEntry = modifiedRows.value.find(
    (entry) => entry.row === id && entry.field === fieldName
  );
  if (existingEntry) {
    existingEntry.value = modifiedEntry.value;
  } else {
    modifiedRows.value.push(modifiedEntry);
  }
};

const saveModifiedRows = () => {
  const processedData = computed(() => {
    return modifiedRows.value.reduce((result, item) => {
      const existingRow = result.find((row) => row.row === item.row);
      if (existingRow) {
        existingRow.fields.push({ [item.field]: item.value });
      } else {
        result.push({ row: item.row, fields: [{ [item.field]: item.value }] });
      }
      return result;
    }, []);
  });
  const id_penawaran = head.value.id;
  const jsonData = JSON.stringify(processedData.value);
  console.log(jsonData);
  updatePenawaran(id_penawaran, jsonData);
};
async function updatePenawaran(id_penawaran, jsonData) {
  try {
    const response = await AuthService.updatePenawaran(id_penawaran, jsonData);
    console.log(response);
    $toast.success("Successfully updated", { position: "top" });
  } catch (error) {
    console.log(error);
  }
}
</script>
