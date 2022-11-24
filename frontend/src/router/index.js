import Vue from 'vue'
import VueRouter from 'vue-router'

import goTo from 'vuetify/es5/services/goto'

import {
  isLoggedIn
} from '../utils/auth'

import {
  clearAuthToken
} from '../utils/auth'

//auth
import Login from '../views/Login.vue'
import Register from '../views/Register.vue'
import ForgotPassword from '../views/ForgotPassword.vue'
import ResetPassword from '../views/ResetPassword.vue'
import VerificationAccount from '../views/VerificationAccount.vue'

//views
import Welcome from '../views/Welcome.vue'
import Home from '../views/Home.vue'
import Notifications from '../views/Notifications.vue'
import Settings from '../views/Settings.vue'
import Profile from '../views/Profile.vue'
import Messenger from '../views/Messenger.vue'

import Messenger2 from '../views/Messenger2.vue'

import Test from '../views/Test.vue'
import Test2 from '../views/Test2.vue'
import Images from '../views/Images.vue'

Vue.use(VueRouter)

const routes = [{
    path: '/',
    name: 'Welcome',
    component: Welcome,
    meta: {
      allowAnonymous: true
    },
  }, {
    path: '/login',
    name: 'Login',
    component: Login,
    meta: {
      allowAnonymous: true
    },
  }, {
    path: '/register',
    name: 'Register',
    component: Register,
    meta: {
      allowAnonymous: true
    },
  }, {
    path: '/forgot-password',
    name: 'ForgotPassword',
    component: ForgotPassword,
    meta: {
      allowAnonymous: true
    },
  }, {
    path: '/reset-password/:token',
    name: 'ResetPassword',
    component: ResetPassword,
    meta: {
      allowAnonymous: true
    },
  }, {
    path: '/activation-account/:token',
    name: 'VerificationAccount',
    component: VerificationAccount,
    meta: {
      allowAnonymous: true
    },
  }, {
    //socialite
    path: '/auth/:provide/callback',
    component: {
      template: '<div class="auth-component"></div>'
    }
  }, {
    path: '/home',
    name: 'Home',
    component: Home,
  }, {
    path: '/notifications',
    name: 'Notifications',
    component: Notifications,
    children: [{
        path: '/notifications',
        name: 'All',
        component: require('../components/tabs/notifications/All.vue')
          .default
      },
      {
        path: '/notifications/new',
        name: 'New',
        component: require('../components/tabs/notifications/New.vue')
          .default
      },
      {
        path: '/notifications/saved',
        name: 'Saved',
        component: require('../components/tabs/notifications/Saved.vue')
          .default
      }
    ],
  }, {
    path: '/settings',
    name: 'Settings',
    component: Settings,
  }, {
    path: '/profile/:user_id',
    name: 'Profile',
    component: Profile,
    children: [{
        path: '/profile/:user_id',
        name: 'Profile',
        component: require('../components/tabs/profile/Profile.vue')
          .default
      },
      {
        path: '/profile/:user_id/about',
        name: 'About',
        component: require('../components/tabs/profile/About.vue')
          .default
      }, {
        path: '/profile/:user_id/friends',
        name: 'Friends',
        component: require('../components/tabs/profile/Friends.vue')
          .default,
        props: true
      },
    ],
  }, {
    path: '/messenger',
    name: 'Messenger',
    component: Messenger,
  }, {
    path: '/test',
    name: 'Test',
    component: Test,
  }, {
    path: '/test2',
    name: 'Test2',
    component: Test2,
  }, {
    path: '/images',
    name: 'Images',
    component: Images,
  },
  {
    path: '/decentralized_messenger',
    name: 'Messenger2',
    component: Messenger2,
  }
]

const router = new VueRouter({
  scrollBehavior: (to, from, savedPosition) => {
    let scrollTo = 0

    if (to.hash) {
      scrollTo = to.hash
    } else if (savedPosition) {
      scrollTo = savedPosition.y
    }

    return goTo(scrollTo)
  },
  mode: 'history',
  // base: process.env.BASE_URL,
  routes
})

router.beforeEach((to, from, next) => {
  if (((to.name == 'Login') || (to.name == 'Register') || (to.name == 'Welcome')) && isLoggedIn()) {
    next({
      path: '/home'
    })
  } else if (!to.meta.allowAnonymous && !isLoggedIn()) {
    clearAuthToken();
    next({
      path: '/login',
    })
  } else {
    next()
  }
})

export default router