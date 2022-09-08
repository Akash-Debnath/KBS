import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '@/views/Home.vue'
import CategoryList from "@/views/CategoryList";
import ArticleDetail from "@/views/ArticleDetail";
import Faq from "@/views/Faq";
import Quiz from "../views/Quiz";
import Search from "../views/Search";
import StartExam from "../views/StartExam";
import Sitemap from "../views/Sitemap";
import Contact from "@/views/Contact";
import Registration from "@/views/Registration";
import DetailsResult from "../views/quizHistory/DetailsResult";
import UserQuizHistory from "../views/quizHistory/UserQuizHistory";
import UserQuizResultList from "../views/quizHistory/UserQuizResultList";
import NotFound from "@/views/NotFound";

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home
  },
  {
    path: '/category-list/:categorySlug',
    name: 'CategoryList',
    component: CategoryList
  },
  {
    path: '/article-detail/:articleID/:articleSlug',
    name: 'ArticleDetail',
    component: ArticleDetail
  },
  {
    path: '/faq',
    name: 'Faq',
    component: Faq
  },
  {
    path: '/quiz',
    name: 'Quiz',
    component: Quiz
  },
  {
    path: '/search',
    name: 'Search',
    component: Search
  },
  {
    path: '/start-exam',
    name: 'StartExam',
    component: StartExam
  },
  {
    path: '/sitemap',
    name: 'Sitemap',
    component: Sitemap
  },
  {
    path: '/contact',
    name: 'Contact',
    component: Contact
  },
  {
    path: '/registration',
    name: 'Registration',
    component: Registration
  },
  {
    path: '/quiz-history',
    name: 'UserQuizHistory',
    beforeEnter : guardMyroute,
    component: UserQuizHistory,
    meta: {
      requiresAuth: true
    }
  },
  {
    path: '/quiz-result-list/:quiz_id',
    name: 'UserQuizResultList',
    beforeEnter : guardMyroute,
    component: UserQuizResultList,
    meta: {
      requiresAuth: true
    }
  },
  {
    path: '/details-result/:quiz_id/:attempt',
    name: 'DetailsResult',
    beforeEnter : guardMyroute,
    component: DetailsResult,
    meta: {
      requiresAuth: true
    }
  },
  {
    path: "/:catchAll(.*)",
    name: 'NotFound',
    component: NotFound
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

router.beforeEach((to, from, next) => {
  // to top
  window.scrollTo(0, 0);
  next();
});

function guardMyroute(to, from, next)
{
  var isAuthenticated= false;
//this is just an example. You will have to find a better or
// centralised way to handle you localstorage data handling
  if(sessionStorage.getItem('userToken'))
    isAuthenticated = true;
  else
    isAuthenticated= false;
  if(isAuthenticated)
  {
    next(); // allow to enter route
  }
  else
  {
    next('/'); // go to '/login';
  }
}




export default router
