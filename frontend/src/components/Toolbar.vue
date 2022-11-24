<template>
<div>
  <v-overlay v-if="overlayLogout == true" :value="overlayLogout" style="background: rgba(255,255,255,0.5);backdrop-filter: blur(5px);z-index:6">
    <span class="mr-3">Prebieha odhlásenie</span>
    <v-progress-circular indeterminate size="24" color="white"></v-progress-circular>
  </v-overlay>
  <v-app-bar class="toolbar" clipped-left clipped-right dark app color="primary" elevation="0" :elevate-on-scroll="true" v-if="$root.me.overlayAvatar == true || $root.friendships.friendsLoading == true || $root.toolbar.allPossibleFriends.usersLoading == true
  || $root.toolbar.fewNewestMessages.overlayNewestMessages == true || $root.toolbar.fewNewestNotifications.overlayNewestNotifications == true || $root.toolbar.fewOldNotifications.overlayOldNotifications == true">
    <v-img class="mr-2" :lazy-src="require('../assets/logo_toolbar.png')" :src="require('../assets/logo_toolbar.png')" max-height="35" max-width="30"></v-img>
    <!-- home -->
    <v-skeleton-loader class="mr-2 hidden-sm-and-down btn_home" type="chip" v-if="this.$route.name != 'Home'"></v-skeleton-loader>
    <v-skeleton-loader class="mr-2 hidden-md-and-up btn_home_sm" type="avatar" v-if="this.$route.name != 'Home'"></v-skeleton-loader>
    <!-- ///// -->
    <v-skeleton-loader class="mr-2" type="chip" height="44"></v-skeleton-loader>
    <v-spacer />
    <v-skeleton-loader class="hidden-sm-and-down" height="40" width="40" style="margin:6px" type="avatar"></v-skeleton-loader>
    <v-skeleton-loader class="hidden-sm-and-down" height="24" width="24" style="margin:12px" type="button" rouned></v-skeleton-loader>
    <v-skeleton-loader class="hidden-sm-and-down" height="24" width="24" style="margin:12px" type="button" rouned></v-skeleton-loader>
    <v-skeleton-loader class="hidden-sm-and-down" height="24" width="24" style="margin:12px" type="button" rouned></v-skeleton-loader>
    <v-skeleton-loader class="hidden-sm-and-down" height="24" width="24" style="margin:12px" type="button" rouned></v-skeleton-loader>

    <v-skeleton-loader class="hidden-md-and-up" height="24" width="24" style="margin:12px" type="button" rouned></v-skeleton-loader>
  </v-app-bar>
  <v-app-bar class="toolbar" clipped-left clipped-right dark app color="primary" elevation="0" :elevate-on-scroll="true" v-else>

    <v-img class="mr-2" :lazy-src="require('../assets/logo_toolbar.png')" :src="require('../assets/logo_toolbar.png')" max-height="35" max-width="30"></v-img>

    <v-btn class="mr-2 hidden-sm-and-down" text large rounded outlined v-if="this.$route.name != 'Home'" to="/home">
      Domov
    </v-btn>

    <!-- <v-btn class="mr-2 hidden-sm-and-down" text large rounded outlined to="/test2">
      Test2
    </v-btn> -->
    <!-- <v-btn class="mr-2 hidden-sm-and-down" text large rounded outlined to="/test">
      Test
    </v-btn>-->

    <!-- <v-btn class="mr-2 hidden-sm-and-down" text large rounded outlined to="/images">
      Images
    </v-btn> -->

    <v-btn icon class="mr-2 hidden-md-and-up" text large rounded outlined v-if="this.$route.name != 'Home'" to="/home">
      <v-icon>
        mdi-home
      </v-icon>
    </v-btn>

    <v-menu max-width="310" v-model="findFriends" :close-on-content-click="false" offset-y v-if="$root.toolbar.allPossibleFriends.users">
      <template v-slot:activator="{ on, attrs }">
        <v-btn class="mr-2" text large rounded outlined v-bind="attrs" v-on="on">
          <v-icon>mdi-magnify</v-icon>
          Vyhľadávanie
        </v-btn>
      </template>

      <v-card class="rounded">
        <v-list-item-content class="justify-center pt-1">
          <!-- <div class="mx-auto text-center"> -->
          <v-list class="pt-0 pb-0 mx-auto text-center" width="310">
            <v-list-item class="pt-0"
              v-if="$root.toolbar.allPossibleFriends.users.length == 0 && $root.toolbar.friendRequests.friendRequests.length == 0 && $root.toolbar.allPossibleFriends.usersLoading != true && $root.friendships.friendsLoading != true">
              <v-text-field class="mt-6" v-model="search" label="Vyhľadať" filled rounded dense append-icon="mdi-magnify" clearable disabled></v-text-field>
            </v-list-item>
            <v-list-item class="pt-0" v-else>
              <v-text-field class="mt-6" v-model="search" label="Vyhľadať" filled rounded dense append-icon="mdi-magnify" clearable></v-text-field>
            </v-list-item>

            <v-divider class="m-0" v-if="$root.toolbar.allPossibleFriends.usersLoading == true && $root.friendships.friendsLoading == true"></v-divider>
            <v-list-item v-if="overlay1 != false" inactive disabled>
              <v-overlay :value="overlay1" :absolute="true" :opacity="0">
                <v-progress-circular indeterminate size="24" color="primary"></v-progress-circular>
              </v-overlay>
            </v-list-item>

            <v-divider class="m-0"
              v-if="filteredUsers.length == 0 && filteredFriendRequests.length == 0 && ($root.toolbar.allPossibleFriends.users.length != 0 || $root.toolbar.friendRequests.friendRequests.length) != 0 && $root.toolbar.allPossibleFriends.usersLoading != true && $root.friendships.friendsLoading != true">
            </v-divider>
            <v-list-item class="justify-center"
              v-if="filteredUsers.length == 0 && filteredFriendRequests.length == 0 && ($root.toolbar.allPossibleFriends.users.length != 0 || $root.toolbar.friendRequests.friendRequests.length) != 0 && $root.toolbar.allPossibleFriends.usersLoading != true && $root.friendships.friendsLoading != true"
              disabled>
              <!-- <v-divider class="m-0" v-if="filteredUsers.length == 0 && filteredFriendRequests.length == 0 && users.length != 0 && friendRequests.length != 0"></v-divider>
              <v-list-item class="justify-center" v-if="filteredUsers.length == 0 && filteredFriendRequests.length == 0 && users.length != 0 && friendRequests.length != 0" disabled> -->
              <v-list-item-content class="pb-0">
                <v-card class="rounded-lg card-comments" elevation="0">
                  <v-btn color="primary" icon fab>
                    <!-- <v-icon style="font-size: 24px">mdi-cancel</v-icon> -->
                    <span class="">Žiadne výsledky</span>
                  </v-btn>
                </v-card>
              </v-list-item-content>
            </v-list-item>

            <v-divider class="m-0"
              v-if="$root.toolbar.allPossibleFriends.users.length == 0 && $root.toolbar.friendRequests.friendRequests.length == 0 && $root.toolbar.allPossibleFriends.usersLoading != true && $root.friendships.friendsLoading != true"></v-divider>
            <v-list-item class="justify-center"
              v-if="$root.toolbar.allPossibleFriends.users.length == 0 && $root.toolbar.friendRequests.friendRequests.length == 0 && $root.toolbar.allPossibleFriends.usersLoading != true && $root.friendships.friendsLoading != true" disabled>
              <!-- <v-divider class="m-0" v-if="filteredUsers.length == 0 && filteredFriendRequests.length == 0 && users.length != 0 && friendRequests.length != 0"></v-divider>
              <v-list-item class="justify-center" v-if="filteredUsers.length == 0 && filteredFriendRequests.length == 0 && users.length != 0 && friendRequests.length != 0" disabled> -->
              <v-list-item-content class="pb-0">
                <v-card class="rounded-lg card-comments" elevation="0">
                  <v-btn color="primary" icon fab>
                    <!-- <v-icon style="font-size: 24px">mdi-cancel</v-icon> -->
                    <span>Neexistuje žiaden záznam</span>
                  </v-btn>
                </v-card>
              </v-list-item-content>
            </v-list-item>
          </v-list>
          <v-list class="p-0" dense>
            <v-list-item-group color="primary">
              <v-divider class="mt-0 mb-0" v-if="filteredFriendRequests.length != 0"></v-divider>
              <v-subheader v-if="filteredFriendRequests.length != 0">Žiadosti o priateľstvo</v-subheader>
              <template v-for="item in filteredFriendRequests">
                <v-list-item :key="item.id" :to="'/profile/' + item.id">
                  <v-badge bordered bottom color="grey accent-4" offset-x="25" dot offset-y="18" v-if="item.icon == null">
                    <v-list-item-avatar style="border: 1px; border-color:primary" color="primary" size="36" class="ml-0">
                      <span v-if="item.name != null" class="white--text">{{item.name.charAt(0).toUpperCase() }}</span>
                    </v-list-item-avatar>
                  </v-badge>

                  <v-badge bordered bottom color="grey accent-4" offset-x="25" dot offset-y="18" v-else>
                    <v-list-item-avatar class="ml-0">
                      <v-img :lazy-src="item.icon" :src="item.icon" :alt="item.alt"></v-img>
                    </v-list-item-avatar>
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
                              <v-icon left class="mr-1 align-middle" small color="white">mdi-clock-outline</v-icon>
                              <span class="align-middle">{{new Date(Date.parse(item.updated_at)).toLocaleString()}}</span>
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
                              <v-icon left class="mr-1 align-middle" small color="white">mdi-clock-outline</v-icon>
                              <span class="align-middle">{{new Date(Date.parse(item.updated_at)).toLocaleString()}}</span>
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
                              <v-icon left class="mr-1 align-middle" small color="white">mdi-clock-outline</v-icon>
                              <span class="align-middle">{{new Date(Date.parse(item.updated_at)).toLocaleString()}}</span>
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
                              <v-icon left class="mr-1 align-middle" small color="white">mdi-clock-outline</v-icon>
                              <span class="align-middle">{{new Date(Date.parse(item.updated_at)).toLocaleString()}}</span>
                            </span>
                          </v-tooltip>

                          <!-- years -->
                          <v-tooltip bottom v-else>
                            <template v-slot:activator="{ on, attrs }">
                              <v-btn v-bind="attrs" v-on="on" x-small text rounded plain class="d-flex justify-start pl-0">
                                <span v-if="Math.floor(Math.round((new Date().getTime() - new Date(item.updated_at).getTime()) / 60000)/525600) > 1">
                                  Pred {{Math.floor(Math.round((new Date().getTime() - new Date(item.updated_at).getTime()) / 60000)/525600)}} rokmi
                                </span>
                                <span v-else>
                                  Pred 1 rokom
                                </span>
                              </v-btn>
                            </template>
                            <span>
                              <v-icon left class="mr-1 align-middle" small color="white">mdi-clock-outline</v-icon>
                              <span class="align-middle">{{new Date(Date.parse(item.updated_at)).toLocaleString()}}</span>
                            </span>
                          </v-tooltip>
                        </span>
                      </v-list-item-subtitle>
                    </v-card>
                  </v-list-item-content>
                </v-list-item>
              </template>
              <v-divider class="mt-0 mb-0" v-if="filteredUsers.length != 0"></v-divider>

              <v-subheader v-if="filteredUsers.length != 0">Ľudia, ktorých možno poznáte</v-subheader>

              <template v-for="item in filteredUsers">
                <v-list-item :key="item.id" :to="'/profile/' + item.id">
                  <v-badge bordered bottom color="grey accent-4" offset-x="25" dot offset-y="18" v-if="item.icon == null">
                    <v-list-item-avatar style="border: 1px; border-color:primary" color="primary" size="36" class="ml-0">
                      <span class="white--text">{{ item.name.charAt(0).toUpperCase() }}</span>
                    </v-list-item-avatar>
                  </v-badge>

                  <v-badge bordered bottom color="grey accent-4" offset-x="25" dot offset-y="18" v-else>
                    <v-list-item-avatar class="ml-0">
                      <v-img :lazy-src="item.icon" :src="item.icon" :alt="item.alt"></v-img>
                    </v-list-item-avatar>
                  </v-badge>
                  <v-list-item-content>
                    <v-card class="p-2 rounded-lg card-comments" elevation="0">
                      <v-list-item-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pl-1">
                        <v-chip small>
                          <v-icon left class="mr-1" small>mdi-account-circle-outline</v-icon>{{item.name}}
                        </v-chip>
                        <v-spacer />
                        <v-btn icon fab x-small color="primary" @click.prevent="sendFriendshipRequest(item)" @mousedown.stop @touchstart.native.stop>
                          <v-icon>mdi-account-plus</v-icon>
                        </v-btn>
                      </v-list-item-title>
                      <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">5 spoločných priateľov</v-card-subtitle>
                    </v-card>
                  </v-list-item-content>
                </v-list-item>
                <!-- <v-divider class="m-0" v-if="i < filteredItems.length - 1" :key="i"></v-divider> -->
              </template>
              <v-divider class="mt-0" v-if="filteredUsers.length != 0 || filteredFriendRequests.length != 0"></v-divider>
              <v-dialog v-if="filteredUsers.length != 0 || filteredFriendRequests.length != 0" v-model="dialogFindFriends" fullscreen hide-overlay transition="dialog-bottom-transition">
                <template v-slot:activator="{ on, attrs }">
                  <v-btn rounded text v-bind="attrs" v-on="on">
                    <v-icon class="mr-1">mdi-expand-all</v-icon>Zobraziť viac
                  </v-btn>
                </template>
                <!-- dialog -->
                <v-card class="toolbar-friends-dialog">
                  <v-toolbar elevation="0" dark color="primary">
                    <v-btn icon dark @click="dialogFindFriends = false">
                      <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Nájsť priateľov</v-toolbar-title>
                  </v-toolbar>
                  <v-container class="toolbar-friends pb-14 p-3">
                    <v-row class="m-0">
                      <v-col cols="12" xl="12" lg="12" md="12" sm="12" xs="12" class="pl-0 pr-0 pb-0">
                        <v-row class="ml-0 mr-0 mb-3">
                          <v-card class="w-100 rounded" elevation="0" min-height="50">
                            <v-card-text class="p-0">
                              <div class="pt-0 pr-4 pl-4"
                                v-if="$root.toolbar.allPossibleFriends.users.length == 0 && $root.toolbar.friendRequests.friendRequests.length == 0 && $root.toolbar.allPossibleFriends.usersLoading != true && $root.friendships.friendsLoading != true">
                                <v-text-field class="mt-6" v-model="search2" label="Vyhľadať" filled rounded dense append-icon="mdi-magnify" clearable disabled></v-text-field>
                              </div>
                              <div class="pt-0 pr-4 pl-4" v-else>
                                <v-text-field class="mt-6" v-model="search2" label="Vyhľadať" filled rounded dense append-icon="mdi-magnify" clearable></v-text-field>
                              </div>

                              <v-divider class="m-0" v-if="$root.toolbar.allPossibleFriends.usersLoading == true && $root.friendships.friendsLoading == true"></v-divider>
                              <v-list-item v-if="overlay1 != false" inactive disabled>
                                <v-overlay :value="overlay1" :absolute="true" :opacity="0">
                                  <v-progress-circular indeterminate size="24" color="primary"></v-progress-circular>
                                </v-overlay>
                              </v-list-item>
                            </v-card-text>
                          </v-card>
                        </v-row>
                      </v-col>
                      <v-col cols="12" xl="6" lg="6" md="12" sm="12" xs="12" class="pl-0 pr-0 pb-0">
                        <v-row class="ml-0 mr-0 mb-3">
                          <v-card class="w-100 rounded" elevation="0" min-height="200">
                            <v-card-text class="p-0">
                              <v-subheader>Žiadosti o priateľstvo</v-subheader>

                              <v-list-item class="justify-center" v-if="$root.toolbar.friendRequests.friendRequests.length == 0" disabled>
                                <v-list-item-content class="pb-0">
                                  <v-card class="rounded-lg card-comments" elevation="0">
                                    <v-btn color="primary" icon fab>
                                      <!-- <v-icon style="font-size: 24px">mdi-cancel</v-icon> -->
                                      <span class="">Žiadne žiadosti o priateľstvo</span>
                                    </v-btn>
                                  </v-card>
                                </v-list-item-content>
                              </v-list-item>

                              <v-list-item class="justify-center" v-if="filteredFriendRequests2.length == 0 && $root.toolbar.friendRequests.friendRequests.length != 0 && $root.friendships.friendsLoading != true" disabled>
                                <v-list-item-content class="pb-0">
                                  <v-card class="rounded-lg card-comments" elevation="0">
                                    <v-btn color="primary" icon fab>
                                      <!-- <v-icon style="font-size: 24px">mdi-cancel</v-icon> -->
                                      <span class="">Žiadne výsledky</span>
                                    </v-btn>
                                  </v-card>
                                </v-list-item-content>
                              </v-list-item>

                              <template v-for="item in filteredFriendRequests2">
                                <v-list-item :key="item.id" :to="'/profile/' + item.id">
                                  <v-badge bordered bottom color="grey accent-4" offset-x="25" dot offset-y="18" v-if="item.icon == null">
                                    <v-list-item-avatar style="border: 1px; border-color:primary" color="primary" size="36" class="ml-0">
                                      <span v-if="item.name != null" class="white--text">{{item.name.charAt(0).toUpperCase() }}</span>
                                    </v-list-item-avatar>
                                  </v-badge>

                                  <v-badge bordered bottom color="grey accent-4" offset-x="25" dot offset-y="18" v-else>
                                    <v-list-item-avatar class="ml-0">
                                      <v-img :lazy-src="item.icon" :src="item.icon" :alt="item.alt"></v-img>
                                    </v-list-item-avatar>
                                  </v-badge>
                                  <v-list-item-content>
                                    <v-card class="p-2 rounded-lg card-comments" elevation="0">
                                      <v-list-item-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pl-1">
                                        <v-chip small>
                                          <v-icon left class="mr-1" small>mdi-account-circle-outline</v-icon>{{item.name}}
                                        </v-chip>
                                        <v-spacer />
                                        <v-btn class="mr-2" icon x-small color="primary" @click.stop @click="acceptFriendshipRequest(item)" v-if="item.requester != $root.me.id">
                                          <v-icon>mdi-check</v-icon>
                                        </v-btn>
                                        <v-btn icon x-small color="error" @click.stop @click="refuseFriendshipRequest(item)" v-if="item.requester != $root.me.id">
                                          <v-icon>mdi-close</v-icon>
                                        </v-btn>
                                        <v-chip text small color="error" @click.stop @click="removeFromFriendshipList(item)" v-if="item.requester == $root.me.id">
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
                                              <v-icon left class="mr-1 align-middle" small color="white">mdi-clock-outline</v-icon>
                                              <span class="align-middle">{{new Date(Date.parse(item.updated_at)).toLocaleString()}}</span>
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
                                              <v-icon left class="mr-1 align-middle" small color="white">mdi-clock-outline</v-icon>
                                              <span class="align-middle">{{new Date(Date.parse(item.updated_at)).toLocaleString()}}</span>
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
                                              <v-icon left class="mr-1 align-middle" small color="white">mdi-clock-outline</v-icon>
                                              <span class="align-middle">{{new Date(Date.parse(item.updated_at)).toLocaleString()}}</span>
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
                                              <v-icon left class="mr-1 align-middle" small color="white">mdi-clock-outline</v-icon>
                                              <span class="align-middle">{{new Date(Date.parse(item.updated_at)).toLocaleString()}}</span>
                                            </span>
                                          </v-tooltip>

                                          <!-- years -->
                                          <v-tooltip bottom v-else>
                                            <template v-slot:activator="{ on, attrs }">
                                              <v-btn v-bind="attrs" v-on="on" x-small text rounded plain class="d-flex justify-start pl-0">
                                                <span v-if="Math.floor(Math.round((new Date().getTime() - new Date(item.updated_at).getTime()) / 60000)/525600) > 1">
                                                  Pred {{Math.floor(Math.round((new Date().getTime() - new Date(item.updated_at).getTime()) / 60000)/525600)}} rokmi
                                                </span>
                                                <span v-else>
                                                  Pred 1 rokom
                                                </span>
                                              </v-btn>
                                            </template>
                                            <span>
                                              <v-icon left class="mr-1 align-middle" small color="white">mdi-clock-outline</v-icon>
                                              <span class="align-middle">{{new Date(Date.parse(item.updated_at)).toLocaleString()}}</span>
                                            </span>
                                          </v-tooltip>
                                        </span>
                                      </v-list-item-subtitle>
                                    </v-card>
                                  </v-list-item-content>
                                </v-list-item>
                              </template>
                            </v-card-text>
                          </v-card>
                        </v-row>
                      </v-col>
                      <v-col cols="12" xl="6" lg="6" md="12" sm="12" xs="12" class="pl-lg-3 pl-xl-3 pl-md-3 pl-0 pr-0 pb-0" min-height="245">
                        <v-row class="ml-0 mr-0 mb-3">
                          <v-card class="w-100 rounded" elevation="0" min-height="200">
                            <v-card-text class="p-0">
                              <v-subheader>Ľudia, ktorých možno poznáte</v-subheader>

                              <v-list-item class="justify-center" v-if="$root.toolbar.allPossibleFriends.users.length == 0" disabled>
                                <v-list-item-content class="pb-0">
                                  <v-card class="rounded-lg card-comments" elevation="0">
                                    <v-btn color="primary" icon fab>
                                      <v-icon style="font-size: 24px">mdi-cancel</v-icon>
                                      <span class="ml-2">Žiadny používatelia</span>
                                    </v-btn>
                                  </v-card>
                                </v-list-item-content>
                              </v-list-item>

                              <v-list-item class="justify-center" v-if="filteredUsers2.length == 0 && $root.toolbar.allPossibleFriends.users.length != 0 && $root.toolbar.allPossibleFriends.usersLoading != true" disabled>
                                <v-list-item-content class="pb-0">
                                  <v-card class="rounded-lg card-comments" elevation="0">
                                    <v-btn color="primary" icon fab>
                                      <!-- <v-icon style="font-size: 24px">mdi-cancel</v-icon> -->
                                      <span class="">Žiadne výsledky</span>
                                    </v-btn>
                                  </v-card>
                                </v-list-item-content>
                              </v-list-item>

                              <template v-for="item in filteredUsers2">
                                <v-list-item :key="item.id" :to="'/profile/' + item.id">
                                  <v-badge bordered bottom color="grey accent-4" offset-x="25" dot offset-y="18" v-if="item.icon == null">
                                    <v-list-item-avatar style="border: 1px; border-color:primary" color="primary" size="36" class="ml-0">
                                      <span class="white--text">{{ item.name.charAt(0).toUpperCase() }}</span>
                                    </v-list-item-avatar>
                                  </v-badge>

                                  <v-badge bordered bottom color="grey accent-4" offset-x="25" dot offset-y="18" v-else>
                                    <v-list-item-avatar class="ml-0">
                                      <v-img :lazy-src="item.icon" :src="item.icon" :alt="item.alt"></v-img>
                                    </v-list-item-avatar>
                                  </v-badge>
                                  <v-list-item-content>
                                    <v-card class="p-2 rounded-lg card-comments" elevation="0">
                                      <v-list-item-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pl-1">
                                        <v-chip small>
                                          <v-icon left class="mr-1" small>mdi-account-circle-outline</v-icon>{{item.name}}
                                        </v-chip>
                                        <v-spacer />
                                        <v-btn icon fab x-small color="primary" @click.prevent="sendFriendshipRequest(item)" @mousedown.stop @touchstart.native.stop>
                                          <v-icon>mdi-account-plus</v-icon>
                                        </v-btn>
                                      </v-list-item-title>
                                      <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">5 spoločných priateľov</v-card-subtitle>
                                    </v-card>
                                  </v-list-item-content>
                                </v-list-item>
                                <!-- <v-divider class="m-0" v-if="i < filteredItems.length - 1" :key="i"></v-divider> -->
                              </template>
                            </v-card-text>
                          </v-card>
                        </v-row>
                      </v-col>
                    </v-row>
                  </v-container>
                </v-card>
              </v-dialog>
            </v-list-item-group>
          </v-list>
          <!-- </div> -->
        </v-list-item-content>
      </v-card>
    </v-menu>

    <!-- avatar image -->
    <v-row class="mr-0 mt-0 mb-0 hidden-sm-and-down justify-end">
      <v-menu min-width="200px" offset-y :disabled="$root.me.overlayAvatar">
        <template v-slot:activator="{ on }">
          <v-btn icon x-large v-on="on">
            <v-avatar color="white" size="40" v-if="$root.me.overlayAvatar == true">
              <v-overlay :value="$root.me.overlayAvatar" :absolute="true" :opacity="0">
                <v-progress-circular indeterminate size="24" color="primary"></v-progress-circular>
              </v-overlay>
            </v-avatar>

            <v-avatar color="white" size="40" v-else-if="$root.me.avatar == null && $root.me.overlayAvatar == false">
              <span v-if="$root.me.name != null" class="text-uppercase primary--text">{{ $root.me.name.charAt(0) }}</span>
            </v-avatar>
            <v-avatar size="40" v-else>
              <img :lazy-src="`${$root.envUrlNoApi}/storage/user-avatar/${$root.me.avatar}`" :src="`${$root.envUrlNoApi}/storage/user-avatar/${$root.me.avatar}`">
            </v-avatar>
          </v-btn>
        </template>
        <v-card class="v-card-logout">
          <v-list-item-content class="justify-center">
            <div class="mx-auto text-center">
              <v-avatar color="primary" size="48" v-if="$root.me.overlayAvatar == true">
                <v-overlay :value="$root.me.overlayAvatar" :absolute="true" :opacity="0">
                  <v-progress-circular indeterminate size="24" color="white"></v-progress-circular>
                </v-overlay>
              </v-avatar>

              <v-avatar color="primary" size="48" v-else-if="$root.me.avatar == null && $root.me.overlayAvatar == false">
                <span v-if="$root.me.name != null" class="text-uppercase white--text">{{ $root.me.name.charAt(0) }}</span>
              </v-avatar>
              <v-avatar color="primary" size="48" v-else>
                <img :lazy-src="`${$root.envUrlNoApi}/storage/user-avatar/${$root.me.avatar}`" :src="`${$root.envUrlNoApi}/storage/user-avatar/${$root.me.avatar}`">
              </v-avatar>
              <!-- </v-avatar> -->
              <h4>{{ $root.me.name }}</h4>
              <p class="caption">
                {{ $root.me.email }}
              </p>
              <v-divider class="my-3"></v-divider>
              <v-btn depressed rounded text :to="'/profile/'+this.$root.me.id">
                <v-icon class="mr-1">mdi-clipboard-edit</v-icon> Môj profil
              </v-btn>
              <v-divider class="my-3"></v-divider>
              <v-btn depressed rounded text @click="logout">
                <v-icon class="mr-1">mdi-logout</v-icon>Odhlásiť sa
              </v-btn>
            </div>
          </v-list-item-content>
        </v-card>
      </v-menu>
      <!-- make new post ...... -->
      <v-menu :close-on-content-click="true" offset-y>
        <!-- class="p-2" bottom max-width="400px" rounded offset-y -->
        <template v-slot:activator="{ on }">
          <v-btn icon v-on="on">
            <v-icon>mdi-plus-box-multiple</v-icon>
          </v-btn>
        </template>
        <v-card class="v-card-logout">
          <v-list dense width="310">
            <v-list-item-group color="primary">
              <v-list-item>
                <v-icon class="mr-7">mdi-pencil-box</v-icon>
                <v-list-item-content>
                  <v-card class="p-2 rounded-lg card-comments" elevation="0">
                    <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-3 pl-1">
                      Príspevok
                    </v-card-title>
                    <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">Zdieľaj príspevok na hlavnej stránke</v-card-subtitle>
                  </v-card>
                </v-list-item-content>
              </v-list-item>

              <v-list-item>
                <v-icon class="mr-7">mdi-book-open-page-variant</v-icon>
                <v-list-item-content>
                  <v-card class="p-2 rounded-lg card-comments" elevation="0">
                    <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-3 pl-1">
                      Príbeh
                    </v-card-title>
                    <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">Zdieľaj fotku alebo video na svoj príbeh</v-card-subtitle>
                  </v-card>
                </v-list-item-content>
              </v-list-item>

              <v-list-item>
                <v-icon class="mr-7">mdi-shield-edit</v-icon>
                <v-list-item-content>
                  <v-card class="p-2 rounded-lg card-comments" elevation="0">
                    <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-3 pl-1">
                      Životná udalosť
                    </v-card-title>
                    <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">Pridajte životnú udalosť do svojho profilu</v-card-subtitle>
                  </v-card>
                </v-list-item-content>
              </v-list-item>

              <v-list-item>
                <v-icon class="mr-7">mdi-application</v-icon>
                <v-list-item-content>
                  <v-card class="p-2 rounded-lg card-comments" elevation="0">
                    <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-3 pl-1">
                      Stránka
                    </v-card-title>
                    <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">Spojte sa a Zdieľajte so zákazníkmi alebo fanúšikmi</v-card-subtitle>
                  </v-card>
                </v-list-item-content>
              </v-list-item>

              <v-list-item>
                <v-icon class="mr-7">mdi-account-group</v-icon>
                <v-list-item-content>
                  <v-card class="p-2 rounded-lg card-comments" elevation="0">
                    <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-3 pl-1">
                      Skupina
                    </v-card-title>
                    <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">Spojte sa s ľuďmi, ktorí majú rovnaké záujmy ako Vy</v-card-subtitle>
                  </v-card>
                </v-list-item-content>
              </v-list-item>

              <v-list-item>
                <v-icon class="mr-7">mdi-calendar-star</v-icon>
                <v-list-item-content>
                  <v-card class="p-2 rounded-lg card-comments" elevation="0">
                    <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-3 pl-1">
                      Podujatie
                    </v-card-title>
                    <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">Spojte ľudí pomocou verejného alebo súkromného podujatia</v-card-subtitle>
                  </v-card>
                </v-list-item-content>
              </v-list-item>
            </v-list-item-group>
          </v-list>
        </v-card>
      </v-menu>

      <!-- web -->
      <!-- messenger -->
      <v-menu :close-on-content-click="false" offset-y>
        <template v-slot:activator="{ on, attrs }">
          <v-btn icon v-bind="attrs" v-on="on">
            <v-badge :content="$store.getters['msgUnreadCounter']" :value="$store.getters['msgUnreadCounter']" :offset-x="11" :offset-y="12" color="orange" overlap top bordered>
              <!-- :content="1" :value="1" -->
              <v-icon>mdi-forum</v-icon>
            </v-badge>
          </v-btn>
        </template>

        <v-card class="rounded">
          <v-list-item-content class="justify-center pt-1">
            <div class="mx-auto text-center">
              <v-list class="pt-0 pb-0" width="310">
                <v-list-item class="pt-0" v-if="$root.toolbar.fewNewestMessages.fewNewestMessages.length == 0 && $root.toolbar.fewNewestMessages.overlayNewestMessages != true">
                  <v-text-field class="mt-6" v-model="searchMsg" label="Vyhľadať" filled rounded dense append-icon="mdi-magnify" clearable disabled></v-text-field>
                </v-list-item>
                <v-list-item class="pt-0" v-else>
                  <v-text-field class="mt-6" v-model="searchMsg" label="Vyhľadať" filled rounded dense append-icon="mdi-magnify" clearable></v-text-field>
                </v-list-item>

                <v-divider class="m-0" v-if="filteredNewestMessages.length == 0 && $root.toolbar.fewNewestMessages.fewNewestMessages.length != 0"></v-divider>
                <v-list-item class="justify-center" v-if="filteredNewestMessages.length == 0 && $root.toolbar.fewNewestMessages.fewNewestMessages.length != 0" disabled>
                  <v-list-item-content>
                    <v-card class="rounded-lg card-comments" elevation="0">
                      <v-btn color="primary" icon fab>
                        <!-- <v-icon style="font-size: 24px">mdi-cancel</v-icon> -->
                        <span class="">Žiadne výsledky</span>
                      </v-btn>
                    </v-card>
                  </v-list-item-content>
                </v-list-item>

                <v-divider class="m-0" v-if="$root.toolbar.fewNewestMessages.fewNewestMessages.length == 0 && $root.toolbar.fewNewestMessages.overlayNewestMessages != true"></v-divider>
                <v-list-item class="justify-center" v-if="$root.toolbar.fewNewestMessages.fewNewestMessages.length == 0 && $root.toolbar.fewNewestMessages.overlayNewestMessages != true" disabled>
                  <v-list-item-content>
                    <v-card class="rounded-lg card-comments" elevation="0">
                      <v-btn color="primary" icon fab>
                        <!-- <v-icon style="font-size: 24px">mdi-cancel</v-icon> -->
                        <span>Žiadne správy</span>
                      </v-btn>
                    </v-card>
                  </v-list-item-content>
                </v-list-item>
              </v-list>
              <v-divider class="m-0" v-if="filteredNewestMessages.length != 0"></v-divider>
              <v-list class="p-0" v-if="filteredNewestMessages.length != 0" three-line width="310" dense>
                <v-list-item-group color="primary">
                  <v-subheader v-if="filteredNewestMessages.length != 0">Najnovšie</v-subheader>
                  <template v-for="item in filteredNewestMessages">
                    <v-list-item :key="item.id" @click="selectDataAboutUser(item)">
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
                            <v-chip small>
                              <v-icon left class="mr-1" small>mdi-account-circle-outline</v-icon>{{item.name}}
                            </v-chip>
                            <v-badge v-if="item.read == 1" :content="'Prečítané'" :value="'Prečítané'" color="orange" offset-x="-5" offset-y="5"></v-badge>
                            <v-badge v-else :content="'Neprečítané'" :value="'Neprečítané'" color="orange" offset-x="-5" offset-y="5"></v-badge>
                          </v-list-item-title>

                          <v-list-item-subtitle class="text-left grey--text font-weight-bold p-0 pl-1 pt-1" v-text="item.text" v-if="item.read == 1"></v-list-item-subtitle>
                          <v-list-item-subtitle class="text-left primary--text font-weight-bold p-0 pl-1 pt-1" v-text="item.text" v-else></v-list-item-subtitle>
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
                                  <v-icon left class="mr-1 align-middle" small color="white">mdi-clock-outline</v-icon>
                                  <span class="align-middle">{{new Date(Date.parse(item.created_at)).toLocaleString()}}</span>
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
                                  <v-icon left class="mr-1 align-middle" small color="white">mdi-clock-outline</v-icon>
                                  <span class="align-middle">{{new Date(Date.parse(item.created_at)).toLocaleString()}}</span>
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
                                  <v-icon left class="mr-1 align-middle" small color="white">mdi-clock-outline</v-icon>
                                  <span class="align-middle">{{new Date(Date.parse(item.created_at)).toLocaleString()}}</span>
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
                                  <v-icon left class="mr-1 align-middle" small color="white">mdi-clock-outline</v-icon>
                                  <span class="align-middle">{{new Date(Date.parse(item.created_at)).toLocaleString()}}</span>
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
                                  <v-icon left class="mr-1 align-middle" small color="white">mdi-clock-outline</v-icon>
                                  <span class="align-middle">{{new Date(Date.parse(item.created_at)).toLocaleString()}}</span>
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
              <v-divider class="mt-0"></v-divider>
              <v-btn rounded text to="/messenger">
                <v-icon class="mr-1">mdi-expand-all</v-icon>Zobraziť všetky správy
              </v-btn>
            </div>
          </v-list-item-content>
        </v-card>
      </v-menu>
      <!-- web -->
      <!-- notifications -->
      <v-menu :close-on-content-click="false" offset-y>
        <template v-slot:activator="{ on, attrs }">
          <v-btn icon v-bind="attrs" v-on="on" @click="notificationsMenu= !notificationsMenu">
            <v-badge :content="$store.getters['notificationCounter']" :value="$store.getters['notificationCounter']" :offset-x="11" :offset-y="12" color="orange" overlap top bordered>
              <!-- :content="1" :value="1" -->
              <v-icon>mdi-bell</v-icon>
            </v-badge>
          </v-btn>
        </template>

        <v-card class="rounded">
          <v-list-item-content class="justify-center pt-1">
            <div class="mx-auto text-center">
              <v-list class="pt-0 pb-0" width="310">
                <v-list-item class="pt-0" v-if="$root.toolbar.fewNewestNotifications.fewNewestNotifications.length == 0 && $root.toolbar.fewOldNotifications.fewOldNotifications.length == 0">
                  <v-text-field class="mt-6" v-model="searchNtf" label="Vyhľadať" filled rounded dense append-icon="mdi-magnify" clearable disabled></v-text-field>
                </v-list-item>
                <v-list-item class="pt-0" v-else>
                  <v-text-field class="mt-6" v-model="searchNtf" label="Vyhľadať" filled rounded dense append-icon="mdi-magnify" clearable></v-text-field>
                </v-list-item>

                <v-divider class="m-0"
                  v-if="(filteredNewNotifications.length == 0 && $root.toolbar.fewNewestNotifications.fewNewestNotifications.length != 0) || (filteredOldNotifications.length == 0 && $root.toolbar.fewOldNotifications.fewOldNotifications.length != 0)">
                </v-divider>
                <v-list-item class="justify-center"
                  v-if="(filteredNewNotifications.length == 0 && $root.toolbar.fewNewestNotifications.fewNewestNotifications.length != 0) || (filteredOldNotifications.length == 0 && $root.toolbar.fewOldNotifications.fewOldNotifications.length != 0)"
                  disabled>
                  <v-list-item-content>
                    <v-card class="rounded-lg card-comments" elevation="0">
                      <v-btn color="primary" icon fab>
                        <!-- <v-icon style="font-size: 24px">mdi-cancel</v-icon> -->
                        <span class="">Žiadne výsledky</span>
                      </v-btn>
                    </v-card>
                  </v-list-item-content>
                </v-list-item>

                <v-divider class="m-0" v-if="$root.toolbar.fewNewestNotifications.fewNewestNotifications.length == 0 && $root.toolbar.fewOldNotifications.fewOldNotifications.length == 0"></v-divider>
                <v-list-item class="justify-center" v-if="$root.toolbar.fewNewestNotifications.fewNewestNotifications.length == 0 && $root.toolbar.fewOldNotifications.fewOldNotifications.length == 0" disabled>
                  <v-list-item-content>
                    <v-card class="rounded-lg card-comments" elevation="0">
                      <v-btn color="primary" icon fab>
                        <!-- <v-icon style="font-size: 24px">mdi-cancel</v-icon> -->
                        <span>Žiadne notifikácie</span>
                      </v-btn>
                    </v-card>
                  </v-list-item-content>
                </v-list-item>
              </v-list>
              <v-divider class="m-0" v-if="filteredNewNotifications.length != 0"></v-divider>

              <v-list class="p-0" v-if="filteredNewNotifications.length != 0 || filteredOldNotifications.length != 0" three-line width="310" dense>
                <v-list-item-group color="primary">
                  <v-subheader v-if="filteredNewNotifications.length != 0">Najnovšie</v-subheader>
                  <template v-for="item in filteredNewNotifications">
                    <v-list-item :key="item.id + '-newNotf'" @click="selectDataAboutNotification(item)">
                      <v-badge bottom dot bordered :color="getColor(item.status)" offset-x="10" offset-y="10" class="mr-2" v-if="item.name == 'admin'">
                        <v-avatar color="primary" size="40">
                          <v-img :lazy-src="require('../assets/logo_toolbar.png')" :src="require('../assets/logo_toolbar.png')" max-height="30" max-width="30"></v-img>
                        </v-avatar>
                      </v-badge>
                      <v-badge bottom dot bordered :color="getColor(item.status)" offset-x="10" offset-y="10" class="mr-2" v-else>
                        <v-avatar color="primary" size="40" v-if="item.avatar == null">
                          <span class="text-uppercase white--text">{{ item.name.charAt(0) }}</span>
                        </v-avatar>
                        <v-avatar color="primary" size="40" v-else>
                          <img :src="`${$root.envUrlNoApi}/storage/user-avatar/${item.avatar}`">
                        </v-avatar>
                      </v-badge>
                      <v-list-item-content>
                        <v-card class="p-2 rounded-lg card-comments" elevation="0">
                          <v-list-item-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pl-1" v-if="item.name == 'admin'">
                            <v-chip small>
                              <v-icon left class="mr-1" small>mdi-account-circle-outline</v-icon>Bešeduj.sk
                            </v-chip>
                            <v-badge v-if="item.status_notif == 'new'" :content="'Nové'" :value="'Nové'" color="orange" offset-x="-5" offset-y="5"></v-badge>
                          </v-list-item-title>
                          <v-list-item-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pl-1" v-else>
                            <v-chip small>
                              <v-icon left class="mr-1" small>mdi-account-circle-outline</v-icon>{{item.name}}
                            </v-chip>
                            <v-badge v-if="item.status_notif == 'new'" :content="'Nové'" :value="'Nové'" color="orange" offset-x="-5" offset-y="5"></v-badge>
                          </v-list-item-title>
                          <v-list-item-subtitle class="text-left primary--text font-weight-bold p-0 pl-1 pt-1" v-text="item.text" v-if="item.status_notif == 'new'"></v-list-item-subtitle>
                          <v-list-item-subtitle class="text-left grey--text font-weight-bold p-0 pl-1 pt-1" v-text="item.text" v-else></v-list-item-subtitle>
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
                                  <v-icon left class="mr-1 align-middle" small color="white">mdi-clock-outline</v-icon>
                                  <span class="align-middle">{{new Date(Date.parse(item.created_at)).toLocaleString()}}</span>
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
                                  <v-icon left class="mr-1 align-middle" small color="white">mdi-clock-outline</v-icon>
                                  <span class="align-middle">{{new Date(Date.parse(item.created_at)).toLocaleString()}}</span>
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
                                  <v-icon left class="mr-1 align-middle" small color="white">mdi-clock-outline</v-icon>
                                  <span class="align-middle">{{new Date(Date.parse(item.created_at)).toLocaleString()}}</span>
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
                                  <v-icon left class="mr-1 align-middle" small color="white">mdi-clock-outline</v-icon>
                                  <span class="align-middle">{{new Date(Date.parse(item.created_at)).toLocaleString()}}</span>
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
                                  <v-icon left class="mr-1 align-middle" small color="white">mdi-clock-outline</v-icon>
                                  <span class="align-middle">{{new Date(Date.parse(item.created_at)).toLocaleString()}}</span>
                                </span>
                              </v-tooltip>
                            </span>
                          </v-list-item-subtitle>
                        </v-card>
                      </v-list-item-content>
                    </v-list-item>
                  </template>

                  <v-divider class="m-0" v-if="filteredOldNotifications.length != 0"></v-divider>

                  <v-subheader v-if="filteredOldNotifications.length != 0">Staršie</v-subheader>
                  <template v-for="item in filteredOldNotifications">
                    <v-list-item :key="item.id + '-oldNotf'" @click="selectDataAboutNotification(item)">
                      <v-badge bottom dot bordered :color="getColor(item.status)" offset-x="10" offset-y="10" class="mr-2" v-if="item.name == 'admin'">
                        <v-avatar color="primary" size="40">
                          <v-img :lazy-src="require('../assets/logo_toolbar.png')" :src="require('../assets/logo_toolbar.png')" max-height="30" max-width="30"></v-img>
                        </v-avatar>
                      </v-badge>
                      <v-badge bottom dot bordered :color="getColor(item.status)" offset-x="10" offset-y="10" class="mr-2" v-else>
                        <v-avatar color="primary" size="40" v-if="item.avatar == null">
                          <span class="text-uppercase white--text">{{ item.name.charAt(0) }}</span>
                        </v-avatar>
                        <v-avatar color="primary" size="40" v-else>
                          <img :src="`${$root.envUrlNoApi}/storage/user-avatar/${item.avatar}`">
                        </v-avatar>
                      </v-badge>
                      <v-list-item-content>
                        <v-card class="p-2 rounded-lg card-comments" elevation="0">
                          <v-list-item-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pl-1" v-if="item.name == 'admin'">
                            <v-chip small>
                              <v-icon left class="mr-1" small>mdi-account-circle-outline</v-icon>Bešeduj.sk
                            </v-chip>
                          </v-list-item-title>
                          <v-list-item-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pl-1" v-else>
                            <v-chip small>
                              <v-icon left class="mr-1" small>mdi-account-circle-outline</v-icon>{{item.name}}
                            </v-chip>
                          </v-list-item-title>

                          <v-list-item-subtitle class="text-left primary--text font-weight-bold p-0 pl-1 pt-1" v-text="item.text" v-if="item.status_notif == 'new'"></v-list-item-subtitle>
                          <v-list-item-subtitle class="text-left grey--text font-weight-bold p-0 pl-1 pt-1" v-text="item.text" v-else></v-list-item-subtitle>

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
                                  <v-icon left class="mr-1 align-middle" small color="white">mdi-clock-outline</v-icon>
                                  <span class="align-middle">{{new Date(Date.parse(item.created_at)).toLocaleString()}}</span>
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
                                  <v-icon left class="mr-1 align-middle" small color="white">mdi-clock-outline</v-icon>
                                  <span class="align-middle">{{new Date(Date.parse(item.created_at)).toLocaleString()}}</span>
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
                                  <v-icon left class="mr-1 align-middle" small color="white">mdi-clock-outline</v-icon>
                                  <span class="align-middle">{{new Date(Date.parse(item.created_at)).toLocaleString()}}</span>
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
                                  <v-icon left class="mr-1 align-middle" small color="white">mdi-clock-outline</v-icon>
                                  <span class="align-middle">{{new Date(Date.parse(item.created_at)).toLocaleString()}}</span>
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
                                  <v-icon left class="mr-1 align-middle" small color="white">mdi-clock-outline</v-icon>
                                  <span class="align-middle">{{new Date(Date.parse(item.created_at)).toLocaleString()}}</span>
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
              <v-divider class="mt-0"></v-divider>
              <v-btn rounded text to="notifications">
                <v-icon class="mr-1">mdi-expand-all</v-icon>Zobraziť všetko
              </v-btn>
            </div>
          </v-list-item-content>
        </v-card>
      </v-menu>

      <!-- small devices toolbar icon hamburger menu -->
      <v-menu class="p-2" min-width="250px" bottom offset-y>
        <template v-slot:activator="{ on }">
          <v-btn icon v-on="on">
            <v-icon>mdi-menu</v-icon>
          </v-btn>
        </template>
        <v-card>
          <v-list dense class="">
            <v-list-item-group color="primary">
              <v-list-item>
                <v-icon class="mr-7">mdi-handshake</v-icon>
                <v-list-item-content>
                  <v-card class="p-2 rounded-lg card-comments" elevation="0">
                    <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-3 pl-1">
                      Poskytnúť odozvu
                    </v-card-title>
                    <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">Nahlásiť chybu</v-card-subtitle>
                  </v-card>
                </v-list-item-content>
              </v-list-item>

              <v-list-item to="/settings">
                <v-icon class="mr-7">mdi-cog</v-icon>
                <v-list-item-content>
                  <v-card class="p-2 rounded-lg card-comments" elevation="0">
                    <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-0 pl-1">
                      Nastavenia
                    </v-card-title>
                  </v-card>
                </v-list-item-content>
              </v-list-item>

              <v-list-item>
                <v-icon class="mr-7">mdi-shield-edit</v-icon>
                <v-list-item-content>
                  <v-card class="p-2 rounded-lg card-comments" elevation="0">
                    <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-0 pl-1">
                      Súkromie
                    </v-card-title>
                  </v-card>
                </v-list-item-content>
              </v-list-item>

              <v-list-item>
                <v-icon class="mr-7">mdi-help-circle</v-icon>
                <v-list-item-content>
                  <v-card class="p-2 rounded-lg card-comments" elevation="0">
                    <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-0 pl-1">
                      Pomoc a podpora
                    </v-card-title>
                  </v-card>
                </v-list-item-content>
              </v-list-item>
            </v-list-item-group>
          </v-list>
        </v-card>
      </v-menu>
    </v-row>

    <!-- small devices -->
    <v-row class="mr-0 mt-0 mb-0 ml-2 hidden-md-and-up justify-end">
      <!-- avatar -->
      <v-menu min-width="250px" style="z-index:12" v-model="smDevicesMenu" :close-on-content-click="false" offset-y>
        <template v-slot:activator=" { on }">
          <v-btn icon v-on="on">
            <v-icon>mdi-menu</v-icon>
          </v-btn>
        </template>
        <v-card class="v-card-logout">
          <v-list dense class="">
            <v-list-item-group fullscreen v-model="menuSmDown" color="primary">
              <v-dialog max-width="250px" v-model="dialogUserProfileLogout">
                <!-- <v-menu bottom min-width="200px" rounded offset-y :disabled="$root.me.overlayAvatar"> -->
                <template v-slot:activator="{ on }">
                  <v-btn icon x-large v-on="on">
                    <v-avatar color="primary" size="40" v-if="$root.me.overlayAvatar == true">
                      <v-overlay :value="$root.me.overlayAvatar" :absolute="true" :opacity="0">
                        <v-progress-circular indeterminate size="24" color="primary"></v-progress-circular>
                      </v-overlay>
                    </v-avatar>

                    <v-avatar color="primary" size="40" v-else-if="$root.me.avatar == null && $root.me.overlayAvatar == false">
                      <span v-if="$root.me.name != null" class="text-uppercase white--text">{{ $root.me.name.charAt(0) }}</span>
                    </v-avatar>
                    <v-avatar color="white" size="40" v-else>
                      <img :lazy-src="`${$root.envUrlNoApi}/storage/user-avatar/${$root.me.avatar}`" :src="`${$root.envUrlNoApi}/storage/user-avatar/${$root.me.avatar}`">
                    </v-avatar>
                  </v-btn>
                </template>
                <v-card class="v-card-logout">
                  <v-list-item-content class="justify-center">
                    <div class="mx-auto text-center">
                      <v-avatar color="primary" size="48" v-if="$root.me.overlayAvatar == true">
                        <v-overlay :value="$root.me.overlayAvatar" :absolute="true" :opacity="0">
                          <v-progress-circular indeterminate size="24" color="white"></v-progress-circular>
                        </v-overlay>
                      </v-avatar>

                      <v-avatar color="primary" size="48" v-else-if="$root.me.avatar == null && $root.me.overlayAvatar == false">
                        <span v-if="$root.me.name != null" class="text-uppercase white--text">{{ $root.me.name.charAt(0) }}</span>
                      </v-avatar>
                      <v-avatar color="primary" size="48" v-else>
                        <img :lazy-src="`${$root.envUrlNoApi}/storage/user-avatar/${$root.me.avatar}`" :src="`${$root.envUrlNoApi}/storage/user-avatar/${$root.me.avatar}`">
                      </v-avatar>
                      <!-- </v-avatar> -->
                      <h4>{{ $root.me.name }}</h4>
                      <p class="caption">
                        {{ $root.me.email }}
                      </p>
                      <v-divider class="my-3"></v-divider>
                      <v-btn depressed rounded text :to="'/profile/'+this.$root.me.id" @click="dialogUserProfileLogout = false; smDevicesMenu = false">
                        <v-icon class="mr-1">mdi-clipboard-edit</v-icon> Môj profil
                      </v-btn>
                      <v-divider class="my-3"></v-divider>
                      <v-btn depressed rounded text @click="logout">
                        <v-icon class="mr-1">mdi-logout</v-icon>Odhlásiť sa
                      </v-btn>
                    </div>
                  </v-list-item-content>
                </v-card>
                <!-- </v-menu> -->
              </v-dialog>
              <!-- make new post ........ -->
              <v-list-item @click="dialog = true; smDevicesMenu = false">
                <v-list-item-avatar class="ml-0" size="36">
                  <v-icon class="mr-1">mdi-pencil-box</v-icon>
                </v-list-item-avatar>
                <v-list-item-content>
                  <v-card class="p-2 rounded-lg card-comments" elevation="0">
                    <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-3 pl-1">
                      Vytvoriť príspevok/príbeh...
                    </v-card-title>
                    <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">Vytorenie nového príspevku/príbehu...</v-card-subtitle>
                  </v-card>
                </v-list-item-content>
              </v-list-item>
              <!-- messages -->
              <v-list-item @click="dialog2 = true; smDevicesMenu = false">
                <div class="mr-7">
                  <v-badge :content="$store.getters['msgUnreadCounter']" :value="$store.getters['msgUnreadCounter']" :offset-x="11" :offset-y="12" color="orange" overlap top bordered>
                    <v-icon>mdi-forum</v-icon>
                  </v-badge>
                </div>
                <v-list-item-content>
                  <v-card class="p-2 rounded-lg card-comments" elevation="0">
                    <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-3 pl-1">
                      Správy
                    </v-card-title>
                    <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">Zobraziť správy</v-card-subtitle>
                  </v-card>
                </v-list-item-content>
              </v-list-item>
              <!-- notif -->
              <v-list-item @click="dialog3 = true; smDevicesMenu = false">
                <div class="mr-7">
                  <v-badge :content="$store.getters['notificationCounter']" :value="$store.getters['notificationCounter']" :offset-x="11" :offset-y="12" color="orange" overlap top bordered>
                    <v-icon>mdi-bell</v-icon>
                  </v-badge>
                </div>
                <v-list-item-content>
                  <v-card class="p-2 rounded-lg card-comments" elevation="0">
                    <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-3 pl-1">
                      Notifikácie
                    </v-card-title>
                    <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">Zobraziť notifikácie</v-card-subtitle>
                  </v-card>
                </v-list-item-content>
              </v-list-item>

              <v-divider></v-divider>

              <v-list-item>
                <v-icon class="mr-7">mdi-handshake</v-icon>
                <v-list-item-content>
                  <v-card class="p-2 rounded-lg card-comments" elevation="0">
                    <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-3 pl-1">
                      Poskytnúť odozvu
                    </v-card-title>
                    <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">Nahlásiť chybu</v-card-subtitle>
                  </v-card>
                </v-list-item-content>
              </v-list-item>

              <v-list-item to="/settings" @click="smDevicesMenu = false">
                <v-icon class="mr-7">mdi-cog</v-icon>
                <v-list-item-content>
                  <v-card class="p-2 rounded-lg card-comments" elevation="0">
                    <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-0 pl-1">
                      Nastavenia
                    </v-card-title>
                  </v-card>
                </v-list-item-content>
              </v-list-item>

              <v-list-item>
                <v-icon class="mr-7">mdi-shield-edit</v-icon>
                <v-list-item-content>
                  <v-card class="p-2 rounded-lg card-comments" elevation="0">
                    <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-0 pl-1">
                      Súkromie
                    </v-card-title>
                  </v-card>
                </v-list-item-content>
              </v-list-item>

              <v-list-item>
                <v-icon class="mr-7">mdi-help-circle</v-icon>
                <v-list-item-content>
                  <v-card class="p-2 rounded-lg card-comments" elevation="0">
                    <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-0 pl-1">
                      Pomoc a podpora
                    </v-card-title>
                  </v-card>
                </v-list-item-content>
              </v-list-item>
            </v-list-item-group>
          </v-list>
        </v-card>
      </v-menu>

      <!-- dialog1  -->
      <v-dialog v-model="dialog" fullscreen transition="dialog-bottom-transition">
        <v-card>
          <v-toolbar dark color="primary">
            <v-btn icon dark @click="dialog = false, menuSmDown = null">
              <v-icon>mdi-close</v-icon>
            </v-btn>
            <v-toolbar-title>Vytvorenie príspevku/príbehu...</v-toolbar-title>
          </v-toolbar>
          <v-list dense>
            <v-list-item-group color="primary">
              <v-list-item>
                <v-icon class="mr-7">mdi-pencil-box</v-icon>
                <v-list-item-content>
                  <v-card class="p-2 rounded-lg card-comments" elevation="0">
                    <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-3 pl-1">
                      Príspevok
                    </v-card-title>
                    <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">Zdieľaj príspevok na hlavnej stránke</v-card-subtitle>
                  </v-card>
                </v-list-item-content>
              </v-list-item>

              <v-list-item>
                <v-icon class="mr-7">mdi-book-open-page-variant</v-icon>
                <v-list-item-content>
                  <v-card class="p-2 rounded-lg card-comments" elevation="0">
                    <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-3 pl-1">
                      Príbeh
                    </v-card-title>
                    <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">Zdieľaj fotku alebo video na svoj príbeh</v-card-subtitle>
                  </v-card>
                </v-list-item-content>
              </v-list-item>

              <v-list-item>
                <v-icon class="mr-7">mdi-shield-edit</v-icon>
                <v-list-item-content>
                  <v-card class="p-2 rounded-lg card-comments" elevation="0">
                    <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-3 pl-1">
                      Životná udalosť
                    </v-card-title>
                    <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">Pridajte životnú udalosť do svojho profilu</v-card-subtitle>
                  </v-card>
                </v-list-item-content>
              </v-list-item>

              <v-list-item>
                <v-icon class="mr-7">mdi-application</v-icon>
                <v-list-item-content>
                  <v-card class="p-2 rounded-lg card-comments" elevation="0">
                    <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-3 pl-1">
                      Stránka
                    </v-card-title>
                    <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">Spojte sa a Zdieľajte so zákazníkmi alebo fanúšikmi</v-card-subtitle>
                  </v-card>
                </v-list-item-content>
              </v-list-item>

              <v-list-item>
                <v-icon class="mr-7">mdi-account-group</v-icon>
                <v-list-item-content>
                  <v-card class="p-2 rounded-lg card-comments" elevation="0">
                    <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-3 pl-1">
                      Skupina
                    </v-card-title>
                    <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">Spojte sa s ľuďmi, ktorí majú rovnaké záujmy ako Vy</v-card-subtitle>
                  </v-card>
                </v-list-item-content>
              </v-list-item>

              <v-list-item>
                <v-icon class="mr-7">mdi-calendar-star</v-icon>
                <v-list-item-content>
                  <v-card class="p-2 rounded-lg card-comments" elevation="0">
                    <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-3 pl-1">
                      Podujatie
                    </v-card-title>
                    <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">Spojte ľudí pomocou verejného alebo súkromného podujatia</v-card-subtitle>
                  </v-card>
                </v-list-item-content>
              </v-list-item>
            </v-list-item-group>
          </v-list>
        </v-card>
      </v-dialog>
      <!-- dialog2  -->
      <!-- messages -->
      <v-dialog v-model="dialog2" fullscreen hide-overlay transition="dialog-bottom-transition">
        <v-card class="rounded">
          <v-toolbar flat dark color="primary">
            <v-btn icon dark @click="dialog2 = false, menuSmDown = null">
              <v-icon>mdi-close</v-icon>
            </v-btn>
            <v-toolbar-title>Správy</v-toolbar-title>
          </v-toolbar>
          <v-list-item-content class="justify-center pt-1">
            <div class="mx-auto text-center w-100">
              <v-list class="pt-0 pb-0">
                <v-list-item class="pt-0" v-if="$root.toolbar.fewNewestMessages.fewNewestMessages.length == 0 && $root.toolbar.fewNewestMessages.overlayNewestMessages != true">
                  <v-text-field class="mt-6" v-model="searchMsg" label="Vyhľadať" filled rounded dense append-icon="mdi-magnify" clearable disabled></v-text-field>
                </v-list-item>
                <v-list-item class="pt-0" v-else>
                  <v-text-field class="mt-6" v-model="searchMsg" label="Vyhľadať" filled rounded dense append-icon="mdi-magnify" clearable></v-text-field>
                </v-list-item>

                <v-divider class="m-0" v-if="filteredNewestMessages.length == 0 && $root.toolbar.fewNewestMessages.fewNewestMessages.length != 0"></v-divider>
                <v-list-item class="justify-center" v-if="filteredNewestMessages.length == 0 && $root.toolbar.fewNewestMessages.fewNewestMessages.length != 0" disabled>
                  <v-list-item-content>
                    <v-card class="rounded-lg card-comments" elevation="0">
                      <v-btn color="primary" icon fab>
                        <!-- <v-icon style="font-size: 24px">mdi-cancel</v-icon> -->
                        <span class="">Žiadne výsledky</span>
                      </v-btn>
                    </v-card>
                  </v-list-item-content>
                </v-list-item>

                <v-divider class="m-0" v-if="$root.toolbar.fewNewestMessages.fewNewestMessages.length == 0 && $root.toolbar.fewNewestMessages.overlayNewestMessages != true"></v-divider>
                <v-list-item class="justify-center" v-if="$root.toolbar.fewNewestMessages.fewNewestMessages.length == 0 && $root.toolbar.fewNewestMessages.overlayNewestMessages != true" disabled>
                  <v-list-item-content>
                    <v-card class="rounded-lg card-comments" elevation="0">
                      <v-btn color="primary" icon fab>
                        <!-- <v-icon style="font-size: 24px">mdi-cancel</v-icon> -->
                        <span>Žiadne správy</span>
                      </v-btn>
                    </v-card>
                  </v-list-item-content>
                </v-list-item>
              </v-list>
              <v-divider class="m-0" v-if="filteredNewestMessages.length != 0"></v-divider>
              <v-list class="p-0" v-if="filteredNewestMessages.length != 0" three-line dense>
                <v-list-item-group color="primary">
                  <v-subheader class="pl-4 pr-4" v-if="filteredNewestMessages.length != 0">Najnovšie</v-subheader>
                  <template v-for="item in filteredNewestMessages">
                    <v-list-item :key="item.id" @click="selectDataAboutUser(item); dialog2 = false" class="w-100">
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
                            <v-chip small>
                              <v-icon left class="mr-1" small>mdi-account-circle-outline</v-icon>{{item.name}}
                            </v-chip>
                            <v-badge v-if="item.read == 1" :content="'Prečítané'" :value="'Prečítané'" color="orange" offset-x="-5" offset-y="5"></v-badge>
                            <v-badge v-else :content="'Neprečítané'" :value="'Neprečítané'" color="orange" offset-x="-5" offset-y="5"></v-badge>
                          </v-list-item-title>

                          <v-list-item-subtitle class="text-left grey--text font-weight-bold p-0 pl-1 pt-1 text-wrap" v-if="item.read == 1">
                            <div v-if="item.image_url != null">
                              <v-icon left class="mr-1 align-middle" small>mdi-image</v-icon>
                            </div>
                            <div v-if="item.video_url != null">
                              <v-icon left class="mr-1 align-middle" small>mdi-video-outline</v-icon>
                            </div>
                            <div v-if="item.text != null">
                              {{item.text}}
                            </div>
                          </v-list-item-subtitle>
                          <v-list-item-subtitle class="text-left primary--text font-weight-bold p-0 pl-1 pt-1 text-wrap" v-else>
                            <div v-if="item.image_url != null">
                              <v-icon left class="mr-1 align-middle" small>mdi-image</v-icon>
                            </div>
                            <div v-if="item.video_url != null">
                              <v-icon left class="mr-1 align-middle" small>mdi-video-outline</v-icon>
                            </div>
                            <div v-if="item.text != null">
                              {{item.text}}
                            </div>
                          </v-list-item-subtitle>
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
                                  <v-icon left class="mr-1 align-middle" small color="white">mdi-clock-outline</v-icon>
                                  <span class="align-middle">{{new Date(Date.parse(item.created_at)).toLocaleString()}}</span>
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
                                  <v-icon left class="mr-1 align-middle" small color="white">mdi-clock-outline</v-icon>
                                  <span class="align-middle">{{new Date(Date.parse(item.created_at)).toLocaleString()}}</span>
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
                                  <v-icon left class="mr-1 align-middle" small color="white">mdi-clock-outline</v-icon>
                                  <span class="align-middle">{{new Date(Date.parse(item.created_at)).toLocaleString()}}</span>
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
                                  <v-icon left class="mr-1 align-middle" small color="white">mdi-clock-outline</v-icon>
                                  <span class="align-middle">{{new Date(Date.parse(item.created_at)).toLocaleString()}}</span>
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
              <v-divider class="mt-0"></v-divider>
              <v-btn rounded text to="/messenger" @click="dialog2 = false">
                <v-icon class="mr-1">mdi-expand-all</v-icon>Zobraziť všetky správy
              </v-btn>
            </div>
          </v-list-item-content>
        </v-card>
      </v-dialog>
      <!-- dialog3  -->
      <!-- notifications -->
      <v-dialog v-model="dialog3" fullscreen hide-overlay transition="dialog-bottom-transition">
        <v-card class="rounded">
          <v-toolbar flat dark color="primary">
            <v-btn icon dark @click="dialog3 = false, menuSmDown = null">
              <v-icon>mdi-close</v-icon>
            </v-btn>
            <v-toolbar-title>Notifikácie</v-toolbar-title>
          </v-toolbar>

          <v-list-item-content class="justify-center pt-1">
            <div class="mx-auto text-center w-100">
              <v-list class="pt-0 pb-0">
                <v-list-item class="pt-0" v-if="$root.toolbar.fewNewestNotifications.fewNewestNotifications.length == 0 && $root.toolbar.fewOldNotifications.fewOldNotifications.length == 0">
                  <v-text-field class="mt-6" v-model="searchNtf" label="Vyhľadať" filled rounded dense append-icon="mdi-magnify" clearable disabled></v-text-field>
                </v-list-item>
                <v-list-item class="pt-0" v-else>
                  <v-text-field class="mt-6" v-model="searchNtf" label="Vyhľadať" filled rounded dense append-icon="mdi-magnify" clearable></v-text-field>
                </v-list-item>

                <v-divider class="m-0"
                  v-if="(filteredNewNotifications.length == 0 && $root.toolbar.fewNewestNotifications.fewNewestNotifications.length != 0) || (filteredOldNotifications.length == 0 && $root.toolbar.fewOldNotifications.fewOldNotifications.length != 0)">
                </v-divider>
                <v-list-item class="justify-center"
                  v-if="(filteredNewNotifications.length == 0 && $root.toolbar.fewNewestNotifications.fewNewestNotifications.length != 0) || (filteredOldNotifications.length == 0 && $root.toolbar.fewOldNotifications.fewOldNotifications.length != 0)"
                  disabled>
                  <v-list-item-content>
                    <v-card class="rounded-lg card-comments" elevation="0">
                      <v-btn color="primary" icon fab>
                        <!-- <v-icon style="font-size: 24px">mdi-cancel</v-icon> -->
                        <span class="">Žiadne výsledky</span>
                      </v-btn>
                    </v-card>
                  </v-list-item-content>
                </v-list-item>

                <v-divider class="m-0" v-if="$root.toolbar.fewNewestNotifications.fewNewestNotifications.length == 0 && $root.toolbar.fewOldNotifications.fewOldNotifications.length == 0"></v-divider>
                <v-list-item class="justify-center" v-if="$root.toolbar.fewNewestNotifications.fewNewestNotifications.length == 0 && $root.toolbar.fewOldNotifications.fewOldNotifications.length == 0" disabled>
                  <v-list-item-content>
                    <v-card class="rounded-lg card-comments" elevation="0">
                      <v-btn color="primary" icon fab>
                        <!-- <v-icon style="font-size: 24px">mdi-cancel</v-icon> -->
                        <span>Žiadne notifikácie</span>
                      </v-btn>
                    </v-card>
                  </v-list-item-content>
                </v-list-item>
              </v-list>
              <v-divider class="m-0" v-if="filteredNewNotifications.length != 0"></v-divider>

              <v-list class="p-0" v-if="filteredNewNotifications.length != 0 || filteredOldNotifications.length != 0" three-line dense>
                <v-list-item-group color="primary">
                  <v-subheader v-if="filteredNewNotifications.length != 0">Najnovšie</v-subheader>
                  <template v-for="item in filteredNewNotifications">
                    <v-list-item :key="item.id + '-newNotf'" @click="selectDataAboutNotification(item)">
                      <v-badge bottom dot bordered :color="getColor(item.status)" offset-x="10" offset-y="10" class="mr-2" v-if="item.name == 'admin'">
                        <v-avatar color="primary" size="40">
                          <v-img :lazy-src="require('../assets/logo_toolbar.png')" :src="require('../assets/logo_toolbar.png')" max-height="30" max-width="30"></v-img>
                        </v-avatar>
                      </v-badge>
                      <v-badge bottom dot bordered :color="getColor(item.status)" offset-x="10" offset-y="10" class="mr-2" v-else>
                        <v-avatar color="primary" size="40" v-if="item.avatar == null">
                          <span class="text-uppercase white--text">{{ item.name.charAt(0) }}</span>
                        </v-avatar>
                        <v-avatar color="primary" size="40" v-else>
                          <img :src="`${$root.envUrlNoApi}/storage/user-avatar/${item.avatar}`">
                        </v-avatar>
                      </v-badge>
                      <v-list-item-content>
                        <v-card class="p-2 rounded-lg card-comments" elevation="0">
                          <v-list-item-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pl-1" v-if="item.name == 'admin'">
                            <v-chip small>
                              <v-icon left class="mr-1" small>mdi-account-circle-outline</v-icon>Bešeduj.sk
                            </v-chip>
                            <v-badge v-if="item.status_notif == 'new'" :content="'Nové'" :value="'Nové'" color="orange" offset-x="-5" offset-y="5"></v-badge>
                          </v-list-item-title>
                          <v-list-item-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pl-1" v-else>
                            <v-chip small>
                              <v-icon left class="mr-1" small>mdi-account-circle-outline</v-icon>{{item.name}}
                            </v-chip>
                            <v-badge v-if="item.status_notif == 'new'" :content="'Nové'" :value="'Nové'" color="orange" offset-x="-5" offset-y="5"></v-badge>
                          </v-list-item-title>
                          <v-list-item-subtitle class="text-left primary--text font-weight-bold p-0 pl-1 pt-1" v-text="item.text" v-if="item.status_notif == 'new'"></v-list-item-subtitle>
                          <v-list-item-subtitle class="text-left grey--text font-weight-bold p-0 pl-1 pt-1" v-text="item.text" v-else></v-list-item-subtitle>
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
                                  <v-icon left class="mr-1 align-middle" small color="white">mdi-clock-outline</v-icon>
                                  <span class="align-middle">{{new Date(Date.parse(item.created_at)).toLocaleString()}}</span>
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
                                  <v-icon left class="mr-1 align-middle" small color="white">mdi-clock-outline</v-icon>
                                  <span class="align-middle">{{new Date(Date.parse(item.created_at)).toLocaleString()}}</span>
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
                                  <v-icon left class="mr-1 align-middle" small color="white">mdi-clock-outline</v-icon>
                                  <span class="align-middle">{{new Date(Date.parse(item.created_at)).toLocaleString()}}</span>
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
                                  <v-icon left class="mr-1 align-middle" small color="white">mdi-clock-outline</v-icon>
                                  <span class="align-middle">{{new Date(Date.parse(item.created_at)).toLocaleString()}}</span>
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
                                  <v-icon left class="mr-1 align-middle" small color="white">mdi-clock-outline</v-icon>
                                  <span class="align-middle">{{new Date(Date.parse(item.created_at)).toLocaleString()}}</span>
                                </span>
                              </v-tooltip>
                            </span>
                          </v-list-item-subtitle>
                        </v-card>
                      </v-list-item-content>
                    </v-list-item>
                  </template>

                  <v-divider class="m-0" v-if="filteredOldNotifications.length != 0"></v-divider>

                  <v-subheader class="pl-4 pr-4" v-if="filteredOldNotifications.length != 0">Staršie</v-subheader>
                  <template v-for="item in filteredOldNotifications">
                    <v-list-item :key="item.id + '-oldNotf'" @click="selectDataAboutNotification(item)">
                      <v-badge bottom dot bordered :color="getColor(item.status)" offset-x="10" offset-y="10" class="mr-2" v-if="item.name == 'admin'">
                        <v-avatar color="primary" size="40">
                          <v-img :lazy-src="require('../assets/logo_toolbar.png')" :src="require('../assets/logo_toolbar.png')" max-height="30" max-width="30"></v-img>
                        </v-avatar>
                      </v-badge>
                      <v-badge bottom dot bordered :color="getColor(item.status)" offset-x="10" offset-y="10" class="mr-2" v-else>
                        <v-avatar color="primary" size="40" v-if="item.avatar == null">
                          <span class="text-uppercase white--text">{{ item.name.charAt(0) }}</span>
                        </v-avatar>
                        <v-avatar color="primary" size="40" v-else>
                          <img :src="`${$root.envUrlNoApi}/storage/user-avatar/${item.avatar}`">
                        </v-avatar>
                      </v-badge>
                      <v-list-item-content>
                        <v-card class="p-2 rounded-lg card-comments" elevation="0">
                          <v-list-item-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pl-1" v-if="item.name == 'admin'">
                            <v-chip small>
                              <v-icon left class="mr-1" small>mdi-account-circle-outline</v-icon>Bešeduj.sk
                            </v-chip>
                          </v-list-item-title>
                          <v-list-item-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pl-1" v-else>
                            <v-chip small>
                              <v-icon left class="mr-1" small>mdi-account-circle-outline</v-icon>{{item.name}}
                            </v-chip>
                          </v-list-item-title>

                          <v-list-item-subtitle class="text-left primary--text font-weight-bold p-0 pl-1 pt-1" v-text="item.text" v-if="item.status_notif == 'new'"></v-list-item-subtitle>
                          <v-list-item-subtitle class="text-left grey--text font-weight-bold p-0 pl-1 pt-1" v-text="item.text" v-else></v-list-item-subtitle>

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
                                  <v-icon left class="mr-1 align-middle" small color="white">mdi-clock-outline</v-icon>
                                  <span class="align-middle">{{new Date(Date.parse(item.created_at)).toLocaleString()}}</span>
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
                                  <v-icon left class="mr-1 align-middle" small color="white">mdi-clock-outline</v-icon>
                                  <span class="align-middle">{{new Date(Date.parse(item.created_at)).toLocaleString()}}</span>
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
                                  <v-icon left class="mr-1 align-middle" small color="white">mdi-clock-outline</v-icon>
                                  <span class="align-middle">{{new Date(Date.parse(item.created_at)).toLocaleString()}}</span>
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
                                  <v-icon left class="mr-1 align-middle" small color="white">mdi-clock-outline</v-icon>
                                  <span class="align-middle">{{new Date(Date.parse(item.created_at)).toLocaleString()}}</span>
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
                                  <v-icon left class="mr-1 align-middle" small color="white">mdi-clock-outline</v-icon>
                                  <span class="align-middle">{{new Date(Date.parse(item.created_at)).toLocaleString()}}</span>
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
              <v-divider class="mt-0"></v-divider>
              <v-btn rounded text to="notifications" @click="dialog3 = false">
                <v-icon class="mr-1">mdi-expand-all</v-icon>Zobraziť všetko
              </v-btn>
            </div>
          </v-list-item-content>
        </v-card>
      </v-dialog>
    </v-row>
  </v-app-bar>
