import Vue from 'vue'
import App from './App.vue'

//Routes import
import router from './router'
//Vuex import
import store from './store'
//vuetify import
import vuetify from './plugins/vuetify'

//for encrypted messenger
//web3 import
//import web3 from './contracts/web3';
//contract import
//import contract from './contracts/contractInstance';

// import {
//   ethers
// } from 'ethers';
// -------------------------------------------------------------
import Vuex from 'vuex'
Vue.use(Vuex)

// bootstrap-vue
import {
  BootstrapVue,
  IconsPlugin
} from 'bootstrap-vue'

Vue.use(BootstrapVue)
// Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin)

// import {
//   VueReCaptcha
// } from 'vue-recaptcha-v3'

// For more options see below
// Vue.use(VueReCaptcha, {
//   siteKey: '****',
//   loaderOptions: {
//     useRecaptchaNet: true,
//     autoHideBadge: false
//   },
//   explicitRenderParameters: {
//     container: 'inline-badge',
//     badge: 'inline',
//     size: 'invisible'
//   }
// })

import VueChatScroll from 'vue-chat-scroll'
Vue.use(VueChatScroll)

// vue scrollbar
import Vuebar from 'vuebar';
Vue.use(Vuebar);

//audio video player
// import VuePlyr from 'vue-plyr';
// import 'vue-plyr/dist/vue-plyr.css';
//
// Vue.use(VuePlyr, {
//   plyr: {}
// });

import Echo from 'laravel-echo'
window.Pusher = require('pusher-js')

// This assumes you have already saved your bearer token in your browsers local storage
const token = localStorage.getItem('authToken');

window.Echo = new Echo({
  broadcaster: 'pusher',
  key: process.env.VUE_APP_PUSHER_APP_KEY,
  cluster: process.env.VUE_APP_PUSHER_CLUSTER,
  authEndpoint: `${process.env.VUE_APP_API_URL}/broadcast`,
  // authHost: "http://localhost",
  // authEndpoint: "/broadcasting/auth",
  // authEndpoint: 'http://127.0.0.1/public/broadcasting/auth',
  encrypted: false, //changed from true
  forceTLS: false,
  wsHost: window.location.hostname,
  wsPort: 6001,
  // wssPort: 6001,
  disableStats: false, //from true
  enabledTransports: ['ws', 'wss'],
  auth: {
    headers: {
      Accept: 'application/json',
      Authorization: 'Bearer ' + token,
    }
  }
})

import ScrollLoader from 'vue-scroll-loader'
Vue.use(ScrollLoader)


// import scss file
require('@/assets/styles/main.scss');

Vue.config.productionTip = false

import axios from 'axios';

import {
  isLoggedIn
} from './utils/auth'

