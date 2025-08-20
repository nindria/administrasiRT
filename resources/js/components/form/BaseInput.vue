<template>
    <div class="grid">
        <Label class="mb-2" :for="name">{{ label }}</Label>
        <Input
            :id="name"
            :name="name"
            :type="$attrs.type"
            class="mt-1 block w-full"
            v-model="inputValue"
            :disabled="$attrs.disabled"
            :onclick="$attrs.onclick || undefined"
            oninput="$attrs.oninput || undefined" 
            :inputmode="$attrs.inputmode || undefined"
            :placeholder="$attrs.placeholder"
            @blur="$attrs.onblur || undefined"
        />
        <InputError :message="message" />
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import InputError from '../InputError.vue';
import Input from '../ui/input/Input.vue';
import Label from '../ui/label/Label.vue';

defineOptions({
    inheritAttrs: false,
});

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
        type: String,
        default: '',
    },
});

const emit = defineEmits(['update:value']);

const inputValue = computed({
    get() {
        return props.value;
    },
    set(value) {
        emit('update:value', value);
    },
});
</script>
