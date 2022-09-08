import {createRouter, createWebHistory} from 'vue-router'
import HomeView from '../views/HomeView.vue';
import LoginView from "../views/LoginView.vue";
import DepositsView from "../views/Pages/DepositsView.vue";
import MenuPageView from "../components/Pages/MenuPageDetails.vue";
import ContentView from "../components/Pages/ContentDetails.vue";
import Faq from "../components/Faq.vue";
import Quiz from "../components/Quiz/Quiz.vue";
import QuizTemplate from "../components/Quiz/QuizTemplate.vue";
import UserQuizResultList from "../components/Quiz/UserQuizResultList.vue";
import DetailsResult from "../components/Quiz/DetailsResult.vue";

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: '/',
            name: 'home',
            component: HomeView
        },
        {
            path: '/deposits',
            name: 'deposits',
            component: DepositsView
        },

        {
            path: '/pages/:slug',
            name: 'pages',
            component: MenuPageView
        },

        {
            path: '/content/:slug',
            name: 'content',
            component: ContentView
        },

        {
            path: '/faq',
            name: 'faq',
            component: Faq

        },
        {
            path: '/quiz',
            name: 'quiz',
            component: Quiz,
            meta: {navigationPart: true}

        },

        {
            path: '/quiz-questions',
            name: 'QuizTemplate',
            component: QuizTemplate,
            meta: {navigationPart: true}

        },

        {
            path: '/quiz-results',
            name: 'QuizResults',
            component: UserQuizResultList,
            meta: {navigationPart: true}
        },

        {
            path: '/details-result/:quiz_id/:attempt',
            name: 'DetailsResult',
            component: DetailsResult,
            meta: {navigationPart: true}

        },

        {
            path: '/login',
            name: 'login',
            component: LoginView,
            meta: {commonPart: true, navigationPart: true}
        },
        {
            path: '/about',
            name: 'about',
            // route level code-splitting
            // this generates a separate chunk (About.[hash].js) for this route
            // which is lazy-loaded when the route is visited.
            component: () => import('../views/AboutView.vue')
        }
    ]
})

export default router
