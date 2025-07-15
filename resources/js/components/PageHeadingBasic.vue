<script setup lang="ts">
import { Client, Expense, Payment, Project } from '@/types/models';

const { title, data } = defineProps<{
    title: string;
    subtitle: string;
    data?: Project | Payment | Client | Expense;
}>();
</script>

<template>
    <div class="lg:flex lg:items-center lg:justify-between">
        <div class="min-w-0 flex-1">
            <h2 class="text-2xl/7 font-bold text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">{{ title }}</h2>
            <span class="text-gray-600 text-lg font-semibold">{{ subtitle }}</span>
            <div class="mt-1 flex flex-col sm:mt-0 sm:flex-row sm:flex-wrap sm:space-x-6"></div>
        </div>
    </div>
</template>
