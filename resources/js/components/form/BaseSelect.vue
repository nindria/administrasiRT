<template>
    <div class="grid">
        <Label class="mb-2" :for="name">{{ label }}</Label>
        <div class="relative">
            <DropdownMenu v-model:open="isOpen">
                <DropdownMenuTrigger as-child>
                    <button
                        type="button"
                        :id="name"
                        :name="name"
                        class="flex h-9 w-full min-w-0 items-center justify-between rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-xs transition-[color,box-shadow] outline-none selection:bg-primary selection:text-primary-foreground file:inline-flex file:h-7 file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50 disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50 aria-invalid:border-destructive aria-invalid:ring-destructive/20 md:text-sm dark:bg-input/30 dark:aria-invalid:ring-destructive/40"
                    >
                        <span class="truncate">{{ selectedOption ? selectedOption.label : placeholder }}</span>
                        <ChevronDown class="h-4 w-4 opacity-50" />
                    </button>
                </DropdownMenuTrigger>
                <DropdownMenuContent class="w-(--reka-dropdown-menu-trigger-width) min-w-56">
                    <DropdownMenuRadioGroup v-model="selectValue as string">
                        <DropdownMenuRadioItem v-for="option in options" :key="option.value" :value="option.value as string">
                            {{ option.label }}
                        </DropdownMenuRadioItem>
                    </DropdownMenuRadioGroup>
                </DropdownMenuContent>
            </DropdownMenu>
        </div>
        <InputError :message="message" />
    </div>
</template>

<script setup lang="ts">
import { ChevronDown } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import InputError from '../InputError.vue';
import { DropdownMenu, DropdownMenuContent, DropdownMenuRadioGroup, DropdownMenuRadioItem, DropdownMenuTrigger } from '../ui/dropdown-menu';
import Label from '../ui/label/Label.vue';

defineOptions({
    inheritAttrs: false,
});

interface Option {
    value: string | number;
    label: string;
}

const props = defineProps({
    label: {
        type: String,
        default: '',
    },
    name: {
        type: String,
        default: '',
    },
    message: {
        type: String,
        default: '',
    },
    value: {
        type: [String, Number],
        default: null,
    },
    options: {
        type: Array as () => Option[],
        default: () => [],
    },
    placeholder: {
        type: String,
        default: 'Pilih opsi',
    },
});

const emit = defineEmits(['update:value']);
const isOpen = ref(false);

const selectValue = computed({
    get() {
        return props.value;
    },
    set(value) {
        emit('update:value', value);
    },
});

const selectedOption = computed(() => {
    return props.options.find((option) => option.value === props.value);
});
</script>
