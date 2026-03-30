<template>
    <h2 class="text-2xl font-bold text-heading">Student Form</h2>

    <form @submit.prevent="onSubmit" class="mt-4">
        <div class="mb-6">
            <label for="student_number" class="block mb-2.5 text-sm font-medium text-heading">Student Number</label>
            <input type="text" v-model="form.student_number" disabled
                class="bg-neutral-secondary-medium border w-1/2 border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block  px-3 py-2.5 shadow-xs placeholder:text-body"
                placeholder="Student Number" required />
        </div>
        <div class="grid gap-6 mb-6 md:grid-cols-2">
            <div>
                <label for="first_name" class="block mb-2.5 text-sm font-medium text-heading">First name</label>
                <input type="text" id="first_name" v-model="form.first_name"
                    class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body"
                    placeholder="John" />
            </div>
            <div>
                <label for="last_name" class="block mb-2.5 text-sm font-medium text-heading">Last name</label>
                <input type="text" id="last_name" v-model="form.last_name"
                    class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body"
                    placeholder="Doe" />
            </div>

        </div>
        <div class="mb-6">
            <label for="email" class="block mb-2.5 text-sm font-medium text-heading">Email address</label>
            <input type="email" id="email" v-model="form.email"
                class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body"
                placeholder="john.doe@company.com" />
        </div>
        <div class="mb-6">
            <label for="grade_level" class="block mb-2.5 text-sm font-medium text-heading">Grade Level</label>
            <input type="text" id="grade_level" v-model="form.grade_level"
                class="bg-neutral-secondary-medium border w-1/2 border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block  px-3 py-2.5 shadow-xs placeholder:text-body"
                placeholder="Grade Level" />
        </div>
        <div class="flex items-start mb-6">
            <div class="flex items-center h-5">
                <input id="status" type="checkbox" v-model="form.status"
                    class="w-4 h-4 border border-default-medium rounded-xs bg-neutral-secondary-medium focus:ring-2 focus:ring-brand-soft" />
            </div>
            <label for="status" class="ms-2 text-sm font-medium text-heading">Status</label>
        </div>
        <button type="submit"
            class="text-white bg-brand box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">Submit</button>
    </form>



</template>

<script setup>
import { onMounted, reactive, ref } from 'vue';
import axios from '@/plugin/axios';
const isAdd = ref(false);
function generateRandom8DigitCode() {
    // Generate a random number between 0 and 9999999 (7 digits)
    const randomNum = Math.floor(Math.random() * 10000000);

    // Pad with leading zeros to ensure 7 digits
    const code = 'S' + randomNum.toString().padStart(7, '0');

    return code;
}

const form = reactive({
    student_number: '',
    first_name: '',
    last_name: '',
    email: '',
    grade_level: '',
    status: false,
});
const onSubmit = async () => {

    try {
        const response = await axios.post('/students', form);
    } catch (error) {
        console.error('Failed to submit form:', error);
    }


};
onMounted(() => {
    form.student_number = generateRandom8DigitCode();
});

</script>

<style lang="scss" scoped></style>