<template>
<div class="">
  <v-toolbar flat>
    <v-tabs grow rounded="false">
      <v-tab @click="btnFriends = true">Priatelia</v-tab>
    </v-tabs>
  </v-toolbar>

  <v-divider v-if="!$vuetify.theme.dark" class="mt-0 mb-0" />

  <div v-if="btnFriends == true">
    <v-container class="p-0" v-if="$root.profile.friendships.friends && btnFriends == true">
      <v-card class="rounded pl-4 pr-4" elevation="0">
        <v-list-item-content class="justify-center pt-1">
          <div class="mx-auto text-center">
            <v-list class="pt-0 pb-0" dense>
              <v-list-item class="p-0" v-if="$root.profile.friendships.friends.length == 0">
                <v-text-field class="mt-6" v-model="search" label="Vyhľadať" filled rounded dense append-icon="mdi-magnify" clearable disabled></v-text-field>
              </v-list-item>
              <v-list-item class="p-0" v-else>
                <v-text-field class="mt-6" v-model="search" label="Vyhľadať" filled rounded dense append-icon="mdi-magnify" clearable></v-text-field>
              </v-list-item>

              <v-divider class="m-0" v-if="filteredItems.length == 0 && $root.profile.friendships.friends.length != 0"></v-divider>
              <v-list-item class="justify-center p-0" v-if="filteredItems.length == 0 && $root.profile.friendships.friends.length != 0" disabled>
                <v-list-item-content class="pb-0">
                  <v-card class="rounded-lg card-comments" elevation="0">
                    <v-btn color="primary" icon fab>
                      <!-- <v-icon style="font-size: 24px">mdi-cancel</v-icon> -->
                      <span class="">Žiadne výsledky</span>
                    </v-btn>
                  </v-card>
                </v-list-item-content>
              </v-list-item>

              <v-divider class="m-0" v-if="$root.profile.friendships.friends.length == 0 && $root.profile.friendships.friends_overlay == false"></v-divider>
              <v-list-item class="justify-center p-0" v-if="$root.profile.friendships.friends.length == 0 && $root.profile.friendships.friends_overlay == false" disabled>
                <v-list-item-content class="pb-0">
                  <v-card class="rounded-lg card-comments" elevation="0">
                    <v-btn color="primary" icon fab>
                      <span>Žiadny priatelia</span>
                    </v-btn>
                  </v-card>
                </v-list-item-content>
              </v-list-item>
            </v-list>
            <v-divider class="m-0" v-if="filteredItems.length != 0"></v-divider>
            <v-divider class="m-0" v-if="$root.profile.friendships.friends_overlay != false"></v-divider>
            <v-list class="p-0" dense>
              <v-list-item-group color="primary">
                <v-list-item v-if="$root.profile.friendships.friends_overlay != false" inactive disabled>
                  <v-overlay :value="$root.profile.friendships.friends_overlay" :absolute="true" :opacity="0">
                    <v-progress-circular indeterminate size="24" color="primary"></v-progress-circular>
                  </v-overlay>
                </v-list-item>
                <template v-for="(item, index) in filteredItems">
                  <v-list-item class="p-0" :key="item.id">
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
                          <v-chip style="height:28px" @click.stop @click="getFriendProfileData(item)">
                            <v-icon left class="mr-1" small>mdi-account-circle-outline</v-icon>{{item.name}}
                          </v-chip>
                          <v-spacer />

                          <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                              <v-btn v-on="on" icon small @click.stop @click="selectDataAboutUser(item)">
                                <v-icon small>mdi-forum</v-icon>
                              </v-btn>
                            </template>
                            <span>Otvoriť chat</span>
                          </v-tooltip>
                        </v-list-item-title>
                      </v-card>
                    </v-list-item-content>
                  </v-list-item>
                  <v-divider class="m-0" v-if="index < filteredItems.length-1" :key="index"></v-divider>
                </template>
              </v-list-item-group>
            </v-list>
          </div>
        </v-list-item-content>
      </v-card>
    </v-container>
    <v-container class="p-0" v-else>
      <!-- <v-subheader>Kontakty</v-subheader> -->
      <v-card class="rounded pl-4 pr-4" elevation="0">
        <v-list-item-content class="justify-center pt-1">
          <div class="mx-auto text-center">
            <v-list class="pt-0 pb-0" dense>
              <v-list-item class="p-0">
                <v-text-field class="mt-6" v-model="search" label="Vyhľadať" filled rounded dense append-icon="mdi-magnify" clearable disabled></v-text-field>
              </v-list-item>
            </v-list>

            <v-divider class="m-0"></v-divider>

            <v-list class="p-0" dense>
              <v-list-item-group color="primary">
                <v-list-item inactive disabled>
                  <v-overlay :value="true" :absolute="true" :opacity="0">
                    <!-- $root.profile.friendships.friends_overlay -->
                    <v-progress-circular indeterminate size="24" color="primary"></v-progress-circular>
                  </v-overlay>
                </v-list-item>
              </v-list-item-group>
            </v-list>
          </div>
        </v-list-item-content>
      </v-card>
    </v-container>
  </div>
  <!-- friendsip requests -->
  <div v-else>
    <v-container class="p-0" v-if="$root.toolbar.friendRequests.friendRequests">
      <v-card class="rounded pl-4 pr-4" elevation="0">
        <v-list-item-content class="justify-center pt-1">
          <div class="mx-auto text-center">
            <v-list class="pt-0 pb-0" dense>
              <v-list-item class="p-0" v-if="$root.toolbar.friendRequests.friendRequests.length == 0">
                <v-text-field class="mt-6" v-model="search" label="Vyhľadať" filled rounded dense append-icon="mdi-magnify" clearable disabled></v-text-field>
              </v-list-item>
              <v-list-item class="p-0" v-else>
                <v-text-field class="mt-6" v-model="search" label="Vyhľadať" filled rounded dense append-icon="mdi-magnify" clearable></v-text-field>
              </v-list-item>

              <v-divider class="m-0" v-if="filteredFriendRequests.length == 0 && $root.toolbar.friendRequests.friendRequests.length != 0"></v-divider>
              <v-list-item class="justify-center p-0" v-if="filteredFriendRequests.length == 0 && $root.toolbar.friendRequests.friendRequests.length != 0" disabled>
                <v-list-item-content class="pb-0">
                  <v-card class="rounded-lg card-comments" elevation="0">
                    <v-btn color="primary" icon fab>
                      <!-- <v-icon style="font-size: 24px">mdi-cancel</v-icon> -->
                      <span class="">Žiadne výsledky</span>
                    </v-btn>
                  </v-card>
                </v-list-item-content>
              </v-list-item>

              <v-divider class="m-0" v-if="$root.toolbar.friendRequests.friendRequests.length == 0 && $root.toolbar.friendRequests.friendRequests_overlay == false"></v-divider>
              <v-list-item class="justify-center p-0" v-if="$root.toolbar.friendRequests.friendRequests.length == 0 && $root.toolbar.friendRequests.friendRequests_overlay == false" disabled>
                <v-list-item-content class="pb-0">
                  <v-card class="rounded-lg card-comments" elevation="0">
                    <v-btn color="primary" icon fab>
                      <span>Žiadne žiadosti</span>
                    </v-btn>
                  </v-card>
                </v-list-item-content>
              </v-list-item>
            </v-list>
            <v-divider class="m-0" v-if="filteredFriendRequests.length != 0"></v-divider>
            <v-divider class="m-0" v-if="$root.toolbar.friendRequests.friendRequests_overlay != false"></v-divider>
            <v-list class="p-0" dense v-if="filteredFriendRequests.length != 0">
              <v-list-item-group color="primary">
                <v-list-item v-if="$root.toolbar.friendRequests.friendRequests_overlay != false" inactive disabled>
                  <v-overlay :value="$root.toolbar.friendRequests.friendRequests_overlay" :absolute="true" :opacity="0">
                    <v-progress-circular indeterminate size="24" color="primary"></v-progress-circular>
                  </v-overlay>
                </v-list-item>
                <template v-for="item in filteredFriendRequests">
                  <v-list-item class="p-0" :key="item.id">
                    <v-badge bordered bottom dot color="grey accent-4" offset-x="10" offset-y="10" class="mr-2" v-if="item.icon == null">
                      <v-avatar color="primary" size="40" v-if="item.icon == null">
                        <span class="text-uppercase white--text">{{ item.name.charAt(0) }}</span>
                      </v-avatar>
                      <v-avatar color="primary" size="40" v-else>
                        <img :src="`${$root.envUrlNoApi}/storage/user-avatar/${item.icon}`">
                      </v-avatar>
                    </v-badge>
                    <v-list-item-content>
                      <v-card class="p-2 rounded-lg card-comments" elevation="0">
                        <v-list-item-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pl-1">
                          <v-chip small>
                            <v-icon left class="mr-1" small>mdi-account-circle-outline</v-icon>{{item.name}}
                          </v-chip>
                          <v-spacer />
                          <v-btn class="mr-2" icon x-small color="primary" @click="acceptFriendshipRequest(item)" v-if="item.requester != $root.me.id">
                            <v-icon>mdi-check</v-icon>
                          </v-btn>
                          <v-btn icon x-small color="error" @click="refuseFriendshipRequest(item)" v-if="item.requester != $root.me.id">
                            <v-icon>mdi-close</v-icon>
                          </v-btn>
                          <v-chip text small color="error" @click="removeFromFriendshipList(item)" v-if="item.requester == $root.me.id">
                            Zrušiť žiadosť
                          </v-chip>
                        </v-list-item-title>
                        <v-list-item-subtitle class="pl-1 text-left">
                          <span>
                            <!-- minutes -->
                            <v-tooltip bottom v-if="Math.round((new Date().getTime() - new Date(item.updated_at).getTime()) / 60000) < 60">
                              <template v-slot:activator="{ on, attrs }">
                                <v-btn v-bind="attrs" v-on="on" x-small text rounded plain class="d-flex justify-start pl-0">
                                  <span v-if="Math.round((new Date().getTime() - new Date(item.updated_at).getTime()) / 60000) > 1">
                                    Pred {{Math.round((new Date().getTime() - new Date(item.updated_at).getTime()) / 60000)}} minútami
                                  </span>
                                  <span v-else>
                                    Pred 1 minútou
                                  </span>
                                </v-btn>
                              </template>
                              <span>
                                <v-icon class="mr-1" small color="white">mdi-clock</v-icon>{{item.updated_at.toLocaleString()}}
                              </span>
                            </v-tooltip>

                            <!-- hours -->
                            <v-tooltip bottom v-else-if="Math.round((new Date().getTime() - new Date(item.updated_at).getTime()) / 60000) < 1440">
                              <template v-slot:activator="{ on, attrs }">
                                <v-btn v-bind="attrs" v-on="on" x-small text rounded plain class="d-flex justify-start pl-0">
                                  <span v-if="Math.floor(Math.round((new Date().getTime() - new Date(item.updated_at).getTime()) / 60000)/60) > 1">
                                    Pred {{Math.floor(Math.round((new Date().getTime() - new Date(item.updated_at).getTime()) / 60000)/60)}} hodinami
                                  </span>
                                  <span v-else>
                                    Pred 1 hodinou
                                  </span>
                                </v-btn>
                              </template>
                              <span>
                                <v-icon class="mr-1" small color="white">mdi-clock</v-icon>{{item.updated_at.toLocaleString()}}
                              </span>
                            </v-tooltip>

                            <!-- days -->
                            <v-tooltip bottom v-else-if="Math.round((new Date().getTime() - new Date(item.updated_at).getTime()) / 60000) < 43200">
                              <template v-slot:activator="{ on, attrs }">
                                <v-btn v-bind="attrs" v-on="on" x-small text rounded plain class="d-flex justify-start pl-0">
                                  <span v-if="Math.floor(Math.round((new Date().getTime() - new Date(item.updated_at).getTime()) / 60000)/1440) > 1">
                                    Pred {{Math.floor(Math.round((new Date().getTime() - new Date(item.updated_at).getTime()) / 60000)/1440)}} dňami
                                  </span>
                                  <span v-else>
                                    Pred 1 dňom
                                  </span>
                                </v-btn>
                              </template>
                              <span>
                                <v-icon class="mr-1" small color="white">mdi-clock</v-icon>{{item.updated_at.toLocaleString()}}
                              </span>
                            </v-tooltip>

                            <!-- months -->
                            <v-tooltip bottom v-else-if="Math.round((new Date().getTime() - new Date(item.updated_at).getTime()) / 60000) < 525600">
                              <template v-slot:activator="{ on, attrs }">
                                <v-btn v-bind="attrs" v-on="on" x-small text rounded plain class="d-flex justify-start pl-0">
                                  <span v-if="Math.floor(Math.round((new Date().getTime() - new Date(item.updated_at).getTime()) / 60000)/43200) > 1">
                                    Pred {{Math.floor(Math.round((new Date().getTime() - new Date(item.updated_at).getTime()) / 60000)/43200)}} mesiacmi
                                  </span>
                                  <span v-else>
                                    Pred 1 mesiacom
                                  </span>
                                </v-btn>
                              </template>
                              <span>
                                <v-icon class="mr-1" small color="white">mdi-clock</v-icon>{{item.updated_at.toLocaleString()}}
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
          </div>
        </v-list-item-content>
      </v-card>
    </v-container>
    <v-container class="p-0" v-else>
      <!-- <v-subheader>Kontakty</v-subheader> -->
      <v-card class="rounded pl-4 pr-4" elevation="0">
        <v-list-item-content class="justify-center pt-1">
          <div class="mx-auto text-center">
            <v-list class="pt-0 pb-0">
              <v-list-item class="p-0">
                <v-text-field class="mt-6" v-model="search" label="Vyhľadať" filled rounded dense append-icon="mdi-magnify" clearable disabled></v-text-field>
              </v-list-item>
            </v-list>

            <v-divider class="m-0"></v-divider>

            <v-list class="p-0" dense>
              <v-list-item-group color="primary">
                <v-list-item inactive disabled>
                  <v-overlay :value="$root.toolbar.friendRequests.friendRequests_overlay" :absolute="true" :opacity="0">
                    <v-progress-circular indeterminate size="24" color="primary"></v-progress-circular>
                  </v-overlay>
                </v-list-item>
              </v-list-item-group>
            </v-list>
          </div>
        </v-list-item-content>
      </v-card>
    </v-container>
  </div>
</div>
</template>

<script>
// import axios from 'axios';
// import moment from 'moment';
import _ from 'lodash';
export default {
  data() {
    return {
      search: '',
      btnFriends: true,
    }
  },

  methods: {
    getColor(e) {
      if (e == 'offline') return 'red'
      else return 'green'
    },

    selectDataAboutUser(item) {
      this.$router.push(`/messenger?name=${item.name}`);
    },

    getFriendProfileData(item) {
      this.$router.push(`/profile/${item.id}`);
    },
  },

  computed: {
    filteredItems() {
      return _.orderBy(this.$root.profile.friendships.friends.filter(item => {
        if (!this.search) return this.$root.profile.friendships.friends;
        return (item.name.toLowerCase()
          .includes(this.search.toLowerCase()));
      }), 'headline');
    },
  },
}
</script>

<style lang="css" scoped>
</style>
