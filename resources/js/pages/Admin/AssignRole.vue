<template>
    <div class="mx-auto mt-10 max-w-xl rounded-2xl bg-white p-6 shadow-md">
        <h2 class="mb-4 text-2xl font-bold">Priskirti rolę vartotojui</h2>

        <form @submit.prevent="assignRole">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Vartotojas</label>
                <select v-model="form.user_id" class="w-full rounded border p-2">
                    <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }} ({{ user.email }})</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Rolė</label>
                <select v-model="form.role" class="w-full rounded border p-2">
                    <option v-for="role in roles" :key="role" :value="role">{{ role }}</option>
                </select>
            </div>

            <button type="submit" class="rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600">Priskirti rolę</button>
        </form>

        <div v-if="message" class="mt-4 font-semibold text-green-600">
            {{ message }}
        </div>
    </div>
</template>

<script setup>
import Layout from '@/layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
defineOptions({
    layout: Layout,
});
const props = defineProps({
    data: Object,
});

const users = ref(props.data.original.users);
const roles = ref(props.data.original.roles);
const message = ref('');

// const form = ref({
//     user_id: null,
//     role: null,
// });

const form = useForm({
    name: '',
    role: '',
    user_id: '',
});
// onMounted(async () => {
//     // const res = await fetch('/admin/users-roles-data');
//     // const data = await res.json();
//     users.value = data.users;
//     roles.value = data.roles;
// });

const assignRole = () => {
    // router.post('/admin/assign-role', form.value, {
    //     onSuccess: () => {
    //         message.value = 'Rolė priskirta sėkmingai!';
    //     },
    //     onError: (errors) => {
    //         message.value = 'Įvyko klaida!';
    //         console.error(errors);
    //     },
    // });
    form.post(route('assign-role'), {
        onFinish: () => {
            form.reset();

            // passwordInput.value.focus();
        },
    });

    // alert('Gaidys');
};
</script>
