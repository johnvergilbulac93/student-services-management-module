<template>
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-2xl shadow-md w-full max-w-md">

            <h2 class="text-2xl font-bold text-center mb-6">Login</h2>

            <form @submit.prevent="login">
                <!-- Email -->
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Email</label>
                    <input v-model="form.email" type="email" placeholder="Enter your email"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label class="block text-sm font-medium mb-1">Password</label>
                    <input v-model="form.password" type="password" placeholder="Enter your password"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Button -->
                <button type="submit"
                    class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition">
                    Sign In
                </button>
            </form>

        </div>
    </div>
</template>

<script setup>
import axios from '@/plugin/axios';
import { reactive } from 'vue';
import router from '@/router';
const form = reactive({
    email: '',
    password: '',
});

const login = async () => {
    try {
        const response = await axios.post('/login', form);
        localStorage.setItem('APP_TOKEN', response.data.access_token);
        router.push('/student');
    } catch (error) {
        console.error('Login failed:', error);
    }
};

</script>

<style lang="scss" scoped></style>