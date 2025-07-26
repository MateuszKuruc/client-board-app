<script setup lang="ts">
import Card from '@/components/volt/Card.vue';

const { value } = defineProps<{
    heading: string;
    subheading?: string;
    value?: number;
    secondValue?: number;
    percentage?: number;
    summary?: number;
    plainNumber?: number;
}>();
</script>

<template>
    <Card class="flex h-60 justify-center border text-center">
        <template #title>{{ heading }}</template>
        <template #subtitle>{{ subheading }}</template>
        <template #content>
            <div class="flex flex-col gap-2">
                <p v-if="value !== null && value !== undefined" class="text-3xl font-bold text-indigo-600">{{ value > 0 ? `${value} zł` : '-' }}</p>
                <p v-if="percentage" class="text-3xl font-bold">
                    <span :class="percentage >= 0 ? 'text-green-600' : 'text-red-600'"> {{ percentage }} % </span>
                </p>
                <p v-if="percentage === null" class="text-3xl font-bold">-</p>
                <p v-if="percentage === 0" class="text-3xl font-bold">0 %</p>
                <p v-if="summary" class="text-3xl font-bold">
                    <span :class="summary >= 0 ? 'text-green-600' : 'text-red-600'"> {{ summary > 0 ? `+ ${summary}` : `- ${summary}` }} zł </span>
                </p>
                <p v-if="summary === 0" class="text-3xl font-bold">0 zł</p>
                <p v-if="secondValue">
                    Pozostało do zapłaty: <span class="text-red-600">{{ secondValue }} zł</span>
                </p>
                <p v-if="plainNumber" class="text-3xl font-bold text-indigo-600">{{ plainNumber }}</p>
            </div>
        </template>
    </Card>
</template>
