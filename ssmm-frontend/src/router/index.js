import { createRouter, createWebHistory } from 'vue-router'
import AppLayout from '@/components/layout/AppLayout.vue'
import StudentServiceRequest from '@/components/pages/ServiceRequest/StudentServiceRequest.vue'
import Login from '@/components/pages/Auth/Login.vue'
import Student from '@/components/pages/Student/Student.vue'
import Form from '@/components/pages/Student/Form.vue'
import Logs from '@/components/pages/Import/Logs.vue'
import Import from '@/components/pages/Import/Import.vue'

function isLoggedIn() {
  return !!localStorage.getItem('APP_TOKEN')
}
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
        {
          path: '/student_request/form/:id',
          name: 'student_request_form',
          component: Form,
        },
        {
          path: 'logs',
          name: 'logs',
          component: Logs,
        },
        {
          path: 'import',
          name: 'import',
          component: Import,
        },
      ],
    },
    {
      path: '/login',
      component: Login,
      name: 'login',
    },
  ],
})
router.beforeEach((to, from, next) => {
  if (to.name === 'login' && isLoggedIn()) {
    // If logged in, redirect to home
    next({ name: 'student' })
  } else {
    next() // Otherwise, allow access
  }
})
export default router
