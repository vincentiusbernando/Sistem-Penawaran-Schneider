<template>
  <HeaderComponent></HeaderComponent>
  <DrawerComponent></DrawerComponent>
  <div class="page">
    <div class="px-4 py-2">
      <div style="display: flex; justify-content: space-between">
        <div>
          <h3 class="mt-2 font-medium text-gray-900">
            {{ head.tgl }}
          </h3>
          <h1 class="mt-2 text-3xl font-medium text-gray-900">
            Customer : {{ head.Customer }}
          </h1>
          <h1 class="mt-2 font-medium text-gray-900">
            By {{ head.Internal }} from {{ head.Team }}
          </h1>
        </div>
        <div>
          <button class="px-4 py-2 btn" @click="saveModifiedRows()">
            Save
          </button>
        </div>
      </div>
      <table>
        <thead>
          <tr class="bg-gray-200">
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
          <tr v-for="row in data" :key="row.id" class="border-gray-200">
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
              <input class="px-3 py-2" v-model="row.delivery_time" />
            </td>
            <td class="px-4 py-2" data-label="Status">
              {{ row.status }}
            </td>
            <td data-label="Remarks">
              <input class="px-3 py-2" v-model="row.remarks" />
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
import { onMounted, ref, watch } from "vue";
import { useRoute } from 'vue-router';
import AuthService from "@/AuthService";

const data = ref([]);
const head = ref({});
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
// Track modified rows
const modifiedRows = ref(new Set());

// Watch for changes in each row
for (let i = 0; i < data.value.length; i++) {
  for (const key in data[i]) {
    if (Object.hasOwnProperty.call(data.value[i], key)) {
      watch(
        () => data[i][key],
        (newValue, oldValue) => {
          if (!isEqual(newValue, oldValue)) {
            // Check if the modification already exists in modifiedRows
            let alreadyModified = false;
            modifiedRows.value.forEach((item) => {
              if (item.rowIndex === i && item.columnName === key) {
                alreadyModified = true;
              }
            });
            // If not already modified, add it to the set
            if (!alreadyModified) {
              modifiedRows.value.add({
                rowIndex: i,
                columnName: key,
              });
            }
          } else {
            // Remove entry if value reverts to the original
            modifiedRows.value.forEach((item) => {
              if (item.rowIndex === i && item.columnName === key) {
                modifiedRows.value.delete(item);
              }
            });
          }
        },
        { deep: true } // Ensure deep watching for nested properties
      );
    }
  }
}

function saveModifiedRows() {
  for (const modifiedItem of modifiedRows.value) {
    const { rowIndex, columnName } = modifiedItem;
    const modifiedRow = data[rowIndex];
    const modifiedValue = modifiedRow[columnName];
    console.log(
      `Row ${rowIndex} - Column ${columnName} modified:`,
      modifiedValue
    );
  }
  modifiedRows.value.clear();
}

// Helper function to check object equality
function isEqual(obj1, obj2) {
  return JSON.stringify(obj1) === JSON.stringify(obj2);
}
</script>
