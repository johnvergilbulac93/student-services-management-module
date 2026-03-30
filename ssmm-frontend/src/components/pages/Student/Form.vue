<template>
    <h2 class="text-2xl font-bold text-heading">Student Form</h2>

    <form @submit.prevent="route.params.id !== 'create' ? onUpdate() : onCreate()" class="mt-4">
        <div class="mb-6">
            <label for="student_number" class="block mb-2.5 text-sm font-medium text-heading">Student Number</label>
            <input type="text" v-model="form.student_number"
                class="bg-neutral-secondary-medium border w-1/2 border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block  px-3 py-2.5 shadow-xs placeholder:text-body placeholder:opacity-40"
                placeholder="Student Number" required />
        </div>
        <div class="grid gap-6 mb-6 md:grid-cols-2">
            <div>
                <label for="first_name" class="block mb-2.5 text-sm font-medium text-heading">First name</label>
                <input type="text" id="first_name" v-model="form.first_name"
                    class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body placeholder:opacity-40"
                    placeholder="John" />
            </div>
            <div>
                <label for="last_name" class="block mb-2.5 text-sm font-medium text-heading">Last name</label>
                <input type="text" id="last_name" v-model="form.last_name"
                    class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body placeholder:opacity-40"
                    placeholder="Doe" />
            </div>

        </div>
        <div class="mb-6">
            <label for="email" class="block mb-2.5 text-sm font-medium text-heading">Email address</label>
            <input type="email" id="email" v-model="form.email"
                class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body placeholder:opacity-40"
                placeholder="john.doe@company.com" />
        </div>
        <div class="mb-6">
            <label for="grade_level" class="block mb-2.5 text-sm font-medium text-heading">Grade Level</label>
            <input type="text" id="grade_level" v-model="form.grade_level"
                class="bg-neutral-secondary-medium border w-1/2 border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block  px-3 py-2.5 shadow-xs placeholder:text-body placeholder:opacity-40 "
                placeholder="Grade Level" />
        </div>
        <div class="flex items-start mb-6">
            <div class="flex items-center h-5">
                <input id="status" type="checkbox" v-model="form.status"
                    class=" cursor-pointer w-4 h-4 border border-default-medium rounded-xs bg-neutral-secondary-medium focus:ring-2 focus:ring-brand-soft" />
            </div>
            <label for="status" class="cursor-pointer ms-2 text-sm font-medium text-heading">Status</label>
        </div>
        <div class="flex items-start mb-6">
            <div class="flex items-center h-5">
                <input id="is_imported" type="checkbox" v-model="form.is_imported" disabled
                    class="w-4 h-4 border cursor-not-allowed border-default-medium rounded-xs bg-neutral-secondary-medium focus:ring-2 focus:ring-brand-soft" />
            </div>
            <label for="is_imported" class=" cursor-not-allowed ms-2 text-sm font-medium text-heading">Is Import</label>
        </div>
        <div class="flex gap-2">
            <button type="button" @click="onBack"
                class="text-body  bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">Back</button>

            <button type="submit"
                class="text-white  bg-brand box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">Submit</button>
        </div>
    </form>



</template>

<script setup>
import { onMounted, reactive, ref } from 'vue';
import axios from '@/plugin/axios';
import router from '@/router';
import { useRoute } from 'vue-router'

const route = useRoute()

const isAdd = ref(false);

const onBack = () => {
    router.push('/student');
};
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
    is_imported: false,

});

const resetForm = () => {
    form.student_number = '';
    form.first_name = '';
    form.last_name = '';
    form.email = '';
    form.grade_level = '';
    form.status = false;
    form.is_imported = false;
};

const onUpdate = async () => {

    try {
        await axios.put(`/students/${route.params.id}`, form);
        router.push('/student');
        resetForm();
        alert('Student updated successfully!')
    } catch (error) {
        if (error.response?.status === 422) {
            const errors = error.response.data.errors
            let messages = ''

            for (const key in errors) {
                if (errors.hasOwnProperty(key)) {
                    messages += `${errors[key].join(', ')}\n`
                }
            }
            alert(messages)
        } else {
            console.error('Failed to submit form:', error)
        }
    }
};
const onCreate = async () => {

    try {
        await axios.post('/students', form);
        router.push('/student');
        resetForm();
        alert('Student created successfully!')
    } catch (error) {
        if (error.response?.status === 422) {
            const errors = error.response.data.errors
            let messages = ''

            for (const key in errors) {
                if (errors.hasOwnProperty(key)) {
                    messages += `${errors[key].join(', ')}\n`
                }
            }
            alert(messages)
        } else {
            console.error('Failed to submit form:', error)
        }
    }


};

const getStudent = async () => {
    if (route.params.id !== 'create') {
        try {
            const response = await axios.get(`/students/${route.params.id}`);
            const data = response.data.data;
            form.student_number = data.student_number;
            form.first_name = data.first_name;
            form.last_name = data.last_name;
            form.email = data.email;
            form.grade_level = data.grade_level;
            form.status = data.status === 'Active' ? true : false;
            form.is_imported = data.is_imported ? true : false;
        } catch (error) {
            console.error('Failed to fetch student:', error);
        }
    }

};
onMounted(() => {
    getStudent();
});

</script>

<style lang="scss" scoped></style>