<template>
    <AppLayout :title="res.room.id">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Kambarys: {{ res.room.id }}</h2>
        </template>

        <div class="px-4 py-6">
            <p class="mb-4 text-gray-700">{{ res.room.comment }}</p>

            <Link href="/rooms" class="inline-block text-sm text-blue-600 hover:underline"> ← Grįžti atgal </Link>
            <div class="h-96 w-72 overflow-hidden">
                <img :src="res.photo_url" :alt="res.photo_url" class="h-full w-full object-cover" />
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    res: Object,
});
</script>
