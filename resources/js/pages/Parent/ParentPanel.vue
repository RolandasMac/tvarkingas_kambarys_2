<template>
    <div class="mx-auto mt-10 max-w-2xl rounded bg-white p-6 shadow">
        <h1 class="mb-6 text-2xl font-bold">Parents panel</h1>

        <!-- Vaikų sąrašas -->
        <div class="mb-8">
            <!-- <div>Gaidys{{ children }}</div> -->
            <h2 class="mb-4 text-xl font-semibold">Childrens</h2>
            <ul v-if="children?.length">
                <li v-for="child in children" :key="child.id" class="mb-2 rounded border bg-gray-100 p-3">
                    <strong>{{ child.name }}</strong> – {{ child.email }}
                </li>
            </ul>
            <p v-else class="text-gray-600">No childrens.</p>
        </div>

        <!-- Pridėti vaiką forma -->
        <div>
            <h2 class="mb-4 text-xl font-semibold">Add child</h2>

            <NavLink
                :href="route('show-add-child')"
                :active="route().current('show-add-child')"
                class="flex h-10 items-center justify-center rounded bg-blue-600 px-4 text-white"
            >
                Add
            </NavLink>
        </div>
        <!-- Žiūrėti vaikų logus forma -->
        <div class="mt-8">
            <h2 class="mb-4 text-xl font-semibold">Report</h2>

            <NavLink
                :href="route('show-childrens-logs')"
                :active="route().current('show-childrens-logs')"
                class="flex h-10 items-center justify-center rounded bg-blue-600 px-4 text-white"
            >
                Show report
            </NavLink>
        </div>
    </div>
</template>

<script setup>
import NavLink from '@/components/NavLink.vue';
import Layout from '@/layouts/AppLayout.vue';
defineOptions({
    layout: Layout,
});
const props = defineProps({
    children: Array,
    errors: Object,
});

// const form = useForm({
//     child_email: '',
//     child_password: '',
// });
</script>
