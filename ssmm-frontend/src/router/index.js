import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import AppLayout from '@/components/layout/AppLayout.vue'
import StudentServiceRequest from '@/components/pages/StudentServiceRequest.vue'
import Student from '@/components/pages/Student.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: AppLayout,
      children: [
        {
          path: '/student',
          name: 'student',
          component: Student,
        },
        {
          path: '/student_request',
          name: 'student_request',
          component: StudentServiceRequest,
        },
      ],
    },
  ],
})

export default router
