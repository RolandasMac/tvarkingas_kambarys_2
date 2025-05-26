<template>
    <AppLayout title="Kambariai">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Kambariai</h2>
        </template>

        <div class="px-4 py-6">
            <Link class="mb-4 inline-block rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700" :href="route('rooms.create')">
                + Naujas kambarys
            </Link>

            <div class="grid grid-cols-1 gap-4">
                <div v-for="room in rooms" :key="room.id" class="rounded border p-4 shadow hover:bg-gray-50">
                    <h3 class="text-lg font-bold">{{ room.time_of_day }}</h3>
                    <p class="text-sm text-gray-600">{{ room.comment }}</p>

                    <Link :href="route('rooms.show', room.id)" class="mt-2 inline-block text-blue-600 hover:underline"> Peržiūrėti </Link>
                </div>
            </div>

            <form @submit.prevent="sendPhoto" method="POST">
                <input @change="handleFileUpload" type="file" name="photo" id="photo" class="mb-4 block w-full rounded border-gray-300 shadow-sm" />
                <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Siųsti</button>
            </form>
            <form @submit.prevent="sendPhotoToAI" method="POST">
                <input @change="handleFileUpload" type="file" name="photo" id="photo" class="mb-4 block w-full rounded border-gray-300 shadow-sm" />
                <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Siųsti analizei</button>
            </form>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    user_id: 1,
    photo: '',
    time_of_day: 'vakaras',
    comment: 'Gaidiškas kambarys',
});

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
