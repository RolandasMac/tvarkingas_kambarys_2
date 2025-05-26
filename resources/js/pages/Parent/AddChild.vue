<template>
    <div class="mx-auto mt-10 max-w-md rounded bg-white p-6 shadow">
        <h1 class="mb-4 text-2xl">Pridėti vaiką</h1>
        <form @submit.prevent="submit">
            <div class="mb-4">
                <label for="child_email" class="mb-1 block font-medium">Vaiko el. paštas</label>
                <input v-model="form.child_email" type="email" id="child_email" name="child_email" class="w-full rounded border px-3 py-2" />
                <p v-if="errors.child_email" class="mt-1 text-sm text-red-600">{{ errors.child_email }}</p>
            </div>

            <div class="mb-4">
                <label for="child_password" class="mb-1 block font-medium">Vaiko slaptažodis</label>
                <input
                    v-model="form.child_password"
                    type="password"
                    id="child_password"
                    name="child_password"
                    class="w-full rounded border px-3 py-2"
                />
                <p v-if="errors.child_password" class="mt-1 text-sm text-red-600">{{ errors.child_password }}</p>
            </div>
            <div class="flex justify-between">
                <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Pridėti vaiką</button>
                <NavLink
                    :href="route('parents_panel')"
                    :active="route().current('show-add-child')"
                    class="rounded bg-blue-600 px-4 py-1 text-center text-white hover:bg-blue-700"
                >
                    Atgal
                </NavLink>
            </div>
        </form>
    </div>
</template>

<script setup>
import NavLink from '@/components/NavLink.vue';
import Layout from '@/layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';
defineOptions({
    layout: Layout,
});
const props = defineProps({
    errors: Object,
});

// Inertia form helper
const form = useForm({
    child_email: '',
    child_password: '',
});

function submit() {
    form.post('/parent/add-child');
}
</script>
