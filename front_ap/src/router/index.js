import { createRouter, createWebHistory } from 'vue-router'
import MainView from '../views/MainView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'main',
      component: MainView
    },
    {
      path: '/parks',
      name: 'parks',
      component: () => import('../views/ParksView.vue')
    },
    {
      path: '/events',
      name: 'events',
      component: () => import('../views/EventsView.vue')
    },
    {
      path: '/park',
      name: 'park',
      component: () => import('../views/ParkView.vue')
    },
    {
      path: '/event',
      name: 'event',
      component: () => import('../views/EventView.vue'),
      props: true
    },
    {
      path: '/support',
      name: 'support',
      component: () => import('../views/SupportView.vue')
    },
    {
      path: '/myEvents',
      name: 'myEvents',
      component: () => import('../views/MyEvents.vue')
    },
    {
      path: '/myPark',
      name: 'myPark',
      component: () => import('../views/MyPark.vue')
    },
    {
      path: '/notes',
      name: 'notes',
      component: () => import('../views/NotesView.vue')
    }
  ]
})

export default router
