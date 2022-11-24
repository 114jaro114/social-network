import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    successRegisterAlert: {
      success: false
    },

    isLoggedOut: {
      username: '',
      logout: false
    },

    speedDialState: {
      state: false,
    },

    bottomNavigationState: {
      state: false,
    },

    buttonToTopState: {
      state: false,
    },

    autoDarkLightModeState: {
      state: false,
    },

    notificationCounter: null,

    updatedPassword: false,

    msgUnreadCounter: 0,

    activatedAccount: false,

    selectedUser: {
      id: null,
      name: null,
      email: null,
      status: null,
      avatar: null,
      created_at: null,
      unread: null,
    },

    me: {
      id: null,
      name: null,
      email: null,
      status: null,
      avatar: null,
      created_at: null,
    },

    friendshipRequestAction: {
      status: null,
      text: null
    }

  },
  getters: {
    successRegisterAlert: state => {
      return state.successRegisterAlert;
    },

    isLoggedOut: state => {
      return state.isLoggedOut
    },

    speedDialState: state => {
      return state.speedDialState
    },

    bottomNavigationState: state => {
      return state.bottomNavigationState
    },

    buttonToTopState: state => {
      return state.buttonToTopState
    },

    autoDarkLightModeState: state => {
      return state.autoDarkLightModeState
    },

    notificationCounter: state => {
      return state.notificationCounter
    },

    updatedPassword: state => {
      return state.updatedPassword
    },

    msgUnreadCounter: state => {
      return state.msgUnreadCounter
    },

    activatedAccount: state => {
      return state.activatedAccount
    },

    selectedUser: state => {
      return state.selectedUser
    },

    me: state => {
      return state.me
    },

    friendshipRequestAction: state => {
      return state.friendshipRequestAction
    },
  },
  mutations: {
    mutationSuccessRegister: (state, payload) => {
      Vue.set(state.successRegisterAlert, 'success', payload.success);
    },

    mutationLogout: (state, payload) => {
      Vue.set(state.isLoggedOut, 'username', payload.username);
      Vue.set(state.isLoggedOut, 'logout', payload.logout);
    },

    mutationSpeedDialState: (state, payload) => {
      Vue.set(state.speedDialState, 'state', payload.status);
    },

    mutationBottomNavigationState: (state, payload) => {
      Vue.set(state.bottomNavigationState, 'state', payload.status);
    },

    mutationButtonToTopState: (state, payload) => {
      Vue.set(state.buttonToTopState, 'state', payload.status);
    },

    mutationAutoDarkLightModeState: (state, payload) => {
      Vue.set(state.autoDarkLightModeState, 'state', payload.status);
    },

    mutationNotificationCounter: (state, payload) => {
      state.notificationCounter = payload.notifCounter
    },

    mutationUpdatedPassword: (state, payload) => {
      state.updatedPassword = payload.state;
    },

    mutationMsgUnreadCounter: (state, payload) => {
      state.msgUnreadCounter = payload.unreadCounter
    },

    mutationActivatedAccount: (state, payload) => {
      state.activatedAccount = payload.state
    },

    mutationSelectedUser: (state, payload) => {
      Vue.set(state.selectedUser, 'id', payload.id);
      Vue.set(state.selectedUser, 'name', payload.name);
      Vue.set(state.selectedUser, 'email', payload.email);
      Vue.set(state.selectedUser, 'status', payload.status);
      Vue.set(state.selectedUser, 'avatar', payload.avatar);
      Vue.set(state.selectedUser, 'created_at', payload.created_at);
      Vue.set(state.selectedUser, 'unread', payload.unread);
    },

    mutationMe: (state, payload) => {
      Vue.set(state.me, 'id', payload.id);
      Vue.set(state.me, 'name', payload.name);
      Vue.set(state.me, 'email', payload.email);
      Vue.set(state.me, 'status', payload.status);
      Vue.set(state.me, 'avatar', payload.avatar);
      Vue.set(state.me, 'created_at', payload.created_at);
    },

    mutationFriendshipRequestAction: (state, payload) => {
      Vue.set(state.friendshipRequestAction, 'status', payload.status);
      Vue.set(state.friendshipRequestAction, 'text', payload.text);
    },
  },

  actions: {
    successRegister: (context, payload) => {
      context.commit('mutationSuccessRegister', payload)
    },

    isLoggedOut: (context, payload) => {
      context.commit('mutationLogout', payload)
    },

    speedDialState: (context, payload) => {
      context.commit('mutationSpeedDialState', payload)
    },

    bottomNavigationState: (context, payload) => {
      context.commit('mutationBottomNavigationState', payload)
    },

    buttonToTopState: (context, payload) => {
      context.commit('mutationButtonToTopState', payload)
    },

    autoDarkLightModeState: (context, payload) => {
      context.commit('mutationAutoDarkLightModeState', payload)
    },

    notificationCounter: (context, payload) => {
      context.commit('mutationNotificationCounter', payload)
    },

    updatedPassword: (context, payload) => {
      context.commit('mutationUpdatedPassword', payload)
    },

    msgUnreadCounter: (context, payload) => {
      context.commit('mutationMsgUnreadCounter', payload)
    },

    activatedAccount: (context, payload) => {
      context.commit('mutationActivatedAccount', payload)
    },

    selectedUser: (context, payload) => {
      context.commit('mutationSelectedUser', payload)
    },
    me: (context, payload) => {
      context.commit('mutationMe', payload)
    },
    friendshipRequestAction: (context, payload) => {
      context.commit('mutationFriendshipRequestAction', payload)
    },
  }
});