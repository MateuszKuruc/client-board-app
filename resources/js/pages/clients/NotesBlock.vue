<script setup lang="ts">
import SectionHeading from '@/components/SectionHeading.vue';
import StyledLink from '@/components/StyledLink.vue';
import { Plus } from 'lucide-vue-next';

const props = withDefaults(defineProps<{
    subheading?: string;
    href: string;
}>(), {
    subheading: 'Sprawdź listę dodatkowych notatek'
});
</script>

<template>
    <div>
        <div class="flex items-center justify-between">
            <SectionHeading heading="Notatki" :subheading="subheading" />
            <StyledLink variant="outline" :href="href">
                <Plus class="w-5 text-gray-400" />
                Dodaj notatkę
            </StyledLink>
        </div>
    </div>
</template>
