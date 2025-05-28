<template>
    <form @submit.prevent="sendPhoto" method="POST" class="space-y-4">
        <!-- Room's photo -->
        <input @change="handleFileUpload" type="file" name="photo" id="photo" class="mb-4 block w-full rounded border-gray-300 shadow-sm" />

        <!-- Select: Rytas / Vakaras -->
        <div>
            <label for="time" class="block text-sm font-medium text-gray-700">Laikas</label>
            <select id="time" v-model="form.time" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <option value="" disabled>Pasirinkite laiką</option>
                <option value="rytas">Rytas</option>
                <option value="vakaras">Vakaras</option>
            </select>
        </div>

        <!-- Komentaras -->
        <div>
            <label for="comment" class="block text-sm font-medium text-gray-700">Komentaras</label>
            <textarea
                id="comment"
                v-model="form.comment"
                rows="3"
                placeholder="Įrašykite komentarą..."
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
            ></textarea>
        </div>

        <!-- Submit -->
        <div>
            <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Siųsti</button>
        </div>
        <!-- <div>{{ $page.props.auth?.user }}</div> -->
    </form>
</template>

<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
// import NavLink from '@/components/NavLink.vue';
import Layout from '@/layouts/AppLayout.vue';
defineOptions({
    layout: Layout,
});
const page = usePage();
const form = useForm({
    user_id: page.props.auth?.user.id,
    photo: '',
    time_of_day: 'vakaras',
    comment: 'Gaidiškas kambarys',
});

const handleFileUpload = (event) => {
    form.photo = event.target.files[0];
};
const sendPhoto = () => {
    // console.log(form.photo);
    // alert('Gaidys');
    form.post(route('send_photo'), {
        forceFormData: true,
        onSuccess: () => form.reset(),
    });
};
</script>
