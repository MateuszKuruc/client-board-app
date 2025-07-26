<script setup lang="ts">
import { SidebarGroup, SidebarGroupContent, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { useNavigation } from '@/composables/useNavigation';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';

const { isActive } = useNavigation();

interface Props {
    items: NavItem[];
    class?: string;
}

defineProps<Props>();
</script>

<template>
    <SidebarGroup :class="`group-data-[collapsible=icon]:p-0 ${$props.class || ''}`">
        <SidebarGroupContent>
            <SidebarMenu>
                <SidebarMenuItem v-for="item in items" :key="item.title">
                    <SidebarMenuButton v-if="!item.children" as-child :is-active="isActive(item)" :tooltip="item.title" size="lg">
                        <Link :href="item.href">
                            <component :is="item.icon" />
                            <span>{{ item.title }}</span>
                        </Link>
                    </SidebarMenuButton>

                    <div v-else>
                        <SidebarMenuButton v-if="item.href" as-child :is-active="isActive(item)" :tooltip="item.title" size="lg">
                            <Link :href="item.href">
                                <component :is="item.icon" />
                                <span>{{ item.title }}</span>
                            </Link>
                        </SidebarMenuButton>

                        <div v-else class="flex items-center gap-2 py-2">
                            <component :is="item.icon" />
                            <span>{{ item.title }}</span>
                        </div>

                        <SidebarMenu>
                            <SidebarMenuItem v-for="child in item.children" :key="child.title" class="pl-6">
                                <SidebarMenuButton as-child :is-active="isActive(child)" :tooltip="child.title" size="lg">
                                    <Link :href="child.href">
                                        <component :is="child.icon" />
                                        <span>{{ child.title }}</span>
                                    </Link>
                                </SidebarMenuButton>
                            </SidebarMenuItem>
                        </SidebarMenu>
                    </div>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarGroupContent>
    </SidebarGroup>
</template>