new Vue({

  router,
  vuetify,
  store,
  data: {
    envUrlNoApi: undefined,

    friendships: {
      friends: undefined,
      friends_overlay: true,
      friendsLoading: true,
    },

    me: {
      id: undefined,
      name: undefined,
      email: undefined,
      avatar: undefined,
      status: undefined,
      created_at: undefined,
      overlayAvatar: true,
    },

    navigationDrawerLeft: {
      numberOfFriends: undefined,
      numberOfUserPosts: undefined
    },

    navigationDrawerRight: {
      countMyFriendshipRequests: 0,
    },

    navigationDrawerCenter: {
      getAllPosts: {
        allPosts: undefined,
        loadingPosts: true,
        loadingPaginate: true,
        countActualPosts: undefined,
        countAllPosts: undefined,
      },
    },

    profilePosts: {
      getAllPosts: {
        allPosts: undefined,
        loadingPosts: true,
        loadingPaginate: true,
        countActualPosts: undefined,
        countAllPosts: undefined,
      },
    },

    profile: {
      data: {
        returnVariable: undefined,
        userData: undefined
      },
      friendships: {
        friends: undefined,
        numberOfFriends: 0,
        friends_overlay: true,
      }
    },

    toolbar: {
      allPossibleFriends: {
        users: undefined,
        usersLoading: true
      },
      friendRequests: {
        friendRequests: undefined,
        friendRequests_overlay: true,
      },
      fewNewestMessages: {
        overlayNewestMessages: true,
        fewNewestMessages: undefined,
      },
      fewNewestNotifications: {
        overlayNewestNotifications: true,
        fewNewestNotifications: undefined,
      },
      fewOldNotifications: {
        overlayOldNotifications: true,
        fewOldNotifications: undefined,
      },

      replyOnComment: undefined,
    },

    messenger2: {
      currentPosts: [],
      users: [],
      amILogged: false,
      currentAccount: '',
      loading: true,
      loadingusers: true,
      loggedInToMetamask: false,
      isMetamaskDetected: false,
      myProfile: {
        id: '',
        name: '',
        email: '',
        avatar: '',
      },
      balance: '',
      // contract,
    },
  },

  async updated() {
    console.log('update');
    // await this.updateAccount();
    // window.ethereum.on('accountsChanged', function(accounts) {
    //   console.log(accounts);
    // })
  },

  methods: {
    // encrypted messenger
    // Bytes32ToWeb2String(text) {
    //   var result = ethers.utils.parseBytes32String(text);
    //   return result;
    // },
    /**
     * gets current account on web3 and
     * store it on currentAccount variable.
     */
    // async updateAccount() {
    //   const accounts = await web3.eth.getAccounts();
    //   if (accounts.length == 0) {
    //     console.log("User is not logged in to MetaMask");
    //   } else {
    //     this.messenger2.loggedInToMetamask = true;
    //     const account = accounts[0];
    //     this.messenger2.currentAccount = account;
    //     web3.eth.defaultAccount = this.messenger2.currentAccount;
    //   }
    // },
    //
    // async getActualBalance() {
    //   const accounts = await web3.eth.getAccounts();
    //   if (accounts.length != 0) {
    //     await web3.eth.getBalance(this.messenger2.currentAccount, (err, balance) => {
    //       this.messenger2.balance = web3.utils.fromWei(balance, "ether") + " ETH"
    //     });
    //   }
    // },
    //
    // async initMyProfile() {
    //   const accounts = await web3.eth.getAccounts();
    //   if (accounts.length != 0) {
    //     const myProfile = await contract.methods.userInfo(this.messenger2.currentAccount).call({
    //       from: this.messenger2.currentAccount,
    //     });
    //     this.messenger2.myProfile.id = this.Bytes32ToWeb2String(myProfile[0]);
    //     this.messenger2.myProfile.name = this.Bytes32ToWeb2String(myProfile[1]);
    //     this.messenger2.myProfile.email = this.Bytes32ToWeb2String(myProfile[2]);
    //     this.messenger2.myProfile.avatar = this.Bytes32ToWeb2String(myProfile[3]);
    //   }
    // },
    //
    // async getUsers() {
    //   this.messenger2.users = [];
    //   const users = await contract.methods.getUsers().call({
    //     from: this.messenger2.currentAccount,
    //   });
    //   for (var i = 0; i < users.length; i++) {
    //     if (this.Bytes32ToWeb2String(users[i].id) == localStorage.getItem('user_id')) {
    //       this.messenger2.amILogged = true;
    //     }
    //
    //     if (this.messenger2.myProfile.name != this.Bytes32ToWeb2String(users[i].name)) {
    //       this.messenger2.users.push({
    //         id: this.Bytes32ToWeb2String(users[i].id),
    //         name: this.Bytes32ToWeb2String(users[i].name),
    //         email: this.Bytes32ToWeb2String(users[i].email),
    //         avatar: this.Bytes32ToWeb2String(users[i].avatar),
    //       })
    //     }
    //   }
    //
    //   this.messenger2.loadingusers = false;
    // },

    // async getPosts() {
    //   const accounts = await web3.eth.getAccounts();
    //   if (accounts.length != 0) {
    //     this.messenger2.currentPosts = [];
    //     this.messenger2.loading = false;
    //     const counter = await contract.methods.getCounter().call({
    //       from: this.messenger2.currentAccount,
    //     });
    //
    //     if (counter !== null) {
    //       const hashes = [];
    //       for (let i = counter; i >= 1; i -= 1) {
    //         hashes.push(contract.methods.getHash(i).call({
    //           from: this.messenger2.currentAccount,
    //         }));
    //       }
    //
    //       const postHashes = await Promise.all(hashes);
    //       for (let i = 0; i < postHashes.length; i += 1) {
    //         if (this.Bytes32ToWeb2String(postHashes[i].touserid) == 'null') {
    //           const myProfile = await contract.methods.userInfo(postHashes[i].owner).call({
    //             from: this.messenger2.currentAccount,
    //           });
    //
    //           const captions = await Promise.resolve(fetch(`https://gateway.ipfs.io/ipfs/${postHashes[i].text}`)
    //             .then(res => res.text()));
    //
    //           this.messenger2.currentPosts.push({
    //             id: i,
    //             key: `key${i}`,
    //             text: captions,
    //             timestamp: new Date(postHashes[i].date * 1000).toLocaleDateString('sk', {
    //               year: 'numeric',
    //               month: 'long',
    //               day: '2-digit',
    //               hour: '2-digit',
    //               minute: '2-digit',
    //               second: '2-digit'
    //             }),
    //             videosrc: `https://gateway.ipfs.io/ipfs/${postHashes[i].video}`,
    //             src: `https://gateway.ipfs.io/ipfs/${postHashes[i].img}`,
    //             owner: this.Bytes32ToWeb2String(myProfile.name),
    //             ownerid: this.Bytes32ToWeb2String(postHashes[i].ownerid),
    //             toUser: this.Bytes32ToWeb2String(postHashes[i].touserid)
    //           });
    //         }
    //       }
    //
    //       this.messenger2.loading = false;
    //     }
    //   }
    // },
    // ------------------
    getDataOfMe() {
      const api = `${process.env.VUE_APP_API_URL}/auth/user`;
      const config = {
        headers: {
          Accept: "application/json",
          Authorization: "Bearer " + localStorage.getItem("authToken"),
        },
      };
      axios.get(api, config)
        .then(res => {
          this.me.id = res.data[0].id;
          this.me.name = res.data[0].name;
          this.me.email = res.data[0].email;
          this.me.avatar = res.data[0].avatar;
          this.me.status = res.data[0].status;
          this.me.created_at = res.data[0].created_at;
          this.me.overlayAvatar = false;
        });
    },

    getAllFriends() {
      const api = `${process.env.VUE_APP_API_URL}/friendships`;
      const config = {
        headers: {
          Accept: "application/json",
          Authorization: "Bearer " + localStorage.getItem("authToken"),
        },
      };
      axios.get(api, config)
        .then(res => {
          this.friendships.friendsLoading = false;
          this.friendships.friends_overlay = false;
          this.friendships.friends = res.data;
        });
    },
    // NavigationDrawerLeft
    getNumberOfFriends() {
      const api = `${process.env.VUE_APP_API_URL}/getNumberOfFriends`;
      const config = {
        headers: {
          Accept: "application/json",
          Authorization: "Bearer " + localStorage.getItem("authToken"),
        },
      };
      axios.get(api, config)
        .then(res => {
          this.navigationDrawerLeft.numberOfFriends = res.data;
        });
    },

    getNumberOfUserPosts() {
      const api = `${process.env.VUE_APP_API_URL}/get_number_of_user_posts`;
      const config = {
        headers: {
          Accept: "application/json",
          Authorization: "Bearer " + localStorage.getItem("authToken"),
        },
      };
      axios.get(api, config)
        .then(res => {
          this.navigationDrawerLeft.numberOfUserPosts = res.data;
        });
    },

    // Toolbar
    getAllPossibleFriends() {
      const api = `${process.env.VUE_APP_API_URL}/getAllPossibleFriends`;
      const config = {
        headers: {
          Accept: "application/json",
          Authorization: "Bearer " + localStorage.getItem("authToken"),
        },
      };
      axios.get(api, config)
        .then(res => {
          this.toolbar.allPossibleFriends.usersLoading = false;
          this.toolbar.allPossibleFriends.users = res.data;
        });
    },

    getFriendRequests() {
      const api = `${process.env.VUE_APP_API_URL}/getAllFriendshipRequests`;
      const config = {
        headers: {
          Accept: "application/json",
          Authorization: "Bearer " + localStorage.getItem("authToken"),
        },
      };
      axios.get(api, config)
        .then(res => {
          this.toolbar.friendRequests.friendRequests = res.data;
          this.toolbar.friendRequests.friendRequests_overlay = false;
        });
    },

    getFewNewestMessages() {
      const api = `${process.env.VUE_APP_API_URL}/getFewNewestMessages`;
      const config = {
        headers: {
          Accept: "application/json",
          Authorization: "Bearer " + localStorage.getItem("authToken"),
        },
      };
      axios.get(api, config)
        .then(res => {
          this.toolbar.fewNewestMessages.overlayNewestMessages = false;
          this.toolbar.fewNewestMessages.fewNewestMessages = res.data;
        });
    },

    getFewNewestNotifications() {
      const api = `${process.env.VUE_APP_API_URL}/getFewNewestNotifications`;
      const config = {
        headers: {
          Accept: "application/json",
          Authorization: "Bearer " + localStorage.getItem("authToken"),
        },
      };
      axios.get(api, config)
        .then(res => {
          this.toolbar.fewNewestNotifications.overlayNewestNotifications = false;
          this.toolbar.fewNewestNotifications.fewNewestNotifications = res.data;
        });
    },

    getFewOldNotifications() {
      const api = `${process.env.VUE_APP_API_URL}/getFewOldNotifications`;
      const config = {
        headers: {
          Accept: "application/json",
          Authorization: "Bearer " + localStorage.getItem("authToken"),
        },
      };
      axios.get(api, config)
        .then(res => {
          this.toolbar.fewOldNotifications.overlayOldNotifications = false;
          this.toolbar.fewOldNotifications.fewOldNotifications = res.data;
        });
    },

    // NavigationDrawerCenter
    getAllDataAboutposts() {
      const api = `${process.env.VUE_APP_API_URL}/getAllDataAboutPosts/0/3`;
      const config = {
        headers: {
          Accept: "application/json",
          Authorization: "Bearer " + localStorage.getItem("authToken"),
        },
      };
      axios.get(api, config)
        .then(res => {
          this.navigationDrawerCenter.getAllPosts.allPosts = res.data[0];
          this.navigationDrawerCenter.getAllPosts.countAllPosts = res.data[1];
          this.navigationDrawerCenter.getAllPosts.countActualPosts = res.data[0].length;
          this.navigationDrawerCenter.getAllPosts.loadingPosts = false;
          this.navigationDrawerCenter.getAllPosts.loadingPaginate = false;
        });
    },

    getCountMyFriendshipRequests() {
      const api = `${process.env.VUE_APP_API_URL}/getCountMyFriendshipRequests`;
      const config = {
        headers: {
          Accept: "application/json",
          Authorization: "Bearer " + localStorage.getItem("authToken"),
        },
      };
      axios.get(api, config)
        .then(res => {
          this.navigationDrawerRight.countMyFriendshipRequests = res.data;
        });
    },
  },

  async created() {
    this.envUrlNoApi = process.env.VUE_APP_API_URL2;
    if (isLoggedIn()) {
      this.getDataOfMe();
      this.getAllFriends();
      // NavigationDrawerLeft
      this.getNumberOfFriends();
      this.getNumberOfUserPosts();
      // Toolbar
      this.getAllPossibleFriends();
      this.getFriendRequests();

      this.getFewNewestMessages();
      this.getFewOldNotifications();
      this.getFewNewestNotifications();
      //navigationDrawerCenter
      this.getAllDataAboutposts();
      this.getCountMyFriendshipRequests();

      // await this.updateAccount();
      // await this.getActualBalance();
      // await this.initMyProfile();
      // await this.getUsers();
      // await this.getPosts();
    }
  },

  render: h => h(App),
}).$mount('#app')