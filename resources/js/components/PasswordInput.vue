<script setup lang="ts">
import { Input } from '@/components/ui/input';
import { Eye, EyeOff } from 'lucide-vue-next';
import { ref, computed } from 'vue';

interface Props {
    id?: string;
    modelValue: string;
    placeholder?: string;
    class?: string;
    autocomplete?: string;
    required?: boolean;
    autofocus?: boolean;
    tabindex?: number;
    readonly?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    placeholder: 'Kata sandi',
    autocomplete: 'current-password',
    required: false,
    autofocus: false,
    readonly: false,
});

const emit = defineEmits<{
    'update:modelValue': [value: string];
}>();

const inputValue = computed({
    get: () => props.modelValue,
    set: (value: string) => emit('update:modelValue', value)
});

const showPassword = ref(false);

const inputRef = ref<HTMLInputElement | null>(null);

const togglePasswordVisibility = () => {
    showPassword.value = !showPassword.value;
    // Keep focus on input after toggle
    setTimeout(() => {
        if (inputRef.value) {
            inputRef.value.focus();
        }
    }, 0);
};

// Remove updateValue function since we'll use v-model directly
</script>

<template>
    <div class="relative">
        <Input
            ref="inputRef"
            :id="id"
            :type="showPassword ? 'text' : 'password'"
            v-model="inputValue"
            :placeholder="placeholder"
            :class="`pr-10 ${$props.class || ''}`"
            :autocomplete="autocomplete"
            :required="required"
            :autofocus="autofocus"
            :tabindex="tabindex"
            :readonly="readonly"
        />
        <button
            type="button"
            class="absolute top-0 right-0 h-full px-3 flex items-center justify-center bg-transparent border-0 cursor-pointer hover:bg-transparent focus:outline-none"
            @click="togglePasswordVisibility"
            tabindex="-1"
        >
            <Eye v-if="!showPassword" class="h-4 w-4 text-muted-foreground" />
            <EyeOff v-else class="h-4 w-4 text-muted-foreground" />
            <span class="sr-only">
                {{ showPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi' }}
            </span>
        </button>
    </div>
</template>
