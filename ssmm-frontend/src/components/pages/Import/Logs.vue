<template>
    <div class="mb-6">

        <input type="text" id="first_name" placeholder="Search by user" v-model="search"
            class="bg-neutral-secondary-medium border w-1/2 border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block  px-3 py-2.5 shadow-xs placeholder:text-body placeholder:opacity-40" />
    </div>

    <div class="relative overflow-x-auto bg-neutral-primary-soft shadow-xs rounded-base border border-default">
        <table class="w-full text-sm text-left rtl:text-right text-body">
            <thead class="bg-neutral-secondary-soft border-b border-default">
                <tr>
                    <th scope="col" class="px-6 py-3 font-medium">
                        Filename
                    </th>
                    <th scope="col" class="px-6 py-3 font-medium">
                        Uploaded By
                    </th>
                    <th scope="col" class="px-6 py-3 font-medium">
                        Summary Logs
                    </th>
                    <th scope="col" class="px-6 py-3 font-medium">
                        Date Uploaded
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in logs" :key="item"
                    class="odd:bg-neutral-primary even:bg-neutral-secondary-soft border-b border-default">
                    <th scope="row" class="px-6 py-4 font-medium text-heading whitespace-nowrap">
                        {{ item.filename }}
                    </th>
                    <td class="px-6 py-4">
                        {{ item.uploaded_by }}

                    </td>
                    <td class="px-6 py-4">
                        <p>Total rows processed: {{ item.summary_logs.total_rows }}</p>
                        <p>Total successful service requests created: {{ item.summary_logs.successful_requests }}</p>
                        <p>Total new students created: {{ item.summary_logs.new_students }}</p>
                        <p>Total skipped rows with reasons:</p>
                        <div v-for="value in item.summary_logs.skipped_rows" :key="value"
                            class=" mb-2 p-2 bg-neutral-secondary-medium rounded">
                            <p>Row: <span>{{ value.row }}</span></p>
                            <p>Reason: <span>{{ value.reason }}</span></p>
                        </div>



                    </td>
                    <td class="px-6 py-4">
                        {{ item.date_uploaded }}

                    </td>

                </tr>
                <tr v-if="!logs.length">
                    <td colspan="4" class="text-center py-4">
                        No logs found.
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
</template>

<script setup>
import axios from '@/plugin/axios';
import { onMounted, ref, watch } from 'vue';
import _ from 'lodash';

const logs = ref([]);
const search = ref('');

const getLogs = _.debounce(async () => {
    try {
        const res = await axios.get('/logs', {
            params: {
                search: search.value
            }
        })
        logs.value = res.data.data
    } catch (err) {
        alert('An error occurred while fetching logs.')
    }
}, 300);

watch(search, () => {
    getLogs()
})

onMounted(() => {
    getLogs()
})
</script>

<style lang="scss" scoped></style>