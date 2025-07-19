<script setup lang="ts">
import Tag from '@/components/volt/Tag.vue';
import { Plus } from 'lucide-vue-next';
import StyledLink from '@/components/StyledLink.vue';
</script>

<template>
    <div class="flex items-center justify-between">
        <div class="flex w-[600px] flex-wrap gap-2">
            <Tag value="VIP" severity="primary" />
            <Tag value="Problematyczny" severity="secondary" />
            <Tag value="Problemy z płatnościami" severity="danger" />
            <Tag value="Długoterminowa współpraca" severity="info" />
            <Tag value="Kontakt przez telefon" severity="warn" />
            <Tag value="Kontakt przez e-mail" severity="danger" />
            <Tag value="Testowy" severity="contrast" />
        </div>
        <StyledLink href="#" variant="outline" >
            <Plus class="w-5 text-gray-400" />
            Edytuj tagi</StyledLink
        >
    </div>
</template>
