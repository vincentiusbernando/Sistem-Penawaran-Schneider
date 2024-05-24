<template>
  <HeaderComponent></HeaderComponent>
  <DrawerComponent></DrawerComponent>
  <div class="page">
    <div class="px-4">
      <div class="flex justify-between">
        <div>
          <h3 class="mt-2 font-medium text-gray-900 py-2">
            {{ head.tgl }}
          </h3>
          <h1 class="mt-2 text-3xl font-medium text-gray-900">
            Customer: {{ head.Customer }}
          </h1>
          <h1 class="mt-2 font-medium text-gray-900 py-2">
            By {{ head.Internal }} from {{ head.Team }}
          </h1>
        </div>
        <div>
          <button class="bg-green-600 text-white font-bold px-4 py-2 rounded" @click="saveModifiedRows">
            Save
          </button>
        </div>
      </div>
      <table class="min-w-full border border-gray-200 rounded-lg overflow-hidden mt-4">
        <thead>
          <tr class="bg-green-600 text-white">
            <th class="px-4 py-2">Ref</th>
            <th class="px-4 py-2">Description</th>
            <th class="px-4 py-2">Quantity</th>
            <th class="px-4 py-2">Unit Price</th>
            <th class="px-4 py-2">Total Price</th>
            <th class="px-4 py-2">Delivery Time</th>
            <th class="px-4 py-2">Status</th>
            <th class="px-4 py-2">Remarks</th>
            <th class="px-4 py-2">Standard Discount</th>
            <th class="px-4 py-2">Additional Discount</th>
            <th class="px-4 py-2">Cash Discount</th>
            <th class="px-4 py-2">Coef</th>
            <th class="px-4 py-2">PL Excel</th>
            <th class="px-4 py-2">PL After Coef</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(row, index) in data" :key="row.id" class="border-t border-gray-200 hover:bg-gray-100">
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
              <input class="px-3 py-2" v-model="row.delivery_time" @input="markModified(index, 'delivery_time')" />
            </td>
            <td class="px-4 py-2" data-label="Status">
              {{ row.status }}
            </td>
            <td data-label="Remarks">
              <input class="px-3 py-2" v-model="row.remarks" @input="markModified(index, 'remarks')" />
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
import { onMounted, ref } from "vue";
import { useRoute } from 'vue-router';
import AuthService from "@/AuthService";

const data = ref([]);
const head = ref({});
const modifiedRows = ref([]);
const route = useRoute();

onMounted(async () => {
  try {
    const response = await AuthService.detailPenawaranInternal(route.params.uri);
    data.value = response.data.data;
    head.value = response.data.head[0];
  } catch (error) {
    console.error("Error fetching data:", error);
  }
});

const markModified = (rowIndex, fieldName) => {
  const modifiedEntry = {
    row: rowIndex,
    field: fieldName,
    value: data.value[rowIndex][fieldName]
  };
  const existingEntry = modifiedRows.value.find(entry => entry.row === rowIndex && entry.field === fieldName);
  if (existingEntry) {
    existingEntry.value = modifiedEntry.value;
  } else {
    modifiedRows.value.push(modifiedEntry);
  }
};

const saveModifiedRows = () => {
  console.log('Modified rows:', modifiedRows.value);
  // Add your logic to handle the saving of modified rows here.
};
</script>
