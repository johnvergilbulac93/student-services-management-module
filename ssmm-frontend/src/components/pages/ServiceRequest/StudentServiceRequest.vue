<template>
    <div class="flex flex-wrap justify-between item-center mb-6">
        <select id="countries" v-model="filter.status"
            class="block w-[200px] px-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs placeholder:text-body">
            <option value="">Filter Status</option>
            <option value="Pending">Pending</option>
            <option value="Approved">Approved</option>
            <option value="Rejected">Rejected</option>
        </select>

        <div id="date-range-picker" class="flex items-center">
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-body" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 10h16m-8-3V4M7 7V4m10 3V4M5 20h14a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Zm3-7h.01v.01H8V13Zm4 0h.01v.01H12V13Zm4 0h.01v.01H16V13Zm-8 4h.01v.01H8V17Zm4 0h.01v.01H12V17Zm4 0h.01v.01H16V17Z" />
                    </svg>
                </div>
                <input id="datepicker-range-start" name="start" type="date" v-model="filter.date_start"
                    class="block w-full ps-9 pe-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand px-3 py-2.5 shadow-xs placeholder:text-body"
                    placeholder="Select date start">
            </div>
            <span class="mx-4 text-body">-</span>
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-body" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 10h16m-8-3V4M7 7V4m10 3V4M5 20h14a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Zm3-7h.01v.01H8V13Zm4 0h.01v.01H12V13Zm4 0h.01v.01H16V13Zm-8 4h.01v.01H8V17Zm4 0h.01v.01H12V17Zm4 0h.01v.01H16V17Z" />
                    </svg>
                </div>
                <input id="datepicker-range-end" name="end" type="date" v-model="filter.date_end"
                    class="block w-full ps-9 pe-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand px-3 py-2.5 shadow-xs placeholder:text-body"
                    placeholder="Select date end">
            </div>
        </div>

    </div>
    <div class="relative overflow-x-auto bg-neutral-primary-soft shadow-xs rounded-base border border-default">
        <table class="w-full text-sm text-left rtl:text-right text-body">
            <thead class="bg-neutral-secondary-soft border-b border-default">
                <tr>
                    <th scope="col" class="px-6 py-3 font-medium">
                    </th>
                    <th scope="col" class="px-6 py-3 font-medium">
                        Student name
                    </th>
                    <th scope="col" class="px-6 py-3 font-medium">
                        Service Type
                    </th>
                    <th scope="col" class="px-6 py-3 font-medium">
                        Date Requested
                    </th>
                    <th scope="col" class="px-6 py-3 font-medium text-center">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3 font-medium text-center" v-if="isAdmin">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in studentRequests" :key="item"
                    class="odd:bg-neutral-primary even:bg-neutral-secondary-soft border-b border-default">
                    <td class="px-6 py-4">
                        <span v-if="item.status === 'Approved'"
                            class="flex w-3 h-3 me-3 bg-success rounded-full"></span>
                        <span v-if="item.status === 'Pending'" class="flex w-3 h-3 me-3 bg-warning rounded-full"></span>
                        <span v-if="item.status === 'Rejected'" class="flex w-3 h-3 me-3 bg-danger rounded-full"></span>
                    </td>
                    <td scope="row" class="px-6 py-4 font-medium text-heading whitespace-nowrap">
                        {{ item.student.full_name }}
                    </td>
                    <td class="px-6 py-4">
                        {{ item.service_type }}
                    </td>
                    <td class="px-6 py-4">
                        {{ item.date_requested }}
                    </td>
                    <td class="px-6 py-4 flex items-center justify-center">
                        <select id="status" v-model="item.status" @change="onStatusChanged(item)"
                            class="block w-37.5 px-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs placeholder:text-body">
                            <option selected>Change Status</option>
                            <option value="Pending">Pending</option>
                            <option value="Approved">Approved</option>
                            <option value="Rejected">Rejected</option>
                        </select>
                    </td>
                    <td class="px-6 py-4" v-if="isAdmin">
                        <button @click="onDelete(item)"
                            class="font-medium  cursor-pointer text-red-500 hover:underline">Delete</button>
                    </td>
                </tr>
                <tr v-if="!studentRequests.length">
                    <td :colspan="isAdmin ? 5 : 4" class="text-center py-4">
                        No service requests found.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import axiosRequest from '@/plugin/axios';
import { onMounted, reactive, ref, watch, computed } from 'vue';
import { useStorage } from '@vueuse/core';


const type = useStorage('type', null);
const studentRequests = ref([]);
const meta = ref(null)

const search = ref('');
const filter = reactive({
    status: '',
    date_start: '',
    date_end: ''
})

const isAdmin = computed(() => type.value === 'admin');

const onDelete = async (item) => {
    const confirmed = confirm('Are you sure you want to delete this service request?');
    if (!confirmed) return;

    try {
        await axiosRequest.delete(`/student-requests/${item.id}`);
        alert(`Service request for ${item.student.full_name} deleted successfully!`);
        getStudentRequests();
    } catch (error) {
        alert('Failed to delete service request:', error);
    }
};
const onStatusChanged = async (item) => {
    const confirmed = confirm('Are you sure you want to change the status?');
    if (!confirmed) return;

    try {
        await axiosRequest.patch(`/student-requests/${item.id}/status`, {
            status: item.status
        });
        alert(`Status for ${item.student.full_name} changed to ${item.status} successfully!`);
    } catch (error) {
        alert('Failed to change status:', error);
    }
};
const getStudentRequests = async () => {
    try {
        const response = await axiosRequest.get('/student-requests', { params: filter });
        studentRequests.value = response.data.data;
        meta.value = response.data.meta;
    } catch (error) {
        alert('Error fetching service requests:', error);
    }
};


watch(filter, () => {
    getStudentRequests();
}, { deep: true });
onMounted(() => {
    getStudentRequests();
});

</script>

<style lang="scss" scoped></style>