<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const { data } = defineProps({
    data: Object,
});

const prevLink = computed(() => data.links[0]);
const nextLink = computed(() => data.links[data.links.length - 1]);

const currentPage = data.current_page;
const lastPage = data.last_page;

const pageLinks = computed(() => {
    const pagesToShow = new Set();

    for (let i = 1; i <= 3 && i <= lastPage; i++) {
        pagesToShow.add(i);
    }

    for (let i = currentPage - 1; i <= currentPage + 1; i++) {
        if (i > 1 && i < lastPage) pagesToShow.add(i);
    }

    pagesToShow.add(lastPage);

    const rawLinks = data.links.slice(1, data.links.length - 1);

    const filtered = rawLinks.filter((link) => {
        const pageNum = Number(link.label);
        return pagesToShow.has(pageNum);
    });

    return filtered;
});
</script>

<template>
    <div class="mt-4 flex flex-wrap justify-center gap-1">
        <!-- Previous -->
        <Link
            :href="prevLink.url || ''"
            :class="[
                'rounded border px-3 py-1 text-sm',
                prevLink.url
                    ? 'bg-white text-gray-700 hover:bg-amber-100 dark:bg-gray-600 dark:text-gray-50 dark:hover:bg-gray-700'
                    : 'pointer-events-none cursor-not-allowed bg-gray-200 text-gray-400 dark:bg-gray-900',
            ]"
            v-html="'&laquo; Poprzednia'"
        />

        <!-- Page Links with Ellipsis -->
        <template v-for="(link, index) in pageLinks" :key="link.label + link.url">
            <!-- Add ellipsis if gap from previous page -->
            <span v-if="index > 0 && Number(link.label) - Number(pageLinks[index - 1].label) > 1" class="px-2 text-gray-400">...</span>

            <Link
                :href="link.url || ''"
                :class="[
                    'rounded border px-3 py-1 text-sm',
                    link.active
                        ? 'bg-amber-600 text-white'
                        : 'bg-white text-gray-700 hover:bg-amber-100 dark:bg-gray-700 dark:text-gray-50 dark:hover:bg-gray-800',
                ]"
                v-html="link.label"
            />
        </template>

        <!-- Next -->
        <Link
            :href="nextLink.url || ''"
            :class="[
                'rounded border px-3 py-1 text-sm',
                nextLink.url
                    ? 'bg-white text-gray-700 hover:bg-amber-100 dark:bg-gray-600 dark:text-gray-50 dark:hover:bg-gray-700'
                    : 'pointer-events-none cursor-not-allowed bg-gray-200 text-gray-400 dark:bg-gray-900',
            ]"
            v-html="'Następna &raquo;'"
        />
    </div>
</template>
