<template>
    <div>
        <Head title="Surūšiuotas kambarių sąrašas" />

        <div class="p-6">
            <h1 class="mb-6 text-3xl font-bold">All rooms logs</h1>
            <div>
                <!-- Export to csv -->
                <div class="mb-4">
                    <a :href="route('exportRoomsLogsCsv')" class="rounded bg-green-500 px-4 py-2 text-white hover:bg-green-600">
                        Eksportuoti vartotojus (CSV)
                    </a>
                </div>
            </div>

            <div v-if="rooms && rooms.length">
                <div v-for="room in rooms" :key="room.id" class="mb-8 rounded-lg border border-gray-200 bg-white p-6 shadow-md">
                    <div v-if="room.user && room.user.parent" class="mb-4">
                        <p class="text-lg font-semibold text-gray-700">
                            Parent: <span class="text-blue-600">{{ room.user.parent.name }}</span>
                        </p>
                    </div>
                    <div v-else class="mb-4">
                        <p class="text-lg text-gray-500">Parent not set</p>
                    </div>

                    <div class="mb-4">
                        <p class="text-base text-gray-700">
                            Child (Romm owner):
                            <span class="font-medium text-purple-600">{{ room.user ? room.user.name : 'Nenurodytas' }}</span>
                        </p>
                    </div>

                    <h2 class="mb-3 text-2xl font-bold text-gray-900">
                        Log comment: <span class="text-green-700">{{ room.comment }}</span>
                    </h2>

                    <div v-if="room.image_path" class="mb-4">
                        <img
                            :src="'/storage/' + room.image_path"
                            :alt="room.name + ' nuotrauka'"
                            class="h-auto max-w-md rounded-lg border border-gray-300 shadow-inner"
                        />
                    </div>

                    <div v-if="room.analysis_summary" class="mt-4 rounded-lg border border-gray-200 bg-gray-50 p-4">
                        <p class="text-lg font-semibold text-gray-800">
                            Result:
                            <span :class="{ 'text-green-600': room.analysis_summary.is_tidy, 'text-red-600': !room.analysis_summary.is_tidy }">
                                {{ room.analysis_summary.overall_status }}
                            </span>
                        </p>
                        <p v-if="room.analysis_summary.debug_messiness_score !== undefined" class="mt-1 text-sm text-gray-600">
                            Score of messiness: {{ room.analysis_summary.debug_messiness_score }}
                        </p>

                        <div v-if="room.analysis_summary.suggestions && room.analysis_summary.suggestions.length" class="mt-3">
                            <h3 class="text-md font-medium text-gray-700">Reviews:</h3>
                            <ul class="list-disc pl-5 text-sm text-gray-700">
                                <li v-for="(suggestion, sIdx) in room.analysis_summary.suggestions" :key="sIdx">
                                    {{ suggestion }}
                                </li>
                            </ul>
                        </div>

                        <div v-if="room.analysis_summary.messy_items && room.analysis_summary.messy_items.length" class="mt-3">
                            <h3 class="text-md font-medium text-gray-700">Found items:</h3>
                            <ul class="list-disc pl-5 text-sm text-gray-700">
                                <li v-for="(item, iIdx) in room.analysis_summary.messy_items" :key="iIdx">
                                    {{ item }}
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div v-else class="mt-4 rounded-lg border border-yellow-200 bg-yellow-50 p-4 text-yellow-800">
                        <p>Room is not analyzed.</p>
                    </div>

                    <p v-if="room.analyzed_at" class="mt-4 text-sm text-gray-500">
                        Last analysis: {{ new Date(room.analyzed_at).toLocaleString('lt-LT') }}
                    </p>
                </div>
            </div>
            <div v-else>
                <p class="text-center text-lg text-gray-500">Rooms not found.</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import Layout from '@/layouts/AppLayout.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
defineOptions({
    layout: Layout,
});
const page = usePage();
const form = useForm({
    user_id: 1,
    photo: '',
    time_of_day: 'vakaras',
    comment: 'Gaidiškas kambarys',
});
const rooms = computed(() => page.props.rooms);
const handleFileUpload = (event) => {
    form.photo = event.target.files[0];
};
const sendPhoto = () => {
    console.log(form.photo);
    form.post(route('rooms.store'), {
        forceFormData: true,
        onSuccess: () => form.reset(),
    });
};
const sendPhotoToAI = () => {
    console.log(form.photo);
    form.post(route('rooms.analyze'), {
        forceFormData: true,
        onSuccess: () => form.reset(),
    });
};
defineProps({
    rooms: Array,
});
</script>
