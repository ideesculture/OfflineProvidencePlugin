import { createRouter, createWebHistory } from 'vue-router'
import List from '/src/components/List.vue'
import TestLoad from '/src/components/TestLoad.vue'
import Edit from '/src/components/Edit.vue'

const routes = [{
        path: '/offline/',
        name: 'List',
        component: List,
    }, {
        path: '/offline/testload',
        name: 'TestLoad',
        component: TestLoad,
    }, {
        path: '/offline/edit/:id',
        name: 'Edit',
        component: Edit,
    },

]
const router = createRouter({
    history: createWebHistory(),
    routes,
})
export default router