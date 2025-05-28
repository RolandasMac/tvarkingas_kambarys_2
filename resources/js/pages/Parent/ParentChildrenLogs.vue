<template>
    <div class="mx-auto max-w-6xl p-6">
        <!-- <div>{{ logs }}</div> -->
        <h1 class="mb-6 text-3xl font-bold text-gray-800">Vaikų kambarių tvarkos žurnalas</h1>

        <div v-if="logs.length === 0" class="rounded-lg bg-yellow-50 p-4 text-yellow-800 shadow">Šiuo metu neturite pridėtų vaikų.</div>

        <div v-else class="space-y-6">
            <div v-for="log in logs" :key="log.user.id" class="rounded-xl bg-white p-5 shadow-md">
                <h2 class="mb-3 text-2xl font-semibold text-blue-800">{{ log?.user.name }}</h2>
                <!-- <div>{{ log.user.room_logs }}</div> -->
                <div v-if="log.user.room_logs.length === 0" class="text-sm text-gray-500">Šis vaikas neturi jokių įrašų.</div>

                <div v-else class="space-y-3">
                    <div v-for="room_log in log.user.room_logs" :key="room_log.id" class="rounded-lg border border-gray-200 p-4 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-lg font-medium text-gray-800">{{ room_log.comment }}</p>
                                <p class="text-sm text-gray-500">{{ formatDate(room_log.created_at) }}</p>
                            </div>

                            <span class="rounded-full bg-green-100 px-3 py-1 text-sm text-green-700">
                                {{ room_log.analysis }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import Layout from '@/layouts/AppLayout.vue';
// import { usePage } from '@inertiajs/vue3';
// import { computed } from 'vue';
defineOptions({
    layout: Layout,
});
const props = defineProps({
    logs: Array,
});
// const page = usePage();
// const children = computed(() => page.props.children);
// Datų formatavimas
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
