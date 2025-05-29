<template>
    <div>
        <!-- <Head title="Vartotojų valdymas" />

        <AdminLayout>
            <template #header>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">Vartotojų valdymas</h2>
            </template> -->
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

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white p-6 shadow-xl sm:rounded-lg">
                    <div v-if="flashSuccess" class="relative mb-4 rounded border border-green-400 bg-green-100 px-4 py-3 text-green-700" role="alert">
                        {{ flashSuccess }}
                    </div>
                    <div v-if="flashError" class="relative mb-4 rounded border border-red-400 bg-red-100 px-4 py-3 text-red-700" role="alert">
                        {{ flashError }}
                    </div>

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Vardas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">El. paštas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Statusas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Veiksmai</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <tr v-for="user in users" :key="user.id">
                                <td class="whitespace-nowrap px-6 py-4">{{ user.name }}</td>
                                <td class="whitespace-nowrap px-6 py-4">{{ user.email }}</td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <span
                                        :class="{ 'bg-red-100 text-red-800': user.is_blocked, 'bg-green-100 text-green-800': !user.is_blocked }"
                                        class="inline-flex rounded-full px-2 text-xs font-semibold leading-5"
                                    >
                                        {{ user.is_blocked ? 'Blokuotas' : 'Aktyvus' }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm font-medium">
                                    <button
                                        @click="toggleBlockUser(user.id)"
                                        :disabled="user.id === $page.props.auth.user.id"
                                        :class="{
                                            'text-indigo-600 hover:text-indigo-900': !user.is_blocked,
                                            'cursor-not-allowed text-gray-400': user.id === $page.props.auth.user.id,
                                            'text-orange-600 hover:text-orange-900': user.is_blocked && user.id !== $page.props.auth.user.id,
                                        }"
                                        class="mr-2"
                                    >
                                        {{ user.is_blocked ? 'Atblokuoti' : 'Blokuoti' }}
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- </AdminLayout> -->
    </div>
</template>

<script setup>
import Layout from '@/layouts/AppLayout.vue';
import { router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineOptions({
    layout: Layout,
});
const props = defineProps({
    users: Array,
    roles: Array,
    flashSuccess: String,
    flashError: String,
});
const message = ref('');
const form = useForm({
    name: '',
    role: '',
    user_id: '',
});

const assignRole = () => {
    form.post(route('assign-role'), {
        onFinish: () => {
            form.reset();
        },
    });
};
const toggleBlockUser = (userId) => {
    if (confirm('Ar tikrai norite pakeisti šio vartotojo blokavimo statusą?')) {
        router.post(route('toggleBlock'), { userId: userId });
    }
};
</script>
