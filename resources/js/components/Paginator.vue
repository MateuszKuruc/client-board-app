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
    <div class="flex justify-center gap-1 mt-4 flex-wrap">
        <!-- Previous -->
        <Link
            :href="prevLink.url || ''"
            :class="[
        'px-3 py-1 border rounded text-sm',
        prevLink.url
          ? 'bg-white text-gray-700 hover:bg-rose-100'
          : 'bg-gray-200 text-gray-400 cursor-not-allowed pointer-events-none',
      ]"
            v-html="'&laquo; Poprzednia'"
        />

        <!-- Page Links with Ellipsis -->
        <template v-for="(link, index) in pageLinks" :key="link.label + link.url">
            <!-- Add ellipsis if gap from previous page -->
            <span
                v-if="
          index > 0 &&
          Number(link.label) - Number(pageLinks[index - 1].label) > 1
        "
                class="px-2 text-gray-400"
            >...</span
            >

            <Link
                :href="link.url || ''"
                :class="[
          'px-3 py-1 border rounded text-sm',
          link.active
            ? 'bg-rose-600 text-white'
            : 'bg-white text-gray-700 hover:bg-rose-100',
        ]"
                v-html="link.label"
            />
        </template>

        <!-- Next -->
        <Link
            :href="nextLink.url || ''"
            :class="[
        'px-3 py-1 border rounded text-sm',
        nextLink.url
          ? 'bg-white text-gray-700 hover:bg-rose-100'
          : 'bg-gray-200 text-gray-400 cursor-not-allowed pointer-events-none',
      ]"
            v-html="'Następna &raquo;'"
        />
    </div>
</template>
