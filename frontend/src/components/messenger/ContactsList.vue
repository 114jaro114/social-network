<template id="contacts-list" class="contacts-list">
<v-navigation-drawer width="600px" :floating="floating" left clipped app :permanent="$vuetify.breakpoint.mdAndUp">
  <v-app-bar class="fixed-bar" flat>
    <!-- <v-toolbar class="notiftoolbar" flat> -->
    <v-tabs grow rounded="false">
      <v-tab @click="btnConversation = true">Konverzácie</v-tab>
      <v-tab @click="btnConversation = false">Priatelia</v-tab>
    </v-tabs>
  </v-app-bar>

  <!-- <v-divider v-if="!$vuetify.theme.dark" class="mt-0 mb-0" /> -->

  <v-container class="pt-0 friend-list">
    <v-card class="" elevation="0" v-if="btnConversation">
      <v-list-item class="p-0" v-if="conversations.length == 0">
        <v-text-field class="mt-6" v-model="search" label="Vyhľadať" filled rounded dense append-icon="mdi-magnify" clearable disabled></v-text-field>
      </v-list-item>
      <v-list-item class="p-0" v-else>
        <v-text-field class="mt-6" v-model="search" label="Vyhľadať" filled rounded dense append-icon="mdi-magnify" clearable></v-text-field>
      </v-list-item>
      <v-divider class="mt-0 mb-0"></v-divider>

      <v-list class="p-0" dense>
        <v-list-item v-if="overlayConversations != false" inactive disabled>
          <v-overlay :value="overlayConversations" :absolute="true" :opacity="0">
            <v-progress-circular indeterminate size="24" color="primary"></v-progress-circular>
          </v-overlay>
        </v-list-item>
        <v-list-item class="justify-center pl-0 pr-0" v-if="filteredConversations.length == 0 && conversations.length != 0" disabled>
          <v-list-item-content>
            <v-card class="rounded-lg card-comments" elevation="0">
              <v-btn color="primary" icon fab>
                <!-- <v-icon style="font-size: 24px">mdi-cancel</v-icon> -->
                <span class="">Žiadne výsledky</span>
              </v-btn>
            </v-card>
          </v-list-item-content>
        </v-list-item>

        <v-list-item class="justify-center pl-0 pr-0" v-if="conversations.length == 0 && overlay == false" disabled>
          <v-list-item-content>
            <v-card class="rounded-lg card-comments" elevation="0">
              <v-btn color="primary" icon fab>
                <span>Žiadne Konverzácie</span>
              </v-btn>
            </v-card>
          </v-list-item-content>
        </v-list-item>
        <v-list-item-group color="primary" v-model="selectedConversation">
          <template v-for="(item, index) in filteredConversations">
            <v-list-item class="p-0" :key="index" @click="selectContact(item)">
              <v-badge bottom dot bordered :color="getColor(item.status)" offset-x="10" offset-y="10" class="mr-2">
                <v-avatar color="primary" size="40" v-if="item.avatar == null">
                  <span class="text-uppercase white--text">{{ item.name.charAt(0) }}</span>
                </v-avatar>
                <v-avatar color="primary" size="40" v-else>
                  <img :src="`${$root.envUrlNoApi}/storage/user-avatar/${item.avatar}`">
                </v-avatar>
              </v-badge>

              <v-list-item-content>
                <v-card class="p-2 rounded-lg card-comments" elevation="0">
                  <v-list-item-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pl-1">
                    <v-chip small @click="getFriendProfileData(item)">
                      <v-icon left class="mr-1" small>mdi-account-circle-outline</v-icon>{{item.name}}
                    </v-chip>
                    <v-badge v-if="item.unread != null" :content="item.unread" :value="item.unread" color="orange" bordered offset-x="-5" offset-y="5"></v-badge>
                    <!-- <v-spacer /> -->
                    <!-- <v-tooltip bottom>
                      <template v-slot:activator="{ on }">
                        <v-btn v-on="on" icon small >
                          <v-icon small>mdi-information</v-icon>
                        </v-btn>
                      </template>
                      <span>Zobraziť profil</span>
                    </v-tooltip> -->
                  </v-list-item-title>
                  <v-list-item-subtitle class="text-left grey--text font-weight-bold p-0 pl-1" v-text="item.text" v-if="item.unread == 0"></v-list-item-subtitle>
                  <v-list-item-subtitle class="text-left primary--text font-weight-bold p-0 pl-1" v-text="item.text" v-else></v-list-item-subtitle>
                  <v-list-item-subtitle class="pl-1 text-left">
                    <span>
                      <!-- minutes -->
                      <v-tooltip bottom v-if="Math.round((new Date().getTime() - new Date(item.created_at).getTime()) / 60000) < 60">
                        <template v-slot:activator="{ on, attrs }">
                          <v-btn v-bind="attrs" v-on="on" x-small text rounded plain class="d-flex justify-start pl-0">
                            <span v-if="Math.round((new Date().getTime() - new Date(item.created_at).getTime()) / 60000) > 1">
                              Pred {{Math.round((new Date().getTime() - new Date(item.created_at).getTime()) / 60000)}} minútami
                            </span>
                            <span v-else>
                              Pred 1 minútou
                            </span>
                          </v-btn>
                        </template>
                        <span>
                          <v-icon class="mr-1" small color="white">mdi-clock</v-icon>{{item.created_at.toLocaleString()}}
                        </span>
                      </v-tooltip>

                      <!-- hours -->
                      <v-tooltip bottom v-else-if="Math.round((new Date().getTime() - new Date(item.created_at).getTime()) / 60000) < 1440">
                        <template v-slot:activator="{ on, attrs }">
                          <v-btn v-bind="attrs" v-on="on" x-small text rounded plain class="d-flex justify-start pl-0">
                            <span v-if="Math.floor(Math.round((new Date().getTime() - new Date(item.created_at).getTime()) / 60000)/60) > 1">
                              Pred {{Math.floor(Math.round((new Date().getTime() - new Date(item.created_at).getTime()) / 60000)/60)}} hodinami
                            </span>
                            <span v-else>
                              Pred 1 hodinou
                            </span>
                          </v-btn>
                        </template>
                        <span>
                          <v-icon class="mr-1" small color="white">mdi-clock</v-icon>{{item.created_at.toLocaleString()}}
                        </span>
                      </v-tooltip>

                      <!-- days -->
                      <v-tooltip bottom v-else-if="Math.round((new Date().getTime() - new Date(item.created_at).getTime()) / 60000) < 43200">
                        <template v-slot:activator="{ on, attrs }">
                          <v-btn v-bind="attrs" v-on="on" x-small text rounded plain class="d-flex justify-start pl-0">
                            <span v-if="Math.floor(Math.round((new Date().getTime() - new Date(item.created_at).getTime()) / 60000)/1440) > 1">
                              Pred {{Math.floor(Math.round((new Date().getTime() - new Date(item.created_at).getTime()) / 60000)/1440)}} dňami
                            </span>
                            <span v-else>
                              Pred 1 dňom
                            </span>
                          </v-btn>
                        </template>
                        <span>
                          <v-icon class="mr-1" small color="white">mdi-clock</v-icon>{{item.created_at.toLocaleString()}}
                        </span>
                      </v-tooltip>

                      <!-- months -->
                      <v-tooltip bottom v-else-if="Math.round((new Date().getTime() - new Date(item.created_at).getTime()) / 60000) < 525600">
                        <template v-slot:activator="{ on, attrs }">
                          <v-btn v-bind="attrs" v-on="on" x-small text rounded plain class="d-flex justify-start pl-0">
                            <span v-if="Math.floor(Math.round((new Date().getTime() - new Date(item.created_at).getTime()) / 60000)/43200) > 1">
                              Pred {{Math.floor(Math.round((new Date().getTime() - new Date(item.created_at).getTime()) / 60000)/43200)}} mesiacmi
                            </span>
                            <span v-else>
                              Pred 1 mesiacom
                            </span>
                          </v-btn>
                        </template>
                        <span>
                          <v-icon class="mr-1" small color="white">mdi-clock</v-icon>{{item.created_at.toLocaleString()}}
                        </span>
                      </v-tooltip>

                      <!-- years -->
                      <v-tooltip bottom v-else>
                        <template v-slot:activator="{ on, attrs }">
                          <v-btn v-bind="attrs" v-on="on" x-small text rounded plain class="d-flex justify-start pl-0">
                            <span v-if="Math.floor(Math.round((new Date().getTime() - new Date(item.created_at).getTime()) / 60000)/525600) > 1">
                              Pred {{Math.floor(Math.round((new Date().getTime() - new Date(item.created_at).getTime()) / 60000)/525600)}} rokmi
                            </span>
                            <span v-else>
                              Pred 1 rokom
                            </span>
                          </v-btn>
                        </template>
                        <span>
                          <v-icon class="mr-1" small color="white">mdi-clock</v-icon>{{item.created_at.toLocaleString()}}
                        </span>
                      </v-tooltip>
                    </span>
                  </v-list-item-subtitle>
                </v-card>
              </v-list-item-content>
            </v-list-item>
          </template>
        </v-list-item-group>
      </v-list>
    </v-card>
    <v-card rounded elevation="0" v-else>
      <v-list-item class="p-0" v-if="friends.length == 0">
        <v-text-field class="mt-6" v-model="search" label="Vyhľadať" filled rounded dense append-icon="mdi-magnify" clearable disabled></v-text-field>
      </v-list-item>
      <v-list-item class="p-0" v-else>
        <v-text-field class="mt-6" v-model="search" label="Vyhľadať" filled rounded dense append-icon="mdi-magnify" clearable></v-text-field>
      </v-list-item>
      <v-divider class="mt-0 mb-0"></v-divider>

      <v-list class="p-0" dense>
        <v-list-item v-if="overlay != false" inactive disabled>
          <v-overlay :value="overlay" :absolute="true" :opacity="0">
            <v-progress-circular indeterminate size="24" color="primary"></v-progress-circular>
          </v-overlay>
        </v-list-item>
        <v-list-item class="justify-center pl-0 pr-0" v-if="filteredFriends.length == 0 && friends.length != 0" disabled>
          <v-list-item-content>
            <v-card class="rounded-lg card-comments" elevation="0">
              <v-btn color="primary" icon fab>
                <!-- <v-icon style="font-size: 24px">mdi-cancel</v-icon> -->
                <span class="">Žiadne výsledky</span>
              </v-btn>
            </v-card>
          </v-list-item-content>
        </v-list-item>

        <v-list-item class="justify-center pl-0 pr-0" v-if="friends.length == 0 && overlay == false" disabled>
          <v-list-item-content>
            <v-card class="rounded-lg card-comments" elevation="0">
              <v-btn color="primary" icon fab>
                <span>Žiadny priatelia</span>
              </v-btn>
            </v-card>
          </v-list-item-content>
        </v-list-item>

        <v-list-item-group color="primary" v-model="selectedFriend">
          <template v-for="(item, index) in filteredFriends">
            <v-list-item class="p-0" :key="index" @click="selectContact(item)">
              <v-badge bottom dot bordered :color="getColor(item.status)" offset-x="10" offset-y="10" class="mr-2">
                <v-avatar color="primary" size="40" v-if="item.avatar == null">
                  <span class="text-uppercase white--text">{{ item.name.charAt(0) }}</span>
                </v-avatar>
                <v-avatar color="primary" size="40" v-else>
                  <img :src="`${$root.envUrlNoApi}/storage/user-avatar/${item.avatar}`">
                </v-avatar>
              </v-badge>

              <v-list-item-content>
                <v-card class="p-2 rounded-lg card-comments" elevation="0">
                  <v-list-item-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pl-1">
                    <v-chip style="height:28px" @click="getFriendProfileData(item)">
                      <v-icon left class="mr-1" small>mdi-account-circle-outline</v-icon>{{item.name}}
                    </v-chip>
                    <v-badge v-if="item.unread != null" :content="item.unread" :value="item.unread" color="orange" bordered offset-x="-5" offset-y="7"></v-badge>
                    <v-spacer />
                    <!-- <v-tooltip bottom>
                      <template v-slot:activator="{ on }">
                        <v-btn v-on="on" icon small @click="getFriendProfileData(item)">
                          <v-icon small>mdi-information</v-icon>
                        </v-btn>
                      </template>
                      <span>Zobraziť profil</span>
                    </v-tooltip> -->
                    <!-- <v-tooltip bottom>
                        <template v-slot:activator="{ on }">
                          <v-btn v-on="on" icon small @click="selectDataAboutUser(item)">
                            <v-icon small>mdi-forum</v-icon>
                          </v-btn>
                        </template>
                        <span>Otvoriť chat</span>
                      </v-tooltip> -->
                  </v-list-item-title>
                </v-card>
              </v-list-item-content>
            </v-list-item>
            <!-- <v-divider class="m-0" v-if="i < filteredFriends.length - 1" :key="i"></v-divider> -->
          </template>
        </v-list-item-group>
      </v-list>
    </v-card>
  </v-container>
  <template v-slot:append>
    <div class="p-3">
      <v-btn class="primary" :loading="overlay" to="/decentralized_messenger" block>Decentralizovaný chat</v-btn>
    </div>
  </template>