</div>
</template>

<script>
import _ from 'lodash';
import axios from 'axios';
import moment from 'moment';
export default {
  name: "Toolbar",
  props: [],
  components: {},
  data() {
    return {
      findFriends: false,
      findFriendsToolbar: false,
      notificationsMenu: false,
      dialog: false,
      dialog2: false,
      dialog3: false,
      dialogFindFriends: false,
      menuSmDown: null,
      dialogUserProfileLogout: false,
      smDevicesMenu: false,
      overlay1: true,
      usersLoading: true,
      // friendsLoading: true,
      overlayPeople: true,
      //search new friends
      search: '',
      searchMsg: '',
      searchNtf: '',
      search2: '',
      // fewNewestMessages: [],
      // overlayNewestMessages: true,
      users: [],
      addFriend: false,
      // friendRequests: [],
      overlayLogout: false,
      // fewNewestNotifications: [],
      // overlayNewestNotifications: true,
      // fewOldNotifications: [],
      // overlayOldNotifications: true,
    }
  },

  created() {
    window.Echo.join('messages.' + localStorage.getItem("user_id"))
      .listen('NewMessage', (e) => {
        this.$root.toolbar.fewNewestMessages.overlayNewestMessages = true;
        let position = this.$root.toolbar.fewNewestMessages.fewNewestMessages.reduce((acc, request, index) => {
          if (request.from == e.message.from) {
            acc.push(index);
          }
          return acc;
        }, []);

        let data = {
          avatar: e.message.from_contact.avatar,
          created_at: e.message.created_at.toLocaleString(),
          email: e.message.from_contact.email,
          from: e.message.from,
          id: e.message.id,
          name: e.message.from_contact.name,
          read: e.message.read,
          status: e.message.from_contact.status,
          text: e.message.text,
          to: e.message.to,
          totalUnreadMsgTo: e.message.totalUnreadMsgTo,
          updated_at: e.message.updated_at.toLocaleString(),
        }

        if (this.$route.fullPath != `/messenger?name=${e.message.from_contact.name}`) {
          data.read = 0;
        } else {
          data.read = 1;
        }
        if (position[0] != null) {
          this.$root.toolbar.fewNewestMessages.fewNewestMessages.splice(position[0], 1);
          this.$root.toolbar.fewNewestMessages.fewNewestMessages[position[0]] = data;
          this.$root.toolbar.fewNewestMessages.overlayNewestMessages = false;
        } else {
          // add data to 0 position in array and other shifted
          this.$root.toolbar.fewNewestMessages.fewNewestMessages.splice(0, 0, data);
          this.$root.toolbar.fewNewestMessages.overlayNewestMessages = false;
        }
      })

    //presence channel
    window.Echo.join('dataAboutMe.' + localStorage.getItem("user_id"))
      .listen('Me', (e) => {
        this.$root.me.id = e.user.id;
        this.$root.me.name = e.user.name;
        this.$root.me.email = e.user.email;
        this.$root.me.avatar = e.user.avatar;
        this.$root.me.status = e.user.status;
        this.$root.me.created_at = e.user.created_at;
      })
  },

  updated() {
    this.overlayStatus();
    this.notifications = this.$store.getters['notificationCounter'];
  },

  methods: {
    getColor(e) {
      if (e == 'offline') return 'red'
      else return 'green'
    },

    getUserProfileData(item) {
      if (this.$route.fullPath != `/profile/${item.id}`) {
        this.$router.push({
          name: 'Profile',
          params: {
            user_id: item.id
          }
        });
      }
    },

    logout() {
      this.overlayLogout = true;
      this.$emit('childToParent', 'true');
      const api = `${process.env.VUE_APP_API_URL}/auth/logout`;
      const config = {
        headers: {
          Accept: "application/json",
          Authorization: "Bearer " + localStorage.getItem("authToken"),
        },
      };
      axios.post(api, null, config)
        .then(() => {
          window.location.href = "/login";
          this.$emit('childToParent', 'false');
          localStorage.setItem('logout', true);
          localStorage.removeItem("user_id");
          localStorage.removeItem("authToken");
          this.overlayLogout = false;
        })
        .catch((e) => {
          console.log(e);
        })
    },

    overlayStatus() {
      if (this.$root.toolbar.allPossibleFriends.usersLoading && this.$root.friendships.friendsLoading) {
        this.overlay1 = true;
      } else {
        this.overlay1 = false;
      }
    },

    functionDialog1() {
      this.dialog = false;
      this.menuSmDown = true;
    },

    selectDataAboutUser(item) {
      this.$router.push(`/messenger?name=${item.name}`);
    },

    selectDataAboutNotification(item) {
      this.dialog3 = false;
      if (item.status_notif == "new") {
        this.$router.push(`/notifications/new`);
      } else {
        this.$router.push(`/notifications`);
      }
    },

    sendFriendshipRequest(item) {
      this.myloadingvariable = true;
      const api = `${process.env.VUE_APP_API_URL}/friendships/sendFriendshipRequest`;
      const config = {
        headers: {
          Accept: "application/json",
          Authorization: "Bearer " + localStorage.getItem("authToken"),
        },
      };

      axios.post(api, {
            user_requested: item.id,
            friendship_status: '2'
          },
          config
        )
        .then(() => {
          this.$root.toolbar.allPossibleFriends.usersLoading = true;
          this.$root.toolbar.allPossibleFriends.users.forEach((elem, index) => {
            if (elem.id == item.id) {
              let data = {
                avatar: elem.avatar,
                created_at: elem.created_at,
                email: elem.email,
                id: elem.id,
                name: elem.name,
                status: elem.status,
                friendship_status: '2',
                requester: this.$root.me.id,
                updated_at: new Date().toLocaleString(),
                user_requested: elem.id
              };
              this.$root.toolbar.allPossibleFriends.users.splice(index, 1);
              this.$root.toolbar.friendRequests.friendRequests.splice(0, 0, data);
              this.$root.toolbar.allPossibleFriends.usersLoading = false;
            }
          })
        })
      console.log(this.$root.toolbar.friendRequests.friendRequests);
    },

    acceptFriendshipRequest(item) {
      const api = `${process.env.VUE_APP_API_URL}/friendships/acceptFriendshipRequest`;
      const config = {
        headers: {
          Accept: "application/json",
          Authorization: "Bearer " + localStorage.getItem("authToken"),
        },
      };

      axios.post(api, {
            requester: item.id,
            user_requested: item.user_requested,
            friendship_status: '1'
          },
          config
        )
        .then(() => {
          // remove this request from array in $root and add data to friendship array
          let position = this.$root.toolbar.friendRequests.friendRequests.reduce((acc, request, index) => {
            if (request.id == item.id) {
              acc.push(index);
            }
            return acc;
          }, []);
          this.$root.friendships.friends.push(this.$root.toolbar.friendRequests.friendRequests[position[0]]);
          this.$root.toolbar.friendRequests.friendRequests.splice(position[0], 1);
          // minus 1 request from counter of requests
          this.$root.navigationDrawerRight.countMyFriendshipRequests -= 1;

          const api = `${process.env.VUE_APP_API_URL}/sendNotification`;
          const config = {
            headers: {
              Accept: "application/json",
              Authorization: "Bearer " + localStorage.getItem("authToken"),
            },
          };
          axios.post(api, {
              from: item.user_requested,
              recipient: item.id,
              title: "Tím Bešeduj.sk",
              subtitle: "Prijatie žiadosti o priateľstvo",
              text: `${item.name}, používateľ ${this.$root.me.name} prijal Vašu žiadosť o priateľstvo.`,
              date: moment(new Date())
                .format('YYYY-MM-DD HH:mm'),
              status: "new",
            }, config)
            .then(() => {})

          // snackbar notif
          this.$store.dispatch('friendshipRequestAction', {
            status: 'accept',
            text: `Žiadosť o priateľstvo používateľa ${item.name} bola prijatá. Odteraz môžte chatovať.`
          });
        })
    },

    refuseFriendshipRequest(item) {
      this.friendship_status = 0;
      const api = `${process.env.VUE_APP_API_URL}/friendships/refuseFriendshipRequest`;
      const config = {
        headers: {
          Accept: "application/json",
          Authorization: "Bearer " + localStorage.getItem("authToken"),
        },
      };

      axios.post(api, {
            requester: item.requester,
            user_requested: this.$root.me.id,
          },
          config
        )
        .then(() => {
          // remove this request from array in $root and add data to friendship array
          let position = this.$root.toolbar.friendRequests.friendRequests.reduce((acc, request, index) => {
            if (request.id == item.id) {
              acc.push(index);
            }
            return acc;
          }, []);
          this.$root.toolbar.allPossibleFriends.users.push(this.$root.toolbar.friendRequests.friendRequests[position[0]]);
          this.$root.toolbar.friendRequests.friendRequests.splice(position[0], 1);
          // minus 1 request from counter of requests
          this.$root.navigationDrawerRight.countMyFriendshipRequests -= 1;

          const api = `${process.env.VUE_APP_API_URL}/sendNotification`;
          const config = {
            headers: {
              Accept: "application/json",
              Authorization: "Bearer " + localStorage.getItem("authToken"),
            },
          };
          axios.post(api, {
              from: this.$root.me.id,
              recipient: item.requester,
              title: "Tím Bešeduj.sk",
              subtitle: "Odmietnutie žiadosti o priateľstvo",
              text: `${item.name}, používateľ ${this.$root.me.name} odmietol Vašu žiadosť o priateľstvo.`,
              date: moment(new Date())
                .format('YYYY-MM-DD HH:mm'),
              status: "new",
            }, config)
            .then(() => {})

          //snackbar notif
          this.$store.dispatch('friendshipRequestAction', {
            status: 'refuse',
            text: `Žiadosť o priateľstvo používateľa ${item.name} bola zamietnutá`
          });
        })
    },

    removeFromFriendshipList(item) {
      this.friendship_status = 0;
      const api = `${process.env.VUE_APP_API_URL}/friendships/removeFromFriendshipList`;
      const config = {
        headers: {
          Accept: "application/json",
          Authorization: "Bearer " + localStorage.getItem("authToken"),
        },
      };

      axios.post(api, {
            requester: this.$root.me.id,
            user_requested: item.id,
          },
          config
        )
        .then(() => {
          console.log("remove tool");
          // remove this request from array in $root and add data to friendship array
          let position = this.$root.toolbar.friendRequests.friendRequests.reduce((acc, request, index) => {
            if (request.id == item.id) {
              acc.push(index);
            }
            return acc;
          }, []);
          this.$root.toolbar.allPossibleFriends.users.push(this.$root.toolbar.friendRequests.friendRequests[position[0]]);
          this.$root.toolbar.friendRequests.friendRequests.splice(position[0], 1);

          //snackbar notif
          this.$store.dispatch('friendshipRequestAction', {
            status: 'remove',
            text: `Žiadosť o priateľstvo používateľa ${item.name} bola zrušená`
          });
        })
    },
  },

  computed: {
    filteredFriendRequests() {
      return _.orderBy(this.$root.toolbar.friendRequests.friendRequests.filter(item => {
        if (!this.search) return this.$root.toolbar.friendRequests.friendRequests;
        return (item.name.toLowerCase()
          .includes(this.search.toLowerCase()));
      }), 'headline');
    },

    filteredUsers() {
      return _.orderBy(this.$root.toolbar.allPossibleFriends.users.filter(item => {
        if (!this.search) return this.$root.toolbar.allPossibleFriends.users;
        return (item.name.toLowerCase()
          .includes(this.search.toLowerCase()));
      }), 'headline');
    },

    filteredFriendRequests2() {
      return _.orderBy(this.$root.toolbar.friendRequests.friendRequests.filter(item => {
        if (!this.search2) return this.$root.toolbar.friendRequests.friendRequests;
        return (item.name.toLowerCase()
          .includes(this.search2.toLowerCase()));
      }), 'headline');
    },

    filteredUsers2() {
      return _.orderBy(this.$root.toolbar.allPossibleFriends.users.filter(item => {
        if (!this.search2) return this.$root.toolbar.allPossibleFriends.users;
        return (item.name.toLowerCase()
          .includes(this.search2.toLowerCase()));
      }), 'headline');
    },

    filteredNewestMessages() {
      return _.orderBy(this.$root.toolbar.fewNewestMessages.fewNewestMessages.filter(item => {
        if (!this.searchMsg) return this.$root.toolbar.fewNewestMessages.fewNewestMessages;
        return (item.name.toLowerCase()
          .includes(this.searchMsg.toLowerCase()));
      }), 'headline');
    },

    filteredNewNotifications() {
      return _.orderBy(this.$root.toolbar.fewNewestNotifications.fewNewestNotifications.filter(item => {
        if (!this.searchNtf) return this.$root.toolbar.fewNewestNotifications.fewNewestNotifications;
        return (item.name.toLowerCase()
          .includes(this.searchNtf.toLowerCase()));
      }), 'headline');
    },

    filteredOldNotifications() {
      return _.orderBy(this.$root.toolbar.fewOldNotifications.fewOldNotifications.filter(item => {
        if (!this.searchNtf) return this.$root.toolbar.fewOldNotifications.fewOldNotifications;
        return (item.name.toLowerCase()
          .includes(this.searchNtf.toLowerCase()));
      }), 'headline');
    },
  },

  mounted() {},
}
</script>

<style>
.v-card-logout .theme--dark.v-btn--active,
.theme--light.v-btn--active {
  color: #1976d2;
}

.v-application.theme--dark .toolbar .v-badge .v-badge__badge:after {
  border-color: #2196f3;
}

.v-application.theme--light .toolbar .v-badge .v-badge__badge:after {
  border-color: #1976d2;
}

.toolbar .v-skeleton-loader__chip {
  border-radius: 100px !important;
  width: 187.69px !important;
  height: 44px !important;
}

.toolbar .btn_home .v-skeleton-loader__chip {
  border-radius: 100px !important;
  width: 101.51px !important;
  height: 44px !important;
}

.toolbar .btn_home_sm .v-skeleton-loader__avatar {
  border-radius: 100px !important;
  width: 44px !important;
  height: 44px !important;
}

.theme--dark .toolbar-friends-dialog {
  background: #121212 !important;
}

.theme--light .toolbar-friends-dialog {
  background: #f5f5f5 !important;
}

/* .toolbar .v-overlay--active {
  opacity: unset !important;
  border-color: unset !important;
  background-color: rgba(255, 255, 255, 0.5) !important;
  backdrop-filter: blur(5px) !important;
} */
</style>
