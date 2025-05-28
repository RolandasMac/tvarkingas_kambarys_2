<template>
    <div class="mx-auto max-w-5xl p-6">
        <h1 class="mb-6 text-3xl font-bold text-gray-800">Tvarkingo kambario žurnalas</h1>
        <!-- <p>{{ logs }}</p> -->
        <!-- <SendPhoto /> -->
        <!-- Send phot button -->
        <div>
            <h2 class="mb-4 text-xl font-semibold">Siųsti nuotrauką</h2>

            <NavLink
                :href="route('show-sendPhoto-page')"
                class=":hover:cursor-pointer flex h-10 cursor-pointer items-center justify-center rounded bg-blue-600 px-4 text-white"
            >
                Siųsti nuotrauką
            </NavLink>
        </div>

        <div v-if="logs.length === 0" class="rounded-lg bg-yellow-50 p-4 text-yellow-800 shadow">Nėra įrašų šiuo metu.</div>

        <div v-else class="space-y-4">
            <div v-for="log in logs" :key="log.id" class="rounded-xl bg-white p-5 shadow-md transition hover:shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">{{ log.comment }}</h2>
                        <p class="text-sm text-gray-500">{{ formatDate(log.created_at) }}</p>
                    </div>

                    <span class="rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-700">
                        {{ log.analysis }}
                    </span>
                </div>

                <p v-if="log.description" class="mt-3 text-gray-700">
                    {{ log.description }}
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import NavLink from '@/components/NavLink.vue';

import Layout from '@/layouts/AppLayout.vue';
defineOptions({
    layout: Layout,
});
defineProps({
    // children: {
    //     type: Array,
    //     required: true,
    // },
    logs: {
        type: Array,
        required: true,
    },
});

// Pagalbinė funkcija datų formatavimui
function formatDate(dateStr) {
    const date = new Date(dateStr);
    return date.toLocaleDateString('lt-LT', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
}
</script>

<style scoped></style>
