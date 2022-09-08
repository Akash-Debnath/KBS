import Vue from 'vue'
import Router from 'vue-router'
import Login from '@/components/login'
import dashboard from '@/components/dashboard/dashboard.vue'
import customerList from '@/components/customer/customerList.vue'
import categoryList from '@/components/category/categoryList.vue'

import articleList from '@/components/article/articleList.vue'

import articleDetails from '@/components/article/articleDetails.vue'

import faqList from '@/components/faq/faqList.vue'
import faqDetails from '@/components/faq/faqDetails.vue'

import roleList from '@/components/roles/roleList.vue'

import quizFormList from '@/components/quiz-form/quizFormList.vue'

import quizList from '@/components/quiz/quizList.vue'
import quizAdd from '@/components/quiz/quizAdd.vue'
import quizEdit from '@/components/quiz/quizEdit.vue'

import quizFormFieldList from '@/components/quiz-form-fields/quizFormFieldList.vue'
import pageConfiguration from '@/components/settings/pageConfigurationNew.vue'
import emailConfiguration from '@/components/settings/emailConfiguration.vue'

import bannerList from '@/components/banner/bannerList'
import commentList from '@/components/comment/commentList'
import notificationList from '@/components/notification/notificationList.vue'

import allHistoryList from '@/components/history/allHistoryList.vue'
import resultList from '@/components/result/resultList.vue'
import userQuizList from '@/components/result/userQuizList.vue'
import userQuizResultList from '@/components/result/userQuizResultList.vue'
import resultDetails from '@/components/result/resultDetails.vue'
import quizResultList from '@/components/result/quizResultList.vue'
import quizResultDetails from '@/components/result/quizResultDetails.vue'

import menuList from '@/components/menu/menuList.vue'
import pageList from '@/components/pages/pageList.vue'
import groupList from '@/components/group/groupList'
import linkList from '@/components/links/linkList.vue'
import interestRate from '@/components/interest-rate/interestRate.vue'
import exchangeRate from '@/components/exchange-rate/exchangeRate.vue'


Vue.use(Router)

let router =  new Router({
  routes: [
    {
      path: '/',
      name: 'Login',
      component: Login,
    },
    {
      path: '/dashboard',
      name: 'dashboard',
      component: dashboard,
      meta: {
        requiresAuth: true
      }
    },
    {
      path: '/user-list',
      name: 'customerList',
      component: customerList,
      meta: {
        requiresAuth: true
      }
    },
    {
      path: '/group-list',
      name: 'groupList',
      component: groupList,
      meta: {
        requiresAuth: true
      }
    },
    {
      path: '/comment-list',
      name: 'commentList',
      component: commentList,
      meta: {
        requiresAuth: true
      }
    },
    {
      path: '/category-list',
      name: 'categoryList',
      component: categoryList,
      meta: {
        requiresAuth: true
      }
    },
    {
      path: '/page-configuration',
      name: 'pageConfiguration',
      component: pageConfiguration,
      meta: {
        requiresAuth: true
      }
    },

    {
      path: '/email-configuration',
      name: 'emailConfiguration',
      component: emailConfiguration,
      meta: {
        requiresAuth: true
      }
    },

    {
      path: '/quiz-add',
      name: 'quizAdd',
      component: quizAdd,
      meta: {
        requiresAuth: true
      }
    },

    {
      path: '/quiz-list',
      name: 'quizList',
      component: quizList,
      meta: {
        requiresAuth: true
      }
    },

    {
      path: '/quiz-edit/:id',
      name: 'quizEdit',
      component: quizEdit,
      meta: {
        requiresAuth: true
      }
    },

    {
      path: '/quiz-form-field-list/',
      name: 'quizFormFieldList',
      component: quizFormFieldList,
      meta: {
        requiresAuth: true
      }
    },

    {
      path: '/quiz-form-list',
      name: 'quizFormList',
      component: quizFormList,
      meta: {
        requiresAuth: true
      }
    },
    {
      path: '/role-list',
      name: 'roleList',
      component: roleList,
      meta: {
        requiresAuth: true
      }
    },
    {
      path: '/faq-list',
      name: 'faqList',
      component: faqList,
      meta: {
        requiresAuth: true
      }
    },

    {
      path: '/faq-details/:id/:slug',
      name: 'faqDetails',
      component: faqDetails,
      meta: {
        requiresAuth: true
      }
    },


    {
      path: '/article-list',
      name: 'articleList',
      component: articleList,
      meta: {
        requiresAuth: true
      }
    },
    {
      path: '/article-details/:id/:slug',
      name: 'articleDetails',
      component: articleDetails,
      meta: {
        requiresAuth: true
      }
    },

    {
      path: '/banner-list',
      name: 'bannerList',
      component: bannerList,
      meta: {
        requiresAuth: true
      }
    },

    {
      path: '/menu-list',
      name: 'menuList',
      component: menuList,
      meta: {
        requiresAuth: true
      }
    },
    {
      path: '/link-list',
      name: 'linkList',
      component: linkList,
    },
    {
      path: '/interest-rate',
      name: 'interestRate',
      component: interestRate,
    },
    {
      path: '/exchange-rate',
      name: 'exchangeRate',
      component: exchangeRate,
    },
    {
      path: '/page-list',
      name: 'pageList',
      component: pageList,
      meta: {
        requiresAuth: true
      }
    },
    
    {
      path: '/notification-list',
      name: 'notificationList',
      component: notificationList,
      meta: {
        requiresAuth: true
      }
    },
    {
      path: '/history-list',
      name: 'allHistoryList',
      component: allHistoryList,
      meta: {
        requiresAuth: true
      }
    },
    {
      path: '/quiz-result-list',
      name: 'quizResultList',
      component: quizResultList,
      meta: {
        requiresAuth: true
      }
    },
    {
      path: '/quiz-result-details/:quizId/:status',
      name: 'quizResultDetails',
      component: quizResultDetails,
      meta: {
        requiresAuth: true
      }
    },
    {
      path: '/result-list',
      name: 'resultList',
      component: resultList,
      meta: {
        requiresAuth: true
      }
    },
    {
      path: '/user-quiz-list/:userId',
      name: 'userQuizList',
      component: userQuizList,
      meta: {
        requiresAuth: true
      }
    },
    {
      path: '/user-quiz-result-list/:userId/:quizId',
      name: 'userQuizResultList',
      component: userQuizResultList,
      meta: {
        requiresAuth: true
      }
    },
    {
      path: '/result-details/:userId/:quizId/:attempt',
      name: 'resultDetails',
      component: resultDetails,
      meta: {
        requiresAuth: true
      }
    }
  ],
  mode: 'history',
  base: '/kbs/admin/'
});

function getRoutesList(routes, pre) {
  return routes.reduce((array, route) => {
    const path = `${pre}${route.path}`;

    if (route.path !== '*') {
      array.push(path);
    }

    if (route.children) {
      array.push(...getRoutesList(route.children, `${path}/`));
    }

    return array;
  }, []);
}



router.beforeEach((to, from, next) => {
  if(to.matched.some(record => record.meta.requiresAuth)) {
    if (!localStorage.getItem('authToken')) {
      next('/');
    }
    else{
      next();
    }
  }else if(to.name == "Login" && localStorage.getItem('authToken') != null){
    next('/dashboard');
  }else {
    next();
  }
});


export default router;