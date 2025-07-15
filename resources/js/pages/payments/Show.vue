<script setup lang="ts">
import ActionButtons from '@/components/ActionButtons.vue';
import PageHeadingProject from '@/components/PageHeadingProject.vue';
import SectionHeading from '@/components/SectionHeading.vue';
import TagSection from '@/components/TagSection.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Payment } from '@/types/models';
import { Head } from '@inertiajs/vue3';



const { payment } = defineProps<{
    payment: Payment;
}>();
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: `Płatność nr ${payment.id}`,
        href: `/klienci/${project.client.slug}/projekty/${project.id}/platnosci/${payment.id}`,
    },
];

</script>

<template>
    <Head :title="breadcrumbs[0].title" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-6">
            <div>
                <PageHeadingProject :title="form.name" :project="project" />
                <TagSection />
            </div>

            <div class="flex items-center justify-between">
                <SectionHeading heading="Informacje o projekcie" subheading="Szczegółowe dane projektu" />
                <ActionButtons :isEditing="isEditing" @cancel="cancelEdit" @save="submitEdit" @edit="startEdit" />
            </div>
        </div>
    </AppLayout>
</template>

<style scoped></style>
