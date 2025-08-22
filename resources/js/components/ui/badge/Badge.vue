<!-- resources/js/components/ui/badge/Badge.vue -->
<script lang="ts">
import { defineComponent, type PropType } from 'vue';

type BadgeVariant = 'default' | 'outline' | 'success' | 'warning' | 'destructive';

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
      : variant === 'outline'
        ? 'border border-gray-300 text-gray-700 dark:text-gray-300'
      : variant === 'success'
        ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
      : variant === 'warning'
        ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'
      : variant === 'destructive'
        ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
      : ''
    ]"
  >
    <slot />
  </span>
</template>