</v-navigation-drawer>
</template>
<script>
import axios from 'axios';
import _ from 'lodash';
export default {
  props: {
    contacts: {
      type: Array,
      default: () => []
    },
    closeChat: {
      type: String,
      default: null
    }
  },

  data() {
    return {
      show2: false,
      // selected: 0
      selected: null,
      // chatInput
      show: false,
      overlay: true,
      overlayConversations: true,
      marker: true,
      iconIndex: 0,
      icons: [
        'mdi-emoticon',
        'mdi-emoticon-neutral',
      ],
      autoselectMenu: false,
      i18n: {
        search: 'Vyhľadať',
        notfound: 'Neboli nájdené žiadne emoji',
        categories: {
          search: 'Výsledok vyhľadávania',
          recent: '',
          people: '',
          nature: '',
          foods: '',
          activity: '',
          places: '',
          objects: '',
          symbols: '',
          flags: '',
          custom: '',
        }
      },

      message: '',

      // table friends
      footerProps: {
        itemsPerPageText: 'Počet riadkov',
        itemsPerPageOptions: [5, 10, 15, -1],
      },
      headerProps: {
        sortByText: "Zoradiť podľa"
      },
      headers: [{
          // text: 'Priateľ',
          align: 'center',
          value: 'name',
          sortable: false,
        },
        // {
        //   text: 'Email',
        //   align: 'center',
        //   value: 'email',
        //   sortable: false,
        // },
      ],
      search: '',
      selectedFriend: null,
      selectedConversation: null,
      friends: [],
      conversations: [],
      btnConversation: true,

      floating: false,
    }
  },

  methods: {
    getColor(e) {
      if (e == 'offline') return 'red'
      else return 'green'
    },
    selectContact(item) {
      if (item.from == undefined) {
        this.updateUnreadCount(item.name, true);
        if (this.$route.fullPath != `/messenger?name=${item.name}`) {
          this.$router.push(`/messenger?name=${item.name}`);
          this.selected = item
          this.$emit('selected', this.selected);
          this.$store.dispatch('selectedUser', {
            id: this.selected.id,
            name: this.selected.name,
            email: this.selected.email,
            status: this.selected.status,
            avatar: this.selected.avatar,
            created_at: this.selected.created_at,
            unread: this.selected.unread,
          });
        }
      } else {
        this.updateUnreadCount(item.name, true);
        if (this.$route.fullPath != `/messenger?name=${item.name}`) {
          this.$router.push(`/messenger?name=${item.name}`);
          item.id = item.from;
          this.selected = item
          this.$emit('selected', this.selected);
          this.$store.dispatch('selectedUser', {
            id: this.selected.from,
            name: this.selected.name,
            email: this.selected.email,
            status: this.selected.status,
            avatar: this.selected.avatar,
            created_at: this.selected.created_at,
            unread: this.selected.unread,
          });
        }
      }
    },

    changeChatByUrl() {
      if (this.$route.query.name != null) {
        for (var i = 0; i < this.friends.length; i++) {
          if (this.$route.query.name == this.friends[i].name) {
            this.selectedFriend = i;
            this.selected = this.friends[i];
            this.$emit('selected', this.selected);
            this.$store.dispatch('selectedUser', {
              id: this.selected.id,
              name: this.selected.name,
              email: this.selected.email,
              status: this.selected.status,
              avatar: this.selected.avatar,
              created_at: this.selected.created_at,
              unread: this.selected.unread,
            });
          }
        }
      }
    },

    changeChatByUrlConversation() {
      if (this.$route.query.name != null) {
        for (var i = 0; i < this.conversations.length; i++) {
          if (this.$route.query.name == this.conversations[i].name) {
            this.selectedConversation = i;
          }
        }
      }
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
          this.overlay = false;
          this.friends = res.data;
          this.changeChatByUrl();
        });
    },

    getConversation() {
      const api = `${process.env.VUE_APP_API_URL}/contacts`;
      const config = {
        headers: {
          Accept: "application/json",
          Authorization: "Bearer " + localStorage.getItem("authToken"),
        },
      };
      axios.get(api, config)
        .then(res => {
          this.conversations = res.data;
          this.selected = this.conversations.length ? this.conversations[0] : null;
          this.overlayConversations = false;
          this.changeChatByUrlConversation();
          if (this.$route.fullPath != '/messenger') {
            this.updateUnreadCount(this.$route.fullPath.match("=(.*)$")[1], true);
          }
        });
    },

    updateUnreadCount(contact, reset) {
      this.conversations = this.conversations.map((single) => {
        if (single.name !== contact) {
          return single;
        }

        if (reset)
          single.unread = 0;
        else
          single.unread += 1;
        return single;
      })
    },

    addFloatingToNavigationDrawer() {

      window.addEventListener('resize', () => {
        if (window.innerWidth < 960) {
          this.floating = true;
        } else {
          this.floating = false;
        }
      });
    },
  },
  updated() {
    //do something after updating vue instance
    this.selected = this.$store.getters['selectedUser'];
    this.selected = this.friends[this.selectedFriend];
    // this.changeChatByUrl();
    // this.changeChatByUrlConversation();
  },

  mounted() {
    //do something after mounting vue instance
    this.getConversation();
    this.getAllFriends();

    if (window.innerWidth < 960) {
      this.floating = true;
    } else {
      this.floating = false;
    }
  },

  created() {
    // this.myloadingvariable = this.$store.getters['contactListLoader'];
    this.selected = this.$store.getters['selectedUser'];
    //presence channel
    window.Echo.join('messages.' + localStorage.getItem("user_id"))
      .listen('NewMessage', (e) => {
        let position = this.conversations.reduce((acc, request, index) => {
          if (request.from == e.message.from) {
            acc.push(index);
          }
          return acc;
        }, []);
        if (position[0] != null) {
          if (this.$route.fullPath != `/messenger?name=${e.message.from_contact.name}`) {
            this.conversations[position[0]].id = e.message.id;
            this.conversations[position[0]].read = e.message.read;
            this.conversations[position[0]].text = e.message.text;
            this.conversations[position[0]].created_at = e.message.created_at;
            this.conversations[position[0]].totalUnreadMsgTo = e.message.totalUnreadMsgTo;
            this.conversations[position[0]].unread += 1;
          } else {
            this.conversations[position[0]].id = e.message.id;
            this.conversations[position[0]].read = e.message.read;
            this.conversations[position[0]].text = e.message.text;
            this.conversations[position[0]].created_at = e.message.created_at;
            this.conversations[position[0]].totalUnreadMsgTo = e.message.totalUnreadMsgTo;
          }
        }
      })
  },

  watch: {
    closeChat() {
      if (this.closeChat == "cau") {
        this.selectedFriend = null;
        this.selectedConversation = null;
        this.$emit('refreshCloseChat', 'refresh');
      }
    },
  },

  computed: {
    filteredConversations() {
      return _.orderBy(this.conversations.filter(item => {
        if (!this.search) return this.conversations;
        return (item.name.toLowerCase()
          .includes(this.search.toLowerCase()));
      }), 'headline');
    },

    filteredFriends() {
      return _.orderBy(this.friends.filter(item => {
        if (!this.search) return this.friends;
        return (item.name.toLowerCase()
          .includes(this.search.toLowerCase()));
      }), 'headline');
    },

    sortedContacts() {
      return _.sortBy(this.contacts, [(contact) => {
          if (contact == this.selected) {
            return Infinity;
          }
          return contact.unread;
        }])
        .reverse();
    },

    // newEvent
    icon() {
      return this.icons[this.iconIndex]
    },
    isDark() {
      return (this.$vuetify.theme.dark) ? 'dark' : 'light'
    },
  }
}
</script>
<style lang="scss" scoped>
.unread {
    background: #82e0a8;
    color: #fff;
    width: 20px;
    // right: 9px;
    // position: absolute;
    border-radius: 3px;
}
// .v-badge--dot .v-badge__badge {
//     width: 12px !important;
//     height: 12px !important;
//     border-radius: 10px !important;
// }

.theme--light .emoji-mart[data-v-7bc71df8] {
    width: 100% !important;
    border: 1px solid #0D47A1;
}

.theme--dark .emoji-mart[data-v-7bc71df8] {
    width: 100% !important;
    border: 1px solid #21CFF3;
}

.theme--light .emoji-mart-bar[data-v-7bc71df8] {
    border: 0 solid #0D47A1 !important;
}

.theme--dark .emoji-mart-bar[data-v-7bc71df8] {
    border: 0 solid #21CFF3 !important;
}

.theme--light .emoji-mart-anchor-bar[data-v-51188104] {
    background-color: #0D47A1 !important;
}

.theme--dark .emoji-mart-anchor-bar[data-v-51188104] {
    background-color: #21CFF3 !important;
}
.emoji-mart-anchors[data-v-51188104] {
    padding: 0 !important;
}
</style>
