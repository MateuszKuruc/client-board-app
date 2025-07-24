<script setup lang="ts">
import Card from '@/components/volt/Card.vue';

const { value } = defineProps<{
    heading: string;
    subheading?: string;
    value?: number;
    secondValue?: number;
    percentage?: number;
}>();
</script>

<template>
    <Card class="flex h-60 justify-center border text-center">
        <template #title>{{ heading }}</template>
        <template #subtitle>{{ subheading }}</template>
        <template #content>
            <div class="flex flex-col gap-2">
                <p v-if="value" class="text-3xl font-bold text-indigo-600">{{ value }} zł</p>
                <p v-if="percentage" class="text-3xl font-bold">
                    <span :class="percentage > 0 ? 'text-green-500' : 'text-red-500'"> {{ percentage }} % </span>
                </p>
                <p v-if="secondValue">
                    Pozostało do zapłaty: <span class="text-red-600">{{ secondValue }} zł</span>
                </p>
            </div>
        </template>
    </Card>
</template>
