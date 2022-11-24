<template>
<v-container class="center p-0">
  <v-row class="ml-0 mr-0 mb-3" v-if="$root.profile.data.returnVariable == -1">
    <v-card class="w-100 rounded" elevation="0">
      <v-flex xs12 sm12>
        <v-layout wrap column>
          <v-flex xs12>
            <v-sheet elevation="0" class="rounded d-flex justify-center" v-if="$root.profilePosts.getAllPosts.loadingPosts == true">
              <v-chip-group v-if="$root.profilePosts.getAllPosts.loadingPosts == true">
                <v-skeleton-loader class="slchip1" type="chip"></v-skeleton-loader>
                <v-skeleton-loader class="slchip2" type="chip"></v-skeleton-loader>
                <v-skeleton-loader class="slchip3" type="chip"></v-skeleton-loader>
              </v-chip-group>
            </v-sheet>
            <v-sheet elevation="0" class="py-4 px-1 rounded d-flex justify-center" v-else>
              <v-chip-group v-model="selectedChip" active-class="primary--text">
                <v-dialog v-model="dialogNewPost" width="500">
                  <template v-slot:activator="{ on, attrs }">
                    <v-chip v-bind="attrs" v-on="on">
                      <v-icon class="mr-1">
                        mdi-image-edit
                      </v-icon>
                      Vytvoriť príspevok
                    </v-chip>
                  </template>

                  <v-card>
                    <v-toolbar dark color="primary" elevation="0">
                      <v-toolbar-title>Nový príspevok</v-toolbar-title>
                      <v-spacer />
                      <v-btn icon dark @click="dialogNewPost = false; selectedChip = ''">
                        <v-icon>mdi-close</v-icon>
                      </v-btn>
                    </v-toolbar>

                    <v-card-text class="pt-3 pb-0">
                      <v-row class="p-2">
                        <v-avatar color="primary" size="40" v-if="$root.me.overlayAvatar == true">
                          <v-overlay :value="$root.me.overlayAvatar" :absolute="true" :opacity="0">
                            <v-progress-circular indeterminate size="24" color="white"></v-progress-circular>
                          </v-overlay>
                        </v-avatar>

                        <v-avatar color="primary" size="40" v-else-if="$root.me.avatar == null && $root.me.overlayAvatar == false">
                          <span v-if="$root.me.name != null" class="text-uppercase white--text">{{ $root.me.name.charAt(0) }}</span>
                        </v-avatar>
                        <v-avatar color="primary" size="40" v-else>
                          <img :lazy-src="`http://127.0.0.1:8000/storage/user-avatar/${$root.me.avatar}`" :src="`http://127.0.0.1:8000/storage/user-avatar/${$root.me.avatar}`">
                        </v-avatar>
                        <span class="ml-1 m-auto">{{$root.me.name}}</span>
                      </v-row>
                      <v-row class="p-2 pb-0">
                        <v-textarea v-model="textNewPost" clearable clear-icon="mdi-close-circle" label="Napíšte, čo si myslíte" value="This is clearable text." auto-grow filled rounded></v-textarea>
                      </v-row>
                      <v-row class="p-2 mt-0" v-if="showDragAndDropFile">
                        <file-pond allowImageCrop={true} allowFileEncode={true} class="filepond" name="filepond" ref="pond" type="input" :files="files" v-on:removefile="handleFileRemoved" v-on:addfile="handleFileAdded"
                          v-on:init="handleFilePondInit" style="width:100%" label-idle="Sem presuňte súbory..." allow-multiple="true" max-files="10" />
                      </v-row>
                    </v-card-text>

                    <v-divider class="mt-0 mb-0"></v-divider>

                    <v-row class="m-0 p-3">
                      <v-tooltip top>
                        <template v-slot:activator="{ on }">
                          <v-btn class="mr-1" v-on="on" rounded outlined elevation="0" color="primary">
                            <v-icon class="mr-1">mdi-account-multiple-plus</v-icon>
                            <!-- Obrázok/video -->
                          </v-btn>
                        </template>
                        <span>Označiť ľudí</span>
                      </v-tooltip>

                      <v-tooltip top>
                        <template v-slot:activator="{ on }">
                          <v-btn v-on="on" rounded elevation="0" color="primary" @click="showDragAndDropFile = !showDragAndDropFile">
                            <v-icon class="mr-1">mdi-image-multiple</v-icon>
                            <!-- Obrázok/video -->
                          </v-btn>
                        </template>
                        <span>Pridať video/obrázok</span>
                      </v-tooltip>
                    </v-row>

                    <v-divider class="mt-0 mb-0"></v-divider>

                    <v-card-actions>
                      <v-btn color="primary" :disabled="isDisabled && !textNewPost" @click="dialogNewPost = false; addPost()" block>
                        Uverejniť
                      </v-btn>
                    </v-card-actions>
                  </v-card>
                </v-dialog>

                <v-chip>
                  <v-icon class="mr-1">
                    mdi-emoticon-cool
                  </v-icon>
                  Pocit/aktivita
                </v-chip>
                <v-chip>
                  <v-icon class="mr-1">
                    mdi-video
                  </v-icon>
                  Živé video
                </v-chip>
              </v-chip-group>
            </v-sheet>
          </v-flex>
        </v-layout>
      </v-flex>
    </v-card>
  </v-row>

  <v-row class="ml-0 mr-0 mb-3" v-if="$root.profilePosts.getAllPosts.loadingPosts == false && $root.profilePosts.getAllPosts.allPosts.length != '0'">
    <v-card class="w-100 rounded" elevation="0">
      <v-card-title>Príspevky</v-card-title>
      <v-card-text class="p-0">
        <v-tabs grow>
          <!-- show-arrows -->
          <v-tab>
            <v-icon class="mr-1">mdi-menu</v-icon>Zobrazenie zoznamu
          </v-tab>
          <v-tab>
            <v-icon class="mr-1">mdi-border-all</v-icon>Mriežkové zobrazenie
          </v-tab>
        </v-tabs>
      </v-card-text>
    </v-card>
  </v-row>

  <v-progress-circular class="mt-3" v-if="$root.profilePosts.getAllPosts.loadingPosts" indeterminate size="24" color="primary"></v-progress-circular>

  <template v-for="(post_data, post_index) in $root.profilePosts.getAllPosts.allPosts">
    <v-flex class="mb-3" xs12 sm12 :key="post_index">
      <v-card class="mb-3 rounded" elevation="0">
        <v-list-item>
          <v-list-item-avatar>
            <v-avatar color="primary" size="40" v-if="post_data.userpostmodel.avatar == null">
              <span v-if="post_data.userpostmodel.name != null" class="text-uppercase white--text">{{ post_data.userpostmodel.name.charAt(0) }}</span>
            </v-avatar>
            <v-avatar color="primary" size="40" v-else>
              <v-img :lazy-src="`${$root.envUrlNoApi}/storage/user-avatar/${post_data.userpostmodel.avatar}`" :src="`${$root.envUrlNoApi}/storage/user-avatar/${post_data.userpostmodel.avatar}`" />
            </v-avatar>
          </v-list-item-avatar>

          <v-list-item-content>
            <v-list-item-title class="d-flex justify-start">
              <v-menu top offset-y transition="scale-transition" :close-on-content-click="false">
                <template v-slot:activator="{ on, attrs }">
                  <v-chip @click="mouseOver(post_data.userpostmodel)" v-bind="attrs" v-on="on" small>
                    <v-icon left class="mr-1" small>mdi-account-circle-outline</v-icon>{{post_data.userpostmodel.name}}
                  </v-chip>
               </template>
               <v-card v-if="dataAboutUserOnHover != null">
                 <v-list>
                   <v-list-item>
                     <v-avatar color="primary" size="60" v-if="post_data.userpostmodel.avatar == null">
                       <span class="text-uppercase white--text">{{ post_data.userpostmodel.name.charAt(0) }}</span>
                     </v-avatar>
                     <v-avatar color="primary" size="60" v-else>
                       <img :src="`${$root.envUrlNoApi}/storage/user-avatar/${post_data.userpostmodel.avatar}`">
                     </v-avatar>

                     <v-list-item-content class="ml-3">
                       <v-card class="p-2 rounded-lg card-comments" elevation="0">
                         <v-list-item-title>
                           {{post_data.userpostmodel.name}}
                         </v-list-item-title>
                         <v-list-item-subtitle>{{post_data.userpostmodel.email}}</v-list-item-subtitle>
                       </v-card>
                     </v-list-item-content>
                   </v-list-item>
                 </v-list>

                 <v-divider class="mt-0 mb-0" />

                 <v-card-actions v-if="isFriendOrNoOrMe_overlay">
                   <v-list class="p-0" dense>
                     <v-list-item-group color="primary">
                       <v-list-item inactive disabled>
                         <v-progress-circular class="d-flex justify-content-center" indeterminate size="24" color="primary"></v-progress-circular>
                       </v-list-item>
                     </v-list-item-group>
                   </v-list>
                 </v-card-actions>

                 <v-card-actions v-else>
                   <v-spacer></v-spacer>
                   <div v-if="isFriendOrNoOrMe == -1">
                     <!-- me -->
                     <div v-if="$root.me.id == post_data.userpostmodel.id">
                       <v-btn small class="ml-3" color="primary" outlined rounded>
                         <v-icon small class="mr-1">mdi-account</v-icon>
                         Vy
                       </v-btn>
                       <v-btn small class="ml-3" color="primary" rounded :to="'/profile/'+$root.me.id">
                         <v-icon small class="mr-1">mdi-account-box</v-icon>
                         Zobraziť môj profil
                       </v-btn>
                     </div>
                     <!-- my friends -->
                     <div v-else>
                       <v-btn small class="ml-3" color="primary" outlined rounded>
                         <v-icon small class="mr-1">mdi-account-check</v-icon>
                         Priatelia
                       </v-btn>
                       <v-btn small class="ml-3" color="primary" rounded :to="'/messenger?name='+post_data.userpostmodel.name">
                         <v-icon small class="mr-1">mdi-forum</v-icon>
                         Správa
                       </v-btn>
                     </div>
                   </div>
                   <div v-else>
                     <div v-if="$root.me.id == post_data.userpostmodel.id">
                       <v-btn small class="ml-3" color="primary" outlined rounded>
                         <v-icon small class="mr-1">mdi-account</v-icon>
                         Vy
                       </v-btn>
                       <v-btn small class="ml-3" color="primary" rounded :to="'/profile/'+$root.me.id">
                         <v-icon small class="mr-1">mdi-account-box</v-icon>
                         Zobraziť môj profil
                       </v-btn>
                     </div>
                     <div v-else-if="isFriendOrNoOrMe == 1">
                       <v-btn small class="ml-3" color="primary" outlined rounded>
                         <v-icon small class="mr-1">mdi-account-check</v-icon>
                         Priatelia
                       </v-btn>
                       <v-btn small class="ml-3" color="primary" rounded :to="'/messenger?name='+post_data.userpostmodel.name">
                         <v-icon small class="mr-1">mdi-forum</v-icon>
                         Správa
                       </v-btn>
                     </div>
                     <!-- if no friends -->
                     <div v-else>
                       <v-btn small class="ml-3" color="primary" outlined rounded disabled>
                         <v-icon small class="mr-1">mdi-account-remove</v-icon>
                         Nie ste priatelia
                       </v-btn>
                       <v-btn small class="ml-3" color="primary" rounded :to="'/profile/'+post_data.userpostmodel.id">
                         <v-icon small class="mr-1">mdi-account-box</v-icon>
                         Zobraziť profil
                       </v-btn>
                     </div>
                   </div>
                 </v-card-actions>
               </v-card>
            </v-menu>
            </v-list-item-title>
            <v-list-item-subtitle class="d-flex justify-start primary--text">
              <v-tooltip bottom>
                <template v-slot:activator="{ on: tooltip }">
                  <v-chip v-on="{ ...tooltip}" small color="primary">
                    <span>
                      <v-icon left class="mr-1" small>mdi-clock-outline</v-icon>{{new Date(Date.parse(post_data.created_at)).toLocaleString('default',{day: 'numeric', month: 'long', year: 'numeric'})}}
                    </span>
                  </v-chip>
                </template>
                <span>
                  <v-icon left class="mr-1 align-middle" small color="white">mdi-clock-outline</v-icon>
                  <span class="align-middle">{{new Date(Date.parse(post_data.created_at)).toLocaleString()}}</span>
                </span>
              </v-tooltip>

              <v-tooltip bottom>
                <template v-slot:activator="{ on: tooltip }">
                  <v-chip class="ml-1" v-if="post_data.status == 'updated'"  v-on="{ ...tooltip}"  small>
                    <v-icon left class="mr-1" small>mdi-pencil</v-icon>
                    <span>Upravené</span>
                  </v-chip>
                </template>
                <span>
                  <v-icon left class="mr-1" small color="white">mdi-clock-outline</v-icon>
                  <span>{{new Date(Date.parse(post_data.updated_at)).toLocaleString()}}</span>
                </span>
              </v-tooltip>
            </v-list-item-subtitle>
          </v-list-item-content>

          <v-list-item-action>
            <v-menu left>
              <template v-slot:activator="{ on: menu, attrs }">
                <v-tooltip bottom>
                  <template v-slot:activator="{ on: tooltip }">
                    <v-btn icon fab small v-bind="attrs" v-on="{ ...tooltip, ...menu }">
                      <v-icon>mdi-dots-horizontal</v-icon>
                    </v-btn>
                  </template>
                  <span>Nastavenia príspevku</span>
                </v-tooltip>
              </template>
              <v-list v-if=" post_data.userpostmodel.name == $root.me.name">
                <v-list-item-group color="primary">
                  <v-list-item @click="updatePost(post_data)">
                    <v-icon class="mr-7">mdi-pencil</v-icon>
                    <v-list-item-content>
                      <v-card class="p-2 rounded-lg card-comments" elevation="0">
                        <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-3 pl-1">
                          Aktualizovať príspevok
                        </v-card-title>
                        <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">Zmeň príspevok</v-card-subtitle>
                      </v-card>
                    </v-list-item-content>
                  </v-list-item>

                  <v-list-item @click="deletePost(post_data)">
                    <v-icon class="mr-7">mdi-delete</v-icon>
                    <v-list-item-content>
                      <v-card class="p-2 rounded-lg card-comments" elevation="0">
                        <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-3 pl-1">
                          Zmazať príspevok
                        </v-card-title>
                        <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">Zmaž príspevok</v-card-subtitle>
                      </v-card>
                    </v-list-item-content>
                  </v-list-item>
                </v-list-item-group>
              </v-list>
              <v-list v-else>
                <v-list-item-group color="primary">
                  <v-list-item @click="reportPost(post_data)">
                    <v-icon class="mr-7">mdi-alert-octagon</v-icon>
                    <v-list-item-content>
                      <v-card class="p-2 rounded-lg card-comments" elevation="0">
                        <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-3 pl-1">
                          Nahlásenie príspevok
                        </v-card-title>
                        <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">Nahlás príspevok</v-card-subtitle>
                      </v-card>
                    </v-list-item-content>
                  </v-list-item>
                </v-list-item-group>
              </v-list>
            </v-menu>
          </v-list-item-action>
        </v-list-item>

        <v-divider class="mt-0 mb-0"></v-divider>

        <v-list-item class="p-0" v-if="post_data.image_url != null">
          <v-list-item-content class="p-0">
            <v-img :lazy-src="`${$root.envUrlNoApi}/storage/post_img_video/${post_data.image_url}`" :src="`${$root.envUrlNoApi}/storage/post_img_video/${post_data.image_url}`" />
          </v-list-item-content>
        </v-list-item>

        <v-list-item class="p-0" v-if="post_data.video_url != null">
          <v-list-item-content class="p-0">
            <vue-plyr>
              <video controls playsinline>
                <!-- crossorigin -->
                <!-- :data-poster="`${$root.envUrlNoApi}/storage/poster/${post_data.video_url.substring(0, post_data.video_url.length - 4)}_poster.png`" -->
                <!-- <source size="576" src="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-576p.mp4" type="video/mp4">
                  <source size="720" src="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-720p.mp4" type="video/mp4">
                  <source size="1080" src="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-1080p.mp4" type="video/mp4"> -->
                <source size="1080" :src="$root.envUrlNoApi + '/storage/post_img_video/' +  post_data.video_url" type="video/mp4">
                <!-- src="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-1080p.mp4" -->
                <!-- <track default="" kind="captions" label="English captions" src="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-HD.en.vtt" srclang="en">
                  <track kind="captions" label="Légendes en français" src="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-HD.fr.vtt" srclang="fr"> -->
              </video>
            </vue-plyr>
          </v-list-item-content>
        </v-list-item>

        <v-list-item>
          <v-list-item-content>
            <v-list-item-subtitle class="text-wrap">
              <div class="text-left">{{ post_data.text }}</div>
            </v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>

        <v-divider class="mt-0 mb-0"></v-divider>

        <!-- reactions -->
        <v-list-item>
          <v-list-item-content>
            <v-flex class="text-left">
              <v-menu open-on-hover bottom offset-y v-if="post_data.like != 0">
                <template v-slot:activator="{ on, attrs }">
                  <v-avatar class="mr-1" size="20" v-bind="attrs" v-on="on">
                    <v-img :lazy-src="require('../assets/img/emoticons/thumbs_up.png')" :src="require('../assets/img/emoticons/thumbs_up.png')" />
                  </v-avatar>
                </template>

                <v-list class="p-2">
                  <v-list-item-title class="font-weight-bold primary--text">Páči sa mi to</v-list-item-title>
                  <template v-for="(item, index) in post_data.postreactionmodel">
                    <v-list-item style="min-height: 20px" v-if="item.reaction_type == 'like' && post_data.id == item.post_id" :key="'menuLike'+index">
                      <v-list-item-subtitle class="m-0">{{ item.userpostreactionmodel.name }}</v-list-item-subtitle>
                    </v-list-item>
                  </template>
                </v-list>
              </v-menu>

              <v-menu open-on-hover bottom offset-y v-if="post_data.heart != 0">
                <template v-slot:activator="{ on, attrs }">
                  <v-avatar class="mr-1" size="20" v-bind="attrs" v-on="on">
                    <v-img :lazy-src="require('../assets/img/emoticons/heart.png')" :src="require('../assets/img/emoticons/heart.png')" />
                  </v-avatar>
                </template>

                <v-list class="p-2">
                  <v-list-item-title class="font-weight-bold primary--text">Super</v-list-item-title>
                  <template v-for="(item, index) in post_data.postreactionmodel">
                    <v-list-item style="min-height: 20px" v-if="item.reaction_type == 'heart' && post_data.id == item.post_id" :key="'menuHeart'+index">
                      <v-list-item-subtitle class="m-0">{{ item.userpostreactionmodel.name }}</v-list-item-subtitle>
                    </v-list-item>
                  </template>
                </v-list>
              </v-menu>

              <v-menu open-on-hover bottom offset-y v-if="post_data.funny != 0">
                <template v-slot:activator="{ on, attrs }">
                  <v-avatar class="mr-1" size="20" v-bind="attrs" v-on="on">
                    <v-img :lazy-src="require('../assets/img/emoticons/face_open_mouth_smiling_eyes.png')" :src="require('../assets/img/emoticons/face_open_mouth_smiling_eyes.png')" />
                  </v-avatar>
                </template>

                <v-list class="p-2">
                  <v-list-item-title class="font-weight-bold primary--text">Haha</v-list-item-title>
                  <template v-for="(item, index) in post_data.postreactionmodel">
                    <v-list-item style="min-height: 20px" v-if="item.reaction_type == 'funny' && post_data.id == item.post_id" :key="'menuFunny'+index">
                      <v-list-item-subtitle class="m-0">{{ item.userpostreactionmodel.name }}</v-list-item-subtitle>
                    </v-list-item>
                  </template>
                </v-list>
              </v-menu>

              <v-menu open-on-hover bottom offset-y v-if="post_data.surprise != 0">
                <template v-slot:activator="{ on, attrs }">
                  <v-avatar class="mr-1" size="20" v-bind="attrs" v-on="on">
                    <v-img :lazy-src="require('../assets/img/emoticons/face_open_mouth.png')" :src="require('../assets/img/emoticons/face_open_mouth.png')" />
                  </v-avatar>
                </template>

                <v-list class="p-2">
                  <v-list-item-title class="font-weight-bold primary--text">Žasnem</v-list-item-title>
                  <template v-for="(item, index) in post_data.postreactionmodel">
                    <v-list-item style="min-height: 20px" v-if="item.reaction_type == 'surprise' && post_data.id == item.post_id" :key="'menuSurprise'+index">
                      <v-list-item-subtitle class="m-0">{{ item.userpostreactionmodel.name }}</v-list-item-subtitle>
                    </v-list-item>
                  </template>
                </v-list>
              </v-menu>

              <v-menu open-on-hover bottom offset-y v-if="post_data.sad != 0">
                <template v-slot:activator="{ on, attrs }">
                  <v-avatar class="mr-1" size="20" v-bind="attrs" v-on="on">
                    <v-img :lazy-src="require('../assets/img/emoticons/disappointed_relieved_face.png')" :src="require('../assets/img/emoticons/disappointed_relieved_face.png')" />
                  </v-avatar>
                </template>

                <v-list class="p-2">
                  <v-list-item-title class="font-weight-bold primary--text">Je mi to ľúto</v-list-item-title>
                  <template v-for="(item, index) in post_data.postreactionmodel">
                    <v-list-item style="min-height: 20px" v-if="item.reaction_type == 'sad' && post_data.id == item.post_id" :key="'menuSad'+index">
                      <v-list-item-subtitle class="m-0">{{ item.userpostreactionmodel.name }}</v-list-item-subtitle>
                    </v-list-item>
                  </template>
                </v-list>
              </v-menu>

              <v-menu open-on-hover bottom offset-y v-if="post_data.angry != 0">
                <template v-slot:activator="{ on, attrs }">
                  <v-avatar class="mr-1" size="20" v-bind="attrs" v-on="on">
                    <v-img :lazy-src="require('../assets/img/emoticons/pouting_face.png')" :src="require('../assets/img/emoticons/pouting_face.png')" />
                  </v-avatar>
                </template>

                <v-list class="p-2">
                  <v-list-item-title class="font-weight-bold primary--text">Štve ma to</v-list-item-title>
                  <template v-for="(item, index) in post_data.postreactionmodel">
                    <v-list-item style="min-height: 20px" v-if="item.reaction_type == 'angry' && post_data.id == item.post_id" :key="'menuAngry'+index">
                      <v-list-item-subtitle class="m-0">{{ item.userpostreactionmodel.name }}</v-list-item-subtitle>
                    </v-list-item>
                  </template>
                </v-list>
              </v-menu>

              <!-- //////////////////////////////////////////////////////////////////////////////////// -->
              <v-btn small text class="pl-2 pr-2" disabled v-if="post_data.count_reactions == 0">
                {{post_data.count_reactions}} hodnotení
              </v-btn>
              <v-dialog max-width="600" :retain-focus="false" v-if="post_data.count_reactions != 0">
                <template v-slot:activator="{ on, attrs }">
                  <v-btn small text outlined class="hidden-sm-and-down primary--color rounded-pill" v-bind="attrs" v-on="on">
                    <div class="primary--color">{{post_data.count_reactions}}</div>
                  </v-btn>
                  <v-btn small text outlined class="hidden-md-and-up primary--color rounded-pill" :min-width="40" v-bind="attrs" v-on="on">
                    {{post_data.count_reactions}}
                  </v-btn>
                </template>

                <template v-slot:default="dialogReactionsPost">
                  <v-card>
                    <v-toolbar color="primary" dark elevation="0">
                      <!-- v-if="selectedDialogReactionsPost == null" -->
                      <v-tabs v-model="tabReactionsPost[post_data.id]" background-color="primary white--text" dark :show-arrows="true">
                        <v-tab>
                          <span>Všetko</span>
                          <span class="ml-1">{{post_data.count_reactions}}</span>
                        </v-tab>

                        <v-tab class="h-100" v-if="post_data.like != 0">
                          <v-avatar class="mr-1" size="20">
                              <v-img :lazy-src="require('../assets/img/emoticons/thumbs_up.png')" :src="require('../assets/img/emoticons/thumbs_up.png')" />
                          </v-avatar>
                          <span>{{post_data.like}}</span>
                        </v-tab>

                        <v-tab class="h-100" v-if="post_data.heart != 0">
                          <v-avatar class="mr-1" size="20">
                              <v-img :lazy-src="require('../assets/img/emoticons/heart.png')" :src="require('../assets/img/emoticons/heart.png')" />
                          </v-avatar>
                          <span>{{post_data.heart}}</span>
                        </v-tab>

                        <v-tab class="h-100" v-if="post_data.funny != 0">
                          <v-avatar class="mr-1" size="20">
                              <v-img :lazy-src="require('../assets/img/emoticons/face_open_mouth_smiling_eyes.png')" :src="require('../assets/img/emoticons/face_open_mouth_smiling_eyes.png')" />
                          </v-avatar>
                          <span>{{post_data.funny}}</span>
                        </v-tab>

                        <v-tab class="h-100" v-if="post_data.surprise != 0">
                          <v-avatar class="mr-1" size="20">
                              <v-img :lazy-src="require('../assets/img/emoticons/face_open_mouth.png')" :src="require('../assets/img/emoticons/face_open_mouth.png')" />
                          </v-avatar>
                          <span>{{post_data.surprise}}</span>
                        </v-tab>

                        <v-tab class="h-100" v-if="post_data.sad != 0">
                          <v-avatar class="mr-1" size="20">
                              <v-img :lazy-src="require('../assets/img/emoticons/disappointed_relieved_face.png')" :src="require('../assets/img/emoticons/disappointed_relieved_face.png')" />
                          </v-avatar>
                          <span>{{post_data.sad}}</span>
                        </v-tab>

                        <v-tab class="h-100" v-if="post_data.angry != 0">
                          <v-avatar class="mr-1" size="20">
                              <v-img :lazy-src="require('../assets/img/emoticons/pouting_face.png')" :src="require('../assets/img/emoticons/pouting_face.png')" />
                          </v-avatar>
                          <span>{{post_data.angry}}</span>
                        </v-tab>
                      </v-tabs>
                      <v-spacer />
                      <v-btn icon dark @click="dialogReactionsPost.value = false">
                        <v-icon>mdi-close</v-icon>
                      </v-btn>
                    </v-toolbar>

                    <!-- <v-toolbar color="primary" dark v-else>
                        <v-btn text outlined class="rounded-pill">
                          <v-icon class="mr-1">mdi-card-account-details</v-icon> Pozrieť profil
                        </v-btn>
                      </v-toolbar> -->

                    <v-tabs-items v-model="tabReactionsPost[post_data.id]">
                      <v-tab-item>
                        <v-card flat>
                          <v-card-text class="p-0">
                            <v-list-item-group v-model="reactionsPostAll" color="primary" v-for="(item, index) in post_data.postreactionmodel" :key="'all'+index">
                              <v-list-item v-if="post_data.id == item.post_id">
                                <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                  <v-avatar color="primary" size="40" v-if="item.userpostreactionmodel.avatar == null">
                                    <span class="text-uppercase white--text">{{ item.userpostreactionmodel.name.charAt(0) }}</span>
                                  </v-avatar>
                                  <v-avatar color="primary" size="40" v-else>
                                    <v-img :lazy-src="`${$root.envUrlNoApi}/storage/user-avatar/${item.userpostreactionmodel.avatar}`" :src="`${$root.envUrlNoApi}/storage/user-avatar/${item.userpostreactionmodel.avatar}`" />
                                  </v-avatar>
                                </v-badge>
                                <v-list-item-content>
                                  <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.userpostreactionmodel.name"></v-list-item-title>
                                </v-list-item-content>
                                <v-list-item-action>
                                  <v-btn icon fab small>
                                    <v-icon>mdi-dots-vertical</v-icon>
                                  </v-btn>
                                </v-list-item-action>
                              </v-list-item>
                            </v-list-item-group>
                          </v-card-text>
                        </v-card>
                      </v-tab-item>

                      <v-tab-item v-if="post_data.like != 0">
                        <v-card flat>
                          <v-card-text class="p-0">
                            <v-list-item-group v-model="reactionsPostLike" color="primary" v-for="(item, index) in post_data.postreactionmodel" :key="'like'+index">
                              <v-list-item v-if="item.reaction_type == 'like' && post_data.id == item.post_id">
                                <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                  <v-avatar color="primary" size="40" v-if="item.userpostreactionmodel.avatar == null">
                                    <span class="text-uppercase white--text">{{ item.userpostreactionmodel.name.charAt(0) }}</span>
                                  </v-avatar>
                                  <v-avatar color="primary" size="40" v-else>
                                    <v-img :lazy-src="`${$root.envUrlNoApi}/storage/user-avatar/${item.userpostreactionmodel.avatar}`" :src="`${$root.envUrlNoApi}/storage/user-avatar/${item.userpostreactionmodel.avatar}`" />
                                  </v-avatar>
                                </v-badge>
                                <v-list-item-content>
                                  <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.userpostreactionmodel.name"></v-list-item-title>
                                </v-list-item-content>
                                <v-list-item-action>
                                  <v-btn icon fab small>
                                    <v-icon>mdi-dots-vertical</v-icon>
                                  </v-btn>
                                </v-list-item-action>
                              </v-list-item>
                            </v-list-item-group>
                          </v-card-text>
                        </v-card>
                      </v-tab-item>

                      <v-tab-item v-if="post_data.heart != 0">
                        <v-card flat>
                          <v-card-text class="p-0">
                            <v-list-item-group v-model="reactionsPostHeart" color="primary" v-for="(item, index) in post_data.postreactionmodel" :key="'heart'+index">
                              <v-list-item v-if="item.reaction_type == 'heart' && post_data.id == item.post_id">
                                <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                  <v-avatar color="primary" size="40" v-if="item.userpostreactionmodel.avatar == null">
                                    <span class="text-uppercase white--text">{{ item.userpostreactionmodel.name.charAt(0) }}</span>
                                  </v-avatar>
                                  <v-avatar color="primary" size="40" v-else>
                                    <v-img :lazy-src="`${$root.envUrlNoApi}/storage/user-avatar/${item.userpostreactionmodel.avatar}`" :src="`${$root.envUrlNoApi}/storage/user-avatar/${item.userpostreactionmodel.avatar}`" />
                                  </v-avatar>
                                </v-badge>
                                <v-list-item-content>
                                  <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.userpostreactionmodel.name"></v-list-item-title>
                                </v-list-item-content>
                                <v-list-item-action>
                                  <v-btn icon fab small>
                                    <v-icon>mdi-dots-vertical</v-icon>
                                  </v-btn>
                                </v-list-item-action>
                              </v-list-item>
                            </v-list-item-group>
                          </v-card-text>
                        </v-card>
                      </v-tab-item>

                      <v-tab-item v-if="post_data.funny != 0">
                        <v-card flat>
                          <v-card-text class="p-0">
                            <v-list-item-group v-model="reactionsPostFunny" color="primary" v-for="(item, index) in post_data.postreactionmodel" :key="'funny'+index">
                              <v-list-item v-if="item.reaction_type == 'funny' && post_data.id == item.post_id">
                                <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                  <v-avatar color="primary" size="40" v-if="item.userpostreactionmodel.avatar == null">
                                    <span class="text-uppercase white--text">{{ item.userpostreactionmodel.name.charAt(0) }}</span>
                                  </v-avatar>
                                  <v-avatar color="primary" size="40" v-else>
                                    <v-img :lazy-src="`${$root.envUrlNoApi}/storage/user-avatar/${item.userpostreactionmodel.avatar}`" :src="`${$root.envUrlNoApi}/storage/user-avatar/${item.userpostreactionmodel.avatar}`" />
                                  </v-avatar>
                                </v-badge>
                                <v-list-item-content>
                                  <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.userpostreactionmodel.name"></v-list-item-title>
                                </v-list-item-content>
                                <v-list-item-action>
                                  <v-btn icon fab small>
                                    <v-icon>mdi-dots-vertical</v-icon>
                                  </v-btn>
                                </v-list-item-action>
                              </v-list-item>
                            </v-list-item-group>
                          </v-card-text>
                        </v-card>
                      </v-tab-item>

                      <v-tab-item v-if="post_data.surprise != 0">
                        <v-card flat>
                          <v-card-text class="p-0">
                            <v-list-item-group v-model="reactionsPostSurprise" color="primary" v-for="(item, index) in post_data.postreactionmodel" :key="'surprise'+index">
                              <v-list-item v-if="item.reaction_type == 'surprise' && post_data.id == item.post_id">
                                <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                  <v-avatar color="primary" size="40" v-if="item.userpostreactionmodel.avatar == null">
                                    <span class="text-uppercase white--text">{{ item.userpostreactionmodel.name.charAt(0) }}</span>
                                  </v-avatar>
                                  <v-avatar color="primary" size="40" v-else>
                                    <v-img :lazy-src="`${$root.envUrlNoApi}/storage/user-avatar/${item.userpostreactionmodel.avatar}`" :src="`${$root.envUrlNoApi}/storage/user-avatar/${item.userpostreactionmodel.avatar}`" />
                                  </v-avatar>
                                </v-badge>
                                <v-list-item-content>
                                  <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.userpostreactionmodel.name"></v-list-item-title>
                                </v-list-item-content>
                                <v-list-item-action>
                                  <v-btn icon fab small>
                                    <v-icon>mdi-dots-vertical</v-icon>
                                  </v-btn>
                                </v-list-item-action>
                              </v-list-item>
                            </v-list-item-group>
                          </v-card-text>
                        </v-card>
                      </v-tab-item>

                      <v-tab-item v-if="post_data.sad != 0">
                        <v-card flat>
                          <v-card-text class="p-0">
                            <v-list-item-group v-model="reactionsPostSad" color="primary" v-for="(item, index) in post_data.postreactionmodel" :key="'sad'+index">
                              <v-list-item v-if="item.reaction_type == 'sad' && post_data.id == item.post_id">
                                <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                  <v-avatar color="primary" size="40" v-if="item.userpostreactionmodel.avatar == null">
                                    <span class="text-uppercase white--text">{{ item.userpostreactionmodel.name.charAt(0) }}</span>
                                  </v-avatar>
                                  <v-avatar color="primary" size="40" v-else>
                                    <v-img :lazy-src="`${$root.envUrlNoApi}/storage/user-avatar/${item.userpostreactionmodel.avatar}`" :src="`${$root.envUrlNoApi}/storage/user-avatar/${item.userpostreactionmodel.avatar}`" />
                                  </v-avatar>
                                </v-badge>
                                <v-list-item-content>
                                  <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.userpostreactionmodel.name"></v-list-item-title>
                                </v-list-item-content>
                                <v-list-item-action>
                                  <v-btn icon fab small>
                                    <v-icon>mdi-dots-vertical</v-icon>
                                  </v-btn>
                                </v-list-item-action>
                              </v-list-item>
                            </v-list-item-group>
                          </v-card-text>
                        </v-card>
                      </v-tab-item>

                      <v-tab-item v-if="post_data.angry != 0">
                        <v-card flat>
                          <v-card-text class="p-0">
                            <v-list-item-group v-model="reactionsPostAngry" color="primary" v-for="(item, index) in post_data.postreactionmodel" :key="'angry'+index">
                              <v-list-item v-if="item.reaction_type == 'angry' && post_data.id == item.post_id">
                                <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                  <v-avatar color="primary" size="40" v-if="item.userpostreactionmodel.avatar == null">
                                    <span class="text-uppercase white--text">{{ item.userpostreactionmodel.name.charAt(0) }}</span>
                                  </v-avatar>
                                  <v-avatar color="primary" size="40" v-else>
                                    <v-img :lazy-src="`${$root.envUrlNoApi}/storage/user-avatar/${item.userpostreactionmodel.avatar}`" :src="`${$root.envUrlNoApi}/storage/user-avatar/${item.userpostreactionmodel.avatar}`" />
                                  </v-avatar>
                                </v-badge>
                                <v-list-item-content>
                                  <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.userpostreactionmodel.name"></v-list-item-title>
                                </v-list-item-content>
                                <v-list-item-action>
                                  <v-btn icon fab small>
                                    <v-icon>mdi-dots-vertical</v-icon>
                                  </v-btn>
                                </v-list-item-action>
                              </v-list-item>
                            </v-list-item-group>
                          </v-card-text>
                        </v-card>
                      </v-tab-item>
                    </v-tabs-items>
                  </v-card>
                </template>
              </v-dialog>
            </v-flex>
            <!-- oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo -->

            <v-flex class="text-right">
              <v-layout wrap justify-end>
                <div class="font-weight-bold">
                  <v-btn small text class="pl-2 pr-2" disabled v-if="post_data.count_comments == 0">
                    {{post_data.count_comments}} Komentáre
                    <v-icon class="ml-2" small>mdi-chevron-up</v-icon>
                  </v-btn>

                  <v-btn small text @click="handleSelected(post_data.id)" :key="post_data.id" class="pl-2 pr-2" v-else>
                    <!-- @click="showPostDetails = !showPostDetails"  -->
                    <span v-if="post_data.count_comments == 1">{{post_data.count_comments}} Komentár</span>
                    <span v-else>{{post_data.count_comments}} Komentáre</span>
                    <v-icon class="ml-2 arrow-d-none" :id="`showPostDetailsArrowDown${post_data.id}`" small>mdi-chevron-down</v-icon>
                    <v-icon class="ml-2 arrow-d-block" :id="`showPostDetailsArrowUp${post_data.id}`" small>mdi-chevron-up</v-icon>
                  </v-btn>

                  <v-dialog v-model="dialogPostShares[post_data.id]" max-width="600" :retain-focus="false">
                    <template v-slot:activator="{ on, attrs }">
                      <v-btn small text class="pl-2 pr-2" v-bind="attrs" v-on="on" disabled v-if="post_data.count_shares == 0">
                        {{post_data.count_shares}} zdieľaní
                      </v-btn>
                      <v-btn small text class="pl-2 pr-2" v-bind="attrs" v-on="on" v-else>
                        <span v-if="post_data.count_shares == 1">{{post_data.count_shares}} zdieľanie</span>
                        <span v-else-if="post_data.count_shares > 5">{{post_data.count_shares}} zdieľania</span>
                        <span v-else>{{post_data.count_shares}} zdieľaní</span>
                      </v-btn>
                    </template>

                    <v-card>
                      <v-toolbar color="primary" dark elevation="0">
                        Zdieľania
                        <v-spacer />
                        <v-btn icon dark @click="dialogPostShares[post_data.id] = false">
                          <v-icon>mdi-close</v-icon>
                        </v-btn>
                      </v-toolbar>

                      <v-card-text class="p-0">
                        <v-list-item-group color="primary" v-for="(item, index) in post_data.postsharemodel" :key="'shares'+index">
                          <v-list-item v-if="post_data.id == item.post_id">
                            <v-list-item-avatar>
                              <v-avatar color="primary" size="40" v-if="item.userpostsharemodel.avatar == null">
                                <span v-if="item.userpostsharemodel.name != null" class="text-uppercase white--text">{{ item.userpostsharemodel.name.charAt(0) }}</span>
                              </v-avatar>
                              <v-avatar color="primary" size="40" v-else>
                                <v-img :lazy-src="`${$root.envUrlNoApi}/storage/user-avatar/${item.userpostsharemodel.avatar}`" :src="`${$root.envUrlNoApi}/storage/user-avatar/${item.userpostsharemodel.avatar}`" />
                              </v-avatar>
                            </v-list-item-avatar>

                            <v-list-item-content>
                              <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.userpostsharemodel.name"></v-list-item-title>
                              <v-list-item-subtitle class="text-left p-0">
                                <span>
                                  <!-- minutes -->
                                  <v-tooltip bottom v-if="Math.round((new Date().getTime() - new Date(item.created_at).getTime()) / 60000) < 60">
                                    <template v-slot:activator="{ on, attrs }">
                                      <v-btn v-bind="attrs" v-on="on" x-small text rounded plain class="d-flex justify-start p-0 pl-0">
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
                                      <v-btn v-bind="attrs" v-on="on" x-small text rounded plain class="d-flex justify-start p-0 pl-0">
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
                                      <v-btn v-bind="attrs" v-on="on" x-small text rounded plain class="d-flex justify-start p-0 pl-0">
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
                                      <v-btn v-bind="attrs" v-on="on" x-small text rounded plain class="d-flex justify-start p-0 pl-0">
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
                                      <v-btn v-bind="attrs" v-on="on" x-small text rounded plain class="d-flex justify-start p-0 pl-0">
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
                            </v-list-item-content>
                            <v-list-item-action>
                              <v-btn icon fab small>
                                <v-icon>mdi-dots-vertical</v-icon>
                              </v-btn>
                            </v-list-item-action>
                          </v-list-item>
                        </v-list-item-group>
                      </v-card-text>
                    </v-card>
                  </v-dialog>
                </div>
              </v-layout>
            </v-flex>
          </v-list-item-content>
        </v-list-item>

        <v-divider class="mt-0 mb-0"></v-divider>

        <!-- /////////////////////////////////////post function like, comment, share///////////////////////////////////////////////////////////// -->
        <v-list-item>
          <v-list-item-content class="text-field-comment p-0">
            <v-card elevation="0">
              <v-card-text class="pl-0 pr-0 hidden-sm-and-down">
                <v-menu open-on-hover top offset-y min-width="200px" rounded="pill">
                  <template v-slot:activator="{ on }">
                    <v-btn text rounded outlined v-on="on" color="primary" v-if="isThereMyReaction(post_data.postreactionmodel)" @click="removeRate(post_data.id)">
                      <span style="color:#ffb71d" v-if="isThereMyReactionTypeReaction(post_data.postreactionmodel)['reaction_type'] == 'like'">
                        <v-avatar class="mr-1" size="20">
                            <v-img :lazy-src="require('../assets/img/emoticons/thumbs_up.png')" :src="require('../assets/img/emoticons/thumbs_up.png')" />
                        </v-avatar>
                        <span class="mt-1 font-weight-bold d-inline-block align-middle">Páči sa mi to</span>
                      </span>
                      <span class="red--text" v-if="isThereMyReactionTypeReaction(post_data.postreactionmodel)['reaction_type'] == 'heart'">
                        <v-avatar class="mr-1" size="20">
                            <v-img :lazy-src="require('../assets/img/emoticons/heart.png')" :src="require('../assets/img/emoticons/heart.png')" />
                        </v-avatar>
                        <span class="mt-1 font-weight-bold d-inline-block align-middle">Super</span>
                      </span>
                      <span style="color:#ffb71d" v-if="isThereMyReactionTypeReaction(post_data.postreactionmodel)['reaction_type'] == 'funny'">
                        <v-avatar class="mr-1" size="20">
                            <v-img :lazy-src="require('../assets/img/emoticons/face_open_mouth_smiling_eyes.png')" :src="require('../assets/img/emoticons/face_open_mouth_smiling_eyes.png')" />
                        </v-avatar>
                        <span class="mt-1 font-weight-bold d-inline-block align-middle">Haha</span>
                      </span>
                      <span style="color:#ffb71d" v-if="isThereMyReactionTypeReaction(post_data.postreactionmodel)['reaction_type'] == 'surprise'">
                        <v-avatar class="mr-1" size="20">
                            <v-img :lazy-src="require('../assets/img/emoticons/face_open_mouth.png')" :src="require('../assets/img/emoticons/face_open_mouth.png')" />
                        </v-avatar>
                        <span class="mt-1 font-weight-bold d-inline-block align-middle">Žasnem</span>
                      </span>
                      <span style="color:#ffb71d" v-if="isThereMyReactionTypeReaction(post_data.postreactionmodel)['reaction_type'] == 'sad'">
                        <v-avatar class="mr-1" size="20">
                            <v-img :lazy-src="require('../assets/img/emoticons/disappointed_relieved_face.png')" :src="require('../assets/img/emoticons/disappointed_relieved_face.png')" />
                        </v-avatar>
                        <span class="mt-1 font-weight-bold d-inline-block align-middle">Je mi to ľúto</span>
                      </span>
                      <span class="red--text" v-if="isThereMyReactionTypeReaction(post_data.postreactionmodel)['reaction_type'] == 'angry'">
                        <v-avatar class="mr-1" size="20">
                            <v-img :lazy-src="require('../assets/img/emoticons/pouting_face.png')" :src="require('../assets/img/emoticons/pouting_face.png')" />
                        </v-avatar>
                        <span class="mt-1 font-weight-bold d-inline-block align-middle">Štve ma to</span>
                      </span>
                    </v-btn>
                    <v-btn text rounded outlined v-on="on" v-else>
                      <v-icon class="mr-1 primary--text" small>mdi-thumb-up</v-icon> Páči sa mi to
                    </v-btn>
                  </template>

                  <v-card class="rounded-pill p-2" elevation="2">
                    <v-btn icon @click="rate('like', post_data.id)">
                      <v-avatar size="20">
                          <v-img :lazy-src="require('../assets/img/emoticons/thumbs_up.png')" :src="require('../assets/img/emoticons/thumbs_up.png')" />
                      </v-avatar>
                    </v-btn>
                    <v-btn icon @click="rate('heart', post_data.id)">
                      <v-avatar size="20">
                          <v-img :lazy-src="require('../assets/img/emoticons/heart.png')" :src="require('../assets/img/emoticons/heart.png')" />
                      </v-avatar>
                    </v-btn>
                    <v-btn icon @click="rate('funny', post_data.id)">
                      <v-avatar size="20">
                        <v-img :lazy-src="require('../assets/img/emoticons/face_open_mouth_smiling_eyes.png')" :src="require('../assets/img/emoticons/face_open_mouth_smiling_eyes.png')" />
                      </v-avatar>
                    </v-btn>

                    <v-btn icon @click="rate('surprise', post_data.id)">
                      <v-avatar size="20">
                        <v-img :lazy-src="require('../assets/img/emoticons/face_open_mouth.png')" :src="require('../assets/img/emoticons/face_open_mouth.png')" />
                      </v-avatar>
                    </v-btn>

                    <v-btn icon @click="rate('sad', post_data.id)">
                      <v-avatar size="20">
                        <v-img :lazy-src="require('../assets/img/emoticons/disappointed_relieved_face.png')" :src="require('../assets/img/emoticons/disappointed_relieved_face.png')" />
                      </v-avatar>
                    </v-btn>

                    <v-btn icon @click="rate('angry', post_data.id)">
                      <v-avatar size="20">
                        <v-img :lazy-src="require('../assets/img/emoticons/pouting_face.png')" :src="require('../assets/img/emoticons/pouting_face.png')" />
                      </v-avatar>
                    </v-btn>
                  </v-card>
                </v-menu>

                <v-btn class="mr-3 ml-3" text rounded outlined @click="focusInput(post_data.id)">
                  <v-icon class="mr-1 primary--text" small>mdi-comment-text-outline</v-icon> Komentovať
                </v-btn>

                <v-tooltip bottom v-if="isThereMyShare(post_data.postsharemodel)">
                  <template v-slot:activator="{ on, attrs }">
                    <v-btn v-bind="attrs" v-on="on" text rounded outlined color="primary" @click="removeShare(post_data.id)">
                      <v-icon class="mr-1 primary--text" small>mdi-share-outline</v-icon><span class="font-weight-bold">Zdieľané</span>
                    </v-btn>
                  </template>
                  <span>Zrušiť zdielanie</span>
                </v-tooltip>

                <v-tooltip bottom v-else>
                  <template v-slot:activator="{ on, attrs }">
                    <v-btn v-bind="attrs" v-on="on" text rounded outlined @click="share(post_data.id)">
                      <v-icon class="mr-1 primary--text" small>mdi-share-outline</v-icon>Zdieľať
                    </v-btn>
                  </template>
                  <span>Zdieľať príspevok</span>
                </v-tooltip>

              </v-card-text>
              <v-card-text class="pl-0 pr-0 hidden-md-and-up">
                <v-menu open-on-hover top offset-y min-width="200px" rounded="pill">
                  <template v-slot:activator="{ on }">
                    <v-btn text rounded outlined v-on="on">
                      <v-icon class="mr-1 primary--text" small>mdi-thumb-up</v-icon>
                    </v-btn>
                  </template>

                  <v-card class="rounded-pill p-2" elevation="2">
                    <v-btn icon @click="rate('like', post_data.id)">
                      <v-avatar size="20">
                        <v-img :lazy-src="require('../assets/img/emoticons/thumbs_up.png')" :src="require('../assets/img/emoticons/thumbs_up.png')" />
                      </v-avatar>
                    </v-btn>
                    <v-btn icon @click="rate('heart', post_data.id)">
                      <v-avatar size="20">
                        <v-img :lazy-src="require('../assets/img/emoticons/heart.png')" :src="require('../assets/img/emoticons/heart.png')" />
                      </v-avatar>
                    </v-btn>
                    <v-btn icon @click="rate('funny', post_data.id)">
                      <v-avatar size="20">
                        <v-img :lazy-src="require('../assets/img/emoticons/face_open_mouth_smiling_eyes.png')" :src="require('../assets/img/emoticons/face_open_mouth_smiling_eyes.png')" />
                      </v-avatar>
                    </v-btn>

                    <v-btn icon @click="rate('surprise', post_data.id)">
                      <v-avatar size="20">
                        <v-img :lazy-src="require('../assets/img/emoticons/face_open_mouth.png')" :src="require('../assets/img/emoticons/face_open_mouth.png')" />
                      </v-avatar>
                    </v-btn>

                    <v-btn icon @click="rate('sad', post_data.id)">
                      <v-avatar size="20">
                        <v-img :lazy-src="require('../assets/img/emoticons/disappointed_relieved_face.png')" :src="require('../assets/img/emoticons/disappointed_relieved_face.png')" />
                      </v-avatar>
                    </v-btn>

                    <v-btn icon @click="rate('angry', post_data.id)">
                      <v-avatar size="20">
                        <v-img :lazy-src="require('../assets/img/emoticons/pouting_face.png')" :src="require('../assets/img/emoticons/pouting_face.png')" />
                      </v-avatar>
                    </v-btn>
                  </v-card>
                </v-menu>

                <v-btn class="mr-3 ml-3" text rounded outlined @click="focusInput(post_data.id)">
                  <v-icon class="mr-1 primary--text" small>mdi-comment-text-outline</v-icon>
                </v-btn>

                <!-- <v-btn text rounded color="primary" outlined @click="share(post.id)" v-if="user_posts_shares.length != 0 && user_posts_shares[post.id-1] != null && user_posts_shares[post.id-1].post_id == post.id">
                    <v-icon class=" mr-1 primary--text" small>mdi-share</v-icon> Zdieľané
                  </v-btn> -->
                <v-btn text rounded outlined @click="share(post_data.id)">
                  <v-icon class="mr-1 primary--text" small>mdi-share-outline</v-icon>
                </v-btn>
              </v-card-text>
            </v-card>
          </v-list-item-content>
        </v-list-item>
        <!-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
        <!-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
        <v-container style="display:none" :id="`showPostDetails${post_data.id}`" class="p-0">
          <v-divider class="mt-0 mb-0"></v-divider>
          <v-list-item class="ml-15 pl-6 mr-2" v-if="showDragAndDropFileAddPostComment[post_data.id] == true">
            <v-row class="p-2 pb-0 pt-3 mt-0">
              <file-pond allowImageCrop={true} allowFileEncode={true} class="filepond" name="filepond" :ref="`pondAddPostComment${post_data.id}`" type="input" :files="filesAddPostComment" v-on:removefile="handleFileRemovedAddPostComment(post_data.id)" v-on:addfile="handleFileAddedAddPostComment(post_data.id)"
                style="width:100%" label-idle="Sem presuňte súbor..." allow-multiple="true" max-files="1" />
                <!-- v-on:init="handleFilePondInitAddPostComment" -->
            </v-row>
          </v-list-item>

          <v-list-item>
            <v-list-item-avatar>
              <v-avatar color="primary" size="40" v-if="post_data.userpostmodel.avatar == null">
                <span v-if="post_data.userpostmodel.name != null" class="text-uppercase white--text">{{ post_data.userpostmodel.name.charAt(0) }}</span>
              </v-avatar>
              <v-avatar color="primary" size="40" v-else>
                <v-img :lazy-src="`${$root.envUrlNoApi}/storage/user-avatar/${post_data.userpostmodel.avatar}`" :src="`${$root.envUrlNoApi}/storage/user-avatar/${post_data.userpostmodel.avatar}`" />
              </v-avatar>
            </v-list-item-avatar>
            <v-list-item-content class="text-field-comment">
              <!-- comment post -->
              <!-- <template v-slot:activator="{ on }"> -->
              <v-text-field @focus="focusedPostComment(post_data.id)" :hide-details="true" :ref="`commentInput${post_data.id}`" filled rounded dense clearable clear-icon="mdi-close" label="Koment" v-model="textNewPostComment[post_data.id]"
                @click:clear="clearPostComment(post_data.id)" @keydown.enter="comment(post_data.id)">
                <template class="mt-0" v-slot:prepend-inner v-if="chipTextPostComment[post_data.id] != undefined">
                  <v-chip class="ma-1" close @click:close="closeChipPostComment(post_data.id)">{{chipTextPostComment[post_data.id]}}</v-chip>
                </template>
                <template slot="append">
                  <v-menu :close-on-content-click="false" v-model="autoselectMenuPost[post_data.id]" offset-y left :position-y="60">
                    <template v-slot:activator="{ on, attrs }">
                      <v-btn small icon v-bind="attrs" v-on="on" @click="setEmojiID(post_data.id)">
                        <v-icon>
                          mdi-emoticon
                        </v-icon>
                      </v-btn>
                    </template>
                    <!-- <Picker :showSearch="false" :showPreview="false" :showSkinTones="true" :emojiTooltip="true" :data="emojiIndex" set="facebook" @select="selectEmoji" /> -->
                    <picker :showSearch="false" :showPreview="false" :set="'messenger'" :showSkinTones="true" :emojiTooltip="true" :infiniteScroll="true" @select="selectEmoji" :i18n="i18n" />
                  </v-menu>

                  <v-btn small icon @click="showFileInputPostComment(post_data.id)">
                    <v-icon>
                      mdi-paperclip
                    </v-icon>
                  </v-btn>

                  <!-- <v-menu class="menu-append-icon" offset-y v-model="autoselectMenu2Post[post_data.id]" rounded="pill">
                    <template v-slot:activator="{ on, attrs }">
                      <v-icon v-bind="attrs" v-on="on">
                        mdi-dots-grid
                      </v-icon>
                    </template>
                    <v-card class="rounded-pill p-2" elevation="2">
                      <v-btn icon @click="showFileInputPostComment(post_data.id)">
                        <v-icon>
                          mdi-paperclip
                        </v-icon>
                      </v-btn>
                    </v-card>
                  </v-menu> -->
                </template>
              </v-text-field>

              <v-menu class="p-2" min-width="250px" max-width="400px" bottom offset-y nudge-bottom="5" origin="top right" v-model="showDialogFriendsPostComment[post_data.id]" :close-on-click="false">
                <v-list class="p-0" dense>
                  <v-list-item-group color="primary" v-model="selectUserFriendsPostComment">
                    <v-list-item-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-2 pt-1 pb-0">
                      <v-subheader>Vybrať priateľa</v-subheader>
                      <v-spacer />
                      <v-btn icon fab x-small color="error" @click="showDialogFriendsPostComment[post_data.id] == !showDialogFriendsPostComment[post_data.id]">
                        <v-icon>mdi-close</v-icon>
                      </v-btn>
                    </v-list-item-title>
                    <v-divider class="mt-0 mb-0" />

                    <v-list-item v-if="overlayDisplayFriendsPostComment == true" inactive disabled>
                      <v-overlay :value="overlayDisplayFriendsPostComment" :absolute="true" :opacity="0">
                        <v-progress-circular indeterminate size="24" color="primary"></v-progress-circular>
                      </v-overlay>
                    </v-list-item>
                    <template v-for="item in displayFriendsPostComment" v-else>
                      <v-list-item :key="item.id" @click="selectDataAboutUserPostComment(item, post_data.id)">
                        <v-badge bottom dot bordered :color="getColor(item.status)" offset-x="10" offset-y="10" class="mr-2">
                          <v-avatar color="primary" size="40" v-if="item.avatar == null">
                            <span class="text-uppercase white--text">{{ item.name.charAt(0) }}</span>
                          </v-avatar>
                          <v-avatar color="primary" size="40" v-else>
                            <img :lazy-src="`${$root.envUrlNoApi}/storage/user-avatar/${item.avatar}`" :src="`${$root.envUrlNoApi}/storage/user-avatar/${item.avatar}`">
                          </v-avatar>
                        </v-badge>
                        <v-list-item-content>
                          <v-card class="p-2 rounded-lg card-comments" elevation="0">
                            <div class="d-flex flex-no-wrap justify-space-between">
                              <div>
                                <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pl-1">
                                  {{item.name}}
                                </v-card-title>
                                <v-card-subtitle class="d-flex text-left caption grey--text p-0 pt-3 pl-1">{{item.text}}</v-card-subtitle>
                              </div>
                            </div>
                          </v-card>
                        </v-list-item-content>
                      </v-list-item>
                    </template>
                  </v-list-item-group>
                </v-list>
              </v-menu>
            </v-list-item-content>
          </v-list-item>
          <!-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
          <!-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
          <v-divider class="mt-0 mb-0" v-if="post_data.count_comments != 0"></v-divider>

          <v-list-item class="d-flex justify-end" v-if="post_data.count_comments != 0">
            <v-menu class="p-2" left rounded offset-y :maxWidth="280" v-model="showCommentsFilter[post_data.id]">
              <!-- :position-y="20" :position-x="20"  -->
              <template v-slot:activator="{ on }">
                <div class="font-weight-bold">
                  <v-btn text v-on="on">
                    {{commentsFilter[selectedItemSortComments]}}
                    <v-icon class="ml-2" small v-if="showCommentsFilter[post_data.id] == true">mdi-chevron-down</v-icon>
                    <v-icon class="ml-2" small v-else>mdi-chevron-up</v-icon>
                  </v-btn>
                </div>
              </template>
              <v-card class="v-card-logout">
                <v-list dense>
                  <v-list-item-group color="primary" v-model="selectedItemSortComments">
                    <v-list-item two-line>
                      <v-list-item-content>
                        <v-list-item-title class="d-flex justify-start">Najrelevantnejšíe</v-list-item-title>
                        <v-list-item-subtitle class="d-flex justify-start">Zobrazovať komentáre priateľov</v-list-item-subtitle>
                        <v-list-item-subtitle class="d-flex justify-start">a najzaujímavejšie komentáre na začiatku.</v-list-item-subtitle>
                      </v-list-item-content>
                    </v-list-item>

                    <v-list-item two-line>
                      <v-list-item-content>
                        <v-list-item-title class="d-flex justify-start">Najnovšie</v-list-item-title>
                        <v-list-item-subtitle class="d-flex justify-start">Zobraziť všetky komentáre, s najnovšími</v-list-item-subtitle>
                        <v-list-item-subtitle class="d-flex justify-start">na začiatku.</v-list-item-subtitle>
                      </v-list-item-content>
                    </v-list-item>

                    <v-list-item two-line>
                      <v-list-item-content>
                        <v-list-item-title class="d-flex justify-start">Všetky</v-list-item-title>
                        <v-list-item-subtitle class="d-flex justify-start">Zobrazovať všetky komentáre vrátane</v-list-item-subtitle>
                        <v-list-item-subtitle class="d-flex justify-start">možného spamu.</v-list-item-subtitle>
                        <v-list-item-subtitle class="d-flex justify-start">Najrelevantnejšie komentáre sa budú</v-list-item-subtitle>
                        <v-list-item-subtitle class="d-flex justify-start">zobrazovať na začiatku.</v-list-item-subtitle>
                      </v-list-item-content>
                    </v-list-item>
                  </v-list-item-group>
                </v-list>
              </v-card>
            </v-menu>
          </v-list-item>

          <!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
          <v-list dense>
            <template v-for="(post_comment_data, index) in post_data.postcommentmodel">
              <v-container class="p-0" append-icon="" v-if="post_data.id == post_comment_data.post_id" :id="`post_comment${post_comment_data.id}`" :key="'comments'+index">
                <v-list-item class="w-100">
                  <v-list-item-avatar class="ml-0">
                    <v-avatar color="primary" size="40" v-if="post_comment_data.userpostcommentmodel.avatar == null">
                      <span v-if="post_comment_data.userpostcommentmodel.name != null" class="text-uppercase white--text">{{ post_comment_data.userpostcommentmodel.name.charAt(0) }}</span>
                    </v-avatar>
                    <v-avatar color="primary" size="40" v-else>
                      <v-img :lazy-src="`${$root.envUrlNoApi}/storage/user-avatar/${post_comment_data.userpostcommentmodel.avatar}`" :src="`${$root.envUrlNoApi}/storage/user-avatar/${post_comment_data.userpostcommentmodel.avatar}`" />
                    </v-avatar>
                  </v-list-item-avatar>
                  <v-list-item-content>
                    <v-card class="p-2 rounded-lg card-comments" elevation="0">
                      <v-list-item-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0">
                        <v-menu top offset-y transition="scale-transition" :close-on-content-click="false">
                          <template v-slot:activator="{ on, attrs }">
                            <v-chip @click="mouseOver(post_comment_data.userpostcommentmodel)" v-bind="attrs" v-on="on" small>
                              <v-icon left class="mr-1" small>mdi-account-circle-outline</v-icon>{{post_comment_data.userpostcommentmodel.name}}
                            </v-chip>
                         </template>
                         <v-card v-if="dataAboutUserOnHover != null">
                           <v-list>
                             <v-list-item>
                               <v-avatar color="primary" size="60" v-if="post_comment_data.userpostcommentmodel.avatar == null">
                                 <span class="text-uppercase white--text">{{ post_comment_data.userpostcommentmodel.name.charAt(0) }}</span>
                               </v-avatar>
                               <v-avatar color="primary" size="60" v-else>
                                 <img :src="`${$root.envUrlNoApi}/storage/user-avatar/${post_comment_data.userpostcommentmodel.avatar}`">
                               </v-avatar>

                               <v-list-item-content class="ml-3">
                                 <v-card class="p-2 rounded-lg card-comments" elevation="0">
                                   <v-list-item-title>
                                     {{post_comment_data.userpostcommentmodel.name}}
                                   </v-list-item-title>
                                   <v-list-item-subtitle>{{post_comment_data.userpostcommentmodel.email}}</v-list-item-subtitle>
                                 </v-card>
                               </v-list-item-content>
                             </v-list-item>
                           </v-list>

                           <v-divider class="mt-0 mb-0" />

                           <v-card-actions v-if="isFriendOrNoOrMe_overlay">
                             <v-list class="p-0" dense>
                               <v-list-item-group color="primary">
                                 <v-list-item inactive disabled>
                                   <v-progress-circular class="d-flex justify-content-center" indeterminate size="24" color="primary"></v-progress-circular>
                                 </v-list-item>
                               </v-list-item-group>
                             </v-list>
                           </v-card-actions>

                           <v-card-actions v-else>
                             <v-spacer></v-spacer>
                             <div v-if="isFriendOrNoOrMe == -1">
                               <!-- me -->
                               <div v-if="$root.me.id == post_comment_data.userpostcommentmodel.id">
                                 <v-btn small class="ml-3" color="primary" outlined rounded>
                                   <v-icon small class="mr-1">mdi-account</v-icon>
                                   Vy
                                 </v-btn>
                                 <v-btn small class="ml-3" color="primary" rounded :to="'/profile/'+$root.me.id">
                                   <v-icon small class="mr-1">mdi-account-box</v-icon>
                                   Zobraziť môj profil
                                 </v-btn>
                               </div>
                               <!-- my friends -->
                               <div v-else>
                                 <v-btn small class="ml-3" color="primary" outlined rounded>
                                   <v-icon small class="mr-1">mdi-account-check</v-icon>
                                   Priatelia
                                 </v-btn>
                                 <v-btn small class="ml-3" color="primary" rounded :to="'/messenger?name='+post_comment_data.userpostcommentmodel.name">
                                   <v-icon small class="mr-1">mdi-forum</v-icon>
                                   Správa
                                 </v-btn>
                               </div>
                             </div>
                             <div v-else>
                               <div v-if="$root.me.id == post_comment_data.userpostcommentmodel.id">
                                 <v-btn small class="ml-3" color="primary" outlined rounded>
                                   <v-icon small class="mr-1">mdi-account</v-icon>
                                   Vy
                                 </v-btn>
                                 <v-btn small class="ml-3" color="primary" rounded :to="'/profile/'+$root.me.id">
                                   <v-icon small class="mr-1">mdi-account-box</v-icon>
                                   Zobraziť môj profil
                                 </v-btn>
                               </div>
                               <div v-else-if="isFriendOrNoOrMe == 1">
                                 <v-btn small class="ml-3" color="primary" outlined rounded>
                                   <v-icon small class="mr-1">mdi-account-check</v-icon>
                                   Priatelia
                                 </v-btn>
                                 <v-btn small class="ml-3" color="primary" rounded :to="'/messenger?name='+post_comment_data.userpostcommentmodel.name">
                                   <v-icon small class="mr-1">mdi-forum</v-icon>
                                   Správa
                                 </v-btn>
                               </div>
                               <!-- if no friends -->
                               <div v-else>
                                 <v-btn small class="ml-3" color="primary" outlined rounded disabled>
                                   <v-icon small class="mr-1">mdi-account-remove</v-icon>
                                   Nie ste priatelia
                                 </v-btn>
                                 <v-btn small class="ml-3" color="primary" rounded :to="'/profile/'+post_comment_data.userpostcommentmodel.id">
                                   <v-icon small class="mr-1">mdi-account-box</v-icon>
                                   Zobraziť profil
                                 </v-btn>
                               </div>
                             </div>
                           </v-card-actions>
                         </v-card>
                      </v-menu>

                      <v-tooltip bottom>
                        <template v-slot:activator="{ on: tooltip }">
                          <v-chip class="ml-1" v-if="post_comment_data.status == 'updated'"  v-on="{ ...tooltip}"  small>
                            <v-icon left class="mr-1" small>mdi-pencil</v-icon>
                            <span>Upravené</span>
                          </v-chip>
                        </template>
                        <span>
                          <v-icon left class="mr-1" small color="white">mdi-clock-outline</v-icon>
                          <span>{{new Date(Date.parse(post_comment_data.updated_at)).toLocaleString()}}</span>
                        </span>
                      </v-tooltip>

                      <v-spacer />

                      <v-menu left :close-on-content-click="true">
                        <template v-slot:activator="{ on: menu, attrs }">
                          <v-tooltip bottom>
                            <template v-slot:activator="{ on: tooltip }">
                              <v-btn icon x-small v-bind="attrs" v-on="{ ...tooltip, ...menu }">
                                <v-icon>mdi-dots-horizontal</v-icon>
                              </v-btn>
                            </template>
                            <span>Nastavenia komentára</span>
                          </v-tooltip>
                        </template>
                        <v-list v-if="post_comment_data.userpostcommentmodel.name == $root.me.name">
                          <v-list-item-group color="primary">
                            <v-list-item @click="updatePostComment(post_comment_data, 'posts_comments')">
                              <v-icon class="mr-7">mdi-pencil</v-icon>
                              <v-list-item-content>
                                <v-card class="p-2 rounded-lg card-comments" elevation="0">
                                  <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-3 pl-1">
                                    Upraviť komentár
                                  </v-card-title>
                                  <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">Zmeň komentár</v-card-subtitle>
                                </v-card>
                              </v-list-item-content>
                            </v-list-item>

                            <v-list-item @click="deletePostComment(post_comment_data, 'posts_comments')">
                              <v-icon class="mr-7">mdi-delete</v-icon>
                              <v-list-item-content>
                                <v-card class="p-2 rounded-lg card-comments" elevation="0">
                                  <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-3 pl-1">
                                    Zmazať komentár
                                  </v-card-title>
                                  <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">Zmaž komentár</v-card-subtitle>
                                </v-card>
                              </v-list-item-content>
                            </v-list-item>
                          </v-list-item-group>
                        </v-list>
                        <v-list v-else>
                          <v-list-item-group color="primary">
                            <v-list-item @click="reportPostComment(post_comment_data, 'posts_comments')">
                              <v-icon class="mr-7">mdi-alert-octagon</v-icon>
                              <v-list-item-content>
                                <v-card class="p-2 rounded-lg card-comments" elevation="0">
                                  <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-3 pl-1">
                                    Nahlásiť komentára
                                  </v-card-title>
                                  <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">Nahlás komentár</v-card-subtitle>
                                </v-card>
                              </v-list-item-content>
                            </v-list-item>
                          </v-list-item-group>
                        </v-list>
                      </v-menu>
                      </v-list-item-title>

                      <v-list-item class="pt-2 p-0" v-if="post_comment_data.image_url != null">
                        <v-list-item-content class="p-0">
                          <v-img :lazy-src="`${$root.envUrlNoApi}/storage/post_img_video/${post_comment_data.image_url}`" :src="`${$root.envUrlNoApi}/storage/post_img_video/${post_comment_data.image_url}`" />
                        </v-list-item-content>
                      </v-list-item>

                      <v-list-item class="pt-2 p-0" v-if="post_comment_data.video_url != null">
                        <v-list-item-content class="p-0">
                          <vue-plyr>
                            <video controls playsinline>
                              <source size="1080" :src="$root.envUrlNoApi + '/storage/post_img_video/' +  post_comment_data.video_url" type="video/mp4">
                            </video>
                          </vue-plyr>
                        </v-list-item-content>
                      </v-list-item>

                      <v-list-item-subtitle class="text-left font-weight-bold p-0 pt-1 text-wrap" v-if="post_comment_data.chip == null">{{post_comment_data.text}}</v-list-item-subtitle>
                      <v-list-item-subtitle class="text-left font-weight-bold p-0 pt-1 text-wrap" v-else>
                        <v-chip small class="mr-1" @click="showUserData(post_comment_data.userpostcommentmodel2)">
                          {{post_comment_data.chip}}
                        </v-chip>
                        <span class="position-absolute mt-1">
                          {{post_comment_data.text}}
                        </span>
                      </v-list-item-subtitle>

                      <v-list-item-subtitle class="text-left p-0 pt-1">
                        <span>
                          <!-- minutes -->
                          <v-tooltip bottom v-if="Math.round((new Date().getTime() - new Date(post_comment_data.created_at).getTime()) / 60000) < 60">
                            <template v-slot:activator="{ on, attrs }">
                              <v-btn v-bind="attrs" v-on="on" x-small text rounded plain class="d-flex justify-start p-0 pl-0">
                                <span v-if="Math.round((new Date().getTime() - new Date(post_comment_data.created_at).getTime()) / 60000) > 1">
                                  Pred {{Math.round((new Date().getTime() - new Date(post_comment_data.created_at).getTime()) / 60000)}} minútami
                                </span>
                                <span v-else>
                                  Pred 1 minútou
                                </span>
                              </v-btn>
                            </template>
                            <span>
                              <v-icon left class="mr-1 align-middle" small color="white">mdi-clock-outline</v-icon>
                              <span class="align-middle">{{new Date(Date.parse(post_comment_data.created_at)).toLocaleString()}}</span>
                            </span>
                          </v-tooltip>

                          <!-- hours -->
                          <v-tooltip bottom v-else-if="Math.round((new Date().getTime() - new Date(post_comment_data.created_at).getTime()) / 60000) < 1440">
                            <template v-slot:activator="{ on, attrs }">
                              <v-btn v-bind="attrs" v-on="on" x-small text rounded plain class="d-flex justify-start p-0 pl-0">
                                <span v-if="Math.floor(Math.round((new Date().getTime() - new Date(post_comment_data.created_at).getTime()) / 60000)/60) > 1">
                                  Pred {{Math.floor(Math.round((new Date().getTime() - new Date(post_comment_data.created_at).getTime()) / 60000)/60)}} hodinami
                                </span>
                                <span v-else>
                                  Pred 1 hodinou
                                </span>
                              </v-btn>
                            </template>
                            <span>
                              <v-icon left class="mr-1 align-middle" small color="white">mdi-clock-outline</v-icon>
                              <span class="align-middle">{{new Date(Date.parse(post_comment_data.created_at)).toLocaleString()}}</span>
                            </span>
                          </v-tooltip>

                          <!-- days -->
                          <v-tooltip bottom v-else-if="Math.round((new Date().getTime() - new Date(post_comment_data.created_at).getTime()) / 60000) < 43200">
                            <template v-slot:activator="{ on, attrs }">
                              <v-btn v-bind="attrs" v-on="on" x-small text rounded plain class="d-flex justify-start p-0 pl-0">
                                <span v-if="Math.floor(Math.round((new Date().getTime() - new Date(post_comment_data.created_at).getTime()) / 60000)/1440) > 1">
                                  Pred {{Math.floor(Math.round((new Date().getTime() - new Date(post_comment_data.created_at).getTime()) / 60000)/1440)}} dňami
                                </span>
                                <span v-else>
                                  Pred 1 dňom
                                </span>
                              </v-btn>
                            </template>
                            <span>
                              <v-icon left class="mr-1 align-middle" small color="white">mdi-clock-outline</v-icon>
                              <span class="align-middle">{{new Date(Date.parse(post_comment_data.created_at)).toLocaleString()}}</span>
                            </span>
                          </v-tooltip>

                          <!-- months -->
                          <v-tooltip bottom v-else-if="Math.round((new Date().getTime() - new Date(post_comment_data.created_at).getTime()) / 60000) < 525600">
                            <template v-slot:activator="{ on, attrs }">
                              <v-btn v-bind="attrs" v-on="on" x-small text rounded plain class="d-flex justify-start p-0 pl-0">
                                <span v-if="Math.floor(Math.round((new Date().getTime() - new Date(post_comment_data.created_at).getTime()) / 60000)/43200) > 1">
                                  Pred {{Math.floor(Math.round((new Date().getTime() - new Date(post_comment_data.created_at).getTime()) / 60000)/43200)}} mesiacmi
                                </span>
                                <span v-else>
                                  Pred 1 mesiacom
                                </span>
                              </v-btn>
                            </template>
                            <span>
                              <v-icon left class="mr-1 align-middle" small color="white">mdi-clock-outline</v-icon>
                              <span class="align-middle">{{new Date(Date.parse(post_comment_data.created_at)).toLocaleString()}}</span>
                            </span>
                          </v-tooltip>

                          <!-- years -->
                          <v-tooltip bottom v-else>
                            <template v-slot:activator="{ on, attrs }">
                              <v-btn v-bind="attrs" v-on="on" x-small text rounded plain class="d-flex justify-start p-0 pl-0">
                                <span v-if="Math.floor(Math.round((new Date().getTime() - new Date(post_comment_data.created_at).getTime()) / 60000)/525600) > 1">
                                  Pred {{Math.floor(Math.round((new Date().getTime() - new Date(post_comment_data.created_at).getTime()) / 60000)/525600)}} rokmi
                                </span>
                                <span v-else>
                                  Pred 1 rokom
                                </span>
                              </v-btn>
                            </template>
                            <span>
                              <v-icon left class="mr-1 align-middle" small color="white">mdi-clock-outline</v-icon>
                              <span class="align-middle">{{new Date(Date.parse(post_comment_data.created_at)).toLocaleString()}}</span>
                            </span>
                          </v-tooltip>
                        </span>
                      </v-list-item-subtitle>

                      <div class="position-absolute mr-2" style="right:0;bottom:0">
                        <!-- ///////////////////////////////////////////////////////////////////////// -->
                        <v-dialog max-width="600" :retain-focus="false" v-if="post_comment_data.count_reactions != 0">
                          <template v-slot:activator="{ on, attrs }">
                            <v-btn x-small text class="primary--color rounded-pill mb-2" :min-width="40" v-bind="attrs" v-on="on">
                              <div class="">
                                <v-menu open-on-hover bottom offset-y v-if="post_comment_data.like != 0">
                                  <template v-slot:activator="{ on, attrs }">
                                    <v-avatar class="mr-1" size="14" v-bind="attrs" v-on="on">
                                      <v-img :lazy-src="require('../assets/img/emoticons/thumbs_up.png')" :src="require('../assets/img/emoticons/thumbs_up.png')" />
                                    </v-avatar>
                                  </template>

                                  <v-list class="p-2">
                                    <v-list-item-title class="font-weight-bold primary--text">Páči sa mi to</v-list-item-title>
                                    <template v-for="(item, index) in post_comment_data.commentreactionmodel2">
                                      <v-list-item style="min-height: 20px" v-if="item.reaction_type == 'like' && post_comment_data.id == item.comment_id" :key="'menuLike'+index">
                                        <v-list-item-subtitle class="m-0">{{ item.usercommentreactionmodel.name }}</v-list-item-subtitle>
                                      </v-list-item>
                                    </template>
                                  </v-list>
                                </v-menu>

                                <v-menu open-on-hover bottom offset-y v-if="post_comment_data.heart != 0">
                                  <template v-slot:activator="{ on, attrs }">
                                    <v-avatar class="mr-1" size="14" v-bind="attrs" v-on="on">
                                      <v-img :lazy-src="require('../assets/img/emoticons/heart.png')" :src="require('../assets/img/emoticons/heart.png')" />
                                    </v-avatar>
                                  </template>

                                  <v-list class="p-2">
                                    <v-list-item-title class="font-weight-bold primary--text">Super</v-list-item-title>
                                    <template v-for="(item, index) in post_comment_data.commentreactionmodel2">
                                      <v-list-item style="min-height: 20px" v-if="item.reaction_type == 'heart' && post_comment_data.id == item.comment_id" :key="'menuHeart'+index">
                                        <v-list-item-subtitle class="m-0">{{ item.usercommentreactionmodel.name }}</v-list-item-subtitle>
                                      </v-list-item>
                                    </template>
                                  </v-list>
                                </v-menu>

                                <v-menu open-on-hover bottom offset-y v-if="post_comment_data.funny != 0">
                                  <template v-slot:activator="{ on, attrs }">
                                    <v-avatar class="mr-1" size="14" v-bind="attrs" v-on="on">
                                      <v-img :lazy-src="require('../assets/img/emoticons/face_open_mouth_smiling_eyes.png')" :src="require('../assets/img/emoticons/face_open_mouth_smiling_eyes.png')" />
                                    </v-avatar>
                                  </template>

                                  <v-list class="p-2">
                                    <v-list-item-title class="font-weight-bold primary--text">Haha</v-list-item-title>
                                    <template v-for="(item, index) in post_comment_data.commentreactionmodel2">
                                      <v-list-item style="min-height: 20px" v-if="item.reaction_type == 'funny' && post_comment_data.id == item.comment_id" :key="'menuFunny'+index">
                                        <v-list-item-subtitle class="m-0">{{ item.usercommentreactionmodel.name }}</v-list-item-subtitle>
                                      </v-list-item>
                                    </template>
                                  </v-list>
                                </v-menu>

                                <v-menu open-on-hover bottom offset-y v-if="post_comment_data.surprise != 0">
                                  <template v-slot:activator="{ on, attrs }">
                                    <v-avatar class="mr-1" size="14" v-bind="attrs" v-on="on">
                                      <v-img :lazy-src="require('../assets/img/emoticons/face_open_mouth.png')" :src="require('../assets/img/emoticons/face_open_mouth.png')" />
                                    </v-avatar>
                                  </template>

                                  <v-list class="p-2">
                                    <v-list-item-title class="font-weight-bold primary--text">Žasnem</v-list-item-title>
                                    <template v-for="(item, index) in post_comment_data.commentreactionmodel2">
                                      <v-list-item style="min-height: 20px" v-if="item.reaction_type == 'surprise' && post_comment_data.id == item.comment_id" :key="'menuSurprise'+index">
                                        <v-list-item-subtitle class="m-0">{{ item.usercommentreactionmodel.name }}</v-list-item-subtitle>
                                      </v-list-item>
                                    </template>
                                  </v-list>
                                </v-menu>

                                <v-menu open-on-hover bottom offset-y v-if="post_comment_data.sad != 0">
                                  <template v-slot:activator="{ on, attrs }">
                                    <v-avatar class="mr-1" size="14" v-bind="attrs" v-on="on">
                                      <v-img :lazy-src="require('../assets/img/emoticons/disappointed_relieved_face.png')" :src="require('../assets/img/emoticons/disappointed_relieved_face.png')" />
                                    </v-avatar>
                                  </template>

                                  <v-list class="p-2">
                                    <v-list-item-title class="font-weight-bold primary--text">Je mi to ľúto</v-list-item-title>
                                    <template v-for="(item, index) in post_comment_data.commentreactionmodel2">
                                      <v-list-item style="min-height: 20px" v-if="item.reaction_type == 'sad' && post_comment_data.id == item.comment_id" :key="'menuSad'+index">
                                        <v-list-item-subtitle class="m-0">{{ item.usercommentreactionmodel.name }}</v-list-item-subtitle>
                                      </v-list-item>
                                    </template>
                                  </v-list>
                                </v-menu>

                                <v-menu open-on-hover bottom offset-y v-if="post_comment_data.angry != 0">
                                  <template v-slot:activator="{ on, attrs }">
                                    <v-avatar class="mr-1" size="14" v-bind="attrs" v-on="on">
                                      <v-img :lazy-src="require('../assets/img/emoticons/pouting_face.png')" :src="require('../assets/img/emoticons/pouting_face.png')" />
                                    </v-avatar>
                                  </template>

                                  <v-list class="p-2">
                                    <v-list-item-title class="font-weight-bold primary--text">Štve ma to</v-list-item-title>
                                    <template v-for="(item, index) in post_comment_data.commentreactionmodel2">
                                      <v-list-item style="min-height: 20px" v-if="item.reaction_type == 'angry' && post_comment_data.id == item.comment_id" :key="'menuAngry'+index">
                                        <v-list-item-subtitle class="m-0">{{ item.usercommentreactionmodel.name }}</v-list-item-subtitle>
                                      </v-list-item>
                                    </template>
                                  </v-list>
                                </v-menu>
                              </div>
                              <div class="">
                                {{post_comment_data.count_reactions}}
                              </div>
                            </v-btn>
                          </template>
                          <!-- ///////////////////////////////////////////////////////////////////////////////////////////////////// -->
                          <template v-slot:default="dialogReactionsComment">
                            <v-card>
                              <v-toolbar color="primary" dark v-if="selectedDialogReactionsComment == null">
                                <v-tabs v-model="tabReactionsComment[post_comment_data.id]" background-color="primary white--text" dark :show-arrows="true">
                                  <v-tab>
                                    <span>Všetko</span>
                                    <span class="ml-1">{{post_comment_data.count_reactions}}</span>
                                  </v-tab>

                                  <v-tab class="h-100" v-if="post_comment_data.like != 0">
                                    <v-avatar class="mr-1" size="20">
                                        <v-img :lazy-src="require('../assets/img/emoticons/thumbs_up.png')" :src="require('../assets/img/emoticons/thumbs_up.png')" />
                                    </v-avatar>
                                    <span>{{post_comment_data.like}}</span>
                                  </v-tab>

                                  <v-tab class="h-100" v-if="post_comment_data.heart != 0">
                                    <v-avatar class="mr-1" size="20">
                                        <v-img :lazy-src="require('../assets/img/emoticons/heart.png')" :src="require('../assets/img/emoticons/heart.png')" />
                                    </v-avatar>
                                    <span>{{post_comment_data.heart}}</span>
                                  </v-tab>

                                  <v-tab class="h-100" v-if="post_comment_data.funny != 0">
                                    <v-avatar class="mr-1" size="20">
                                        <v-img :lazy-src="require('../assets/img/emoticons/face_open_mouth_smiling_eyes.png')" :src="require('../assets/img/emoticons/face_open_mouth_smiling_eyes.png')" />
                                    </v-avatar>
                                    <span>{{post_comment_data.funny}}</span>
                                  </v-tab>

                                  <v-tab class="h-100" v-if="post_comment_data.surprise != 0">
                                    <v-avatar class="mr-1" size="20">
                                        <v-img :lazy-src="require('../assets/img/emoticons/face_open_mouth.png')" :src="require('../assets/img/emoticons/face_open_mouth.png')" />
                                    </v-avatar>
                                    <span>{{post_comment_data.surprise}}</span>
                                  </v-tab>

                                  <v-tab class="h-100" v-if="post_comment_data.sad != 0">
                                    <v-avatar class="mr-1" size="20">
                                        <v-img :lazy-src="require('../assets/img/emoticons/disappointed_relieved_face.png')" :src="require('../assets/img/emoticons/disappointed_relieved_face.png')" />
                                    </v-avatar>
                                    <span>{{post_comment_data.sad}}</span>
                                  </v-tab>

                                  <v-tab class="h-100" v-if="post_comment_data.angry != 0">
                                    <v-avatar class="mr-1" size="20">
                                        <v-img :lazy-src="require('../assets/img/emoticons/pouting_face.png')" :src="require('../assets/img/emoticons/pouting_face.png')" />
                                    </v-avatar>
                                    <span>{{post_comment_data.angry}}</span>
                                  </v-tab>
                                </v-tabs>

                                <v-spacer />
                                <v-btn icon dark @click="dialogReactionsComment.value = false">
                                  <v-icon>mdi-close</v-icon>
                                </v-btn>
                              </v-toolbar>

                              <!-- <v-toolbar color="primary" dark v-else>
                              <v-btn text outlined class="rounded-pill">
                                <v-icon class="mr-1">mdi-card-account-details</v-icon> Pozrieť profil
                              </v-btn>
                            </v-toolbar> -->

                              <v-tabs-items v-model="tabReactionsComment[post_comment_data.id]">
                                <v-tab-item>
                                  <v-card flat>
                                    <v-card-text class="p-0">
                                      <v-list-item-group v-model="reactionsCommentAll" color="primary" v-for="(item, index) in post_comment_data.commentreactionmodel2" :key="'all'+index">
                                        <v-list-item v-if="post_comment_data.id == item.comment_id">
                                          <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                            <v-avatar color="primary" size="40" v-if="item.usercommentreactionmodel.avatar == null">
                                              <span class="text-uppercase white--text">{{ item.usercommentreactionmodel.name.charAt(0) }}</span>
                                            </v-avatar>
                                            <v-avatar color="primary" size="40" v-else>
                                              <v-img :lazy-src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usercommentreactionmodel.avatar}`" :src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usercommentreactionmodel.avatar}`" />
                                            </v-avatar>
                                          </v-badge>
                                          <v-list-item-content>
                                            <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.usercommentreactionmodel.name"></v-list-item-title>
                                          </v-list-item-content>
                                          <v-list-item-action>
                                            <v-btn icon fab small>
                                              <v-icon>mdi-dots-vertical</v-icon>
                                            </v-btn>
                                          </v-list-item-action>
                                        </v-list-item>
                                      </v-list-item-group>
                                    </v-card-text>
                                  </v-card>
                                </v-tab-item>

                                <v-tab-item v-if="post_comment_data.like != 0">
                                  <v-card flat>
                                    <v-card-text class="p-0">
                                      <v-list-item-group v-model="reactionsCommentLike" color="primary" v-for="(item, index) in post_comment_data.commentreactionmodel2" :key="'like'+index">
                                        <v-list-item v-if="item.reaction_type == 'like' && post_comment_data.id == item.comment_id">
                                          <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                            <v-avatar color="primary" size="40" v-if="item.usercommentreactionmodel.avatar == null">
                                              <span class="text-uppercase white--text">{{ item.usercommentreactionmodel.name.charAt(0) }}</span>
                                            </v-avatar>
                                            <v-avatar color="primary" size="40" v-else>
                                              <v-img :lazy-src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usercommentreactionmodel.avatar}`" :src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usercommentreactionmodel.avatar}`" />
                                            </v-avatar>
                                          </v-badge>
                                          <v-list-item-content>
                                            <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.usercommentreactionmodel.name"></v-list-item-title>
                                          </v-list-item-content>
                                          <v-list-item-action>
                                            <v-btn icon fab small>
                                              <v-icon>mdi-dots-vertical</v-icon>
                                            </v-btn>
                                          </v-list-item-action>
                                        </v-list-item>
                                      </v-list-item-group>
                                    </v-card-text>
                                  </v-card>
                                </v-tab-item>

                                <v-tab-item v-if="post_comment_data.heart != 0">
                                  <v-card flat>
                                    <v-card-text class="p-0">
                                      <v-list-item-group v-model="reactionsCommentHeart" color="primary" v-for="(item, index) in post_comment_data.commentreactionmodel2" :key="'heart'+index">
                                        <v-list-item v-if="item.reaction_type == 'heart' && post_comment_data.id == item.comment_id">
                                          <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                            <v-avatar color="primary" size="40" v-if="item.usercommentreactionmodel.avatar == null">
                                              <span class="text-uppercase white--text">{{ item.usercommentreactionmodel.name.charAt(0) }}</span>
                                            </v-avatar>
                                            <v-avatar color="primary" size="40" v-else>
                                              <v-img :lazy-src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usercommentreactionmodel.avatar}`" :src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usercommentreactionmodel.avatar}`" />
                                            </v-avatar>
                                          </v-badge>
                                          <v-list-item-content>
                                            <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.usercommentreactionmodel.name"></v-list-item-title>
                                          </v-list-item-content>
                                          <v-list-item-action>
                                            <v-btn icon fab small>
                                              <v-icon>mdi-dots-vertical</v-icon>
                                            </v-btn>
                                          </v-list-item-action>
                                        </v-list-item>
                                      </v-list-item-group>
                                    </v-card-text>
                                  </v-card>
                                </v-tab-item>

                                <v-tab-item v-if="post_comment_data.funny != 0">
                                  <v-card flat>
                                    <v-card-text class="p-0">
                                      <v-list-item-group v-model="reactionsCommentFunny" color="primary" v-for="(item, index) in post_comment_data.commentreactionmodel2" :key="'funny'+index">
                                        <v-list-item v-if="item.reaction_type == 'funny' && post_comment_data.id == item.comment_id">
                                          <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                            <v-avatar color="primary" size="40" v-if="item.usercommentreactionmodel.avatar == null">
                                              <span class="text-uppercase white--text">{{ item.usercommentreactionmodel.name.charAt(0) }}</span>
                                            </v-avatar>
                                            <v-avatar color="primary" size="40" v-else>
                                              <v-img :lazy-src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usercommentreactionmodel.avatar}`" :src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usercommentreactionmodel.avatar}`" />
                                            </v-avatar>
                                          </v-badge>
                                          <v-list-item-content>
                                            <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.usercommentreactionmodel.name"></v-list-item-title>
                                          </v-list-item-content>
                                          <v-list-item-action>
                                            <v-btn icon fab small>
                                              <v-icon>mdi-dots-vertical</v-icon>
                                            </v-btn>
                                          </v-list-item-action>
                                        </v-list-item>
                                      </v-list-item-group>
                                    </v-card-text>
                                  </v-card>
                                </v-tab-item>

                                <v-tab-item v-if="post_comment_data.surprise != 0">
                                  <v-card flat>
                                    <v-card-text class="p-0">
                                      <v-list-item-group v-model="reactionsCommentSurprise" color="primary" v-for="(item, index) in post_comment_data.commentreactionmodel2" :key="'surprise'+index">
                                        <v-list-item v-if="item.reaction_type == 'surprise' && post_comment_data.id == item.comment_id">
                                          <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                            <v-avatar color="primary" size="40" v-if="item.usercommentreactionmodel.avatar == null">
                                              <span class="text-uppercase white--text">{{ item.usercommentreactionmodel.name.charAt(0) }}</span>
                                            </v-avatar>
                                            <v-avatar color="primary" size="40" v-else>
                                              <v-img :lazy-src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usercommentreactionmodel.avatar}`" :src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usercommentreactionmodel.avatar}`" />
                                            </v-avatar>
                                          </v-badge>
                                          <v-list-item-content>
                                            <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.usercommentreactionmodel.name"></v-list-item-title>
                                          </v-list-item-content>
                                          <v-list-item-action>
                                            <v-btn icon fab small>
                                              <v-icon>mdi-dots-vertical</v-icon>
                                            </v-btn>
                                          </v-list-item-action>
                                        </v-list-item>
                                      </v-list-item-group>
                                    </v-card-text>
                                  </v-card>
                                </v-tab-item>

                                <v-tab-item v-if="post_comment_data.sad != 0">
                                  <v-card flat>
                                    <v-card-text class="p-0">
                                      <v-list-item-group v-model="reactionsCommentSad" color="primary" v-for="(item, index) in post_comment_data.commentreactionmodel2" :key="'sad'+index">
                                        <v-list-item v-if="item.reaction_type == 'sad' && post_comment_data.id == item.comment_id">
                                          <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                            <v-avatar color="primary" size="40" v-if="item.usercommentreactionmodel.avatar == null">
                                              <span class="text-uppercase white--text">{{ item.usercommentreactionmodel.name.charAt(0) }}</span>
                                            </v-avatar>
                                            <v-avatar color="primary" size="40" v-else>
                                              <v-img :lazy-src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usercommentreactionmodel.avatar}`" :src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usercommentreactionmodel.avatar}`" />
                                            </v-avatar>
                                          </v-badge>
                                          <v-list-item-content>
                                            <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.usercommentreactionmodel.name"></v-list-item-title>
                                          </v-list-item-content>
                                          <v-list-item-action>
                                            <v-btn icon fab small>
                                              <v-icon>mdi-dots-vertical</v-icon>
                                            </v-btn>
                                          </v-list-item-action>
                                        </v-list-item>
                                      </v-list-item-group>
                                    </v-card-text>
                                  </v-card>
                                </v-tab-item>

                                <v-tab-item v-if="post_comment_data.angry != 0">
                                  <v-card flat>
                                    <v-card-text class="p-0">
                                      <v-list-item-group v-model="reactionsCommentAngry" color="primary" v-for="(item, index) in post_comment_data.commentreactionmodel2" :key="'angry'+index">
                                        <v-list-item v-if="item.reaction_type == 'angry' && post_comment_data.id == item.comment_id">
                                          <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                            <v-avatar color="primary" size="40" v-if="item.usercommentreactionmodel.avatar == null">
                                              <span class="text-uppercase white--text">{{ item.usercommentreactionmodel.name.charAt(0) }}</span>
                                            </v-avatar>
                                            <v-avatar color="primary" size="40" v-else>
                                              <v-img :lazy-src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usercommentreactionmodel.avatar}`" :src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usercommentreactionmodel.avatar}`" />
                                            </v-avatar>
                                          </v-badge>
                                          <v-list-item-content>
                                            <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.usercommentreactionmodel.name"></v-list-item-title>
                                          </v-list-item-content>
                                          <v-list-item-action>
                                            <v-btn icon fab small>
                                              <v-icon>mdi-dots-vertical</v-icon>
                                            </v-btn>
                                          </v-list-item-action>
                                        </v-list-item>
                                      </v-list-item-group>
                                    </v-card-text>
                                  </v-card>
                                </v-tab-item>
                              </v-tabs-items>
                            </v-card>
                          </template>
                        </v-dialog>

                      </div>
                    </v-card>
                    <v-row class="w-100 ml-0 mr-0 mt-0">
                      <!-- first comment on post -->
                      <v-menu open-on-hover top offset-y min-width="200px" rounded="pill">
                        <template v-slot:activator="{ on }">
                          <v-btn class="mt-1" text rounded x-small outlined v-on="on" color="primary" v-if="isThereMyReaction(post_comment_data.commentreactionmodel2)" @click="removeRateComment(post_comment_data.id, 'posts_comments')">
                            <span style="color:#ffb71d" v-if="isThereMyReactionTypeReaction(post_comment_data.commentreactionmodel2)['reaction_type'] == 'like'">
                              <v-avatar class="mr-1" size="14">
                                  <v-img :lazy-src="require('../assets/img/emoticons/thumbs_up.png')" :src="require('../assets/img/emoticons/thumbs_up.png')" />
                              </v-avatar>
                              <span class="font-weight-bold d-inline-block align-middle">Páči sa mi to</span>
                            </span>
                            <span class="red--text" v-if="isThereMyReactionTypeReaction(post_comment_data.commentreactionmodel2)['reaction_type'] == 'heart'">
                              <v-avatar class="mr-1" size="14">
                                  <v-img :lazy-src="require('../assets/img/emoticons/heart.png')" :src="require('../assets/img/emoticons/heart.png')" />
                              </v-avatar>
                              <span class="font-weight-bold d-inline-block align-middle">Super</span>
                            </span>
                            <span style="color:#ffb71d" v-if="isThereMyReactionTypeReaction(post_comment_data.commentreactionmodel2)['reaction_type'] == 'funny'">
                              <v-avatar class="mr-1" size="14">
                                  <v-img :lazy-src="require('../assets/img/emoticons/face_open_mouth_smiling_eyes.png')" :src="require('../assets/img/emoticons/face_open_mouth_smiling_eyes.png')" />
                              </v-avatar>
                              <span class="font-weight-bold d-inline-block align-middle">Haha</span>
                            </span>
                            <span style="color:#ffb71d" v-if="isThereMyReactionTypeReaction(post_comment_data.commentreactionmodel2)['reaction_type'] == 'surprise'">
                              <v-avatar class="mr-1" size="14">
                                  <v-img :lazy-src="require('../assets/img/emoticons/face_open_mouth.png')" :src="require('../assets/img/emoticons/face_open_mouth.png')" />
                              </v-avatar>
                              <span class="font-weight-bold d-inline-block align-middle">Žasnem</span>
                            </span>
                            <span style="color:#ffb71d" v-if="isThereMyReactionTypeReaction(post_comment_data.commentreactionmodel2)['reaction_type'] == 'sad'">
                              <v-avatar class="mr-1" size="14">
                                  <v-img :lazy-src="require('../assets/img/emoticons/disappointed_relieved_face.png')" :src="require('../assets/img/emoticons/disappointed_relieved_face.png')" />
                              </v-avatar>
                              <span class="font-weight-bold d-inline-block align-middle">Je mi to ľúto</span>
                            </span>
                            <span class="red--text" v-if="isThereMyReactionTypeReaction(post_comment_data.commentreactionmodel2)['reaction_type'] == 'angry'">
                              <v-avatar class="mr-1" size="14">
                                  <v-img :lazy-src="require('../assets/img/emoticons/pouting_face.png')" :src="require('../assets/img/emoticons/pouting_face.png')" />
                              </v-avatar>
                              <span class="font-weight-bold d-inline-block align-middle">Štve ma to</span>
                            </span>
                          </v-btn>
                          <v-btn class="mt-1" x-small text rounded outlined v-on="on" v-else>
                            <v-icon class="mr-1 primary--text" small>mdi-thumb-up</v-icon> Páči sa mi to
                          </v-btn>
                        </template>

                        <v-card class="rounded-pill p-2" elevation="2">
                          <v-btn icon @click="rateComment('like', post_comment_data.id, 'posts_comments')">
                            <v-avatar size="20">
                              <v-img :lazy-src="require('../assets/img/emoticons/thumbs_up.png')" :src="require('../assets/img/emoticons/thumbs_up.png')" />
                            </v-avatar>
                          </v-btn>
                          <v-btn icon @click="rateComment('heart', post_comment_data.id, 'posts_comments')">
                            <v-avatar size="20">
                              <v-img :lazy-src="require('../assets/img/emoticons/heart.png')" :src="require('../assets/img/emoticons/heart.png')" />
                            </v-avatar>
                          </v-btn>
                          <v-btn icon @click="rateComment('funny', post_comment_data.id, 'posts_comments')">
                            <v-avatar size="20">
                              <v-img :lazy-src="require('../assets/img/emoticons/face_open_mouth_smiling_eyes.png')" :src="require('../assets/img/emoticons/face_open_mouth_smiling_eyes.png')" />
                            </v-avatar>
                          </v-btn>

                          <v-btn icon @click="rateComment('surprise', post_comment_data.id, 'posts_comments')">
                            <v-avatar size="20">
                              <v-img :lazy-src="require('../assets/img/emoticons/face_open_mouth.png')" :src="require('../assets/img/emoticons/face_open_mouth.png')" />
                            </v-avatar>
                          </v-btn>

                          <v-btn icon @click="rateComment('sad', post_comment_data.id, 'posts_comments')">
                            <v-avatar size="20">
                              <v-img :lazy-src="require('../assets/img/emoticons/disappointed_relieved_face.png')" :src="require('../assets/img/emoticons/disappointed_relieved_face.png')" />
                            </v-avatar>
                          </v-btn>

                          <v-btn icon @click="rateComment('angry', post_comment_data.id, 'posts_comments')">
                            <v-avatar size="20">
                              <v-img :lazy-src="require('../assets/img/emoticons/pouting_face.png')" :src="require('../assets/img/emoticons/pouting_face.png')" />
                            </v-avatar>
                          </v-btn>
                        </v-card>
                      </v-menu>
                      <!-- //////////////////////////////////////////////////////////////////////////////answer on post_comment/////////////////////////////////////////////////////////////////////// -->
                      <div class="">
                        <v-btn @click.stop x-small text rounded outlined class="caption primary--text font-weight-bold mt-1 ml-1" @click="focusInputAnswerOnComment(post_comment_data)">
                          Odpovedať
                        </v-btn>
                      </div>

                    </v-row>
                    <!-- //////////////////////////////////////////////////////////////////////////////Count of answer on comment/////////////////////////////////////////////////////////////////////// -->
                    <v-row class="ml-0 mr-0 mt-0 pt-1 pb-1">
                      <v-chip @click="show_comments(post_comment_data.id)" v-if="post_comment_data.count_comments > 0" outlined small class="d-flex justify-start caption primary--text font-weight-bold"
                        :id="`btn_answers_on_comments${post_comment_data.id}`" v-bind:class="'btn_answers_on_comments' + post_comment_data.id">
                        <!-- <v-icon class="" color="primary" size="20">mdi-alpha-l</v-icon> -->
                        <span v-if="post_comment_data.count_comments == 1">
                          {{post_comment_data.count_comments}} Odpoveď
                        </span>
                          <span v-else>
                          {{post_comment_data.count_comments}} Odpovede
                        </span>


                        <v-icon left class="ml-2 arrow-d-none" :id="`btn_answers_on_comments_arrow_down${post_comment_data.id}`" v-bind:class="'btn_answers_on_comments_arrow_down' + post_comment_data.id" small>mdi-chevron-down</v-icon>
                        <v-icon left class="ml-2 arrow-d-block" :id="`btn_answers_on_comments_arrow_up${post_comment_data.id}`" v-bind:class="'btn_answers_on_comments_arrow_up' + post_comment_data.id" small>mdi-chevron-up</v-icon>
                      </v-chip>
                    </v-row>
                  </v-list-item-content>
                </v-list-item>
                <!-- //////////////////////////////////////////////////////////////////answer on comments///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
                <template v-for="(answer_on_comment_data, index) in post_comment_data.answeroncommentmodel">
                  <div v-bind:class="'answers_on_comments' + post_comment_data.id" :id="`answers_on_comments${answer_on_comment_data.id}`" style="display:none" :key="index">
                    <v-list-item v-if="post_comment_data.id == answer_on_comment_data.comment_id" class="ml-12">
                      <v-list-item-avatar class="ml-0 mr-2">
                        <v-avatar color="primary" size="28" v-if="answer_on_comment_data.useransweroncommentmodel.avatar == null">
                          <span v-if="answer_on_comment_data.useransweroncommentmodel.name != null" class="text-uppercase white--text">{{ answer_on_comment_data.useransweroncommentmodel.name.charAt(0) }}</span>
                        </v-avatar>
                        <v-avatar color="primary" size="28" v-else>
                          <v-img :lazy-src="`${$root.envUrlNoApi}/storage/user-avatar/${answer_on_comment_data.useransweroncommentmodel.avatar}`"
                            :src="`${$root.envUrlNoApi}/storage/user-avatar/${answer_on_comment_data.useransweroncommentmodel.avatar}`" />
                        </v-avatar>
                      </v-list-item-avatar>
                      <v-list-item-content>
                        <v-card class="p-2 rounded-lg card-comments" elevation="0" color="#f5f5f5">
                          <v-list-item-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0">
                            <v-menu top offset-y transition="scale-transition" :close-on-content-click="false">
                              <template v-slot:activator="{ on, attrs }">
                                <v-chip @click="mouseOver(answer_on_comment_data.useransweroncommentmodel)" v-bind="attrs" v-on="on" small>
                                  <v-icon left class="mr-1" small>mdi-account-circle-outline</v-icon>{{answer_on_comment_data.useransweroncommentmodel.name}}
                                </v-chip>
                             </template>
                             <v-card v-if="dataAboutUserOnHover != null">
                               <v-list>
                                 <v-list-item>
                                   <v-avatar color="primary" size="60" v-if="answer_on_comment_data.useransweroncommentmodel.avatar == null">
                                     <span class="text-uppercase white--text">{{answer_on_comment_data.useransweroncommentmodel.name.charAt(0) }}</span>
                                   </v-avatar>
                                   <v-avatar color="primary" size="60" v-else>
                                     <img :src="`${$root.envUrlNoApi}/storage/user-avatar/${answer_on_comment_data.useransweroncommentmodel.avatar}`">
                                   </v-avatar>

                                   <v-list-item-content class="ml-3">
                                     <v-card class="p-2 rounded-lg card-comments" elevation="0">
                                       <v-list-item-title>{{answer_on_comment_data.useransweroncommentmodel.name}}</v-list-item-title>
                                       <v-list-item-subtitle>{{answer_on_comment_data.useransweroncommentmodel.email}}</v-list-item-subtitle>
                                     </v-card>
                                   </v-list-item-content>
                                 </v-list-item>
                               </v-list>

                               <v-divider class="mt-0 mb-0" />

                               <v-card-actions v-if="isFriendOrNoOrMe_overlay">
                                 <v-list class="p-0" dense>
                                   <v-list-item-group color="primary">
                                     <v-list-item inactive disabled>
                                       <v-progress-circular class="d-flex justify-content-center" indeterminate size="24" color="primary"></v-progress-circular>
                                     </v-list-item>
                                   </v-list-item-group>
                                 </v-list>
                               </v-card-actions>

                               <v-card-actions v-else>
                                 <v-spacer></v-spacer>
                                 <div v-if="isFriendOrNoOrMe == -1">
                                   <!-- me -->
                                   <div v-if="$root.me.id == answer_on_comment_data.useransweroncommentmodel.id">
                                     <v-btn small class="ml-3" color="primary" outlined rounded>
                                       <v-icon small class="mr-1">mdi-account</v-icon>
                                       Vy
                                     </v-btn>
                                     <v-btn small class="ml-3" color="primary" rounded :to="'/profile/'+$root.me.id">
                                       <v-icon small class="mr-1">mdi-account-box</v-icon>
                                       Zobraziť môj profil
                                     </v-btn>
                                   </div>
                                   <!-- my friends -->
                                   <div v-else>
                                     <v-btn small class="ml-3" color="primary" outlined rounded>
                                       <v-icon small class="mr-1">mdi-account-check</v-icon>
                                       Priatelia
                                     </v-btn>
                                     <v-btn small class="ml-3" color="primary" rounded :to="'/messenger?name='+answer_on_comment_data.useransweroncommentmodel.name">
                                       <v-icon small class="mr-1">mdi-forum</v-icon>
                                       Správa
                                     </v-btn>
                                   </div>
                                 </div>
                                 <div v-else>
                                   <div v-if="$root.me.id == answer_on_comment_data.useransweroncommentmodel.id">
                                     <v-btn small class="ml-3" color="primary" outlined rounded>
                                       <v-icon small class="mr-1">mdi-account</v-icon>
                                       Vy
                                     </v-btn>
                                     <v-btn small class="ml-3" color="primary" rounded :to="'/profile/'+$root.me.id">
                                       <v-icon small class="mr-1">mdi-account-box</v-icon>
                                       Zobraziť môj profil
                                     </v-btn>
                                   </div>
                                   <div v-else-if="isFriendOrNoOrMe == 1">
                                     <v-btn small class="ml-3" color="primary" outlined rounded>
                                       <v-icon small class="mr-1">mdi-account-check</v-icon>
                                       Priatelia
                                     </v-btn>
                                     <v-btn small class="ml-3" color="primary" rounded :to="'/messenger?name='+answer_on_comment_data.useransweroncommentmodel.name">
                                       <v-icon small class="mr-1">mdi-forum</v-icon>
                                       Správa
                                     </v-btn>
                                   </div>
                                   <!-- if no friends -->
                                   <div v-else>
                                     <v-btn small class="ml-3" color="primary" outlined rounded disabled>
                                       <v-icon small class="mr-1">mdi-account-remove</v-icon>
                                       Nie ste priatelia
                                     </v-btn>
                                     <v-btn small class="ml-3" color="primary" rounded :to="'/profile/'+answer_on_comment_data.useransweroncommentmodel.id">
                                       <v-icon small class="mr-1">mdi-account-box</v-icon>
                                       Zobraziť profil
                                     </v-btn>
                                   </div>
                                 </div>
                               </v-card-actions>
                             </v-card>
                          </v-menu>

                          <v-tooltip bottom>
                            <template v-slot:activator="{ on: tooltip }">
                              <v-chip class="ml-1" v-if="answer_on_comment_data.status == 'updated'"  v-on="{ ...tooltip}"  small>
                                <v-icon left class="mr-1" small>mdi-pencil</v-icon>
                                <span>Upravené</span>
                              </v-chip>
                            </template>
                            <span>
                              <v-icon left class="mr-1" small color="white">mdi-clock-outline</v-icon>
                              <span>{{new Date(Date.parse(answer_on_comment_data.updated_at)).toLocaleString()}}</span>
                            </span>
                          </v-tooltip>

                          <v-spacer />

                          <v-menu left>
                            <template v-slot:activator="{ on: menu, attrs }">
                              <v-tooltip bottom>
                                <template v-slot:activator="{ on: tooltip }">
                                  <v-btn icon x-small v-bind="attrs" v-on="{ ...tooltip, ...menu }">
                                    <v-icon>mdi-dots-horizontal</v-icon>
                                  </v-btn>
                                </template>
                                <span>Nastavenia komentára</span>
                              </v-tooltip>
                            </template>
                            <v-list v-if=" answer_on_comment_data.useransweroncommentmodel.name == $root.me.name">
                              <v-list-item-group color="primary">
                                <v-list-item @click="updatePostComment(answer_on_comment_data, 'answers_on_comments')">
                                  <v-icon class="mr-7">mdi-pencil</v-icon>
                                  <v-list-item-content>
                                    <v-card class="p-2 rounded-lg card-comments" elevation="0">
                                      <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-3 pl-1">
                                        Upraviť komentár
                                      </v-card-title>
                                      <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">Zmeň komentár</v-card-subtitle>
                                    </v-card>
                                  </v-list-item-content>
                                </v-list-item>

                                <v-list-item @click="deletePostComment(answer_on_comment_data, 'answers_on_comments')">
                                  <v-icon class="mr-7">mdi-delete</v-icon>
                                  <v-list-item-content>
                                    <v-card class="p-2 rounded-lg card-comments" elevation="0">
                                      <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-3 pl-1">
                                        Zmazať komentár
                                      </v-card-title>
                                      <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">Zmaž komentár</v-card-subtitle>
                                    </v-card>
                                  </v-list-item-content>
                                </v-list-item>
                              </v-list-item-group>
                            </v-list>
                            <v-list v-else>
                              <v-list-item-group color="primary">
                                <v-list-item @click="reportPostComment(answer_on_comment_data, 'answers_on_comments')">
                                  <v-icon class="mr-7">mdi-alert-octagon</v-icon>
                                  <v-list-item-content>
                                    <v-card class="p-2 rounded-lg card-comments" elevation="0">
                                      <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-3 pl-1">
                                        Nahlásiť komentára
                                      </v-card-title>
                                      <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">Nahlás komentár</v-card-subtitle>
                                    </v-card>
                                  </v-list-item-content>
                                </v-list-item>
                              </v-list-item-group>
                            </v-list>
                          </v-menu>
                          </v-list-item-title>

                          <v-list-item class="pt-2 p-0" v-if="answer_on_comment_data.image_url != null">
                            <v-list-item-content class="p-0">
                              <v-img :lazy-src="`${$root.envUrlNoApi}/storage/post_img_video/${answer_on_comment_data.image_url}`" :src="`${$root.envUrlNoApi}/storage/post_img_video/${answer_on_comment_data.image_url}`" />
                            </v-list-item-content>
                          </v-list-item>

                          <v-list-item class="pt-2 p-0" v-if="answer_on_comment_data.video_url != null">
                            <v-list-item-content class="p-0">
                              <vue-plyr>
                                <video controls playsinline>
                                  <source size="1080" :src="$root.envUrlNoApi + '/storage/post_img_video/' +  answer_on_comment_data.video_url" type="video/mp4">
                                </video>
                              </vue-plyr>
                            </v-list-item-content>
                          </v-list-item>

                          <v-list-item-subtitle class="text-left font-weight-bold p-0 pt-1 text-wrap" v-if="answer_on_comment_data.chip == null">{{answer_on_comment_data.text}}</v-list-item-subtitle>
                          <v-list-item-subtitle class="text-left font-weight-bold p-0 pt-1 text-wrap" v-else>
                            <v-chip small class="mr-1" @click="showUserData(answer_on_comment_data.useransweroncommentmodel2)">
                              {{answer_on_comment_data.chip}}
                            </v-chip>
                            <span class="position-absolute mt-1">
                              {{answer_on_comment_data.text}}
                            </span>
                          </v-list-item-subtitle>

                          <v-list-item-subtitle class="text-left p-0 pt-1">
                            <span>
                              <!-- minutes -->
                              <v-tooltip bottom v-if="Math.round((new Date().getTime() - new Date(answer_on_comment_data.created_at).getTime()) / 60000) < 60">
                                <template v-slot:activator="{ on, attrs }">
                                  <v-btn v-bind="attrs" v-on="on" x-small text rounded plain class="d-flex justify-start p-0 pl-0">
                                    <span v-if="Math.round((new Date().getTime() - new Date(answer_on_comment_data.created_at).getTime()) / 60000) > 1">
                                      Pred {{Math.round((new Date().getTime() - new Date(answer_on_comment_data.created_at).getTime()) / 60000)}} minútami
                                    </span>
                                    <span v-else>
                                      Pred 1 minútou
                                    </span>
                                  </v-btn>
                                </template>
                                <span>
                                  <v-icon left class="mr-1 align-middle" small color="white">mdi-clock-outline</v-icon>
                                  <span class="align-middle">{{new Date(Date.parse(answer_on_comment_data.created_at)).toLocaleString()}}</span>
                                </span>
                              </v-tooltip>

                              <!-- hours -->
                              <v-tooltip bottom v-else-if="Math.round((new Date().getTime() - new Date(answer_on_comment_data.created_at).getTime()) / 60000) < 1440">
                                <template v-slot:activator="{ on, attrs }">
                                  <v-btn v-bind="attrs" v-on="on" x-small text rounded plain class="d-flex justify-start p-0 pl-0">
                                    <span v-if="Math.floor(Math.round((new Date().getTime() - new Date(answer_on_comment_data.created_at).getTime()) / 60000)/60) > 1">
                                      Pred {{Math.floor(Math.round((new Date().getTime() - new Date(answer_on_comment_data.created_at).getTime()) / 60000)/60)}} hodinami
                                    </span>
                                    <span v-else>
                                      Pred 1 hodinou
                                    </span>
                                  </v-btn>
                                </template>
                                <span>
                                  <v-icon left class="mr-1 align-middle" small color="white">mdi-clock-outline</v-icon>
                                  <span class="align-middle">{{new Date(Date.parse(answer_on_comment_data.created_at)).toLocaleString()}}</span>
                                </span>
                              </v-tooltip>

                              <!-- days -->
                              <v-tooltip bottom v-else-if="Math.round((new Date().getTime() - new Date(answer_on_comment_data.created_at).getTime()) / 60000) < 43200">
                                <template v-slot:activator="{ on, attrs }">
                                  <v-btn v-bind="attrs" v-on="on" x-small text rounded plain class="d-flex justify-start p-0 pl-0">
                                    <span v-if="Math.floor(Math.round((new Date().getTime() - new Date(answer_on_comment_data.created_at).getTime()) / 60000)/1440) > 1">
                                      Pred {{Math.floor(Math.round((new Date().getTime() - new Date(answer_on_comment_data.created_at).getTime()) / 60000)/1440)}} dňami
                                    </span>
                                    <span v-else>
                                      Pred 1 dňom
                                    </span>
                                  </v-btn>
                                </template>
                                <span>
                                  <v-icon left class="mr-1 align-middle" small color="white">mdi-clock-outline</v-icon>
                                  <span class="align-middle">{{new Date(Date.parse(answer_on_comment_data.created_at)).toLocaleString()}}</span>
                                </span>
                              </v-tooltip>

                              <!-- months -->
                              <v-tooltip bottom v-else-if="Math.round((new Date().getTime() - new Date(answer_on_comment_data.created_at).getTime()) / 60000) < 525600">
                                <template v-slot:activator="{ on, attrs }">
                                  <v-btn v-bind="attrs" v-on="on" x-small text rounded plain class="d-flex justify-start p-0 pl-0">
                                    <span v-if="Math.floor(Math.round((new Date().getTime() - new Date(answer_on_comment_data.created_at).getTime()) / 60000)/43200) > 1">
                                      Pred {{Math.floor(Math.round((new Date().getTime() - new Date(answer_on_comment_data.created_at).getTime()) / 60000)/43200)}} mesiacmi
                                    </span>
                                    <span v-else>
                                      Pred 1 mesiacom
                                    </span>
                                  </v-btn>
                                </template>
                                <span>
                                  <v-icon left class="mr-1 align-middle" small color="white">mdi-clock-outline</v-icon>
                                  <span class="align-middle">{{new Date(Date.parse(answer_on_comment_data.created_at)).toLocaleString()}}</span>
                                </span>
                              </v-tooltip>

                              <!-- years -->
                              <v-tooltip bottom v-else>
                                <template v-slot:activator="{ on, attrs }">
                                  <v-btn v-bind="attrs" v-on="on" x-small text rounded plain class="d-flex justify-start p-0 pl-0">
                                    <span v-if="Math.floor(Math.round((new Date().getTime() - new Date(answer_on_comment_data.created_at).getTime()) / 60000)/525600) > 1">
                                      Pred {{Math.floor(Math.round((new Date().getTime() - new Date(answer_on_comment_data.created_at).getTime()) / 60000)/525600)}} rokmi
                                    </span>
                                    <span v-else>
                                      Pred 1 rokom
                                    </span>
                                  </v-btn>
                                </template>
                                <span>
                                  <v-icon left class="mr-1 align-middle" small color="white">mdi-clock-outline</v-icon>
                                  <span class="align-middle">{{new Date(Date.parse(answer_on_comment_data.created_at)).toLocaleString()}}</span>
                                </span>
                              </v-tooltip>
                            </span>
                          </v-list-item-subtitle>

                          <div class="position-absolute mr-2" style="right:0;bottom:0">
                            <!-- ///////////////////////////////////////////////////////////////////////// -->
                            <v-dialog max-width="600" :retain-focus="false" v-if="answer_on_comment_data.count_reactions != 0">
                              <template v-slot:activator="{ on, attrs }">
                                <v-btn x-small text class=" primary--color rounded-pill mb-2" :min-width="40" v-bind="attrs" v-on="on">
                                  <div class="">
                                    <v-menu open-on-hover bottom offset-y v-if="answer_on_comment_data.like != 0">
                                      <template v-slot:activator="{ on, attrs }">
                                        <v-avatar class="mr-1" size="14" v-bind="attrs" v-on="on">
                                          <v-img :lazy-src="require('../assets/img/emoticons/thumbs_up.png')" :src="require('../assets/img/emoticons/thumbs_up.png')" />
                                        </v-avatar>
                                      </template>

                                      <v-list class="p-2">
                                        <v-list-item-title class="font-weight-bold primary--text">Páči sa mi to</v-list-item-title>
                                        <template v-for="(item, index) in answer_on_comment_data.commentreactionmodel">
                                          <v-list-item style="min-height: 20px" v-if="item.reaction_type == 'like' && answer_on_comment_data.id == item.answer_on_comment_id" :key="'menuLike'+index">
                                            <v-list-item-subtitle class="m-0">{{ item.usercommentreactionmodel.name }}</v-list-item-subtitle>
                                          </v-list-item>
                                        </template>
                                      </v-list>
                                    </v-menu>

                                    <v-menu open-on-hover bottom offset-y v-if="answer_on_comment_data.heart != 0">
                                      <template v-slot:activator="{ on, attrs }">
                                        <v-avatar class="mr-1" size="14" v-bind="attrs" v-on="on">
                                          <v-img :lazy-src="require('../assets/img/emoticons/heart.png')" :src="require('../assets/img/emoticons/heart.png')" />
                                        </v-avatar>
                                      </template>

                                      <v-list class="p-2">
                                        <v-list-item-title class="font-weight-bold primary--text">Super</v-list-item-title>
                                        <template v-for="(item, index) in answer_on_comment_data.commentreactionmodel">
                                          <v-list-item style="min-height: 20px" v-if="item.reaction_type == 'heart' && answer_on_comment_data.id == item.answer_on_comment_id" :key="'menuHeart'+index">
                                            <v-list-item-subtitle class="m-0">{{ item.usercommentreactionmodel.name }}</v-list-item-subtitle>
                                          </v-list-item>
                                        </template>
                                      </v-list>
                                    </v-menu>

                                    <v-menu open-on-hover bottom offset-y v-if="answer_on_comment_data.funny != 0">
                                      <template v-slot:activator="{ on, attrs }">
                                        <v-avatar class="mr-1" size="14" v-bind="attrs" v-on="on">
                                          <v-img :lazy-src="require('../assets/img/emoticons/face_open_mouth_smiling_eyes.png')" :src="require('../assets/img/emoticons/face_open_mouth_smiling_eyes.png')" />
                                        </v-avatar>
                                      </template>

                                      <v-list class="p-2">
                                        <v-list-item-title class="font-weight-bold primary--text">Haha</v-list-item-title>
                                        <template v-for="(item, index) in answer_on_comment_data.commentreactionmodel">
                                          <v-list-item style="min-height: 20px" v-if="item.reaction_type == 'funny' && answer_on_comment_data.id == item.answer_on_comment_id" :key="'menuFunny'+index">
                                            <v-list-item-subtitle class="m-0">{{ item.usercommentreactionmodel.name }}</v-list-item-subtitle>
                                          </v-list-item>
                                        </template>
                                      </v-list>
                                    </v-menu>

                                    <v-menu open-on-hover bottom offset-y v-if="answer_on_comment_data.surprise != 0">
                                      <template v-slot:activator="{ on, attrs }">
                                        <v-avatar class="mr-1" size="14" v-bind="attrs" v-on="on">
                                          <v-img :lazy-src="require('../assets/img/emoticons/face_open_mouth.png')" :src="require('../assets/img/emoticons/face_open_mouth.png')" />
                                        </v-avatar>
                                      </template>

                                      <v-list class="p-2">
                                        <v-list-item-title class="font-weight-bold primary--text">Žasnem</v-list-item-title>
                                        <template v-for="(item, index) in answer_on_comment_data.commentreactionmodel">
                                          <v-list-item style="min-height: 20px" v-if="item.reaction_type == 'surprise' && answer_on_comment_data.id == item.answer_on_comment_id" :key="'menuSurprise'+index">
                                            <v-list-item-subtitle class="m-0">{{ item.usercommentreactionmodel.name }}</v-list-item-subtitle>
                                          </v-list-item>
                                        </template>
                                      </v-list>
                                    </v-menu>

                                    <v-menu open-on-hover bottom offset-y v-if="answer_on_comment_data.sad != 0">
                                      <template v-slot:activator="{ on, attrs }">
                                        <v-avatar class="mr-1" size="14" v-bind="attrs" v-on="on">
                                          <v-img :lazy-src="require('../assets/img/emoticons/disappointed_relieved_face.png')" :src="require('../assets/img/emoticons/disappointed_relieved_face.png')" />
                                        </v-avatar>
                                      </template>

                                      <v-list class="p-2">
                                        <v-list-item-title class="font-weight-bold primary--text">Je mi to ľúto</v-list-item-title>
                                        <template v-for="(item, index) in answer_on_comment_data.commentreactionmodel">
                                          <v-list-item style="min-height: 20px" v-if="item.reaction_type == 'sad' && answer_on_comment_data.id == item.answer_on_comment_id" :key="'menuSad'+index">
                                            <v-list-item-subtitle class="m-0">{{ item.usercommentreactionmodel.name }}</v-list-item-subtitle>
                                          </v-list-item>
                                        </template>
                                      </v-list>
                                    </v-menu>

                                    <v-menu open-on-hover bottom offset-y v-if="answer_on_comment_data.angry != 0">
                                      <template v-slot:activator="{ on, attrs }">
                                        <v-avatar class="mr-1" size="14" v-bind="attrs" v-on="on">
                                          <v-img :lazy-src="require('../assets/img/emoticons/pouting_face.png')" :src="require('../assets/img/emoticons/pouting_face.png')" />
                                        </v-avatar>
                                      </template>

                                      <v-list class="p-2">
                                        <v-list-item-title class="font-weight-bold primary--text">Štve ma to</v-list-item-title>
                                        <template v-for="(item, index) in answer_on_comment_data.commentreactionmodel">
                                          <v-list-item style="min-height: 20px" v-if="item.reaction_type == 'angry' && answer_on_comment_data.id == item.answer_on_comment_id" :key="'menuAngry'+index">
                                            <v-list-item-subtitle class="m-0">{{ item.usercommentreactionmodel.name }}</v-list-item-subtitle>
                                          </v-list-item>
                                        </template>
                                      </v-list>
                                    </v-menu>
                                  </div>
                                  <div class="">
                                    {{answer_on_comment_data.count_reactions}}
                                  </div>
                                </v-btn>
                              </template>

                              <template v-slot:default="dialogReactionsAnswerOnComment">
                                <v-card>
                                  <v-toolbar color="primary" dark v-if="selectedDialogReactionsAnswerOnComment == null">
                                    <v-tabs v-model="tabReactionsAnswerOnComment[answer_on_comment_data.id]" background-color="primary white--text" dark :show-arrows="true">
                                      <v-tab>
                                        <span>Všetko</span>
                                        <span class="ml-1">{{answer_on_comment_data.count_reactions}}</span>
                                      </v-tab>

                                      <v-tab class="h-100" v-if="answer_on_comment_data.like != 0">
                                        <v-avatar class="mr-1" size="20">
                                            <v-img :lazy-src="require('../assets/img/emoticons/thumbs_up.png')" :src="require('../assets/img/emoticons/thumbs_up.png')" />
                                        </v-avatar>
                                        <span>{{answer_on_comment_data.like}}</span>
                                      </v-tab>

                                      <v-tab class="h-100" v-if="answer_on_comment_data.heart != 0">
                                        <v-avatar class="mr-1" size="20">
                                            <v-img :lazy-src="require('../assets/img/emoticons/heart.png')" :src="require('../assets/img/emoticons/heart.png')" />
                                        </v-avatar>
                                        <span>{{answer_on_comment_data.heart}}</span>
                                      </v-tab>

                                      <v-tab class="h-100" v-if="answer_on_comment_data.funny != 0">
                                        <v-avatar class="mr-1" size="20">
                                            <v-img :lazy-src="require('../assets/img/emoticons/face_open_mouth_smiling_eyes.png')" :src="require('../assets/img/emoticons/face_open_mouth_smiling_eyes.png')" />
                                        </v-avatar>
                                        <span>{{answer_on_comment_data.funny}}</span>
                                      </v-tab>

                                      <v-tab class="h-100" v-if="answer_on_comment_data.surprise != 0">
                                        <v-avatar class="mr-1" size="20">
                                            <v-img :lazy-src="require('../assets/img/emoticons/face_open_mouth.png')" :src="require('../assets/img/emoticons/face_open_mouth.png')" />
                                        </v-avatar>
                                        <span>{{answer_on_comment_data.surprise}}</span>
                                      </v-tab>

                                      <v-tab class="h-100" v-if="answer_on_comment_data.sad != 0">
                                        <v-avatar class="mr-1" size="20">
                                            <v-img :lazy-src="require('../assets/img/emoticons/disappointed_relieved_face.png')" :src="require('../assets/img/emoticons/disappointed_relieved_face.png')" />
                                        </v-avatar>
                                        <span>{{answer_on_comment_data.sad}}</span>
                                      </v-tab>

                                      <v-tab class="h-100" v-if="answer_on_comment_data.angry != 0">
                                        <v-avatar class="mr-1" size="20">
                                            <v-img :lazy-src="require('../assets/img/emoticons/pouting_face.png')" :src="require('../assets/img/emoticons/pouting_face.png')" />
                                        </v-avatar>
                                        <span>{{answer_on_comment_data.angry}}</span>
                                      </v-tab>
                                    </v-tabs>

                                    <v-spacer />
                                    <v-btn icon dark @click="dialogReactionsAnswerOnComment.value = false">
                                      <v-icon>mdi-close</v-icon>
                                    </v-btn>
                                  </v-toolbar>

                                  <!-- <v-toolbar color="primary" dark v-else>
                                  <v-btn text outlined class="rounded-pill">
                                    <v-icon class="mr-1">mdi-card-account-details</v-icon> Pozrieť profil
                                  </v-btn>
                                </v-toolbar> -->

                                  <v-tabs-items v-model="tabReactionsAnswerOnComment[answer_on_comment_data.id]">
                                    <v-tab-item>
                                      <v-card flat>
                                        <v-card-text class="p-0">
                                          <v-list-item-group v-model="reactionsAnswerOnCommentAll" color="primary" v-for="(item, index) in answer_on_comment_data.commentreactionmodel" :key="'all'+index">
                                            <v-list-item v-if="answer_on_comment_data.id == item.answer_on_comment_id">
                                              <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                                <v-avatar color="primary" size="40" v-if="item.usercommentreactionmodel.avatar == null">
                                                  <span class="text-uppercase white--text">{{ item.usercommentreactionmodel.name.charAt(0) }}</span>
                                                </v-avatar>
                                                <v-avatar color="primary" size="40" v-else>
                                                  <v-img :lazy-src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usercommentreactionmodel.avatar}`" :src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usercommentreactionmodel.avatar}`" />
                                                </v-avatar>
                                              </v-badge>
                                              <v-list-item-content>
                                                <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.usercommentreactionmodel.name"></v-list-item-title>
                                              </v-list-item-content>
                                              <v-list-item-action>
                                                <v-btn icon fab small>
                                                  <v-icon>mdi-dots-vertical</v-icon>
                                                </v-btn>
                                              </v-list-item-action>
                                            </v-list-item>
                                          </v-list-item-group>
                                        </v-card-text>
                                      </v-card>
                                    </v-tab-item>

                                    <v-tab-item v-if="answer_on_comment_data.like != 0">
                                      <v-card flat>
                                        <v-card-text class="p-0">
                                          <v-list-item-group v-model="reactionsAnswerOnCommentLike" color="primary" v-for="(item, index) in answer_on_comment_data.commentreactionmodel" :key="'like'+index">
                                            <v-list-item v-if="item.reaction_type == 'like' && answer_on_comment_data.id == item.answer_on_comment_id">
                                              <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                                <v-avatar color="primary" size="40" v-if="item.usercommentreactionmodel.avatar == null">
                                                  <span class="text-uppercase white--text">{{ item.usercommentreactionmodel.name.charAt(0) }}</span>
                                                </v-avatar>
                                                <v-avatar color="primary" size="40" v-else>
                                                  <v-img :lazy-src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usercommentreactionmodel.avatar}`" :src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usercommentreactionmodel.avatar}`" />
                                                </v-avatar>
                                              </v-badge>
                                              <v-list-item-content>
                                                <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.usercommentreactionmodel.name"></v-list-item-title>
                                              </v-list-item-content>
                                              <v-list-item-action>
                                                <v-btn icon fab small>
                                                  <v-icon>mdi-dots-vertical</v-icon>
                                                </v-btn>
                                              </v-list-item-action>
                                            </v-list-item>
                                          </v-list-item-group>
                                        </v-card-text>
                                      </v-card>
                                    </v-tab-item>

                                    <v-tab-item v-if="answer_on_comment_data.heart != 0">
                                      <v-card flat>
                                        <v-card-text class="p-0">
                                          <v-list-item-group v-model="reactionsAnswerOnCommentHeart" color="primary" v-for="(item, index) in answer_on_comment_data.commentreactionmodel" :key="'heart'+index">
                                            <v-list-item v-if="item.reaction_type == 'heart' && answer_on_comment_data.id == item.answer_on_comment_id">
                                              <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                                <v-avatar color="primary" size="40" v-if="item.usercommentreactionmodel.avatar == null">
                                                  <span class="text-uppercase white--text">{{ item.usercommentreactionmodel.name.charAt(0) }}</span>
                                                </v-avatar>
                                                <v-avatar color="primary" size="40" v-else>
                                                  <v-img :lazy-src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usercommentreactionmodel.avatar}`" :src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usercommentreactionmodel.avatar}`" />
                                                </v-avatar>
                                              </v-badge>
                                              <v-list-item-content>
                                                <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.usercommentreactionmodel.name"></v-list-item-title>
                                              </v-list-item-content>
                                              <v-list-item-action>
                                                <v-btn icon fab small>
                                                  <v-icon>mdi-dots-vertical</v-icon>
                                                </v-btn>
                                              </v-list-item-action>
                                            </v-list-item>
                                          </v-list-item-group>
                                        </v-card-text>
                                      </v-card>
                                    </v-tab-item>

                                    <v-tab-item v-if="answer_on_comment_data.funny != 0">
                                      <v-card flat>
                                        <v-card-text class="p-0">
                                          <v-list-item-group v-model="reactionsAnswerOnCommentFunny" color="primary" v-for="(item, index) in answer_on_comment_data.commentreactionmodel" :key="'funny'+index">
                                            <v-list-item v-if="item.reaction_type == 'funny' && answer_on_comment_data.id == item.answer_on_comment_id">
                                              <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                                <v-avatar color="primary" size="40" v-if="item.usercommentreactionmodel.avatar == null">
                                                  <span class="text-uppercase white--text">{{ item.usercommentreactionmodel.name.charAt(0) }}</span>
                                                </v-avatar>
                                                <v-avatar color="primary" size="40" v-else>
                                                  <v-img :lazy-src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usercommentreactionmodel.avatar}`" :src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usercommentreactionmodel.avatar}`" />
                                                </v-avatar>
                                              </v-badge>
                                              <v-list-item-content>
                                                <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.usercommentreactionmodel.name"></v-list-item-title>
                                              </v-list-item-content>
                                              <v-list-item-action>
                                                <v-btn icon fab small>
                                                  <v-icon>mdi-dots-vertical</v-icon>
                                                </v-btn>
                                              </v-list-item-action>
                                            </v-list-item>
                                          </v-list-item-group>
                                        </v-card-text>
                                      </v-card>
                                    </v-tab-item>

                                    <v-tab-item v-if="answer_on_comment_data.surprise != 0">
                                      <v-card flat>
                                        <v-card-text class="p-0">
                                          <v-list-item-group v-model="reactionsAnswerOnCommentSurprise" color="primary" v-for="(item, index) in answer_on_comment_data.commentreactionmodel" :key="'surprise'+index">
                                            <v-list-item v-if="item.reaction_type == 'surprise' && answer_on_comment_data.id == item.answer_on_comment_id">
                                              <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                                <v-avatar color="primary" size="40" v-if="item.usercommentreactionmodel.avatar == null">
                                                  <span class="text-uppercase white--text">{{ item.usercommentreactionmodel.name.charAt(0) }}</span>
                                                </v-avatar>
                                                <v-avatar color="primary" size="40" v-else>
                                                  <v-img :lazy-src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usercommentreactionmodel.avatar}`" :src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usercommentreactionmodel.avatar}`" />
                                                </v-avatar>
                                              </v-badge>
                                              <v-list-item-content>
                                                <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.usercommentreactionmodel.name"></v-list-item-title>
                                              </v-list-item-content>
                                              <v-list-item-action>
                                                <v-btn icon fab small>
                                                  <v-icon>mdi-dots-vertical</v-icon>
                                                </v-btn>
                                              </v-list-item-action>
                                            </v-list-item>
                                          </v-list-item-group>
                                        </v-card-text>
                                      </v-card>
                                    </v-tab-item>

                                    <v-tab-item v-if="answer_on_comment_data.sad != 0">
                                      <v-card flat>
                                        <v-card-text class="p-0">
                                          <v-list-item-group v-model="reactionsAnswerOnCommentSad" color="primary" v-for="(item, index) in answer_on_comment_data.commentreactionmodel" :key="'sad'+index">
                                            <v-list-item v-if="item.reaction_type == 'sad' && answer_on_comment_data.id == item.answer_on_comment_id">
                                              <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                                <v-avatar color="primary" size="40" v-if="item.usercommentreactionmodel.avatar == null">
                                                  <span class="text-uppercase white--text">{{ item.usercommentreactionmodel.name.charAt(0) }}</span>
                                                </v-avatar>
                                                <v-avatar color="primary" size="40" v-else>
                                                  <v-img :lazy-src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usercommentreactionmodel.avatar}`" :src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usercommentreactionmodel.avatar}`" />
                                                </v-avatar>
                                              </v-badge>
                                              <v-list-item-content>
                                                <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.usercommentreactionmodel.name"></v-list-item-title>
                                              </v-list-item-content>
                                              <v-list-item-action>
                                                <v-btn icon fab small>
                                                  <v-icon>mdi-dots-vertical</v-icon>
                                                </v-btn>
                                              </v-list-item-action>
                                            </v-list-item>
                                          </v-list-item-group>
                                        </v-card-text>
                                      </v-card>
                                    </v-tab-item>

                                    <v-tab-item v-if="answer_on_comment_data.angry != 0">
                                      <v-card flat>
                                        <v-card-text class="p-0">
                                          <v-list-item-group v-model="reactionsAnswerOnCommentAngry" color="primary" v-for="(item, index) in answer_on_comment_data.commentreactionmodel" :key="'angry'+index">
                                            <v-list-item v-if="item.reaction_type == 'angry' && answer_on_comment_data.id == item.answer_on_comment_id">
                                              <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                                <v-avatar color="primary" size="40" v-if="item.usercommentreactionmodel.avatar == null">
                                                  <span class="text-uppercase white--text">{{ item.usercommentreactionmodel.name.charAt(0) }}</span>
                                                </v-avatar>
                                                <v-avatar color="primary" size="40" v-else>
                                                  <v-img :lazy-src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usercommentreactionmodel.avatar}`" :src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usercommentreactionmodel.avatar}`" />
                                                </v-avatar>
                                              </v-badge>
                                              <v-list-item-content>
                                                <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.usercommentreactionmodel.name"></v-list-item-title>
                                              </v-list-item-content>
                                              <v-list-item-action>
                                                <v-btn icon fab small>
                                                  <v-icon>mdi-dots-vertical</v-icon>
                                                </v-btn>
                                              </v-list-item-action>
                                            </v-list-item>
                                          </v-list-item-group>
                                        </v-card-text>
                                      </v-card>
                                    </v-tab-item>
                                  </v-tabs-items>
                                </v-card>
                              </template>
                            </v-dialog>
                            <!-- ///////////////////////////////////////////////////////////////////////// -->
                          </div>
                        </v-card>
                        <!-- ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
                        <v-row class="w-100 ml-0 mr-0 mt-0">
                          <v-menu open-on-hover top offset-y min-width="200px" rounded="pill">
                            <template v-slot:activator="{ on }">
                              <v-btn class="mt-1" text rounded x-small outlined v-on="on" color="primary" v-if="isThereMyReaction(answer_on_comment_data.commentreactionmodel)" @click="removeRateComment(answer_on_comment_data.id, 'answers_on_comments')">
                                <span style="color:#ffb71d" v-if="isThereMyReactionTypeReaction(answer_on_comment_data.commentreactionmodel)['reaction_type'] == 'like'">
                                  <v-avatar class="mr-1" size="14">
                                      <v-img :lazy-src="require('../assets/img/emoticons/thumbs_up.png')" :src="require('../assets/img/emoticons/thumbs_up.png')" />
                                  </v-avatar>
                                  <span class="font-weight-bold d-inline-block align-middle">Páči sa mi to</span>
                                </span>
                                <span class="red--text" v-if="isThereMyReactionTypeReaction(answer_on_comment_data.commentreactionmodel)['reaction_type'] == 'heart'">
                                  <v-avatar class="mr-1" size="14">
                                      <v-img :lazy-src="require('../assets/img/emoticons/heart.png')" :src="require('../assets/img/emoticons/heart.png')" />
                                  </v-avatar>
                                  <span class="font-weight-bold d-inline-block align-middle">Super</span>
                                </span>
                                <span style="color:#ffb71d" v-if="isThereMyReactionTypeReaction(answer_on_comment_data.commentreactionmodel)['reaction_type'] == 'funny'">
                                  <v-avatar class="mr-1" size="14">
                                      <v-img :lazy-src="require('../assets/img/emoticons/face_open_mouth_smiling_eyes.png')" :src="require('../assets/img/emoticons/face_open_mouth_smiling_eyes.png')" />
                                  </v-avatar>
                                  <span class="font-weight-bold d-inline-block align-middle">Haha</span>
                                </span>
                                <span style="color:#ffb71d" v-if="isThereMyReactionTypeReaction(answer_on_comment_data.commentreactionmodel)['reaction_type'] == 'surprise'">
                                  <v-avatar class="mr-1" size="14">
                                      <v-img :lazy-src="require('../assets/img/emoticons/face_open_mouth.png')" :src="require('../assets/img/emoticons/face_open_mouth.png')" />
                                  </v-avatar>
                                  <span class="font-weight-bold d-inline-block align-middle">Žasnem</span>
                                </span>
                                <span style="color:#ffb71d" v-if="isThereMyReactionTypeReaction(answer_on_comment_data.commentreactionmodel)['reaction_type'] == 'sad'">
                                  <v-avatar class="mr-1" size="14">
                                      <v-img :lazy-src="require('../assets/img/emoticons/disappointed_relieved_face.png')" :src="require('../assets/img/emoticons/disappointed_relieved_face.png')" />
                                  </v-avatar>
                                  <span class="font-weight-bold d-inline-block align-middle">Je mi to ľúto</span>
                                </span>
                                <span class="red--text" v-if="isThereMyReactionTypeReaction(answer_on_comment_data.commentreactionmodel)['reaction_type'] == 'angry'">
                                  <v-avatar class="mr-1" size="14">
                                      <v-img :lazy-src="require('../assets/img/emoticons/pouting_face.png')" :src="require('../assets/img/emoticons/pouting_face.png')" />
                                  </v-avatar>
                                  <span class="font-weight-bold d-inline-block align-middle">Štve ma to</span>
                                </span>
                              </v-btn>
                              <v-btn class="mt-1" x-small text rounded outlined v-on="on" v-else>
                                <v-icon class="mr-1 primary--text" small>mdi-thumb-up</v-icon> Páči sa mi to
                              </v-btn>
                            </template>

                            <v-card class="rounded-pill p-2" elevation="2">
                              <v-btn icon @click="rateComment('like', answer_on_comment_data.id, 'answers_on_comments')">
                                <v-avatar size="20">
                                  <v-img :lazy-src="require('../assets/img/emoticons/thumbs_up.png')" :src="require('../assets/img/emoticons/thumbs_up.png')" />
                                </v-avatar>
                              </v-btn>
                              <v-btn icon @click="rateComment('heart', answer_on_comment_data.id, 'answers_on_comments')">
                                <v-avatar size="20">
                                  <v-img :lazy-src="require('../assets/img/emoticons/heart.png')" :src="require('../assets/img/emoticons/heart.png')" />
                                </v-avatar>
                              </v-btn>
                              <v-btn icon @click="rateComment('funny', answer_on_comment_data.id, 'answers_on_comments')">
                                <v-avatar size="20">
                                  <v-img :lazy-src="require('../assets/img/emoticons/face_open_mouth_smiling_eyes.png')" :src="require('../assets/img/emoticons/face_open_mouth_smiling_eyes.png')" />
                                </v-avatar>
                              </v-btn>

                              <v-btn icon @click="rateComment('surprise', answer_on_comment_data.id, 'answers_on_comments')">
                                <v-avatar size="20">
                                  <v-img :lazy-src="require('../assets/img/emoticons/face_open_mouth.png')" :src="require('../assets/img/emoticons/face_open_mouth.png')" />
                                </v-avatar>
                              </v-btn>

                              <v-btn icon @click="rateComment('sad', answer_on_comment_data.id, 'answers_on_comments')">
                                <v-avatar size="20">
                                  <v-img :lazy-src="require('../assets/img/emoticons/disappointed_relieved_face.png')" :src="require('../assets/img/emoticons/disappointed_relieved_face.png')" />
                                </v-avatar>
                              </v-btn>

                              <v-btn icon @click="rateComment('angry', answer_on_comment_data.id, 'answers_on_comments')">
                                <v-avatar size="20">
                                <v-img :lazy-src="require('../assets/img/emoticons/pouting_face.png')" :src="require('../assets/img/emoticons/pouting_face.png')" />
                                </v-avatar>
                              </v-btn>
                            </v-card>
                          </v-menu>

                          <!-- //////////////////////////////////////////////////////////////////////////////answer on comment/////////////////////////////////////////////////////////////////////// -->
                          <v-btn @click.stop x-small text rounded outlined class="caption primary--text font-weight-bold mt-1 ml-1" @click="focusInputAnswerOnComment(answer_on_comment_data)">
                            Odpovedať
                          </v-btn>
                        </v-row>

                        <v-row class="ml-0 mr-0 mt-0">
                          <v-btn v-if="answer_on_comment_data.count_comments == 1" text small class="d-flex justify-start caption primary--text font-weight-bold">
                            <v-icon class="mr-1" small color="primary">mdi-subdirectory-arrow-right</v-icon>
                            {{answer_on_comment_data.count_comments}} Odpoveď
                          </v-btn>
                          <v-btn v-if="answer_on_comment_data.count_comments > 1" text small class="d-flex justify-start caption primary--text font-weight-bold">
                            <v-icon class="mr-1" small color="primary">mdi-subdirectory-arrow-right</v-icon>
                            {{answer_on_comment_data.count_comments}} Odpovede
                          </v-btn>
                        </v-row>
                        <!-- <v-row class="ml-0 mr-0 mt-0">
                        <v-btn v-if="comments3.length > 0" text small class="d-flex justify-start caption primary--text font-weight-bold">
                          <v-icon class="mr-1" small color="primary">mdi-subdirectory-arrow-right</v-icon>
                          {{comments3.length}} Odpovede
                        </v-btn>
                      </v-row> -->
                      </v-list-item-content>
                    </v-list-item>
                  </div>
                </template>
                <!-- koniec answer on post comment -->

                <!-- odpoved na post -->
                <v-container :id="`itemsPostComment${post_comment_data.id}`" class="p-0" style="display:none" >
                  <v-list-item class="ml-12 pl-11" v-if="$root.replyOnComment != undefined">
                    <v-icon size="20" class="primary--text">mdi-share-outline</v-icon>
                    <v-list-item-content>
                      <v-card class="p-2 rounded-lg card-comments" elevation="0" color="#f5f5f5">
                        <v-list-item-title class="text-left p-0" v-if="$root.replyOnComment.comment_id == undefined">
                          <span v-if="$root.replyOnComment.userpostcommentmodel.id == $root.me.id"><small>Odpovedáte na svoj vlastný komentár.</small></span>
                        </v-list-item-title>
                        <v-list-item-title class="text-left p-0" v-else>
                          <span v-if="$root.replyOnComment.useransweroncommentmodel.id == $root.me.id"><small>Odpovedáte na svoj vlastný komentár.</small></span>
                        </v-list-item-title>

                        <v-list-item-title class="d-flex justify-start">
                          <v-chip small v-if="$root.replyOnComment.comment_id == undefined">
                            <v-icon left class="mr-1" small>mdi-account-circle-outline</v-icon>
                            <span class="font-weight-bold" v-if="$root.replyOnComment.userpostcommentmodel.id == $root.me.id">Vy</span>
                            <span class="font-weight-bold" v-else>{{$root.replyOnComment.userpostcommentmodel.name}}</span>
                          </v-chip>

                          <v-chip small v-else>
                            <v-icon left class="mr-1" small>mdi-account-circle-outline</v-icon>
                            <span class="font-weight-bold" v-if="$root.replyOnComment.useransweroncommentmodel.id == $root.me.id">Vy</span>
                            <span class="font-weight-bold" v-else>{{$root.replyOnComment.useransweroncommentmodel.name}}</span>
                          </v-chip>

                          <v-spacer />

                          <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                              <v-btn v-on="on" text x-small color="primary" @click="goToComment()">
                                <v-icon class="mr-1" small >mdi-format-align-top</v-icon>
                              </v-btn>
                            </template>
                            <span>Skrolovať na koment</span>
                          </v-tooltip>

                        </v-list-item-title>

                        <v-list-item class="p-0" v-if="$root.replyOnComment.image_url != null">
                          <v-list-item-content class="p-0 pb-1 pt-2">
                            <v-img :lazy-src="`${$root.envUrlNoApi}/storage/post_img_video/${$root.replyOnComment.image_url}`" :src="`${$root.envUrlNoApi}/storage/post_img_video/${$root.replyOnComment.image_url}`" />
                          </v-list-item-content>
                        </v-list-item>

                        <v-list-item class="p-0" v-if="$root.replyOnComment.video_url != null">
                          <v-list-item-content class="p-0 pb-1 pt-2">
                            <vue-plyr>
                              <video controls playsinline>
                                <source size="1080" :src="$root.envUrlNoApi + '/storage/post_img_video/' +  $root.replyOnComment.video_url" type="video/mp4">
                              </video>
                            </vue-plyr>
                          </v-list-item-content>
                        </v-list-item>


                        <v-list-item-subtitle class="text-left primary--text font-weight-bold p-0 text-wrap" v-if="$root.replyOnComment.chip == null">
                          {{$root.replyOnComment.text}}
                        </v-list-item-subtitle>
                        <v-list-item-subtitle class="text-left primary--text font-weight-bold p-0 pt-1 text-wrap" v-else>
                          <v-chip small @click="showUserData($root.replyOnComment.useransweroncommentmodel2)">
                            <span v-if="$root.replyOnComment.chip == $root.me.name">Vy</span>
                            <span v-else>{{$root.replyOnComment.chip}}</span>
                          </v-chip>
                          {{$root.replyOnComment.text}}
                        </v-list-item-subtitle>
                      </v-card>
                    </v-list-item-content>
                  </v-list-item>

                  <v-list-item class="ml-15 pl-15 mr-2" v-if="showDragAndDropFileComment">
                    <v-row class="p-2 mt-0">
                      <file-pond allowImageCrop={true} allowFileEncode={true} class="filepond" name="filepond" :ref="`pondAddComment${post_comment_data.id}`" type="input" :files="filesAddComment" v-on:removefile="handleFileRemovedAddComment(post_comment_data.id)" v-on:addfile="handleFileAddedAddComment(post_comment_data.id)"
                        style="width:100%" label-idle="Sem presuňte súbory..." allow-multiple="true" max-files="10" />
                        <!-- v-on:init="handleFilePondInitAddComment" -->
                    </v-row>
                  </v-list-item>

                  <v-list-item class="ml-12">
                    <v-list-item-avatar class="ml-0 mr-2">
                      <v-avatar color="primary" size="28" v-if="post_data.userpostmodel.avatar == null">
                        <span v-if="post_data.userpostmodel.name != null" class="text-uppercase white--text">{{ post_data.userpostmodel.name.charAt(0) }}</span>
                      </v-avatar>
                      <v-avatar color="primary" size="28" v-else>
                        <v-img :lazy-src="`${$root.envUrlNoApi}/storage/user-avatar/${post_data.userpostmodel.avatar}`" :src="`${$root.envUrlNoApi}/storage/user-avatar/${post_data.userpostmodel.avatar}`" />
                      </v-avatar>
                    </v-list-item-avatar>
                    <v-list-item-content>
                      <!-- <v-menu class="p-2" bottom max-width="400px" rounded offset-y v-model="showDialogFriendsAnswerOnComment"> -->
                      <!-- <template v-slot:activator="{ on }"> -->
                      <!-- v-on="on"  -->
                      <v-text-field @focus="focusedAnswerOnComment(post_comment_data.id)" :hide-details="true" :ref="`commentInputChild${post_comment_data.id}`" filled rounded dense clearable clear-icon="mdi-close" label="Koment" v-model="textNewAnswerOnComment"
                        @click:clear="clearAnswerOnComment(post_comment_data.id); closeChipAnswerOnComment(post_comment_data.id)" @keydown.enter="answerOnComment(post_comment_data.id)">
                        <template v-slot:prepend-inner v-if="chipTextAnswerOnComment[post_comment_data.id] != undefined">
                          <v-chip class="ma-1" close @click:close="closeChipAnswerOnComment(post_comment_data.id)">{{chipTextAnswerOnComment[post_comment_data.id]}}</v-chip>
                        </template>
                        <template slot="append">
                          <v-menu :close-on-content-click="false" v-model="autoselectMenuComments[post_comment_data.id]" offset-y :position-y="60">
                            <template v-slot:activator="{ on, attrs }">
                              <v-btn small icon v-bind="attrs" v-on="on" @click="setEmojiID(post_data.id)">
                                <v-icon>
                                  mdi-emoticon
                                </v-icon>
                              </v-btn>
                            </template>
                            <picker :showSearch="false" :showPreview="false" :set="'messenger'" :showSkinTones="true" :emojiTooltip="true" :infiniteScroll="false" @select="selectEmojiAnswerOnComment" :i18n="i18n" />
                          </v-menu>
                          <v-btn small icon @click="showFileInputComment()">
                            <v-icon>
                              mdi-paperclip
                            </v-icon>
                          </v-btn>
                          <!-- <v-menu class="menu-append-icon" offset-y v-model="autoselectMenu2Comments[post_comment_data.id]" rounded="pill">
                            <template v-slot:activator="{ on, attrs }">
                              <v-icon v-bind="attrs" v-on="on">
                                mdi-dots-grid
                              </v-icon>
                            </template>
                            <v-card class="rounded-pill p-2" elevation="2">
                              <v-btn icon @click="showFileInputComment()">
                                <v-icon>
                                  mdi-paperclip
                                </v-icon>
                              </v-btn>
                            </v-card>
                          </v-menu> -->
                        </template>
                      </v-text-field>
                    </v-list-item-content>
                  </v-list-item>
                  <v-divider />
                </v-container>
              </v-container>
            </template>
          </v-list>
        </v-container>
        <!-- //////////////////////////////////////////////////////////////////////koniec post_comment -->
      </v-card>
    </v-flex>
  </template>


  <!-- ........................................................................dialog update post..................................................................................................... -->
  <v-dialog v-model="dialogUpdatePost" persistent max-width="400">
    <v-card>
      <v-card-title class="text-h5 primary--text">
        Úprava príspevku
      </v-card-title>

      <v-divider class="mt-0 mb-0"></v-divider>

      <v-card-text class="pt-3 pb-0">
        <v-row class="p-2">
          <v-avatar color="primary" size="40" v-if="$root.me.overlayAvatar == true">
            <v-overlay :value="$root.me.overlayAvatar" :absolute="true" :opacity="0">
              <v-progress-circular indeterminate size="24" color="white"></v-progress-circular>
            </v-overlay>
          </v-avatar>

          <v-avatar color="primary" size="40" v-else-if="$root.me.avatar == null && $root.me.overlayAvatar == false">
            <span v-if="$root.me.name != null" class="text-uppercase white--text">{{ $root.me.name.charAt(0) }}</span>
          </v-avatar>
          <v-avatar color="primary" size="40" v-else>
            <img :lazy-src="`${$root.envUrlNoApi}/storage/user-avatar/${$root.me.avatar}`" :src="`${$root.envUrlNoApi}/storage/user-avatar/${$root.me.avatar}`">
          </v-avatar>
          <span class="ml-1 m-auto">{{$root.me.name}}</span>
        </v-row>
        <v-row class="p-2 pb-0">
          <v-textarea v-model="updateTextPost" clearable clear-icon="mdi-close-circle" label="Napíšte, čo si myslíte" value="This is clearable text." auto-grow filled rounded></v-textarea>
        </v-row>
        <v-row class="p-2 mt-0" v-if="showDragAndDropFilePost">
          <file-pond allowImageCrop={true} allowFileEncode={true} class="filepond" name="filepond" ref="pondPost" type="input" :files="filesPost" v-on:removefile="handleFileRemovedPost" v-on:addfile="handleFileAddedPost" v-on:init="handleFilePondInitPost"
            style="width:100%" label-idle="Sem presuňte súbory..." allow-multiple="true" max-files="10" />
        </v-row>
      </v-card-text>

      <v-divider class="mt-0 mb-0"></v-divider>

      <v-row class="m-0">
        <v-col cols="2">
          <v-tooltip top>
            <template v-slot:activator="{ on }">
              <v-btn v-on="on" icon x-large color="primary">
                <v-icon class="mr-1">mdi-account-multiple-plus</v-icon>
              </v-btn>
            </template>
            <span>Označiť ľudí</span>
          </v-tooltip>
        </v-col>
        <v-col cols="2">
          <v-tooltip top>
            <template v-slot:activator="{ on }">
              <v-btn v-on="on" icon x-large color="primary" @click="showDragAndDropFilePost = !showDragAndDropFilePost">
                <v-icon class="mr-1">mdi-image-multiple</v-icon>
              </v-btn>
            </template>
            <span>Pridať video/obrázok</span>
          </v-tooltip>
        </v-col>
      </v-row>

      <v-divider class="mt-0 mb-0"></v-divider>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn text color="primary" :disabled="(updateTextPost == post_data_text && isDisabledPost) || (!updateTextPost && isDisabledPost)"  @click="saveUpdatePost(post_data_id)">
          Uložiť zmenu
        </v-btn>

        <v-btn text color="red" @click="dialogUpdatePost = false; updateTextPost = ''; showDragAndDropFilePost = false; filesPost = []; post_data_text = '', post_data_id = null; post_data_img_video = []">
          Zrušiť
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>

  <!-- ........................................................................dialog update comment..................................................................................................... -->
  <v-dialog v-model="dialogUpdatePostComment" persistent max-width="400">
    <v-card>
      <v-card-title class="text-h5 primary--text">
        Úprava komentára
      </v-card-title>

      <v-divider class="mt-0 mb-0"></v-divider>

      <v-card-text class="pt-3 pb-0">
        <v-row class="p-2">
          <v-avatar color="primary" size="40" v-if="$root.me.overlayAvatar == true">
            <v-overlay :value="$root.me.overlayAvatar" :absolute="true" :opacity="0">
              <v-progress-circular indeterminate size="24" color="white"></v-progress-circular>
            </v-overlay>
          </v-avatar>

          <v-avatar color="primary" size="40" v-else-if="$root.me.avatar == null && $root.me.overlayAvatar == false">
            <span v-if="$root.me.name != null" class="text-uppercase white--text">{{ $root.me.name.charAt(0) }}</span>
          </v-avatar>
          <v-avatar color="primary" size="40" v-else>
            <img :lazy-src="`${$root.envUrlNoApi}/storage/user-avatar/${$root.me.avatar}`" :src="`${$root.envUrlNoApi}/storage/user-avatar/${$root.me.avatar}`">
          </v-avatar>
          <span class="ml-1 m-auto">{{$root.me.name}}</span>
        </v-row>
        <v-row class="p-2 pb-0">
          <v-textarea v-model="updateTextPostComment" clearable clear-icon="mdi-close-circle" label="Napíšte, čo si myslíte" value="This is clearable text." auto-grow filled rounded></v-textarea>
        </v-row>
        <v-row class="p-2 mt-0" v-if="showDragAndDropFilePostComment">
          <file-pond allowImageCrop={true} allowFileEncode={true} class="filepond" name="filepond" ref="pondPostComment" type="input" :files="filesPostComment" v-on:removefile="handleFileRemovedPostComment" v-on:addfile="handleFileAddedPostComment" v-on:init="handleFilePondInitPostComment"
            style="width:100%" label-idle="Sem presuňte súbory..." allow-multiple="true" max-files="10" />
        </v-row>
      </v-card-text>

      <v-divider class="mt-0 mb-0"></v-divider>

      <v-row class="m-0">
        <v-col cols="2">
          <v-tooltip top>
            <template v-slot:activator="{ on }">
              <v-btn v-on="on" icon x-large color="primary">
                <v-icon class="mr-1">mdi-account-multiple-plus</v-icon>
                <!-- Obrázok/video -->
              </v-btn>
            </template>
            <span>Označiť ľudí</span>
          </v-tooltip>
        </v-col>
        <v-col cols="2">
          <v-tooltip top>
            <template v-slot:activator="{ on }">
              <v-btn v-on="on" icon x-large color="primary" @click="showDragAndDropFilePostComment = !showDragAndDropFilePostComment">
                <v-icon class="mr-1">mdi-image-multiple</v-icon>
                <!-- Obrázok/video -->
              </v-btn>
            </template>
            <span>Pridať video/obrázok</span>
          </v-tooltip>
        </v-col>
      </v-row>

      <v-divider class="mt-0 mb-0"></v-divider>

      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn v-if="isDisabledUpdatePostComment == 'answers_on_comments'" text color="primary" :disabled="(updateTextPostComment == post_comment_data_text) || (!updateTextPostComment)"  @click="saveUpdatePostComment(post_comment_data_id, isDisabledUpdatePostComment)">
          Uložiť zmenu
        </v-btn>

        <v-btn v-else text color="primary" :disabled="(updateTextPostComment == post_comment_data_text && isDisabledPostComment) || (!updateTextPostComment && isDisabledPostComment)"  @click="saveUpdatePostComment(post_comment_data_id, isDisabledUpdatePostComment)">
          Uložiť zmenu
        </v-btn>

        <v-btn text color="red" @click="dialogUpdatePostComment = false; updateTextPostComment = ''; showDragAndDropFilePostComment = false; filesPostComment = []">
          Zrušiť
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>

  <v-row class="p-3" align="center" v-if="this.$root.profilePosts.getAllPosts.countActualPosts == this.$root.profilePosts.getAllPosts.countAllPosts
                                          && this.$root.profilePosts.getAllPosts.countActualPosts != 0 && this.$root.profilePosts.getAllPosts.countAllPosts != 0
                                          && this.$root.profilePosts.getAllPosts.loadingPosts == false">
    <v-divider />
    <v-btn class="ml-3 mr-3" small rounded text outlined color="primary" disabled>Všetko</v-btn>
    <v-divider />
  </v-row>

  <v-row class="p-3" align="center" v-else-if="this.$root.profilePosts.getAllPosts.countActualPosts == 0 && this.$root.profilePosts.getAllPosts.countAllPosts == 0 && this.$root.profilePosts.getAllPosts.loadingPosts == false">
    <v-divider />
    <v-btn class="ml-3 mr-3" small rounded text outlined color="primary" disabled>Žiadne príspevky</v-btn>
    <v-divider />
  </v-row>

  <scroll-loader :loader-method="scrollLoaderGetAllPosts" :loader-disable="$root.profilePosts.getAllPosts.loadingPaginate" v-else>
      <v-progress-circular v-if="!$root.profilePosts.getAllPosts.loadingPaginate" indeterminate size="24" color="primary"></v-progress-circular>
  </scroll-loader>
  </v-container>
  </template>

  <script>
  import axios from 'axios';
  // import _ from 'lodash';
    // import moment from 'moment';

  import {
  Picker
  } from 'emoji-mart-vue'

  // import data from "emoji-mart-vue-fast/data/all.json";
  // import "emoji-mart-vue-fast/css/emoji-mart.css";
  // import {
  //   Picker,
  //   EmojiIndex
  // } from "emoji-mart-vue-fast";
  // let emojiIndex = new EmojiIndex(data);

  import VuePlyr from 'vue-plyr';
  import 'vue-plyr/dist/vue-plyr.css';

  // Import Vue FilePond
  import vueFilePond from 'vue-filepond'
  // Import FilePond styles
  import 'filepond/dist/filepond.min.css'
  // Import image preview plugin styles
  import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css'
  // Import image preview and file type validation plugins
  import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type'
  import FilePondPluginImagePreview from 'filepond-plugin-image-preview'
  // Import the plugin code
  import FilePondPluginFileEncode from 'filepond-plugin-file-encode';
  import FilePondPluginImageCrop from 'filepond-plugin-image-crop';
  // Import the plugin code
  import FilePondPluginImageEdit from 'filepond-plugin-image-edit';
  import FilePondPluginImageResize from 'filepond-plugin-image-resize';
  import FilePondPluginImageTransform from 'filepond-plugin-image-transform';
  // Import the plugin styles
  import 'filepond-plugin-image-edit/dist/filepond-plugin-image-edit.css';
  // Create component
  const FilePond = vueFilePond(
  FilePondPluginFileValidateType,
  FilePondPluginImagePreview,
  FilePondPluginFileEncode,
  FilePondPluginImageCrop,
  FilePondPluginImageEdit,
  FilePondPluginImageResize,
  FilePondPluginImageTransform
  )
  export default {
    name: "Center",
    components: {
      'vue-plyr': VuePlyr,
      Picker,
      FilePond,
    },
    props: ['value'],
    data() {
      return {
        // emojiIndex: emojiIndex,

        imageUrl: null,
        videoUrl: null,
        imageUrlPostComment: null,
        videoUrlPostComment: null,
        imageUrlAnswerOnComment: null,
        videoUrlAnswerOnComment: null,

        showDragAndDropFile: false,
        showDragAndDropFilePostComment: false,
        showDragAndDropFilePost: false,

        files: [],
        filesPostComment: [],
        filesPost: [],

        urls: [],
        photo: null,

        dialogPostShares: {},
        // message: '',
        // answerParent: '',
        // answerChild: '',
        itemsPostComment: false,
        itemsAnswerOnComment: false,
        selectedChip: '',
        textNewPost: null,
        // textNewPostComment: null,
        textNewPostComment: [],
        // textNewAnswerOnComment: null,
        // textNewAnswerOnComment: [],
        // textNewAnswerOnPostComment: [],
        textNewAnswerOnComment: null,
        textNewAnswerOnPostComment: null,
        // chatInput
        // autoselectMenu: false,
        autoselectMenuPost: {},
        autoselectMenuComments: {},
        autoselectMenu2Post: {},
        autoselectMenu2Comments: {},
        // autoselectMenu2: false,
        showPostDetails: false,
        showCommentsFilter: {},
        selectedItemSortComments: 1,

        tabReactionsPost: {},
        selectedDialogReactionsPost: null,
        dialogReactionsPost: false,
        reactionsPostAll: null,
        reactionsPostLike: null,
        reactionsPostHeart: null,
        reactionsPostFunny: null,
        reactionsPostSurprise: null,
        reactionsPostSad: null,
        reactionsPostAngry: null,

        tabReactionsComment: {},
        selectedDialogReactionsComment: null,
        dialogReactionsComment: false,
        reactionsCommentAll: null,
        reactionsCommentLike: null,
        reactionsCommentHeart: null,
        reactionsCommentFunny: null,
        reactionsCommentSurprise: null,
        reactionsCommentSad: null,
        reactionsCommentAngry: null,

        tabReactionsAnswerOnComment: {},
        selectedDialogReactionsAnswerOnComment: null,
        dialogReactionsAnswerOnComment: false,
        reactionsAnswerOnCommentAll: null,
        reactionsAnswerOnCommentLike: null,
        reactionsAnswerOnCommentHeart: null,
        reactionsAnswerOnCommentFunny: null,
        reactionsAnswerOnCommentSurprise: null,
        reactionsAnswerOnCommentSad: null,
        reactionsAnswerOnCommentAngry: null,

        bn: '',
        tab: null,

        // username: localStorage.getItem('username'),
        // email: '',
        // avatar: '',
        // overlayAvatar: true,
        dialogNewPost: false,
        i18n: {
          search: 'Vyhľadať',
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
        emojiID: null,
        comments3: [],
        commentsFilter: ['Najrelevantnejšíe komentáre', 'Najnovšie komentáre', 'Všetky komentáre'],
        isDisabled: true,
        isDisabledPost: true,
        isDisabledPostComment: true,

        //slide show
        slideShow: null,
        chipTextPostComment: [],
        chipTextAnswerOnComment: [],
        displayFriendsPostComment: [],
        displayFriendsAnswerOnComment: [],
        showDialogFriendsPostComment: [],
        showDialogFriendsAnswerOnComment: false,
        selectUserFriendsPostComment: '',
        focusedInputPostComment: null,
        focusedInputAnswerOnComment: null,
        overlayDisplayFriendsPostComment: true,

        page: this.$root.profilePosts.getAllPosts.countActualPosts,
        pageSize: 3,
        loadingNewPostData: false,



        dataAboutUserOnHover: null,
        dataAboutUser: false,
        isFriendOrNoOrMe: '',
        isFriendOrNoOrMe_overlay: true,
        userDataProfile: null,

        dialogUpdatePostComment: false,
        updateTextPostComment: '',
        isDisabledUpdatePostComment: '',
        post_comment_data_text: '',
        post_comment_data_id: null,

        dialogUpdatePost: false,
        updateTextPost: '',
        post_data_text: '',
        post_data_id: null,
        post_data_img_video: [],

        showDragAndDropFileComment: false,
        filesAddComment: [],

        showDragAndDropFileAddPostComment: [],
        filesAddPostComment: [],
      }
    },

    methods: {
      showFileInputComment() {
        this.showDragAndDropFileComment = !this.showDragAndDropFileComment;
      },

      showFileInputPostComment(index) {
        if (this.showDragAndDropFileAddPostComment[index] == undefined) {
          this.showDragAndDropFileAddPostComment = [];
          this.filesAddPostComment = [];
        }

        if (this.showDragAndDropFileAddPostComment[index] == true) {
          this.showDragAndDropFileAddPostComment = [];
        } else {
          this.showDragAndDropFileAddPostComment[index] = true;
        }
      },

      scrollLoaderGetAllPosts() {
        this.loadingNewPostData = true;
        const api = `${process.env.VUE_APP_API_URL}/getAllDataAboutUserPosts/${this.$route.params.user_id}/${this.$root.profilePosts.getAllPosts.countActualPosts}/${this.pageSize}`;
        const config = {
          headers: {
            Accept: "application/json",
            Authorization: "Bearer " + localStorage.getItem("authToken"),
          },
        };
        axios.get(api, config)
          .then(res => {
            console.log(res.data);
            if (res.data[0].length != 0) {
              this.$root.profilePosts.getAllPosts.countActualPosts += res.data[0].length;
              this.$root.profilePosts.getAllPosts.allPosts = [...this.$root.profilePosts.getAllPosts.allPosts, ...res.data[0]]
            }
            this.loadingNewPostData = false;
          });
      },

      showUserData(data) {
        console.log(data);
      },

      getColor(e) {
        if (e == 'offline') return 'red'
        else return 'green'
      },

      focusedPostComment(id) {
        this.focusedInputPostComment = id;
      },

      focusedAnswerOnComment(id) {
        this.focusedInputAnswerOnComment = id;
      },

      closeChipPostComment(id) {
        this.chipTextPostComment.splice(id, 1, null)
        // this.chipTextPostComment[id] = null;
        this.selectUserFriendsPostComment = '';
        this.$nextTick(() => this.$refs[`commentInput${id}`][0].focus());
      },

      closeChipAnswerOnComment(id) {
        // document.getElementById(`itemsPostComment${id}`).style.display = 'none';
        this.chipTextAnswerOnComment.splice(id, 1, undefined)
        this.$nextTick(() => this.$refs['commentInputChild' + id][0].focus());
      },

      selectDataAboutUserPostComment(item, id) {
        this.chipTextPostComment[id] = item.name + ' ';
        this.textNewPostComment[id] = '';
        this.$refs[`commentInput${id}`][0].focus()
        this.$nextTick(() => this.$refs[`commentInput${id}`][0].focus());
        this.showDialogFriendsAnswerOnComment = false;
      },

      handleSelected(id) {
        if (document.getElementById(`showPostDetails${id}`).style.display == 'block') {
          document.getElementById(`showPostDetails${id}`).style.display = 'none';

          document.getElementById("showPostDetailsArrowDown" + id).classList.remove('arrow-d-block');
          document.getElementById("showPostDetailsArrowDown" + id).classList.add('arrow-d-none');
          document.getElementById("showPostDetailsArrowUp" + id).classList.remove('arrow-d-none');
          document.getElementById("showPostDetailsArrowUp" + id).classList.add('arrow-d-block');
        } else {
          document.getElementById(`showPostDetails${id}`).style.display = 'block';

          document.getElementById("showPostDetailsArrowDown" + id).classList.remove('arrow-d-none');
          document.getElementById("showPostDetailsArrowDown" + id).classList.add('arrow-d-block');
          document.getElementById("showPostDetailsArrowUp" + id).classList.remove('arrow-d-block');
          document.getElementById("showPostDetailsArrowUp" + id).classList.add('arrow-d-none');
        }

      },

      focusInput(id) {
        if (document.getElementById(`showPostDetails${id}`).style.display != 'block') {
          document.getElementById(`showPostDetails${id}`).style.display = 'block';
          if (document.getElementById("showPostDetailsArrowDown" + id)) {
            document.getElementById("showPostDetailsArrowDown" + id).classList.remove('arrow-d-none');
            document.getElementById("showPostDetailsArrowDown" + id).classList.add('arrow-d-block');
            document.getElementById("showPostDetailsArrowUp" + id).classList.remove('arrow-d-block');
            document.getElementById("showPostDetailsArrowUp" + id).classList.add('arrow-d-none');
          }
          // accessing the dynamic ref
          this.$refs[`commentInput${id}`][0].focus();
        } else {
          // accessing the dynamic ref
          this.$refs[`commentInput${id}`][0].focus();
        }
      },

      getIcon(item) {
        if (item == 'like') {
          return 'mdi-thumb-up';
        } else if (item == 'heart') {
          return 'mdi-cards-heart';
        } else if (item == 'funny') {
          return 'mdi-emoticon-excited';
        } else if (item == 'surprise') {
          return 'mdi-emoticon-frown';
        } else if (item == 'sad') {
          return 'mdi-emoticon-cry';
        } else {
          return 'mdi-emoticon-devil';
        }
      },

      setEmojiID(id) {
        this.emojiID = id;
      },
      // emoji input char
      selectEmoji(emoji) {
        if (this.textNewPostComment[this.emojiID] != null) {
          this.$set(this.textNewPostComment, this.emojiID, this.textNewPostComment[this.emojiID] += emoji.native)
        } else {
          this.$set(this.textNewPostComment, this.emojiID, this.textNewPostComment[this.emojiID] = emoji.native)
        }
        // if (this.textNewPostComment[this.emojiID] != null) {
        //   this.textNewPostComment[this.emojiID] += emoji.native;
        // } else {
        //   this.textNewPostComment[this.emojiID] = emoji.native;
        // }
      },

      selectEmojiAnswerOnComment(emoji) {
        if (this.textNewAnswerOnComment != null) {
          this.textNewAnswerOnComment += emoji.native;
        } else {
          this.textNewAnswerOnComment = emoji.native;
        }
      },

      // chatInput
      toggleMarker() {
        this.marker = !this.marker
      },

      clearPostComment(id) {
        this.textNewPostComment[id] = null;
        this.itemsPostComment = false;
      },

      clearAnswerOnComment() {
        this.textNewAnswerOnComment = '';
        this.itemsPostComment = false;
        for (var x = 0; x < this.$root.profilePosts.getAllPosts.allPosts.length; x++) {
          for (var y = 0; y < this.$root.profilePosts.getAllPosts.allPosts[x].postcommentmodel.length; y++) {
            document.getElementById(`itemsPostComment${this.$root.profilePosts.getAllPosts.allPosts[x].postcommentmodel[y].id}`).style.display = 'none';
            for (var z = 0; z < this.$root.profilePosts.getAllPosts.allPosts[x].postcommentmodel[y].answeroncommentmodel.length; z++) {
              document.getElementById(`itemsPostComment${this.$root.profilePosts.getAllPosts.allPosts[x].postcommentmodel[y].answeroncommentmodel[z].comment_id}`).style.display = 'none';
            }
          }
        }
      },

      goToComment() {
        if (this.$root.replyOnComment.comment_id != undefined) {
            if (document.getElementById("btn_answers_on_comments_arrow_down" + this.$root.replyOnComment.comment_id).classList.contains('arrow-d-block')) {
            // scroll to your element
            document.querySelector("#answers_on_comments" + this.$root.replyOnComment.id).scrollIntoView(true);
            // now - fixed header
            if(window.scrollY){
              window.scroll(0, window.scrollY - 64);
            }
          }
        } else {
          // scroll to your element
          document.querySelector("#post_comment" + this.$root.replyOnComment.id).scrollIntoView(true);
          // now - fixed header
          if(window.scrollY){
            window.scroll(0, window.scrollY - 64);
          }
        }
      },

      focusInputAnswerOnComment(item) {
        this.$root.replyOnComment = undefined;
        this.filesAddComment = [];
        this.showDragAndDropFileComment = false;

        this.chipTextAnswerOnComment.forEach((elem, index) => {
          if (document.getElementById(`itemsPostComment${index}`).style != null) {
            document.getElementById(`itemsPostComment${index}`).style.display = 'none';
          }
        })

        if (item.answeroncommentmodel != undefined) {
          if (document.getElementById("btn_answers_on_comments" + item.id) != undefined) {
            if (document.getElementById("btn_answers_on_comments_arrow_down" + item.id).classList.contains('arrow-d-block')) {
              document.getElementById("btn_answers_on_comments_arrow_down" + item.id).classList.add('arrow-d-block');
            } else {
                document.getElementById("btn_answers_on_comments_arrow_down" + item.id).classList.add('arrow-d-none');
            }
          }

          this.$root.replyOnComment = undefined;
          if (document.getElementById(`itemsPostComment${item.id}`).style != null) {
            document.getElementById(`itemsPostComment${item.id}`).style.display = 'none';
            if (document.getElementById(`itemsPostComment${item.id}`).style.display == 'none') {
              document.getElementById(`itemsPostComment${item.id}`).style.display = 'block';
              this.$root.replyOnComment = item;
              this.chipTextAnswerOnComment = [];
              this.chipTextAnswerOnComment[item.id] = item.userpostcommentmodel.name + ' ';
              this.textNewAnswerOnComment = '';
              this.$nextTick(() => this.$refs['commentInputChild' + item.id][0].focus());
              this.showDialogFriendsAnswerOnComment = false;
            }
          }
        } else {
          if (document.getElementById("btn_answers_on_comments" + item.id) != undefined) {
            if (document.getElementById("btn_answers_on_comments_arrow_down" + item.comment_id).classList.contains('arrow-d-block')) {
              document.getElementById("btn_answers_on_comments_arrow_down" + item.comment_id).classList.add('arrow-d-block');
            } else {
                document.getElementById("btn_answers_on_comments_arrow_down" + item.comment_id).classList.add('arrow-d-none');
            }
          }
          this.$root.replyOnComment = undefined;
          if (document.getElementById(`itemsPostComment${item.comment_id}`).style != null) {
            document.getElementById(`itemsPostComment${item.comment_id}`).style.display = 'none';
            if (document.getElementById(`itemsPostComment${item.comment_id}`).style.display == 'none') {
              document.getElementById(`itemsPostComment${item.comment_id}`).style.display = 'block';
              this.$root.replyOnComment = item;
              this.chipTextAnswerOnComment = [];
              this.chipTextAnswerOnComment[item.comment_id] = item.useransweroncommentmodel.name + ' ';
              this.textNewAnswerOnComment = '';
              this.$nextTick(() => this.$refs['commentInputChild' + item.comment_id][0].focus());
              this.showDialogFriendsAnswerOnComment = false;
            }
          }
        }
      },
      //////////////////////////////////////////////////////////////////////////////////////////////////////////////
      //filepond getFiles
      handleFilePondInit: function() {
        console.log("FilePond has initialized");
      },
      handleFileAdded: function() {
        this.files = [];
        for (var i = 0; i < this.$refs.pond.getFiles().length; i++) {
          this.files.push(this.$refs.pond.getFiles()[i].file)
        }
        this.isDisabled = false;
      },

      handleFileRemoved: function(error, file) {
        this.$refs.pond.removeFile(file.id);
        this.files = [];
        for (var i = 0; i < this.$refs.pond.getFiles().length; i++) {
          this.files.push(this.$refs.pond.getFiles()[i].file)
        }
        this.isDisabled = true;
      },

      //filepond getFiles postComment
      handleFilePondInitPostComment: function() {
        console.log("FilePond post comment has initialized");
      },

      handleFileAddedPostComment: function() {
        this.filesPostComment = [];
        for (var i = 0; i < this.$refs.pondPostComment.getFiles().length; i++) {
          this.filesPostComment.push(this.$refs.pondPostComment.getFiles()[i].file)
        }
        this.isDisabledPostComment = false;
      },

      handleFileRemovedPostComment: function(error, file) {
        this.$refs.pondPostComment.removeFile(file.id);
        this.filesPostComment = [];
        for (var i = 0; i < this.$refs.pondPostComment.getFiles().length; i++) {
          this.filesPostComment.push(this.$refs.pondPostComment.getFiles()[i].file)
        }
        this.isDisabledPostComment = true;
      },

      //filepond getFiles post
      handleFilePondInitPost: function() {
        console.log("FilePond post comment has initialized");
        if (this.post_data_img_video.length != 0) {
          let type;
          if (this.post_data_img_video[1] == 'png') {
            type = 'obrázok';
          } else if (this.post_data_img_video[1] == 'mp4') {
            type = 'video';
          }

          this.$refs.pondPost.addFile(`http://127.0.0.1:8000/storage/post_img_video/${this.post_data_img_video[0]}`)
          .then((file) => {
            file.file.name = type;
              // File has been added
          })
          .catch((error) => {
            console.log(error);
              // Something went wrong
          });
        }
      },

      handleFileAddedPost: function() {
        this.filesPost = [];
        for (var i = 0; i < this.$refs.pondPost.getFiles().length; i++) {
          this.filesPost.push(this.$refs.pondPost.getFiles()[i].file)
        }

        if (this.filesPost[0] instanceof File) {
          this.isDisabledPost = false;
        } else {
          this.isDisabledPost = true;
        }
      },

      handleFileRemovedPost: function(error, file) {
        this.$refs.pondPost.removeFile(file.id);
        this.filesPost = [];
        for (var i = 0; i < this.$refs.pondPost.getFiles().length; i++) {
          this.filesPost.push(this.$refs.pondPost.getFiles()[i].file)
        }
        if (this.filesPost[0] instanceof File) {
          this.isDisabledPost = true;
        } else {
          this.isDisabledPost = false;
        }
      },

      // -------------------------------------------

      handleFilePondInitAddPostComment: function() {
        console.log("FilePond AddPostComment has initialized");
      },
      handleFileAddedAddPostComment: function(index) {
        this.filesAddPostComment = [];
        for (var i = 0; i < this.$refs['pondAddPostComment' + index][0].getFiles().length; i++) {
          this.filesAddPostComment.push(this.$refs['pondAddPostComment' + index][0].getFiles()[i].file)
        }
      },

      handleFileRemovedAddPostComment: function(index, error, file) {
        this.$refs['pondAddPostComment' + index][0].removeFile(file.id);
        this.filesAddPostComment = [];
        for (var i = 0; i < this.$refs['pondAddPostComment' + index][0].getFiles().length; i++) {
          this.filesAddPostComment.push(this.$refs['pondAddPostComment' + index][0].getFiles()[i].file)
        }
      },

      ///////////////////////////////////
      handleFilePondInitAddComment: function() {
        console.log("FilePond AddComment has initialized");
      },
      handleFileAddedAddComment: function(index) {
        this.filesAddComment = [];
        for (var i = 0; i < this.$refs['pondAddComment' + index][0].getFiles().length; i++) {
          this.filesAddComment.push(this.$refs['pondAddComment' + index][0].getFiles()[i].file)
        }
      },

      handleFileRemovedAddComment: function(index, error, file) {
        this.$refs['pondAddComment' + index][0].removeFile(file.id);
        this.filesAddComment = [];
        for (var i = 0; i < this.$refs['pondAddComment' + index][0].getFiles().length; i++) {
          this.filesAddComment.push(this.$refs['pondAddComment' + index][0].getFiles()[i].file)
        }
      },
      /////////////////add..... remove....... update......report post//////////////////////////////////////
      addPost() {
        const api = `${process.env.VUE_APP_API_URL}/add_post`;
        const config = {
          headers: {
            Accept: "application/json",
            Authorization: "Bearer " + localStorage.getItem("authToken"),
          },
        };
        let formData = new FormData()
        for (let i = 0; i < this.files.length; i++) {
          formData.append('image_video_url[]', this.files[i])
        }

        if (this.textNewPost != null) {
          formData.append('text', this.textNewPost);
        }

        axios.post(api, formData, config)
          .then(() => {})
          .catch((error) => {
            console.log(error)
          })
      },

      deletePost(item) {
        const api = `${process.env.VUE_APP_API_URL}/delete_post`;
        const config = {
          headers: {
            Accept: "application/json",
            Authorization: "Bearer " + localStorage.getItem("authToken"),
          },
        };
        axios.post(api, {
            id: item.id
          },
          config
        )
        .then(() => {})
        .catch((error) => {
          console.log(error)
        })
      },

      updatePost(item) {
        this.dialogUpdatePost = true;
        this.updateTextPost = item.text;
        this.post_data_text = item.text;
        this.post_data_id = item.id;
        if (item.image_url != null) {
          this.post_data_img_video.push(item.image_url);
          this.post_data_img_video.push('png');
        } else if (item.video_url != null) {
          this.post_data_img_video.push(item.video_url);
          this.post_data_img_video.push('mp4');
        }
      },

      saveUpdatePost(index) {
        const api = `${process.env.VUE_APP_API_URL}/update_post`;
        const config = {
          headers: {
            Accept: "application/json",
            Authorization: "Bearer " + localStorage.getItem("authToken"),
          },
        };

        let formData = new FormData()
        for (let i = 0; i < this.filesPost.length; i++) {
          if (this.filesPost[i] instanceof File) {
            formData.append('image_video_url[]', this.filesPost[i])
          } else {
            formData.append('image_video_url[]', 'same')
          }
        }

        if (this.updateTextPost != null) {
          formData.append('text', this.updateTextPost);
        }
        formData.append('post_id', index);

        axios.post(api, formData, config)
          .then(() => {
            this.filesPost = [];
            this.dialogUpdatePost = false;
            this.post_data_text = '';
            this.post_data_id = null;
            this.updateTextPost = '';
            this.showDragAndDropFilePost = false;
            this.post_data_img_video = [];
          })
          .catch((error) => {
            console.log(error)
          })
      },

      reportPost(item) {
        console.log(item);
      },
      ///////////////////////////////////////////////////////////////////////////////// rate post
      rate(item, index) {
        const api = `${process.env.VUE_APP_API_URL}/add_post_reaction`;
        const config = {
          headers: {
            Accept: "application/json",
            Authorization: "Bearer " + localStorage.getItem("authToken"),
          },
        };
        axios.post(api, {
              reaction_type: item,
              post_id: index,
            },
            config
          )
          .then((res) => {
            console.log(res.data);
          })
          .catch((error) => {
            console.log(error)
          })
      },

      // remove rate post
      removeRate(index) {
        const api = `${process.env.VUE_APP_API_URL}/delete_post_reaction`;
        const config = {
          headers: {
            Accept: "application/json",
            Authorization: "Bearer " + localStorage.getItem("authToken"),
          },
        };
        axios.post(api, {
              post_id: index,
            },
            config
          )
          .then(() => {})
          .catch((error) => {
            console.log(error)
          })
      },

      // share post
      share(index) {
        const api = `${process.env.VUE_APP_API_URL}/add_post_share`;
        const config = {
          headers: {
            Accept: "application/json",
            Authorization: "Bearer " + localStorage.getItem("authToken"),
          },
        };
        axios.post(api, {
              post_id: index,
            },
            config
          )
          .then((res) => {
            console.log(res.data);
          })
          .catch((error) => {
            console.log(error)
          })
      },

      removeShare(index) {
        const api = `${process.env.VUE_APP_API_URL}/delete_post_share`;
        const config = {
          headers: {
            Accept: "application/json",
            Authorization: "Bearer " + localStorage.getItem("authToken"),
          },
        };
        axios.post(api, {
              post_id: index,
            },
            config
          )
          .then(() => {})
          .catch((error) => {
            console.log(error)
          })
      },

      //add comment
      comment(index) {
        if (this.textNewPostComment == '') {
          return;
        }
        const api = `${process.env.VUE_APP_API_URL}/add_post_comment`;
        const config = {
          headers: {
            Accept: "application/json",
            Authorization: "Bearer " + localStorage.getItem("authToken"),
          },
        };

        let formData = new FormData()
        formData.append('post_id', index);

        formData.append('text', this.textNewPostComment[index]);

        for (let i = 0; i < this.filesAddPostComment.length; i++) {
          formData.append('image_video_url[]', this.filesAddPostComment[i])
        }

        if (this.chipTextPostComment[index] != undefined) {
          formData.append('chip', this.chipTextPostComment[index]);
        } else {
          formData.append('chip', '0');
        }

          axios.post(api, formData, config)
          .then(() => {
            this.showDragAndDropFileAddPostComment = [];
            this.filesAddPostComment = [];
            this.closeChipPostComment(index);
            this.clearPostComment(index);
          })
          .catch((error) => {
            console.log(error)
          })
      },

      //add answer on comment
      answerOnComment(index) {
        if (this.textNewAnswerOnComment == '') {
          return;
        }
        const api = `${process.env.VUE_APP_API_URL}/add_answer_on_comment`;
        const config = {
          headers: {
            Accept: "application/json",
            Authorization: "Bearer " + localStorage.getItem("authToken"),
          },
        };

        let formData = new FormData()
        formData.append('comment_id', index);

        formData.append('text', this.textNewAnswerOnComment);

        for (let i = 0; i < this.filesAddComment.length; i++) {
          formData.append('image_video_url[]', this.filesAddComment[i])
        }

        if (this.chipTextAnswerOnComment[index] != undefined) {
          formData.append('chip', this.chipTextAnswerOnComment[index]);
        } else {
          formData.append('chip', '0');
        }


          axios.post(api, formData, config)
          .then(() => {
            this.showDragAndDropFileComment = false;
            this.filesAddComment = [];
            this.closeChipAnswerOnComment(index)
            this.clearAnswerOnComment();
          })
          .catch((error) => {
            console.log(error)
          })
      },

      deletePostComment(item, type) {
        const api = `${process.env.VUE_APP_API_URL}/delete_post_comment`;
        const config = {
          headers: {
            Accept: "application/json",
            Authorization: "Bearer " + localStorage.getItem("authToken"),
          },
        };
        axios.post(api, {
              id: item.id,
              type: type
            },
            config
          )
          .then(() => {})
          .catch((error) => {
            console.log(error)
          })
      },

      updatePostComment(item, type) {
        this.dialogUpdatePostComment = true;
        if (type == "answers_on_comments") {
          this.updateTextPostComment = item.text;
          this.isDisabledUpdatePostComment = 'answers_on_comments';
          this.post_comment_data_text = item.text;
          this.post_comment_data_id = item.id
        } else {
          this.updateTextPostComment = item.text;
          this.isDisabledUpdatePostComment = 'posts_comments';
          this.post_comment_data_text = item.text;
          this.post_comment_data_id = item.id
        }
      },

      saveUpdatePostComment(index, type) {
        console.log(index, type);
        const api = `${process.env.VUE_APP_API_URL}/update_post_comment`;
        const config = {
          headers: {
            Accept: "application/json",
            Authorization: "Bearer " + localStorage.getItem("authToken"),
          },
        };

        let formData = new FormData()
        for (let i = 0; i < this.filesPostComment.length; i++) {
          formData.append('image_video_url[]', this.filesPostComment[i])
        }

        if (this.updateTextPostComment != null) {
          formData.append('text', this.updateTextPostComment);
        }

        formData.append('id', index);
        formData.append('type', type);

        axios.post(api, formData, config)
          .then((res) => {
            console.log(res.data);
            this.filesPostComment = [];
            this.dialogUpdatePostComment = false;
            this.post_comment_data_text = '';
            this.post_comment_data_id = null;
            this.updateTextPostComment = '';
            this.showDragAndDropFilePostComment = false;
          })
          .catch((error) => {
            console.log(error)
          })
      },

      reportPostComment(item, type) {
        console.log(item, type);
      },

      // add rate on comment
      rateComment(item, index, type) {
        const api = `${process.env.VUE_APP_API_URL}/add_comment_reaction`;
        const config = {
          headers: {
            Accept: "application/json",
            Authorization: "Bearer " + localStorage.getItem("authToken"),
          },
        };
        axios.post(api, {
              reaction_type: item,
              id: index,
              type: type
            },
            config
          )
          .then((res) => {
            console.log(res.data);
          })
          .catch((error) => {
            console.log(error)
          })
      },

      // remove rate post
      removeRateComment(index, type) {
        const api = `${process.env.VUE_APP_API_URL}/delete_comment_reaction`;
        const config = {
          headers: {
            Accept: "application/json",
            Authorization: "Bearer " + localStorage.getItem("authToken"),
          },
        };
        axios.post(api, {
              id: index,
              type: type
            },
            config
          )
          .then(() => {})
          .catch((error) => {
            console.log(error)
          })
      },

      show_comments(post_comment_data_id) {
        console.log(post_comment_data_id);
        var length = document.getElementsByClassName("answers_on_comments" + post_comment_data_id).length;

        if (document.getElementById("btn_answers_on_comments_arrow_up" + post_comment_data_id).classList.contains('arrow-d-block')) {
          for (var i = 0; i < length; i++) {
            document.getElementsByClassName("answers_on_comments" + post_comment_data_id)[i].style.display = 'block';
          }
          document.getElementById("btn_answers_on_comments_arrow_up" + post_comment_data_id).classList.remove('arrow-d-block');
          document.getElementById("btn_answers_on_comments_arrow_up" + post_comment_data_id).classList.add('arrow-d-none');

          document.getElementById("btn_answers_on_comments_arrow_down" + post_comment_data_id).classList.remove('arrow-d-none');
          document.getElementById("btn_answers_on_comments_arrow_down" + post_comment_data_id).classList.add('arrow-d-block');

        } else {
          for (var x = 0; x < length; x++) {
          document.getElementsByClassName("answers_on_comments" + post_comment_data_id)[x].style.display = 'none';
          }
          document.getElementById("btn_answers_on_comments_arrow_up" + post_comment_data_id).classList.remove('arrow-d-none');
          document.getElementById("btn_answers_on_comments_arrow_up" + post_comment_data_id).classList.add('arrow-d-block');

          document.getElementById("btn_answers_on_comments_arrow_down" + post_comment_data_id).classList.remove('arrow-d-block');
          document.getElementById("btn_answers_on_comments_arrow_down" + post_comment_data_id).classList.add('arrow-d-none');
        }
        // if (document.getElementsByClassName("btn_answers_on_comments_arrow_up" + post_comment_data_id)[0].style.display == 'block') {
        //   for (var i = 0; i < length; i++) {
        //     document.getElementsByClassName("answers_on_comments" + post_comment_data_id)[i].style.display = 'block';
        //   }
        //   document.getElementsByClassName("btn_answers_on_comments_arrow_down" + post_comment_data_id)[0].style.display = 'block';
        //   document.getElementsByClassName("btn_answers_on_comments_arrow_up" + post_comment_data_id)[0].style.display = 'none';
        // } else {
        //   for (var x = 0; x < length; x++) {
        //   document.getElementsByClassName("answers_on_comments" + post_comment_data_id)[x].style.display = 'none';
        //   }
        //   document.getElementsByClassName("btn_answers_on_comments_arrow_down" + post_comment_data_id)[0].style.display = 'none';
        //   document.getElementsByClassName("btn_answers_on_comments_arrow_up" + post_comment_data_id)[0].style.display = 'block';
        // }
      },

      isThereMyReaction(data) {
          return data.find( post => post.user_id === this.$root.me.id ) !== undefined;
      },

      isThereMyReactionTypeReaction(data) {
        // return data.find( post => post.user_id === this.$root.me.id ) !== undefined;
        return data.find(post => {
          if (post.user_id === this.$root.me.id) return post;
        })
      },

      isThereMyShare(data) {
          return data.find( post => post.user_id === this.$root.me.id ) !== undefined;
      },

      mouseOver: function(item) {
        this.dataAboutUserOnHover = null;
        this.isFriendOrNoOrMe_overlay = true;
        this.dataAboutUser = false;
        // if (this.dataAboutUser == false) {
        const api = `${process.env.VUE_APP_API_URL}/checkIfUserIsMyFriend/${item.id}`;
        const config = {
          headers: {
            Accept: "application/json",
            Authorization: "Bearer " + localStorage.getItem("authToken"),
          },
        };
        axios.get(api, config)
          .then(res => {
            this.dataAboutUser = true;
            this.isFriendOrNoOrMe_overlay = false;
            this.isFriendOrNoOrMe = res.data[0];
            this.userDataProfile = res.data[1][0];
          });
        // }

        this.dataAboutUserOnHover = item;
      },
    },

    watch: {
      textNewPostComment() {
        if (this.chipTextPostComment[this.focusedInputPostComment] == null && this.textNewPostComment[this.focusedInputPostComment] != null && this.textNewPostComment[this.focusedInputPostComment].length < 11) {
          this.overlayDisplayFriendsPostComment = true;
          const api = `${process.env.VUE_APP_API_URL}/getapf`;
          const config = {
            headers: {
              Accept: "application/json",
              Authorization: "Bearer " + localStorage.getItem("authToken"),
            },
          };
          axios.post(api, {
              text: this.textNewPostComment[this.focusedInputPostComment]
            }, config)
            .then(valid => {
              if (valid.data.length != 0) {
                this.displayFriendsPostComment = valid.data;
                this.showDialogFriendsPostComment[this.focusedInputPostComment] = true;
              } else {
                this.displayFriendsPostComment = [];
                this.showDialogFriendsPostComment[this.focusedInputPostComment] = false;
              }
              this.overlayDisplayFriendsPostComment = false;
            });
        }
      },
      page (value) {
        this.$root.profilePosts.getAllPosts.loadingPaginate = value > this.$root.profilePosts.getAllPosts.countPosts
      }
    },

    updated() {

    },

    created() {
      // update posts
      window.Echo.join('postdata')
        .listen('PostsData', (e) => {
          if (e.dataType == 'addNewPost') {
            //add new post on beginning of array
            this.$root.profilePosts.getAllPosts.allPosts.unshift(e.allDataPost[0]);
          }

          this.$root.profilePosts.getAllPosts.allPosts.forEach((elem, index) => {
            if (elem.id == e.allDataPost[0].id) {

              if (e.dataType == 'updatedPost') {
                this.$root.profilePosts.getAllPosts.allPosts.splice(index, 1, e.allDataPost[0]);
              }

              if (e.dataType == 'deletedPost') {
                this.$root.profilePosts.getAllPosts.allPosts.splice(index, 1);
              }

              if (e.dataType == 'updatedComment') {
                this.$root.profilePosts.getAllPosts.allPosts[index].postcommentmodel = e.allDataPost[0].postcommentmodel;
              }

              if (e.dataType == 'deletePostComment') {
                this.$root.profilePosts.getAllPosts.allPosts[index].count_comments = e.allDataPost[0].count_comments;
                this.$root.profilePosts.getAllPosts.allPosts[index].postcommentmodel = e.allDataPost[0].postcommentmodel;
              }

              if (e.dataType == 'addUpdatePostReaction' || e.dataType == 'deletePostReaction') {
                this.$root.profilePosts.getAllPosts.allPosts[index].postreactionmodel = e.allDataPost[0].postreactionmodel;
                this.$root.profilePosts.getAllPosts.allPosts[index].count_reactions = e.allDataPost[0].count_reactions;
                this.$root.profilePosts.getAllPosts.allPosts[index].like = e.allDataPost[0].like;
                this.$root.profilePosts.getAllPosts.allPosts[index].heart = e.allDataPost[0].heart;
                this.$root.profilePosts.getAllPosts.allPosts[index].funny = e.allDataPost[0].funny;
                this.$root.profilePosts.getAllPosts.allPosts[index].surprise = e.allDataPost[0].surprise;
                this.$root.profilePosts.getAllPosts.allPosts[index].sad = e.allDataPost[0].sad;
                this.$root.profilePosts.getAllPosts.allPosts[index].angry = e.allDataPost[0].angry;
              }

              if (e.dataType == 'addUpdateCommentReaction' || e.dataType == 'deleteCommentReaction') {
                this.$root.profilePosts.getAllPosts.allPosts[index].postcommentmodel = e.allDataPost[0].postcommentmodel;
              }

              if (e.dataType == 'addPostShare' || e.dataType == 'deletePostShare') {
                this.$root.profilePosts.getAllPosts.allPosts[index].postsharemodel = e.allDataPost[0].postsharemodel;
                this.$root.profilePosts.getAllPosts.allPosts[index].count_shares = e.allDataPost[0].count_shares;
              }
            }
          });

          this.$root.profilePosts.getAllPosts.allPosts.forEach((elem, index) => {
            if (elem.id == e.allDataPost[0].id) {
              if (e.dataType == 'addPostComment' || e.dataType == 'addAnswerOnComment') {
                this.$root.profilePosts.getAllPosts.allPosts[index].count_comments = e.allDataPost[0].count_comments;
                // if (e.dataType == 'addAnswerOnComment') {
                  // this.$root.profilePosts.getAllPosts.allPosts[index].postcommentmodel.forEach((elem2, index2) => {
                    // if (elem2.id == e.post_comment_id) {
                      // console.log("dnu");
                      // this.$root.profilePosts.getAllPosts.allPosts[index].postcommentmodel[index2] = e.allDataPost[0].postcommentmodel[index2];
                      this.$root.profilePosts.getAllPosts.allPosts[index].postcommentmodel = e.allDataPost[0].postcommentmodel;
                      setTimeout(function() {
                        var length = document.getElementsByClassName("answers_on_comments" + e.post_comment_id).length;
                        if (length != 0) {
                          if (document.getElementById("btn_answers_on_comments_arrow_down" + e.post_comment_id).classList.contains('arrow-d-block')) {
                            document.getElementById("btn_answers_on_comments_arrow_down" + e.post_comment_id).classList.add('arrow-d-block');
                            for (var i = 0; i < length; i++) {
                              document.getElementsByClassName("answers_on_comments" + e.post_comment_id)[i].style.display = 'block';
                            }
                          }
                        }
                      }, 200);
                    // }
                  // })
                // } else {
                //   this.$root.profilePosts.getAllPosts.allPosts[index].postcommentmodel.push(e.allDataPost[0].postcommentmodel[e.post_comment_id-1]);
                // }
              }
            }
          })
        })
    }
  }
  </script>

  <style>
  .filepond--credits {
  display: none;
  }

  .slchip1 .v-skeleton-loader__chip {
  width: 164.11px;
  margin: 4px 8px 4px 0px;
  }

  .slchip2 .v-skeleton-loader__chip {
  width: 130.59px;
  margin: 4px 8px 4px 0px;
  }

  .slchip3 .v-skeleton-loader__chip {
  width: 115.81px;
  margin: 4px 8px 4px 0px;
  }

  .text-field-comment {
  padding-left: 4px !important;
  padding-right: 4px !important;

  }

  .theme--light.card-comments {
  background-color: #f5f5f5 !important;
  }

  .theme--dark.card-comments {
  background-color: #363636 !important;
  }

  .v-list-item__content>:not(:last-child) {
  margin-bottom: 0;
  }

  .v-text-field--enclosed.v-input--dense:not(.v-text-field--solo) .v-input__prepend-inner {
  margin-top: 6px !important;
  }

  .arrow-d-none {
    display: none !important;
  }

  .arrow-d-block {
    display: block !important;
  }
  </style>
