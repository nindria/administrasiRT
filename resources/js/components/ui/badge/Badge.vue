<!-- resources/js/components/ui/badge/Badge.vue -->
<script lang="ts">
import { defineComponent, type PropType } from 'vue';

type BadgeVariant = 'default' | 'outline';

export default defineComponent({
  name: 'Badge',
  props: {
    variant: {
      type: String as PropType<BadgeVariant>,
      default: 'default'
    }
  }
});
</script>

<template>
  <span 
    :class="[
      'px-2 py-1 text-xs font-medium rounded-full',
      variant === 'default' 
        ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200'
        : 'border text-gray-700 dark:text-gray-300'
    ]"
  >
    <slot />
  </span>
</template>