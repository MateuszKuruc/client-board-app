<script setup lang="ts">
import Card from '@/components/volt/Card.vue';

const { value } = defineProps<{
    heading: string;
    subheading?: string;
    value: number;
    secondValue?: number;
}>();
</script>

<template>
    <Card class="flex h-60 justify-center border text-center">
        <template #title>{{ heading }}</template>
        <template #subtitle>{{ subheading }}</template>
        <template #content>
            <div class="flex flex-col gap-2">
                <p class="text-3xl font-bold text-indigo-600">{{ value }} zł</p>
                <p v-if="secondValue">
                    Pozostało do zapłaty: <span class="text-red-600">{{ secondValue }} zł</span>
                </p>
            </div>
        </template>
    </Card>
</template>
