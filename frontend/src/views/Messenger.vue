<template>
<div class="messenger h-100">
  <ContactsList @refreshCloseChat="refreshCloseChat" :closeChat="closeChat" :contacts="contacts" @selected="startConversationWith" />
  <v-main class="mt-16 pt-0 pb-0">
    <Conversation @closeChat="onClickChild" :contact="selectedContact" :messages="messages" :overlayMessages="overlayMessages" @new="saveNewMessage" />
  </v-main>
</div>
</template>
<script>
import Conversation from "../components/messenger/Conversation.vue";
import ContactsList from "../components/messenger/ContactsList.vue";
import axios from 'axios';

export default {
  name: "Notifications",
  components: {
    Conversation,
    ContactsList,
  },
  props: ['drawerNew'],
  data() {
    return {
      selectedContact: null,
      messages: [],
      contacts: [],
      autoselectMenu: false,
      overlayMessages: true,
      closeChat: null,
    }
  },

  computed: {},

  methods: {
    refreshCloseChat(value) {
      if (value == 'refresh') {
        this.closeChat = null;
      }
    },

    onClickChild(value) {
      this.closeChat = value;
    },

    friend_list() {
      this.$store.dispatch('selectedUser', {
        id: null,
        name: null,
        email: null,
        status: null,
        avatar: null,
        created_at: null,
        unread: null,
      });
    },

    startConversationWith(contact) {
      this.overlayMessages = true;
      const api = `${process.env.VUE_APP_API_URL}/conversation/${contact.id}`;
      const config = {
        headers: {
          Accept: "application/json",
          Authorization: "Bearer " + localStorage.getItem("authToken"),
        },
      };
      axios.get(api, config)
        .then(res => {
          this.$root.toolbar.fewNewestMessages.fewNewestMessages.forEach((elem, index) => {
            if (elem.name == contact.name) {
              this.$root.toolbar.fewNewestMessages.fewNewestMessages[index].read = 1;
            }
          })
          this.messages = res.data;
          this.selectedContact = contact;
          this.overlayMessages = false;
          //unread messages
          const api = `${process.env.VUE_APP_API_URL}/getAllUnreadMessages`;
          axios.get(api, config)
            .then((res) => {
              this.$store.dispatch('msgUnreadCounter', {
                unreadCounter: res.data
              });
            });
        });
    },
    saveNewMessage(message) {
      this.messages.push(message);
    },

    handleIncoming(message) {
      if (this.selectedContact && message.from == this.selectedContact.id) {
        this.saveNewMessage(message);
        return;
      }
    },

    fetchMessages() {
      if (this.selectedContact != null) {
        const api = `${process.env.VUE_APP_API_URL}/conversation/${this.selectedContact.id}`;
        const config = {
          headers: {
            Accept: "application/json",
            Authorization: "Bearer " + localStorage.getItem("authToken"),
          },
        };
        axios.get(api, config)
          .then(res => {
            console.log(res);
            this.messages = res.data;
          });
      }
    },
  },

  mounted() {
    // const api = `${process.env.VUE_APP_API_URL}/contacts`;
    // const config = {
    //   headers: {
    //     Accept: "application/json",
    //     Authorization: "Bearer " + localStorage.getItem("authToken"),
    //   },
    // };
    // axios.get(api, config)
    //   .then(res => {
    //     this.contacts = res.data;
    //     this.users = res.data
    //     // this.$store.dispatch('contactListLoader', {
    //     //   cancelLoader: false
    //     // });
    //   });
    //reset choodes user while come back to the messenger page
    this.friend_list();
  },

  updated() {
    // console.log(this.selectedItem);
    this.selectedContact = this.$store.getters['selectedUser'];
  },

  created() {
    //presence channel
    window.Echo.join('messages.' + localStorage.getItem("user_id"))
      .listen('NewMessage', (e) => {
        console.log(e.message);
        this.handleIncoming(e.message);
        if (this.$route.fullPath != `/messenger?name=${e.message.from_contact.name}`) {
          this.$store.dispatch('msgUnreadCounter', {
            unreadCounter: e.message.totalUnreadMsgTo
          });
        }
        // if (e.message.to == localStorage.getItem("user_id")) {
        //   this.$store.dispatch('msgUnreadCounter', {
        //     unreadCounter: e.message.totalUnreadMsgTo
        //   });
        // }
      })

    window.Echo.join('messagesstatusread.' + localStorage.getItem("user_id"))
      .listen('MessageStatusRead', (e) => {
        this.messages.forEach((elem, index) => {
          if (elem.from == e.id) {
            this.messages[index].read = 1;
          }
        });
      })


    window.Echo.join('messagereaction')
      .listen('MessageReactionData', (e) => {
        // console.log(e);
        this.messages.forEach((elem, index) => {
          if (elem.id == e.allDataMessage[0].id) {
            if (e.dataType == 'addUpdateMessageReaction' || e.dataType == 'deleteMessageReaction') {
              this.messages[index].messagereactionmodel = e.allDataMessage[0].messagereactionmodel;
              this.messages[index].count_reactions = e.allDataMessage[0].count_reactions;
              this.messages[index].like = e.allDataMessage[0].like;
              this.messages[index].heart = e.allDataMessage[0].heart;
              this.messages[index].funny = e.allDataMessage[0].funny;
              this.messages[index].surprise = e.allDataMessage[0].surprise;
              this.messages[index].sad = e.allDataMessage[0].sad;
              this.messages[index].angry = e.allDataMessage[0].angry;
            }
          }
        })
      });

    this.selectedContact = this.$store.getters['selectedUser'];
    // console.log(this.$store.getters['selectedUser']);
    //private channel
    // window.Echo.private(`messages.${localStorage.getItem("user_id")}`)
    //   .listen('NewMessage', (e) => {
    //     this.handleIncoming(e.message);
    //   })
  },
}
</script>
<style type="scss">
.friend-list .theme--dark.v-badge .v-badge__badge:after {
  border-color: #363636;
}

.friend-list .theme--light.v-badge .v-badge__badge:after {
  border-color: #f5f5f5;
}
</style>
