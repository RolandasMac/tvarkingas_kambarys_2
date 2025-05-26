<template>
    <AppLayout title="Naujas kambarys">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Sukurti naują kambarį</h2>
        </template>

        <div class="px-4 py-6">
            <form @submit.prevent="submit" class="max-w-md space-y-4">
                <div>
                    <label for="name" class="block font-medium text-gray-700">Pavadinimas</label>
                    <input id="name" v-model="form.name" type="text" class="mt-1 w-full rounded border-gray-300 shadow-sm" />
                </div>

                <div>
                    <label for="description" class="block font-medium text-gray-700">Aprašymas</label>
                    <textarea id="description" v-model="form.description" rows="3" class="mt-1 w-full rounded border-gray-300 shadow-sm" />
                </div>

                <button type="submit" class="rounded bg-green-600 px-4 py-2 text-white hover:bg-green-700">Išsaugoti</button>
            </form>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    description: '',
});

function submit() {
    form.post(route('rooms.store'));
}
</script>
