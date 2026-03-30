<template>
    <div class="flex justify-between item-center mb-6">
        <input type="text" id="first_name" placeholder="Search by name or student number" v-model="search"
            class="bg-neutral-secondary-medium border w-1/2 border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block  px-3 py-2.5 shadow-xs placeholder:text-body" />

        <button type="button" @click="onAdd"
            class="text-white cursor-pointer bg-brand box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2 focus:outline-none">Add
            Student</button>
    </div>
    <div class="relative overflow-x-auto bg-neutral-primary-soft shadow-xs rounded-base border border-default">
        <table class="w-full text-sm text-left rtl:text-right text-body">
            <thead class="bg-neutral-secondary-soft border-b border-default">
                <tr>
                    <th scope="col" class="px-6 py-3 font-medium">
                        Student Number
                    </th>
                    <th scope="col" class="px-6 py-3 font-medium">
                        Student Name
                    </th>
                    <th scope="col" class="px-6 py-3 font-medium">
                        Grade Level
                    </th>
                    <th scope="col" class="px-6 py-3 font-medium">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3 font-medium">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in students" :key="item"
                    class="odd:bg-neutral-primary even:bg-neutral-secondary-soft border-b border-default">
                    <th scope="row" class="px-6 py-4 font-medium text-heading whitespace-nowrap">
                        {{ item.student_number }}
                    </th>
                    <td class="px-6 py-4">
                        {{ item.full_name }}

                    </td>
                    <td class="px-6 py-4">
                        {{ item.grade_level }}

                    </td>
                    <td class="px-6 py-4">
                        {{ item.status }}

                    </td>
                    <td class="px-6 py-4">
                        <button @click="onEdit(item)"
                            class="font-medium cursor-pointer text-fg-brand hover:underline">Edit</button> |
                        <button @click="onDelete(item)"
                            class="font-medium cursor-pointer text-red-500 hover:underline">Delete</button>

                    </td>
                </tr>
                <tr v-if="!students.length">
                    <td colspan="5" class="text-center py-4">
                        No students found.
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
    <!-- <div class="mt-4 flex items-center justify-between gap-1">
        <div class="flex gap-1">
            <button v-for="link in meta?.links || []" :key="link.label" :class="[
                'px-3 py-1 rounded border',
                link.active ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700',
                !link.url ? 'cursor-not-allowed opacity-50' : 'hover:bg-blue-200'
            ]" :disabled="!link.url" v-html="link.label" @click="goToPage(link.url)" />
        </div>

        <div>
            Showing {{ meta?.from || 0 }} to {{ meta?.to || 0 }} of {{ meta?.total || 0 }} entries
        </div>
    </div> -->


</template>

<script setup>
import axios from '@/plugin/axios';
import router from '@/router';
import { onMounted, ref, watch } from 'vue';
import _ from 'lodash';

const students = ref([]);
const meta = ref(null);
const search = ref('');

watch(search, async (newSearch) => {
    getStudents();
});

const onAdd = () => {
    router.push({ name: 'student_request_form', params: { id: 'create' } });
};
const onEdit = (item) => {
    router.push({ name: 'student_request_form', params: { id: item.id } });
};
const onDelete = async (item) => {
    if (confirm('Are you sure you want to delete this student?')) {
        try {
            await axios.delete(`/students/${item.id}`);
            getStudents();
        } catch (error) {
            console.error('Failed to delete student:', error);
        }
    }
};
const getStudents = _.debounce(async () => {
    try {
        const response = await axios.get('/students', { params: { search: search.value } });
        students.value = response.data.data;
        meta.value = response.data.meta;

    } catch (error) {
        console.error('Failed to fetch students:', error);
    }
}, 300);

onMounted(() => {
    getStudents();
});
</script>

<style lang="scss" scoped></style>