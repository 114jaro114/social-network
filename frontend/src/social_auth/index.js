import Vue from 'vue'
import axios from 'axios';
import VueAxios from 'vue-axios'
import VueSocialauth from 'vue-social-auth'

Vue.use(VueAxios, axios)
Vue.use(VueSocialauth, {
  providers: {
    google: {
      clientId: '815173062221-m267p2pdrgt376fidogkdr47kc8kng2b.apps.googleusercontent.com',
      // redirectUri: 'http://localhost:8080/login' // Your client app URL
      redirectUri: 'http://localhost:8080/auth/google/callback' // Your client app URL
    },
    facebook: {
      clientId: '2857032101203624',
      // redirectUri: 'http://localhost:8080/login' // Your client app URL
      redirectUri: 'http://localhost:8080/auth/facebook/callback' // Your client app URL
    },
    // twitter: {
    //   clientId: '',
    //   redirectUri: 'http://localhost:8080/register' // Your client app URL
    // },
    // github: {
    //   clientId: '',
    //   redirectUri: 'http://localhost:8080/register' // Your client app URL
    // }
  }
})