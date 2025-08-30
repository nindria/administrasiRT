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
        ? 'bg-primary/10 text-primary dark:bg-primary/20 dark:text-primary'
      : variant === 'outline'
        ? 'border border-border text-foreground dark:text-foreground'
      : variant === 'success'
        ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
      : variant === 'warning'
        ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'
      : variant === 'destructive'
        ? 'bg-destructive/10 text-destructive dark:bg-destructive/20 dark:text-destructive'
      : ''
    ]"
  >
    <slot />
  </span>
</template>