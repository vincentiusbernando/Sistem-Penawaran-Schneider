<template>
  <div class="page">
    <div class="px-4 py-2">
      <table>
        <thead>
          <tr class="bg-gray-200">
            <th class="px-4 py-2">Date</th>
            <th class="px-4 py-2">Team</th>
            <th class="px-4 py-2">Internal</th>
            <th class="px-4 py-2">Detail</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="row in data" :key="row.id" class="border-gray-200">
            <td class="px-4 py-2" data-label="ID">{{ row.tgl }}</td>
            <td class="px-4 py-2" data-label="Name">
              {{ row.Team }}
            </td>
            <td class="px-4 py-2" data-label="Email">
              {{ row.Internal }}
            </td>
            <td class="px-4 py-2" data-label="Detail">
              <a href="/internal/1">Detail</a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { defineProps } from "vue";
defineProps({
  data: {
    type: Array,
    required: true,
  },
});
</script>
