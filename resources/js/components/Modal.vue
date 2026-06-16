<!--
  MODAL - Responsive mobile-first design
  Full-screen on mobile, centered dialog on tablet+
-->
<template>
  <dialog :id="id" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box w-full max-w-sm sm:max-w-md lg:max-w-lg mx-auto rounded-t-2xl sm:rounded-2xl">
      <h3 class="font-bold text-lg sm:text-xl mb-2">{{ title }}</h3>
      <div class="py-3 sm:py-4">
        <slot />
      </div>
      <div class="modal-action flex-col sm:flex-row gap-2 sm:gap-3">
        <form method="dialog" class="w-full sm:w-auto">
          <button class="btn w-full sm:w-auto">Cerrar</button>
        </form>
      </div>
    </div>
    <form method="dialog" class="modal-backdrop">
      <button>close</button>
    </form>
  </dialog>
</template>
<script setup>
const props = defineProps({ id: { type: String, required: true }, title: { type: String, default: 'Modal' } });
</script>
