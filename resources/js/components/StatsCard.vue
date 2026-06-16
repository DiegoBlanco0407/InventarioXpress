<template>
  <div class="stats shadow">
    <div class="stat">
      <div class="stat-title">{{ title }}</div>
      <div class="stat-value">{{ value }}</div>
      <div class="stat-desc">{{ desc }}</div>
    </div>
  </div>
</template>
<script setup>
const props = defineProps({ title: String, value: [String, Number], desc: String });
</script>
