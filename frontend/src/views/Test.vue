<template>
<v-container class="center pt-16 pb-16">
  <v-flex xs12 sm12>
    <v-layout wrap column>
      <v-flex xs12>
        <v-sheet elevation="0" class="mb-3 py-4 px-1 rounded d-flex justify-center" v-if="$root.navigationDrawerCenter.getAllPosts.loadingPosts == true">
          <v-chip-group>
            <v-skeleton-loader class="slchip1" type="chip"></v-skeleton-loader>
            <v-skeleton-loader class="slchip2" type="chip"></v-skeleton-loader>
            <v-skeleton-loader class="slchip3" type="chip"></v-skeleton-loader>
          </v-chip-group>
        </v-sheet>
        <v-sheet elevation="0" class="mb-3 py-4 px-1 rounded d-flex justify-center" v-else>
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
                    <file-pond allowImageCrop={true} allowFileEncode={true} class="filepond" name="filepond" ref="pond" type="input" :files="files" v-on:removefile="handleFileRemoved" v-on:addfile="handleFileAdded" v-on:init="handleFilePondInit"
                      style="width:100%" label-idle="Sem presuňte súbory..." allow-multiple="true" max-files="10" />
                  </v-row>
                </v-card-text>

                <v-divider class="mt-0 mb-0"></v-divider>

                <v-row class="m-0">
                  <v-col cols="1">
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
                  <v-col cols="1">
                    <v-tooltip top>
                      <template v-slot:activator="{ on }">
                        <v-btn v-on="on" icon x-large color="primary" @click="showDragAndDropFile = !showDragAndDropFile">
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

  <v-overlay class="pt-16" style="margin-top:60vh" :value="$root.navigationDrawerCenter.getAllPosts.loadingPosts" :absolute="true" :opacity="0">
    <v-progress-circular indeterminate size="24" color="primary"></v-progress-circular>
  </v-overlay>
  <!-- /////////////////////////////////////////////////////////////////////post for-cycle///////////////////////////////////////////////////////////////////////////// -->
  <template v-for="(post, post_index) in $root.navigationDrawerCenter.getAllPosts.posts">
    <v-flex class="mb-3" xs12 sm12 :key="post_index">
      <v-card class="mb-3 rounded" elevation="0">
        <v-list-item>
          <v-list-item-avatar>
            <v-avatar color="primary" size="40" v-if="post.avatar == null">
              <span v-if="post.name != null" class="text-uppercase white--text">{{ post.name.charAt(0) }}</span>
            </v-avatar>
            <v-avatar color="primary" size="40" v-else>
              <v-img :lazy-src="`http://127.0.0.1:8000/storage/user-avatar/${post.avatar}`" :src="`http://127.0.0.1:8000/storage/user-avatar/${post.avatar}`" />
            </v-avatar>
          </v-list-item-avatar>

          <v-list-item-content>
            <v-list-item-title class="d-flex justify-start">
              <v-tooltip bottom>
                <template v-slot:activator="{ on: tooltip }">
                  <v-chip v-on="{ ...tooltip}" small>
                    <span>
                      <v-icon left class="mr-1" small>mdi-account-circle-outline</v-icon>{{post.name}}
                    </span>
                  </v-chip>
                </template>
                <span v-if="post.name == $root.me.name">
                  <v-icon left class="mr-1" small color="white">mdi-check-circle</v-icon>Ty
                </span>
                <span v-else>
                  <v-icon left class="mr-1" small color="white">mdi-check-circle</v-icon>Priateľ
                </span>
              </v-tooltip>

            </v-list-item-title>
            <v-list-item-subtitle class="d-flex justify-start primary--text">
              <v-tooltip bottom>
                <template v-slot:activator="{ on: tooltip }">
                  <v-chip v-on="{ ...tooltip}" small color="primary">
                    <span>
                      <v-icon left class="mr-1" small>mdi-clock-outline</v-icon>{{new Date(Date.parse(post.created_at)).toLocaleString('default',{day: 'numeric', month: 'long', year: 'numeric'})}}
                    </span>
                  </v-chip>
                  <!-- <span v-on="{ ...tooltip}">
                    <v-icon class="mr-1" small color="primary">mdi-clock-outline</v-icon>
                    {{new Date(Date.parse(post.created_at)).toLocaleString('default',{day: 'numeric', month: 'long', year: 'numeric'})}}
                  </span> -->
                </template>
                <span>
                  <v-icon left class="mr-1" small color="white">mdi-clock-outline</v-icon>{{post.created_at}}
                </span>
              </v-tooltip>
            </v-list-item-subtitle>

            <!-- <v-list-item-subtitle class="d-flex justify-start primary--text" v-if="post.name == username">
              <v-icon class="mr-1" small color="primary">mdi-check-circle</v-icon>Ty
            </v-list-item-subtitle>
            <v-list-item-subtitle class="d-flex justify-start primary--text" v-else>
              <v-icon class="mr-1" small color="primary">mdi-check-circle</v-icon>Priateľ
            </v-list-item-subtitle> -->
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
              <v-list>
                <v-list-item-group color="primary" v-if=" post.name == $root.me.name">
                  <v-list-item>
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

                  <v-list-item @click="deletePost(post)">
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
            </v-menu>
          </v-list-item-action>
        </v-list-item>

        <v-divider class="mt-0 mb-0"></v-divider>

        <v-list-item class="p-0" v-if="post.image_url != null">
          <v-list-item-content class="p-0">
            <!-- <v-skeleton-loader type="image"></v-skeleton-loader> -->
            <v-img :lazy-src="`http://127.0.0.1:8000/storage/post_img_video/${post.image_url}`" :src="`http://127.0.0.1:8000/storage/post_img_video/${post.image_url}`" />
          </v-list-item-content>
        </v-list-item>

        <v-list-item class="p-0" v-if="post.video_url != null">
          <v-list-item-content class="p-0">
            <vue-plyr>
              <video controls playsinline>
                <!-- crossorigin -->
                <!-- data-poster="`http://127.0.0.1:8000/storage/post_img_video/${post.video_url}`" -->
                <!-- <source size="576" src="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-576p.mp4" type="video/mp4">
                  <source size="720" src="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-720p.mp4" type="video/mp4">
                  <source size="1080" src="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-1080p.mp4" type="video/mp4"> -->
                <source size="1080" :src="'http://127.0.0.1:8000/storage/post_img_video/' +  post.video_url" type="video/mp4">
                <!-- src="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-1080p.mp4" -->
                <!-- <track default="" kind="captions" label="English captions" src="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-HD.en.vtt" srclang="en">
                  <track kind="captions" label="Légendes en français" src="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-HD.fr.vtt" srclang="fr"> -->
              </video>
            </vue-plyr>
            <!-- <v-img :lazy-src="`http://127.0.0.1:8000/storage/post_img_video/${post.video_url}`" :src="`http://127.0.0.1:8000/storage/post_img_video/${post.video_url}`" /> -->
          </v-list-item-content>
        </v-list-item>

        <v-list-item>
          <v-list-item-content>
            {{post.text}}
          </v-list-item-content>
        </v-list-item>

        <v-divider class="mt-0 mb-0"></v-divider>
        <!-- reactions -->
        <v-list-item>
          <v-list-item-content>
            <v-flex class="text-left">
              <v-menu open-on-hover bottom offset-y v-if="post.like != 0">
                <template v-slot:activator="{ on, attrs }">
                  <v-avatar class="mr-1" size="20" v-bind="attrs" v-on="on">
                    <v-img lazy-src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/thumbs-up-sign_1f44d.png"
                      src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/thumbs-up-sign_1f44d.png" />
                  </v-avatar>
                </template>

                <v-list class="p-2">
                  <v-list-item-title class="font-weight-bold primary--text">Páči sa mi to</v-list-item-title>
                  <template v-for="(item, index) in $root.navigationDrawerCenter.getAllPostsReactions.posts_reactions">
                    <v-list-item style="min-height: 20px" v-if="item.reaction_type == 'like' && post.id == item.post_id" :key="'menuLike'+index">
                      <v-list-item-subtitle class="m-0">{{ item.name }}</v-list-item-subtitle>
                    </v-list-item>
                  </template>
                </v-list>
              </v-menu>

              <v-menu open-on-hover bottom offset-y v-if="post.heart != 0">
                <template v-slot:activator="{ on, attrs }">
                  <v-avatar class="mr-1" size="20" v-bind="attrs" v-on="on">
                    <v-img lazy-src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/heavy-black-heart_2764.png"
                      src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/heavy-black-heart_2764.png" />
                  </v-avatar>
                </template>

                <v-list class="p-2">
                  <v-list-item-title class="font-weight-bold primary--text">Super</v-list-item-title>
                  <template v-for="(item, index) in $root.navigationDrawerCenter.getAllPostsReactions.posts_reactions">
                    <v-list-item style="min-height: 20px" v-if="item.reaction_type == 'heart' && post.id == item.post_id" :key="'menuHeart'+index">
                      <v-list-item-subtitle class="m-0">{{ item.name }}</v-list-item-subtitle>
                    </v-list-item>
                  </template>
                </v-list>
              </v-menu>

              <v-menu open-on-hover bottom offset-y v-if="post.funny != 0">
                <template v-slot:activator="{ on, attrs }">
                  <v-avatar class="mr-1" size="20" v-bind="attrs" v-on="on">
                    <v-img lazy-src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/smiling-face-with-open-mouth-and-smiling-eyes_1f604.png"
                      src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/smiling-face-with-open-mouth-and-smiling-eyes_1f604.png" />
                  </v-avatar>
                </template>

                <v-list class="p-2">
                  <v-list-item-title class="font-weight-bold primary--text">Haha</v-list-item-title>
                  <template v-for="(item, index) in $root.navigationDrawerCenter.getAllPostsReactions.posts_reactions">
                    <v-list-item style="min-height: 20px" v-if="item.reaction_type == 'funny' && post.id == item.post_id" :key="'menuFunny'+index">
                      <v-list-item-subtitle class="m-0">{{ item.name }}</v-list-item-subtitle>
                    </v-list-item>
                  </template>
                </v-list>
              </v-menu>

              <v-menu open-on-hover bottom offset-y v-if="post.surprise != 0">
                <template v-slot:activator="{ on, attrs }">
                  <v-avatar class="mr-1" size="20" v-bind="attrs" v-on="on">
                    <v-img lazy-src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/face-with-open-mouth_1f62e.png"
                      src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/face-with-open-mouth_1f62e.png" />
                  </v-avatar>
                </template>

                <v-list class="p-2">
                  <v-list-item-title class="font-weight-bold primary--text">Žasnem</v-list-item-title>
                  <template v-for="(item, index) in $root.navigationDrawerCenter.getAllPostsReactions.posts_reactions">
                    <v-list-item style="min-height: 20px" v-if="item.reaction_type == 'surprise' && post.id == item.post_id" :key="'menuSurprise'+index">
                      <v-list-item-subtitle class="m-0">{{ item.name }}</v-list-item-subtitle>
                    </v-list-item>
                  </template>
                </v-list>
              </v-menu>

              <v-menu open-on-hover bottom offset-y v-if="post.sad != 0">
                <template v-slot:activator="{ on, attrs }">
                  <v-avatar class="mr-1" size="20" v-bind="attrs" v-on="on">
                    <v-img lazy-src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/disappointed-but-relieved-face_1f625.png"
                      src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/disappointed-but-relieved-face_1f625.png" />
                  </v-avatar>
                </template>

                <v-list class="p-2">
                  <v-list-item-title class="font-weight-bold primary--text">Je mi to ľúto</v-list-item-title>
                  <template v-for="(item, index) in $root.navigationDrawerCenter.getAllPostsReactions.posts_reactions">
                    <v-list-item style="min-height: 20px" v-if="item.reaction_type == 'sad' && post.id == item.post_id" :key="'menuSad'+index">
                      <v-list-item-subtitle class="m-0">{{ item.name }}</v-list-item-subtitle>
                    </v-list-item>
                  </template>
                </v-list>
              </v-menu>

              <v-menu open-on-hover bottom offset-y v-if="post.angry != 0">
                <template v-slot:activator="{ on, attrs }">
                  <v-avatar class="mr-1" size="20" v-bind="attrs" v-on="on">
                    <v-img lazy-src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/pouting-face_1f621.png"
                      src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/pouting-face_1f621.png" />
                  </v-avatar>
                </template>

                <v-list class="p-2">
                  <v-list-item-title class="font-weight-bold primary--text">Štve ma to</v-list-item-title>
                  <template v-for="(item, index) in $root.navigationDrawerCenter.getAllPostsReactions.posts_reactions">
                    <v-list-item style="min-height: 20px" v-if="item.reaction_type == 'angry' && post.id == item.post_id" :key="'menuAngry'+index">
                      <v-list-item-subtitle class="m-0">{{ item.name }}</v-list-item-subtitle>
                    </v-list-item>
                  </template>
                </v-list>
              </v-menu>

              <v-btn small text class="pl-2 pr-2" disabled v-if="post.count_reactions == 0">
                {{post.count_reactions}} hodnotení
              </v-btn>
              <v-dialog max-width="600" :retain-focus="false" v-if="post.count_reactions != 0">
                <template v-slot:activator="{ on, attrs }">
                  <v-btn small text outlined class="hidden-sm-and-down primary--color rounded-pill" v-bind="attrs" v-on="on">
                    <div class="primary--color">{{post.count_reactions}}</div>
                  </v-btn>
                  <v-btn small text outlined class="hidden-md-and-up primary--color rounded-pill" :min-width="40" v-bind="attrs" v-on="on">
                    {{post.count_reactions}}
                  </v-btn>
                </template>

                <template v-slot:default="dialogReactionsPost">
                  <v-card>
                    <v-toolbar color="primary" dark>
                      <!-- v-if="selectedDialogReactionsPost == null" -->
                      <v-tabs v-model="tabReactionsPost[post.id]" background-color="primary white--text" dark :show-arrows="true">
                        <v-tab>
                          <span>Všetko</span>
                          <span class="ml-1">{{post.count_reactions}}</span>
                        </v-tab>

                        <v-tab class="h-100" v-if="post.like != 0">
                          <v-img max-height="20" max-width="20" lazy-src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/thumbs-up-sign_1f44d.png"
                            src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/thumbs-up-sign_1f44d.png" />
                          <span class="ml-1">{{post.like}}</span>
                        </v-tab>

                        <v-tab class="h-100" v-if="post.heart != 0">
                          <v-img max-height="20" max-width="20" lazy-src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/heavy-black-heart_2764.png"
                            src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/heavy-black-heart_2764.png" />
                          <span class="ml-1">{{post.heart}}</span>
                        </v-tab>

                        <v-tab class="h-100" v-if="post.funny != 0">
                          <v-img max-height="20" max-width="20" lazy-src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/smiling-face-with-open-mouth-and-smiling-eyes_1f604.png"
                            src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/smiling-face-with-open-mouth-and-smiling-eyes_1f604.png" />
                          <span class="ml-1">{{post.funny}}</span>
                        </v-tab>

                        <v-tab class="h-100" v-if="post.surprise != 0">
                          <v-img max-height="20" max-width="20" lazy-src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/face-with-open-mouth_1f62e.png"
                            src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/face-with-open-mouth_1f62e.png" />
                          <span class="ml-1">{{post.surprise}}</span>
                        </v-tab>

                        <v-tab class="h-100" v-if="post.sad != 0">
                          <v-img max-height="20" max-width="20" lazy-src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/disappointed-but-relieved-face_1f625.png"
                            src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/disappointed-but-relieved-face_1f625.png" />
                          <span class="ml-1">{{post.sad}}</span>
                        </v-tab>

                        <v-tab class="h-100" v-if="post.angry != 0">
                          <v-img max-height="20" max-width="20" lazy-src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/pouting-face_1f621.png"
                            src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/pouting-face_1f621.png" />
                          <span class="ml-1">{{post.angry}}</span>
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

                    <v-tabs-items v-model="tabReactionsPost[post.id]">
                      <v-tab-item>
                        <v-card flat>
                          <v-card-text class="p-0">
                            <v-list-item-group v-model="reactionsPostAll" color="primary" v-for="(item, index) in $root.navigationDrawerCenter.getAllPostsReactions.posts_reactions" :key="'all'+index">
                              <v-list-item v-if="post.id == item.post_id">
                                <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                  <v-avatar color="primary" size="40" v-if="item.avatar == null">
                                    <span class="text-uppercase white--text">{{ item.name.charAt(0) }}</span>
                                  </v-avatar>
                                  <v-avatar color="primary" size="40" v-else>
                                    <v-img :lazy-src="`http://127.0.0.1:8000/storage/user-avatar/${item.avatar}`" :src="`http://127.0.0.1:8000/storage/user-avatar/${item.avatar}`" />
                                  </v-avatar>
                                </v-badge>
                                <v-list-item-content>
                                  <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.name"></v-list-item-title>
                                  <v-list-item-subtitle class="d-flex justify-start questrial caption grey--text" v-text="item.text"></v-list-item-subtitle>
                                  <v-list-item-subtitle class="d-flex justify-start font-weight-bold primary--text">
                                    <v-icon v-if="item.text2" small color="primary">mdi-check-circle</v-icon> {{item.text2}}
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
                      </v-tab-item>

                      <v-tab-item v-if="post.like != 0">
                        <v-card flat>
                          <v-card-text class="p-0">
                            <v-list-item-group v-model="reactionsPostLike" color="primary" v-for="(item, index) in $root.navigationDrawerCenter.getAllPostsReactions.posts_reactions" :key="'like'+index">
                              <v-list-item v-if="item.reaction_type == 'like' && post.id == item.post_id">
                                <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                  <v-avatar color="primary" size="40" v-if="item.avatar == null">
                                    <span class="text-uppercase white--text">{{ item.name.charAt(0) }}</span>
                                  </v-avatar>
                                  <v-avatar color="primary" size="40" v-else>
                                    <v-img :lazy-src="`http://127.0.0.1:8000/storage/user-avatar/${item.avatar}`" :src="`http://127.0.0.1:8000/storage/user-avatar/${item.avatar}`" />
                                  </v-avatar>
                                </v-badge>
                                <v-list-item-content>
                                  <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.name"></v-list-item-title>
                                  <v-list-item-subtitle class="d-flex justify-start questrial caption grey--text" v-text="item.text"></v-list-item-subtitle>
                                  <v-list-item-subtitle class="d-flex justify-start font-weight-bold primary--text">
                                    <v-icon v-if="item.text2" small color="primary">mdi-check-circle</v-icon> {{item.text2}}
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
                      </v-tab-item>

                      <v-tab-item v-if="post.heart != 0">
                        <v-card flat>
                          <v-card-text class="p-0">
                            <v-list-item-group v-model="reactionsPostHeart" color="primary" v-for="(item, index) in $root.navigationDrawerCenter.getAllPostsReactions.posts_reactions" :key="'heart'+index">
                              <v-list-item v-if="item.reaction_type == 'heart' && post.id == item.post_id">
                                <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                  <v-avatar color="primary" size="40" v-if="item.avatar == null">
                                    <span class="text-uppercase white--text">{{ item.name.charAt(0) }}</span>
                                  </v-avatar>
                                  <v-avatar color="primary" size="40" v-else>
                                    <v-img :lazy-src="`http://127.0.0.1:8000/storage/user-avatar/${item.avatar}`" :src="`http://127.0.0.1:8000/storage/user-avatar/${item.avatar}`" />
                                  </v-avatar>
                                </v-badge>
                                <v-list-item-content>
                                  <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.name"></v-list-item-title>
                                  <v-list-item-subtitle class="d-flex justify-start questrial caption grey--text" v-text="item.text"></v-list-item-subtitle>
                                  <!-- <v-list-item-subtitle class="d-flex justify-start font-weight-bold primary--text">
                                      <v-icon v-if="item.text2" small color="primary">mdi-check-circle</v-icon> {{item.text2}}
                                    </v-list-item-subtitle> -->
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

                      <v-tab-item v-if="post.funny != 0">
                        <v-card flat>
                          <v-card-text class="p-0">
                            <v-list-item-group v-model="reactionsPostFunny" color="primary" v-for="(item, index) in $root.navigationDrawerCenter.getAllPostsReactions.posts_reactions" :key="'funny'+index">
                              <v-list-item v-if="item.reaction_type == 'funny' && post.id == item.post_id">
                                <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                  <v-avatar color="primary" size="40" v-if="item.avatar == null">
                                    <span class="text-uppercase white--text">{{ item.name.charAt(0) }}</span>
                                  </v-avatar>
                                  <v-avatar color="primary" size="40" v-else>
                                    <v-img :lazy-src="`http://127.0.0.1:8000/storage/user-avatar/${item.avatar}`" :src="`http://127.0.0.1:8000/storage/user-avatar/${item.avatar}`" />
                                  </v-avatar>
                                </v-badge>
                                <v-list-item-content>
                                  <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.name"></v-list-item-title>
                                  <v-list-item-subtitle class="d-flex justify-start questrial caption grey--text" v-text="item.text"></v-list-item-subtitle>
                                  <v-list-item-subtitle class="d-flex justify-start font-weight-bold primary--text">
                                    <v-icon v-if="item.text2" small color="primary">mdi-check-circle</v-icon> {{item.text2}}
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
                      </v-tab-item>

                      <v-tab-item v-if="post.surprise != 0">
                        <v-card flat>
                          <v-card-text class="p-0">
                            <v-list-item-group v-model="reactionsPostSurprise" color="primary" v-for="(item, index) in $root.navigationDrawerCenter.getAllPostsReactions.posts_reactions" :key="'surprise'+index">
                              <v-list-item v-if="item.reaction_type == 'surprise' && post.id == item.post_id">
                                <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                  <v-avatar color="primary" size="40" v-if="item.avatar == null">
                                    <span class="text-uppercase white--text">{{ item.name.charAt(0) }}</span>
                                  </v-avatar>
                                  <v-avatar color="primary" size="40" v-else>
                                    <v-img :lazy-src="`http://127.0.0.1:8000/storage/user-avatar/${item.avatar}`" :src="`http://127.0.0.1:8000/storage/user-avatar/${item.avatar}`" />
                                  </v-avatar>
                                </v-badge>
                                <v-list-item-content>
                                  <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.name"></v-list-item-title>
                                  <v-list-item-subtitle class="d-flex justify-start questrial caption grey--text" v-text="item.text"></v-list-item-subtitle>
                                  <v-list-item-subtitle class="d-flex justify-start font-weight-bold primary--text">
                                    <v-icon v-if="item.text2" small color="primary">mdi-check-circle</v-icon> {{item.text2}}
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
                      </v-tab-item>

                      <v-tab-item v-if="post.sad != 0">
                        <v-card flat>
                          <v-card-text class="p-0">
                            <v-list-item-group v-model="reactionsPostSad" color="primary" v-for="(item, index) in $root.navigationDrawerCenter.getAllPostsReactions.posts_reactions" :key="'sad'+index">
                              <v-list-item v-if="item.reaction_type == 'sad' && post.id == item.post_id">
                                <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                  <v-avatar color="primary" size="40" v-if="item.avatar == null">
                                    <span class="text-uppercase white--text">{{ item.name.charAt(0) }}</span>
                                  </v-avatar>
                                  <v-avatar color="primary" size="40" v-else>
                                    <v-img :lazy-src="`http://127.0.0.1:8000/storage/user-avatar/${item.avatar}`" :src="`http://127.0.0.1:8000/storage/user-avatar/${item.avatar}`" />
                                  </v-avatar>
                                </v-badge>
                                <v-list-item-content>
                                  <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.name"></v-list-item-title>
                                  <v-list-item-subtitle class="d-flex justify-start questrial caption grey--text" v-text="item.text"></v-list-item-subtitle>
                                  <v-list-item-subtitle class="d-flex justify-start font-weight-bold primary--text">
                                    <v-icon v-if="item.text2" small color="primary">mdi-check-circle</v-icon> {{item.text2}}
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
                      </v-tab-item>

                      <v-tab-item v-if="post.angry != 0">
                        <v-card flat>
                          <v-card-text class="p-0">
                            <v-list-item-group v-model="reactionsPostAngry" color="primary" v-for="(item, index) in $root.navigationDrawerCenter.getAllPostsReactions.posts_reactions" :key="'angry'+index">
                              <v-list-item v-if="item.reaction_type == 'angry' && post.id == item.post_id">
                                <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                  <v-avatar color="primary" size="40" v-if="item.avatar == null">
                                    <span class="text-uppercase white--text">{{ item.name.charAt(0) }}</span>
                                  </v-avatar>
                                  <v-avatar color="primary" size="40" v-else>
                                    <v-img :lazy-src="`http://127.0.0.1:8000/storage/user-avatar/${item.avatar}`" :src="`http://127.0.0.1:8000/storage/user-avatar/${item.avatar}`" />
                                  </v-avatar>
                                </v-badge>
                                <v-list-item-content>
                                  <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.name"></v-list-item-title>
                                  <v-list-item-subtitle class="d-flex justify-start questrial caption grey--text" v-text="item.text"></v-list-item-subtitle>
                                  <v-list-item-subtitle class="d-flex justify-start font-weight-bold primary--text">
                                    <v-icon v-if="item.text2" small color="primary">mdi-check-circle</v-icon> {{item.text2}}
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
                      </v-tab-item>
                    </v-tabs-items>

                    <!-- <v-divider class="mt-0 mb-0"></v-divider>

                    <v-card-actions class="justify-end">
                      <v-btn text outlined class="primary white--text" @click="dialogReactionsPost.value = false">Zavrieť</v-btn>
                    </v-card-actions> -->
                  </v-card>
                </template>
              </v-dialog>
            </v-flex>


            <!-- oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo -->

            <v-flex class="text-right">
              <v-layout wrap justify-end>
                <div class="font-weight-bold">
                  <v-btn text class="pl-2 pr-2" disabled v-if="post.count_comments == 0">
                    {{post.count_comments}} Komentáre
                    <v-icon class="ml-2" small>mdi-chevron-up</v-icon>
                  </v-btn>

                  <v-btn text @click="handleSelected(post.id)" :key="post.id" class="pl-2 pr-2" v-else>
                    <!-- @click="showPostDetails = !showPostDetails"  -->
                    <span v-if="post.count_comments == 1">{{post.count_comments}} Komentár</span>
                    <span v-else>{{post.count_comments}} Komentáre</span>
                    <v-icon class="ml-2" style="display:none" :id="`showPostDetailsArrowDown${post.id}`" small>mdi-chevron-down</v-icon>
                    <v-icon class="ml-2" style="display:block" :id="`showPostDetailsArrowUp${post.id}`" small>mdi-chevron-up</v-icon>
                  </v-btn>

                  <v-dialog v-model="dialogPostShares[post.id]" max-width="600" :retain-focus="false">
                    <template v-slot:activator="{ on, attrs }">
                      <v-btn text class="pl-2 pr-2" v-bind="attrs" v-on="on" disabled v-if="post.count_shares == 0">
                        {{post.count_shares}} zdieľaní
                      </v-btn>
                      <v-btn text class="pl-2 pr-2" v-bind="attrs" v-on="on" v-else>
                        <span v-if="post.count_shares == 1">{{post.count_shares}} zdieľanie</span>
                        <span v-else-if="post.count_shares > 5">{{post.count_shares}} zdieľania</span>
                        <span v-else>{{post.count_shares}} zdieľaní</span>
                      </v-btn>
                    </template>

                    <v-card>
                      <v-card-title class="primary">
                        Zdieľania
                        <v-spacer />
                        <v-btn icon dark @click="dialogPostShares[post.id] = false">
                          <v-icon>mdi-close</v-icon>
                        </v-btn>
                      </v-card-title>

                      <v-card-text class="pt-3">
                        <v-list-item-group color="primary" v-for="(item, index) in $root.navigationDrawerCenter.getAllPostsShares.posts_shares" :key="'shares'+index">
                          <!-- v-model="selectedDialogReactionsPost"  -->
                          <v-list-item v-if="post.id == item.post_id">
                            <v-list-item-avatar>
                              <v-avatar color="primary" size="40" v-if="item.avatar == null">
                                <span v-if="item.name != null" class="text-uppercase white--text">{{ item.name.charAt(0) }}</span>
                              </v-avatar>
                              <v-avatar color="primary" size="40" v-else>
                                <!-- stareeee <v-img :lazy-src="`http://127.0.0.1:8000/storage/user-avatar/${post.avatar}`" :src="`http://127.0.0.1:8000/storage/user-avatar/${item.avatar}`" /> -->
                                <v-img :lazy-src="`http://127.0.0.1:8000/storage/user-avatar/${item.avatar}`" :src="`http://127.0.0.1:8000/storage/user-avatar/${item.avatar}`" />
                              </v-avatar>
                            </v-list-item-avatar>

                            <v-list-item-content>
                              <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.name"></v-list-item-title>
                              <v-tooltip bottom>
                                <template v-slot:activator="{ on }">
                                  <v-list-item-subtitle v-on="on" class="d-flex justify-start primary--text" v-if="Math.round((new Date().getTime() - new Date(item.created_at).getTime()) / 60000) < 60">
                                    <v-icon class="mr-1" small color="primary">mdi-clock</v-icon>{{Math.round((new Date().getTime() - new Date(item.created_at).getTime()) / 60000)}}
                                  </v-list-item-subtitle>
                                  <v-list-item-subtitle v-on="on" class="d-flex justify-start primary--text" v-else>
                                    <v-icon class="mr-1" small color="primary">mdi-clock</v-icon>{{Math.floor(Math.round((new Date().getTime() - new Date(item.created_at).getTime()) / 60000)/60)}} h
                                  </v-list-item-subtitle>
                                </template>
                                <span>{{item.created_at.toLocaleString()}}</span>
                              </v-tooltip>

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

        <!-- post function like, comment, share -->
        <v-list-item>
          <v-list-item-content class="text-field-comment p-0">
            <v-card elevation="0">
              <v-card-text class="pl-0 pr-0 hidden-sm-and-down">
                <v-menu open-on-hover top offset-y min-width="200px" rounded="pill">
                  <template v-slot:activator="{ on }">
                    <v-btn text rounded outlined v-on="on">
                      <v-icon class="mr-1 primary--text" small>mdi-thumb-up</v-icon> Páči sa mi to
                    </v-btn>
                  </template>

                  <v-card class="rounded-pill p-2" elevation="2">
                    <v-btn icon @click="rate('like', post.id)">
                      <v-avatar size="20">
                        <v-img src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/thumbs-up-sign_1f44d.png">
                        </v-img>
                      </v-avatar>
                    </v-btn>
                    <v-btn icon @click="rate('heart', post.id)">
                      <v-avatar size="20">
                        <v-img src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/heavy-black-heart_2764.png">
                        </v-img>
                      </v-avatar>
                    </v-btn>
                    <v-btn icon @click="rate('funny', post.id)">
                      <v-avatar size="20">
                        <v-img src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/smiling-face-with-open-mouth-and-smiling-eyes_1f604.png">
                        </v-img>
                      </v-avatar>
                    </v-btn>

                    <v-btn icon @click="rate('surprise', post.id)">
                      <v-avatar size="20">
                        <v-img src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/face-with-open-mouth_1f62e.png">
                        </v-img>
                      </v-avatar>
                    </v-btn>

                    <v-btn icon @click="rate('sad', post.id)">
                      <v-avatar size="20">
                        <v-img src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/disappointed-but-relieved-face_1f625.png">
                        </v-img>
                      </v-avatar>
                    </v-btn>

                    <v-btn icon @click="rate('angry', post.id)">
                      <v-avatar size="20">
                        <v-img src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/pouting-face_1f621.png">
                        </v-img>
                      </v-avatar>
                    </v-btn>
                  </v-card>
                </v-menu>

                <v-btn class="mr-3 ml-3" text rounded outlined @click="focusInput(post.id)">
                  <v-icon class="mr-1 primary--text" small>mdi-comment-processing</v-icon> Komentovať
                </v-btn>

                <!-- <v-btn text rounded color="primary" outlined @click="share(post.id)" v-if="user_posts_shares.length != 0 && user_posts_shares[post.id-1] != null && user_posts_shares[post.id-1].post_id == post.id">
                    <v-icon class=" mr-1 primary--text" small>mdi-share</v-icon> Zdieľané
                  </v-btn> -->
                <v-btn text rounded outlined @click="share(post.id)">
                  <v-icon class="mr-1 primary--text" small>mdi-share</v-icon> Zdieľať
                </v-btn>
              </v-card-text>
              <v-card-text class="pl-0 pr-0 hidden-md-and-up">
                <v-menu open-on-hover top offset-y min-width="200px" rounded="pill">
                  <template v-slot:activator="{ on }">
                    <v-btn text rounded outlined v-on="on">
                      <v-icon class="mr-1 primary--text" small>mdi-thumb-up</v-icon>
                    </v-btn>
                  </template>

                  <v-card class="rounded-pill p-2" elevation="2">
                    <v-btn icon @click="rate('like', post.id)">
                      <v-avatar size="20">
                        <v-img src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/thumbs-up-sign_1f44d.png">
                        </v-img>
                      </v-avatar>
                    </v-btn>
                    <v-btn icon @click="rate('heart', post.id)">
                      <v-avatar size="20">
                        <v-img src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/heavy-black-heart_2764.png">
                        </v-img>
                      </v-avatar>
                    </v-btn>
                    <v-btn icon @click="rate('funny', post.id)">
                      <v-avatar size="20">
                        <v-img src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/smiling-face-with-open-mouth-and-smiling-eyes_1f604.png">
                        </v-img>
                      </v-avatar>
                    </v-btn>

                    <v-btn icon @click="rate('surprise', post.id)">
                      <v-avatar size="20">
                        <v-img src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/face-with-open-mouth_1f62e.png">
                        </v-img>
                      </v-avatar>
                    </v-btn>

                    <v-btn icon @click="rate('sad', post.id)">
                      <v-avatar size="20">
                        <v-img src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/disappointed-but-relieved-face_1f625.png">
                        </v-img>
                      </v-avatar>
                    </v-btn>

                    <v-btn icon @click="rate('angry', post.id)">
                      <v-avatar size="20">
                        <v-img src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/pouting-face_1f621.png">
                        </v-img>
                      </v-avatar>
                    </v-btn>
                  </v-card>
                </v-menu>

                <v-btn class="mr-3 ml-3" text rounded outlined @click="focusInput(post.id)">
                  <v-icon class="mr-1 primary--text" small>mdi-comment-processing</v-icon>
                </v-btn>

                <!-- <v-btn text rounded color="primary" outlined @click="share(post.id)" v-if="user_posts_shares.length != 0 && user_posts_shares[post.id-1] != null && user_posts_shares[post.id-1].post_id == post.id">
                    <v-icon class=" mr-1 primary--text" small>mdi-share</v-icon> Zdieľané
                  </v-btn> -->
                <v-btn text rounded outlined @click="share(post.id)">
                  <v-icon class="mr-1 primary--text" small>mdi-share</v-icon>
                </v-btn>
              </v-card-text>
            </v-card>
          </v-list-item-content>
        </v-list-item>

        <!-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
        <!-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

        <v-container style="display:none" :id="`showPostDetails${post.id}`" class="p-0">
          <!-- v-model="showPostDetails[post.id]"   -->
          <!--  v-if="showPostDetails"  -->
          <v-divider class="mt-0 mb-0"></v-divider>

          <v-list-item>
            <v-list-item-avatar>
              <v-avatar color="primary" size="40" v-if="post.avatar == null">
                <span v-if="post.name != null" class="text-uppercase white--text">{{ post.name.charAt(0) }}</span>
              </v-avatar>
              <v-avatar color="primary" size="40" v-else>
                <v-img :lazy-src="`http://127.0.0.1:8000/storage/user-avatar/${post.avatar}`" :src="`http://127.0.0.1:8000/storage/user-avatar/${post.avatar}`" />
              </v-avatar>
            </v-list-item-avatar>
            <v-list-item-content class="text-field-comment">
              <!-- comment post -->
              <!-- <template v-slot:activator="{ on }"> -->
              <v-text-field @focus="focusedPostComment(post.id)" :hide-details="true" :ref="`commentInput${post.id}`" filled rounded dense clearable clear-icon="mdi-close" label="Koment" v-model="textNewPostComment[post.id]"
                @click:clear="clearPostComment(post.id)" @keydown.enter="comment(post.id)">
                <template class="mt-0" v-slot:prepend-inner v-if="chipTextPostComment[post.id] != undefined">
                  <v-chip class="ma-1" close @click:close="closeChipPostComment(post.id)">{{chipTextPostComment[post.id]}}</v-chip>
                </template>
                <template slot="append">
                  <v-menu :close-on-content-click="false" v-model="autoselectMenuPost[post.id]" offset-y :position-y="60">
                    <template v-slot:activator="{ on, attrs }">
                      <v-icon class="mr-2" v-bind="attrs" v-on="on" @click="setEmojiID(post.id)">
                        mdi-emoticon
                      </v-icon>
                    </template>

                    <picker :showSearch="false" :showPreview="false" :set="'messenger'" :showSkinTones="true" :emojiTooltip="true" :infiniteScroll="false" @select="selectEmoji" :i18n="i18n" />
                  </v-menu>
                  <v-menu class="menu-append-icon" offset-y v-model="autoselectMenu2Post[post.id]" rounded="pill">
                    <template v-slot:activator="{ on, attrs }">
                      <v-icon v-bind="attrs" v-on="on">
                        mdi-dots-grid
                      </v-icon>
                    </template>
                    <v-card class="rounded-pill p-2" elevation="2">
                      <v-btn icon class="mr-2">
                        <v-icon>
                          mdi-camera
                        </v-icon>
                      </v-btn>
                      <v-btn icon class="mr-2">
                        <v-icon>
                          mdi-image-area
                        </v-icon>
                      </v-btn>
                      <v-btn icon>
                        <v-icon>
                          mdi-paperclip
                        </v-icon>
                      </v-btn>
                    </v-card>
                  </v-menu>
                </template>
              </v-text-field>

              <!-- <v-card v-model="showDialogFriendsPostComment[post.id]" class="position-absolute" min-width="250" style="top:70px; left:9%;z-index:2"
                v-if="showDialogFriendsPostComment[post.id] != false && showDialogFriendsPostComment[post.id] != undefined">
                <v-list class="p-0" dense>
                  <v-list-item-group color="primary" v-model="selectUserFriendsPostComment">
                    <v-list-item-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-2 pt-1">
                      <span class="pt-3">Vybrať priateľa</span>
                      <v-spacer />
                      <v-btn icon fab x-small color="error" @click="showDialogFriendsPostComment[post.id] == !showDialogFriendsPostComment[post.id]">
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
                      <v-list-item :key="item.id" @click="selectDataAboutUserPostComment(item, post.id)">
                        <v-badge bottom dot bordered :color="getColor(item.status)" offset-x="10" offset-y="10" class="mr-2">
                          <v-avatar color="primary" size="40" v-if="item.avatar == null">
                            <span class="text-uppercase white--text">{{ item.name.charAt(0) }}</span>
                          </v-avatar>
                          <v-avatar color="primary" size="40" v-else>
                            <img :lazy-src="`http://127.0.0.1:8000/storage/user-avatar/${item.avatar}`" :src="`http://127.0.0.1:8000/storage/user-avatar/${item.avatar}`">
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
              </v-card> -->

              <v-menu class="p-2" min-width="250px" max-width="400px" bottom offset-y nudge-bottom="5" origin="top right" v-model="showDialogFriendsPostComment[post.id]" :close-on-click="false">
                <v-list class="p-0" dense>
                  <v-list-item-group color="primary" v-model="selectUserFriendsPostComment">
                    <v-list-item-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-2 pt-1 pb-0">
                      <v-subheader>Vybrať priateľa</v-subheader>
                      <v-spacer />
                      <v-btn icon fab x-small color="error" @click="showDialogFriendsPostComment[post.id] == !showDialogFriendsPostComment[post.id]">
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
                      <v-list-item :key="item.id" @click="selectDataAboutUserPostComment(item, post.id)">
                        <v-badge bottom dot bordered :color="getColor(item.status)" offset-x="10" offset-y="10" class="mr-2">
                          <v-avatar color="primary" size="40" v-if="item.avatar == null">
                            <span class="text-uppercase white--text">{{ item.name.charAt(0) }}</span>
                          </v-avatar>
                          <v-avatar color="primary" size="40" v-else>
                            <img :lazy-src="`http://127.0.0.1:8000/storage/user-avatar/${item.avatar}`" :src="`http://127.0.0.1:8000/storage/user-avatar/${item.avatar}`">
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
          <v-divider class="mt-0 mb-0" v-if="post.count_comments != 0"></v-divider>

          <v-list-item class="d-flex justify-end" v-if="post.count_comments != 0">
            <v-menu class="p-2" left rounded offset-y :maxWidth="280" v-model="showCommentsFilter[post.id]">
              <!-- :position-y="20" :position-x="20"  -->
              <template v-slot:activator="{ on }">
                <div class="font-weight-bold">
                  <v-btn text v-on="on">
                    {{commentsFilter[selectedItemSortComments]}}
                    <v-icon class="ml-2" small v-if="showCommentsFilter[post.id] == true">mdi-chevron-down</v-icon>
                    <v-icon class="ml-2" small v-else>mdi-chevron-up</v-icon>
                  </v-btn>
                </div>
              </template>
              <v-card class="v-card-logout">
                <v-list dense>
                  <v-list-item-group color="primary" v-model="selectedItemSortComments">
                    <v-list-item two-line>
                      <!-- <v-list-item-icon>
                          <v-icon class="mr-1">mdi-application</v-icon>
                        </v-list-item-icon> -->
                      <v-list-item-content>
                        <v-list-item-title class="d-flex justify-start">Najrelevantnejšíe</v-list-item-title>
                        <v-list-item-subtitle class="d-flex justify-start">Zobrazovať komentáre priateľov</v-list-item-subtitle>
                        <v-list-item-subtitle class="d-flex justify-start">a najzaujímavejšie komentáre na začiatku.</v-list-item-subtitle>
                      </v-list-item-content>
                    </v-list-item>

                    <v-list-item two-line>
                      <!-- <v-list-item-icon>
                          <v-icon class="mr-1">mdi-account-group</v-icon>
                        </v-list-item-icon> -->
                      <v-list-item-content>
                        <v-list-item-title class="d-flex justify-start">Najnovšie</v-list-item-title>
                        <v-list-item-subtitle class="d-flex justify-start">Zobraziť všetky komentáre, s najnovšími</v-list-item-subtitle>
                        <v-list-item-subtitle class="d-flex justify-start">na začiatku.</v-list-item-subtitle>
                      </v-list-item-content>
                    </v-list-item>

                    <v-list-item two-line>
                      <!-- <v-list-item-icon>
                          <v-icon class="mr-1">mdi-calendar-star</v-icon>
                        </v-list-item-icon> -->
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

          <v-list dense>
            <template v-for="(posts_comments, index) in $root.navigationDrawerCenter.getAllPostsComments.posts_comments">
              <v-container class="pt-0" append-icon="" v-if="post.id == posts_comments.post_id" :key="'comments'+index">
                <!-- v-list-group -->
                <!-- <template v-slot:activator> -->
                <!-- <V-list-item-group v-for="(item, index) in posts_comments" :key="'comments'+index"> -->
                <v-list-item class="w-100 ">
                  <v-list-item-avatar class="ml-0">
                    <v-avatar color="primary" size="40" v-if="posts_comments.avatar == null">
                      <span v-if="posts_comments.name != null" class="text-uppercase white--text">{{ posts_comments.name.charAt(0) }}</span>
                    </v-avatar>
                    <v-avatar color="primary" size="40" v-else>
                      <v-img :lazy-src="`http://127.0.0.1:8000/storage/user-avatar/${posts_comments.avatar}`" :src="`http://127.0.0.1:8000/storage/user-avatar/${posts_comments.avatar}`" />
                    </v-avatar>
                  </v-list-item-avatar>
                  <v-list-item-content>
                    <v-card class="p-2 rounded-lg card-comments" elevation="0">
                      <v-list-item-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pl-1">
                        <v-chip small>
                          <v-icon left class="mr-1" small>mdi-account-circle-outline</v-icon>{{posts_comments.name}}
                        </v-chip>
                      </v-list-item-title>
                      <v-list-item-subtitle class="text-left primary--text font-weight-bold p-0 pl-1 pt-1">{{posts_comments.text}}</v-list-item-subtitle>
                      <v-list-item-subtitle class="text-left p-0 pl-1">
                        <span>
                          <!-- minutes -->
                          <v-tooltip bottom v-if="Math.round((new Date().getTime() - new Date(posts_comments.created_at).getTime()) / 60000) < 60">
                            <template v-slot:activator="{ on, attrs }">
                              <v-btn v-bind="attrs" v-on="on" x-small text rounded plain class="d-flex justify-start p-0 pl-0">
                                <span v-if="Math.round((new Date().getTime() - new Date(posts_comments.created_at).getTime()) / 60000) > 1">
                                  Pred {{Math.round((new Date().getTime() - new Date(posts_comments.created_at).getTime()) / 60000)}} minútami
                                </span>
                                <span v-else>
                                  Pred 1 minútou
                                </span>
                              </v-btn>
                            </template>
                            <span>
                              <v-icon class="mr-1" small color="white">mdi-clock</v-icon>{{posts_comments.created_at.toLocaleString()}}
                            </span>
                          </v-tooltip>

                          <!-- hours -->
                          <v-tooltip bottom v-else-if="Math.round((new Date().getTime() - new Date(posts_comments.created_at).getTime()) / 60000) < 1440">
                            <template v-slot:activator="{ on, attrs }">
                              <v-btn v-bind="attrs" v-on="on" x-small text rounded plain class="d-flex justify-start p-0 pl-0">
                                <span v-if="Math.floor(Math.round((new Date().getTime() - new Date(posts_comments.created_at).getTime()) / 60000)/60) > 1">
                                  Pred {{Math.floor(Math.round((new Date().getTime() - new Date(posts_comments.created_at).getTime()) / 60000)/60)}} hodinami
                                </span>
                                <span v-else>
                                  Pred 1 hodinou
                                </span>
                              </v-btn>
                            </template>
                            <span>
                              <v-icon class="mr-1" small color="white">mdi-clock</v-icon>{{posts_comments.created_at.toLocaleString()}}
                            </span>
                          </v-tooltip>

                          <!-- days -->
                          <v-tooltip bottom v-else-if="Math.round((new Date().getTime() - new Date(posts_comments.created_at).getTime()) / 60000) < 43200">
                            <template v-slot:activator="{ on, attrs }">
                              <v-btn v-bind="attrs" v-on="on" x-small text rounded plain class="d-flex justify-start p-0 pl-0">
                                <span v-if="Math.floor(Math.round((new Date().getTime() - new Date(posts_comments.created_at).getTime()) / 60000)/1440) > 1">
                                  Pred {{Math.floor(Math.round((new Date().getTime() - new Date(posts_comments.created_at).getTime()) / 60000)/1440)}} dňami
                                </span>
                                <span v-else>
                                  Pred 1 dňom
                                </span>
                              </v-btn>
                            </template>
                            <span>
                              <v-icon class="mr-1" small color="white">mdi-clock</v-icon>{{posts_comments.created_at.toLocaleString()}}
                            </span>
                          </v-tooltip>

                          <!-- months -->
                          <v-tooltip bottom v-else-if="Math.round((new Date().getTime() - new Date(posts_comments.created_at).getTime()) / 60000) < 525600">
                            <template v-slot:activator="{ on, attrs }">
                              <v-btn v-bind="attrs" v-on="on" x-small text rounded plain class="d-flex justify-start p-0 pl-0">
                                <span v-if="Math.floor(Math.round((new Date().getTime() - new Date(posts_comments.created_at).getTime()) / 60000)/43200) > 1">
                                  Pred {{Math.floor(Math.round((new Date().getTime() - new Date(posts_comments.created_at).getTime()) / 60000)/43200)}} mesiacmi
                                </span>
                                <span v-else>
                                  Pred 1 mesiacom
                                </span>
                              </v-btn>
                            </template>
                            <span>
                              <v-icon class="mr-1" small color="white">mdi-clock</v-icon>{{posts_comments.created_at.toLocaleString()}}
                            </span>
                          </v-tooltip>

                          <!-- years -->
                          <v-tooltip bottom v-else>
                            <template v-slot:activator="{ on, attrs }">
                              <v-btn v-bind="attrs" v-on="on" x-small text rounded plain class="d-flex justify-start p-0 pl-0">
                                <span v-if="Math.floor(Math.round((new Date().getTime() - new Date(posts_comments.created_at).getTime()) / 60000)/525600) > 1">
                                  Pred {{Math.floor(Math.round((new Date().getTime() - new Date(posts_comments.created_at).getTime()) / 60000)/525600)}} rokmi
                                </span>
                                <span v-else>
                                  Pred 1 rokom
                                </span>
                              </v-btn>
                            </template>
                            <span>
                              <v-icon class="mr-1" small color="white">mdi-clock</v-icon>{{posts_comments.created_at.toLocaleString()}}
                            </span>
                          </v-tooltip>
                        </span>
                      </v-list-item-subtitle>

                      <div class="position-absolute mr-10" style="right:0;bottom:-20px">
                        <!-- ///////////////////////////////////////////////////////////////////////// -->
                        <v-dialog max-width="600" :retain-focus="false" v-if="posts_comments.count_reactions != 0">
                          <template v-slot:activator="{ on, attrs }">
                            <v-btn small text outlined class="hidden-sm-and-down primary--color rounded-pill" v-bind="attrs" v-on="on">
                              <div class="">
                                <v-menu open-on-hover bottom offset-y v-if="posts_comments.like != 0">
                                  <template v-slot:activator="{ on, attrs }">
                                    <v-avatar class="mr-1" size="20" v-bind="attrs" v-on="on">
                                      <v-img lazy-src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/thumbs-up-sign_1f44d.png"
                                        src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/thumbs-up-sign_1f44d.png" />
                                    </v-avatar>
                                  </template>

                                  <v-list class="p-2">
                                    <v-list-item-title class="font-weight-bold primary--text">Páči sa mi to</v-list-item-title>
                                    <template v-for="(item, index) in $root.navigationDrawerCenter.getAllCommentReactions.answers_reactions">
                                      <v-list-item style="min-height: 20px" v-if="item.reaction_type == 'like' && posts_comments.id == item.comment_id" :key="'menuLike'+index">
                                        <v-list-item-subtitle class="m-0">{{ item.name }}</v-list-item-subtitle>
                                      </v-list-item>
                                    </template>
                                  </v-list>
                                </v-menu>

                                <v-menu open-on-hover bottom offset-y v-if="posts_comments.heart != 0">
                                  <template v-slot:activator="{ on, attrs }">
                                    <v-avatar class="mr-1" size="20" v-bind="attrs" v-on="on">
                                      <v-img lazy-src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/heavy-black-heart_2764.png"
                                        src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/heavy-black-heart_2764.png" />
                                    </v-avatar>
                                  </template>

                                  <v-list class="p-2">
                                    <v-list-item-title class="font-weight-bold primary--text">Super</v-list-item-title>
                                    <template v-for="(item, index) in $root.navigationDrawerCenter.getAllCommentReactions.answers_reactions">
                                      <v-list-item style="min-height: 20px" v-if="item.reaction_type == 'heart' && posts_comments.id == item.comment_id" :key="'menuHeart'+index">
                                        <v-list-item-subtitle class="m-0">{{ item.name }}</v-list-item-subtitle>
                                      </v-list-item>
                                    </template>
                                  </v-list>
                                </v-menu>

                                <v-menu open-on-hover bottom offset-y v-if="posts_comments.funny != 0">
                                  <template v-slot:activator="{ on, attrs }">
                                    <v-avatar class="mr-1" size="20" v-bind="attrs" v-on="on">
                                      <v-img lazy-src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/smiling-face-with-open-mouth-and-smiling-eyes_1f604.png"
                                        src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/smiling-face-with-open-mouth-and-smiling-eyes_1f604.png" />
                                    </v-avatar>
                                  </template>

                                  <v-list class="p-2">
                                    <v-list-item-title class="font-weight-bold primary--text">Haha</v-list-item-title>
                                    <template v-for="(item, index) in $root.navigationDrawerCenter.getAllCommentReactions.answers_reactions">
                                      <v-list-item style="min-height: 20px" v-if="item.reaction_type == 'funny' && posts_comments.id == item.comment_id" :key="'menuFunny'+index">
                                        <v-list-item-subtitle class="m-0">{{ item.name }}</v-list-item-subtitle>
                                      </v-list-item>
                                    </template>
                                  </v-list>
                                </v-menu>

                                <v-menu open-on-hover bottom offset-y v-if="posts_comments.surprise != 0">
                                  <template v-slot:activator="{ on, attrs }">
                                    <v-avatar class="mr-1" size="20" v-bind="attrs" v-on="on">
                                      <v-img lazy-src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/face-with-open-mouth_1f62e.png"
                                        src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/face-with-open-mouth_1f62e.png" />
                                    </v-avatar>
                                  </template>

                                  <v-list class="p-2">
                                    <v-list-item-title class="font-weight-bold primary--text">Žasnem</v-list-item-title>
                                    <template v-for="(item, index) in $root.navigationDrawerCenter.getAllCommentReactions.answers_reactions">
                                      <v-list-item style="min-height: 20px" v-if="item.reaction_type == 'surprise' && posts_comments.id == item.comment_id" :key="'menuSurprise'+index">
                                        <v-list-item-subtitle class="m-0">{{ item.name }}</v-list-item-subtitle>
                                      </v-list-item>
                                    </template>
                                  </v-list>
                                </v-menu>

                                <v-menu open-on-hover bottom offset-y v-if="posts_comments.sad != 0">
                                  <template v-slot:activator="{ on, attrs }">
                                    <v-avatar class="mr-1" size="20" v-bind="attrs" v-on="on">
                                      <v-img lazy-src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/disappointed-but-relieved-face_1f625.png"
                                        src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/disappointed-but-relieved-face_1f625.png" />
                                    </v-avatar>
                                  </template>

                                  <v-list class="p-2">
                                    <v-list-item-title class="font-weight-bold primary--text">Je mi to ľúto</v-list-item-title>
                                    <template v-for="(item, index) in $root.navigationDrawerCenter.getAllCommentReactions.answers_reactions">
                                      <v-list-item style="min-height: 20px" v-if="item.reaction_type == 'sad' && posts_comments.id == item.comment_id" :key="'menuSad'+index">
                                        <v-list-item-subtitle class="m-0">{{ item.name }}</v-list-item-subtitle>
                                      </v-list-item>
                                    </template>
                                  </v-list>
                                </v-menu>

                                <v-menu open-on-hover bottom offset-y v-if="posts_comments.angry != 0">
                                  <template v-slot:activator="{ on, attrs }">
                                    <v-avatar class="mr-1" size="20" v-bind="attrs" v-on="on">
                                      <v-img lazy-src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/pouting-face_1f621.png"
                                        src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/pouting-face_1f621.png" />
                                    </v-avatar>
                                  </template>

                                  <v-list class="p-2">
                                    <v-list-item-title class="font-weight-bold primary--text">Štve ma to</v-list-item-title>
                                    <template v-for="(item, index) in $root.navigationDrawerCenter.getAllCommentReactions.answers_reactions">
                                      <v-list-item style="min-height: 20px" v-if="item.reaction_type == 'angry' && posts_comments.id == item.comment_id" :key="'menuAngry'+index">
                                        <v-list-item-subtitle class="m-0">{{ item.name }}</v-list-item-subtitle>
                                      </v-list-item>
                                    </template>
                                  </v-list>
                                </v-menu>
                              </div>
                              <div class="primary--color">{{posts_comments.count_reactions}}</div>
                            </v-btn>
                            <v-btn text outlined class="hidden-md-and-up primary--color rounded-pill" :min-width="40" v-bind="attrs" v-on="on">
                              <div class="">
                                <v-menu open-on-hover bottom offset-y v-if="posts_comments.like != 0">
                                  <template v-slot:activator="{ on, attrs }">
                                    <v-avatar class="mr-1" size="20" v-bind="attrs" v-on="on">
                                      <v-img lazy-src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/thumbs-up-sign_1f44d.png"
                                        src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/thumbs-up-sign_1f44d.png" />
                                    </v-avatar>
                                  </template>

                                  <v-list class="p-2">
                                    <v-list-item-title class="font-weight-bold primary--text">Páči sa mi to</v-list-item-title>
                                    <template v-for="(item, index) in $root.navigationDrawerCenter.getAllCommentReactions.answers_reactions">
                                      <v-list-item style="min-height: 20px" v-if="item.reaction_type == 'like' && posts_comments.id == item.comment_id" :key="'menuLike'+index">
                                        <v-list-item-subtitle class="m-0">{{ item.name }}</v-list-item-subtitle>
                                      </v-list-item>
                                    </template>
                                  </v-list>
                                </v-menu>

                                <v-menu open-on-hover bottom offset-y v-if="posts_comments.heart != 0">
                                  <template v-slot:activator="{ on, attrs }">
                                    <v-avatar class="mr-1" size="20" v-bind="attrs" v-on="on">
                                      <v-img lazy-src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/heavy-black-heart_2764.png"
                                        src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/heavy-black-heart_2764.png" />
                                    </v-avatar>
                                  </template>

                                  <v-list class="p-2">
                                    <v-list-item-title class="font-weight-bold primary--text">Super</v-list-item-title>
                                    <template v-for="(item, index) in $root.navigationDrawerCenter.getAllCommentReactions.answers_reactions">
                                      <v-list-item style="min-height: 20px" v-if="item.reaction_type == 'heart' && posts_comments.id == item.comment_id" :key="'menuHeart'+index">
                                        <v-list-item-subtitle class="m-0">{{ item.name }}</v-list-item-subtitle>
                                      </v-list-item>
                                    </template>
                                  </v-list>
                                </v-menu>

                                <v-menu open-on-hover bottom offset-y v-if="posts_comments.funny != 0">
                                  <template v-slot:activator="{ on, attrs }">
                                    <v-avatar class="mr-1" size="20" v-bind="attrs" v-on="on">
                                      <v-img lazy-src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/smiling-face-with-open-mouth-and-smiling-eyes_1f604.png"
                                        src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/smiling-face-with-open-mouth-and-smiling-eyes_1f604.png" />
                                    </v-avatar>
                                  </template>

                                  <v-list class="p-2">
                                    <v-list-item-title class="font-weight-bold primary--text">Haha</v-list-item-title>
                                    <template v-for="(item, index) in $root.navigationDrawerCenter.getAllCommentReactions.answers_reactions">
                                      <v-list-item style="min-height: 20px" v-if="item.reaction_type == 'funny' && posts_comments.id == item.comment_id" :key="'menuFunny'+index">
                                        <v-list-item-subtitle class="m-0">{{ item.name }}</v-list-item-subtitle>
                                      </v-list-item>
                                    </template>
                                  </v-list>
                                </v-menu>

                                <v-menu open-on-hover bottom offset-y v-if="posts_comments.surprise != 0">
                                  <template v-slot:activator="{ on, attrs }">
                                    <v-avatar class="mr-1" size="20" v-bind="attrs" v-on="on">
                                      <v-img lazy-src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/face-with-open-mouth_1f62e.png"
                                        src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/face-with-open-mouth_1f62e.png" />
                                    </v-avatar>
                                  </template>

                                  <v-list class="p-2">
                                    <v-list-item-title class="font-weight-bold primary--text">Žasnem</v-list-item-title>
                                    <template v-for="(item, index) in $root.navigationDrawerCenter.getAllCommentReactions.answers_reactions">
                                      <v-list-item style="min-height: 20px" v-if="item.reaction_type == 'surprise' && posts_comments.id == item.comment_id" :key="'menuSurprise'+index">
                                        <v-list-item-subtitle class="m-0">{{ item.name }}</v-list-item-subtitle>
                                      </v-list-item>
                                    </template>
                                  </v-list>
                                </v-menu>

                                <v-menu open-on-hover bottom offset-y v-if="posts_comments.sad != 0">
                                  <template v-slot:activator="{ on, attrs }">
                                    <v-avatar class="mr-1" size="20" v-bind="attrs" v-on="on">
                                      <v-img lazy-src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/disappointed-but-relieved-face_1f625.png"
                                        src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/disappointed-but-relieved-face_1f625.png" />
                                    </v-avatar>
                                  </template>

                                  <v-list class="p-2">
                                    <v-list-item-title class="font-weight-bold primary--text">Je mi to ľúto</v-list-item-title>
                                    <template v-for="(item, index) in $root.navigationDrawerCenter.getAllCommentReactions.answers_reactions">
                                      <v-list-item style="min-height: 20px" v-if="item.reaction_type == 'sad' && posts_comments.id == item.comment_id" :key="'menuSad'+index">
                                        <v-list-item-subtitle class="m-0">{{ item.name }}</v-list-item-subtitle>
                                      </v-list-item>
                                    </template>
                                  </v-list>
                                </v-menu>

                                <v-menu open-on-hover bottom offset-y v-if="posts_comments.angry != 0">
                                  <template v-slot:activator="{ on, attrs }">
                                    <v-avatar class="mr-1" size="20" v-bind="attrs" v-on="on">
                                      <v-img lazy-src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/pouting-face_1f621.png"
                                        src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/pouting-face_1f621.png" />
                                    </v-avatar>
                                  </template>

                                  <v-list class="p-2">
                                    <v-list-item-title class="font-weight-bold primary--text">Štve ma to</v-list-item-title>
                                    <template v-for="(item, index) in $root.navigationDrawerCenter.getAllCommentReactions.answers_reactions">
                                      <v-list-item style="min-height: 20px" v-if="item.reaction_type == 'angry' && posts_comments.id == item.comment_id" :key="'menuAngry'+index">
                                        <v-list-item-subtitle class="m-0">{{ item.name }}</v-list-item-subtitle>
                                      </v-list-item>
                                    </template>
                                  </v-list>
                                </v-menu>
                              </div>
                              <div class="">
                                {{posts_comments.count_reactions}}
                              </div>
                            </v-btn>
                          </template>

                          <template v-slot:default="dialogReactionsComment">
                            <v-card>
                              <v-toolbar color="primary" dark v-if="selectedDialogReactionsComment == null">
                                <v-tabs v-model="tabReactionsComment[posts_comments.id]" background-color="primary white--text" dark :show-arrows="true">
                                  <v-tab>
                                    <span>Všetko</span>
                                    <span class="ml-1">{{posts_comments.count_reactions}}</span>
                                  </v-tab>

                                  <v-tab class="h-100" v-if="posts_comments.like != 0">
                                    <v-img max-height="20" max-width="20" lazy-src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/thumbs-up-sign_1f44d.png"
                                      src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/thumbs-up-sign_1f44d.png" />
                                    <span class="ml-1">{{posts_comments.like}}</span>
                                  </v-tab>

                                  <v-tab class="h-100" v-if="posts_comments.heart != 0">
                                    <v-img max-height="20" max-width="20" lazy-src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/heavy-black-heart_2764.png"
                                      src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/heavy-black-heart_2764.png" />
                                    <span class="ml-1">{{posts_comments.heart}}</span>
                                  </v-tab>

                                  <v-tab class="h-100" v-if="posts_comments.funny != 0">
                                    <v-img max-height="20" max-width="20" lazy-src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/smiling-face-with-open-mouth-and-smiling-eyes_1f604.png"
                                      src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/smiling-face-with-open-mouth-and-smiling-eyes_1f604.png" />
                                    <span class="ml-1">{{posts_comments.funny}}</span>
                                  </v-tab>

                                  <v-tab class="h-100" v-if="posts_comments.surprise != 0">
                                    <v-img max-height="20" max-width="20" lazy-src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/face-with-open-mouth_1f62e.png"
                                      src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/face-with-open-mouth_1f62e.png" />
                                    <span class="ml-1">{{posts_comments.surprise}}</span>
                                  </v-tab>

                                  <v-tab class="h-100" v-if="posts_comments.sad != 0">
                                    <v-img max-height="20" max-width="20" lazy-src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/disappointed-but-relieved-face_1f625.png"
                                      src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/disappointed-but-relieved-face_1f625.png" />
                                    <span class="ml-1">{{posts_comments.sad}}</span>
                                  </v-tab>

                                  <v-tab class="h-100" v-if="posts_comments.angry != 0">
                                    <v-img max-height="20" max-width="20" lazy-src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/pouting-face_1f621.png"
                                      src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/pouting-face_1f621.png" />
                                    <span class="ml-1">{{posts_comments.angry}}</span>
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

                              <v-tabs-items v-model="tabReactionsComment[posts_comments.id]">
                                <v-tab-item>
                                  <v-card flat>
                                    <v-card-text class="p-0">
                                      <v-list-item-group v-model="reactionsCommentAll" color="primary" v-for="(item, index) in $root.navigationDrawerCenter.getAllCommentReactions.answers_reactions" :key="'all'+index">
                                        <v-list-item v-if="posts_comments.id == item.comment_id">
                                          <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                            <v-avatar color="primary" size="40" v-if="item.avatar == null">
                                              <span class="text-uppercase white--text">{{ item.name.charAt(0) }}</span>
                                            </v-avatar>
                                            <v-avatar color="primary" size="40" v-else>
                                              <v-img :lazy-src="`http://127.0.0.1:8000/storage/user-avatar/${item.avatar}`" :src="`http://127.0.0.1:8000/storage/user-avatar/${item.avatar}`" />
                                            </v-avatar>
                                          </v-badge>
                                          <v-list-item-content>
                                            <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.name"></v-list-item-title>
                                            <v-list-item-subtitle class="d-flex justify-start questrial caption grey--text" v-text="item.text"></v-list-item-subtitle>
                                            <v-list-item-subtitle class="d-flex justify-start font-weight-bold primary--text">
                                              <v-icon v-if="item.text2" small color="primary">mdi-check-circle</v-icon> {{item.text2}}
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
                                </v-tab-item>

                                <v-tab-item v-if="posts_comments.like != 0">
                                  <v-card flat>
                                    <v-card-text class="p-0">
                                      <v-list-item-group v-model="reactionsCommentLike" color="primary" v-for="(item, index) in $root.navigationDrawerCenter.getAllCommentReactions.answers_reactions" :key="'like'+index">
                                        <v-list-item v-if="item.reaction_type == 'like' && posts_comments.id == item.comment_id">
                                          <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                            <v-avatar color="primary" size="40" v-if="item.avatar == null">
                                              <span class="text-uppercase white--text">{{ item.name.charAt(0) }}</span>
                                            </v-avatar>
                                            <v-avatar color="primary" size="40" v-else>
                                              <v-img :lazy-src="`http://127.0.0.1:8000/storage/user-avatar/${item.avatar}`" :src="`http://127.0.0.1:8000/storage/user-avatar/${item.avatar}`" />
                                            </v-avatar>
                                          </v-badge>
                                          <v-list-item-content>
                                            <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.name"></v-list-item-title>
                                            <v-list-item-subtitle class="d-flex justify-start questrial caption grey--text" v-text="item.text"></v-list-item-subtitle>
                                            <v-list-item-subtitle class="d-flex justify-start font-weight-bold primary--text">
                                              <v-icon v-if="item.text2" small color="primary">mdi-check-circle</v-icon> {{item.text2}}
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
                                </v-tab-item>

                                <v-tab-item v-if="posts_comments.heart != 0">
                                  <v-card flat>
                                    <v-card-text class="p-0">
                                      <v-list-item-group v-model="reactionsCommentHeart" color="primary" v-for="(item, index) in $root.navigationDrawerCenter.getAllCommentReactions.answers_reactions" :key="'heart'+index">
                                        <v-list-item v-if="item.reaction_type == 'heart' && posts_comments.id == item.comment_id">
                                          <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                            <v-avatar color="primary" size="40" v-if="item.avatar == null">
                                              <span class="text-uppercase white--text">{{ item.name.charAt(0) }}</span>
                                            </v-avatar>
                                            <v-avatar color="primary" size="40" v-else>
                                              <v-img :lazy-src="`http://127.0.0.1:8000/storage/user-avatar/${item.avatar}`" :src="`http://127.0.0.1:8000/storage/user-avatar/${item.avatar}`" />
                                            </v-avatar>
                                          </v-badge>
                                          <v-list-item-content>
                                            <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.name"></v-list-item-title>
                                            <v-list-item-subtitle class="d-flex justify-start questrial caption grey--text" v-text="item.text"></v-list-item-subtitle>
                                            <v-list-item-subtitle class="d-flex justify-start font-weight-bold primary--text">
                                              <v-icon v-if="item.text2" small color="primary">mdi-check-circle</v-icon> {{item.text2}}
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
                                </v-tab-item>

                                <v-tab-item v-if="posts_comments.funny != 0">
                                  <v-card flat>
                                    <v-card-text class="p-0">
                                      <v-list-item-group v-model="reactionsCommentFunny" color="primary" v-for="(item, index) in $root.navigationDrawerCenter.getAllCommentReactions.answers_reactions" :key="'funny'+index">
                                        <v-list-item v-if="item.reaction_type == 'funny' && posts_comments.id == item.comment_id">
                                          <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                            <v-avatar color="primary" size="40" v-if="item.avatar == null">
                                              <span class="text-uppercase white--text">{{ item.name.charAt(0) }}</span>
                                            </v-avatar>
                                            <v-avatar color="primary" size="40" v-else>
                                              <v-img :lazy-src="`http://127.0.0.1:8000/storage/user-avatar/${item.avatar}`" :src="`http://127.0.0.1:8000/storage/user-avatar/${item.avatar}`" />
                                            </v-avatar>
                                          </v-badge>
                                          <v-list-item-content>
                                            <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.name"></v-list-item-title>
                                            <v-list-item-subtitle class="d-flex justify-start questrial caption grey--text" v-text="item.text"></v-list-item-subtitle>
                                            <v-list-item-subtitle class="d-flex justify-start font-weight-bold primary--text">
                                              <v-icon v-if="item.text2" small color="primary">mdi-check-circle</v-icon> {{item.text2}}
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
                                </v-tab-item>

                                <v-tab-item v-if="posts_comments.surprise != 0">
                                  <v-card flat>
                                    <v-card-text class="p-0">
                                      <v-list-item-group v-model="reactionsCommentSurprise" color="primary" v-for="(item, index) in $root.navigationDrawerCenter.getAllCommentReactions.answers_reactions" :key="'surprise'+index">
                                        <v-list-item v-if="item.reaction_type == 'surprise' && posts_comments.id == item.comment_id">
                                          <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                            <v-avatar color="primary" size="40" v-if="item.avatar == null">
                                              <span class="text-uppercase white--text">{{ item.name.charAt(0) }}</span>
                                            </v-avatar>
                                            <v-avatar color="primary" size="40" v-else>
                                              <v-img :lazy-src="`http://127.0.0.1:8000/storage/user-avatar/${item.avatar}`" :src="`http://127.0.0.1:8000/storage/user-avatar/${item.avatar}`" />
                                            </v-avatar>
                                          </v-badge>
                                          <v-list-item-content>
                                            <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.name"></v-list-item-title>
                                            <v-list-item-subtitle class="d-flex justify-start questrial caption grey--text" v-text="item.text"></v-list-item-subtitle>
                                            <v-list-item-subtitle class="d-flex justify-start font-weight-bold primary--text">
                                              <v-icon v-if="item.text2" small color="primary">mdi-check-circle</v-icon> {{item.text2}}
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
                                </v-tab-item>

                                <v-tab-item v-if="posts_comments.sad != 0">
                                  <v-card flat>
                                    <v-card-text class="p-0">
                                      <v-list-item-group v-model="reactionsCommentSad" color="primary" v-for="(item, index) in $root.navigationDrawerCenter.getAllCommentReactions.answers_reactions" :key="'sad'+index">
                                        <v-list-item v-if="item.reaction_type == 'sad' && posts_comments.id == item.comment_id">
                                          <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                            <v-avatar color="primary" size="40" v-if="item.avatar == null">
                                              <span class="text-uppercase white--text">{{ item.name.charAt(0) }}</span>
                                            </v-avatar>
                                            <v-avatar color="primary" size="40" v-else>
                                              <v-img :lazy-src="`http://127.0.0.1:8000/storage/user-avatar/${item.avatar}`" :src="`http://127.0.0.1:8000/storage/user-avatar/${item.avatar}`" />
                                            </v-avatar>
                                          </v-badge>
                                          <v-list-item-content>
                                            <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.name"></v-list-item-title>
                                            <v-list-item-subtitle class="d-flex justify-start questrial caption grey--text" v-text="item.text"></v-list-item-subtitle>
                                            <v-list-item-subtitle class="d-flex justify-start font-weight-bold primary--text">
                                              <v-icon v-if="item.text2" small color="primary">mdi-check-circle</v-icon> {{item.text2}}
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
                                </v-tab-item>

                                <v-tab-item v-if="posts_comments.angry != 0">
                                  <v-card flat>
                                    <v-card-text class="p-0">
                                      <v-list-item-group v-model="reactionsCommentAngry" color="primary" v-for="(item, index) in $root.navigationDrawerCenter.getAllCommentReactions.answers_reactions" :key="'angry'+index">
                                        <v-list-item v-if="item.reaction_type == 'angry' && posts_comments.id == item.comment_id">
                                          <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                            <v-avatar color="primary" size="40" v-if="item.avatar == null">
                                              <span class="text-uppercase white--text">{{ item.name.charAt(0) }}</span>
                                            </v-avatar>
                                            <v-avatar color="primary" size="40" v-else>
                                              <v-img :lazy-src="`http://127.0.0.1:8000/storage/user-avatar/${item.avatar}`" :src="`http://127.0.0.1:8000/storage/user-avatar/${item.avatar}`" />
                                            </v-avatar>
                                          </v-badge>
                                          <v-list-item-content>
                                            <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.name"></v-list-item-title>
                                            <v-list-item-subtitle class="d-flex justify-start questrial caption grey--text" v-text="item.text"></v-list-item-subtitle>
                                            <v-list-item-subtitle class="d-flex justify-start font-weight-bold primary--text">
                                              <v-icon v-if="item.text2" small color="primary">mdi-check-circle</v-icon> {{item.text2}}
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
                                </v-tab-item>
                              </v-tabs-items>
                              <!-- <v-divider class="mt-0 mb-0"></v-divider>

                              <v-card-actions class="justify-end">
                                <v-btn text outlined class="primary white--text" @click="dialogReactionsComment.value = false">Zavrieť</v-btn>
                              </v-card-actions> -->
                            </v-card>
                          </template>
                        </v-dialog>

                      </div>
                    </v-card>
                    <v-row class="w-100 ml-0 mr-0 mt-0">
                      <!-- first comment on post -->
                      <v-menu open-on-hover top offset-y min-width="200px" rounded="pill">
                        <template v-slot:activator="{ on }">
                          <v-btn class="mt-1 caption" x-small text rounded outlined v-on="on">
                            Páči sa mi to
                          </v-btn>
                        </template>

                        <v-card class="rounded-pill p-2" elevation="2">
                          <v-btn icon @click="rateComment('like', posts_comments.id, 'posts_comments')">
                            <v-avatar size="20">
                              <v-img src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/thumbs-up-sign_1f44d.png">
                              </v-img>
                            </v-avatar>
                          </v-btn>
                          <v-btn icon @click="rateComment('heart', posts_comments.id, 'posts_comments')">
                            <v-avatar size="20">
                              <v-img src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/heavy-black-heart_2764.png">
                              </v-img>
                            </v-avatar>
                          </v-btn>
                          <v-btn icon @click="rateComment('funny', posts_comments.id, 'posts_comments')">
                            <v-avatar size="20">
                              <v-img src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/smiling-face-with-open-mouth-and-smiling-eyes_1f604.png">
                              </v-img>
                            </v-avatar>
                          </v-btn>

                          <v-btn icon @click="rateComment('surprise', posts_comments.id, 'posts_comments')">
                            <v-avatar size="20">
                              <v-img src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/face-with-open-mouth_1f62e.png">
                              </v-img>
                            </v-avatar>
                          </v-btn>

                          <v-btn icon @click="rateComment('sad', posts_comments.id, 'posts_comments')">
                            <v-avatar size="20">
                              <v-img src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/disappointed-but-relieved-face_1f625.png">
                              </v-img>
                            </v-avatar>
                          </v-btn>

                          <v-btn icon @click="rateComment('angry', posts_comments.id, 'posts_comments')">
                            <v-avatar size="20">
                              <v-img src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/pouting-face_1f621.png">
                              </v-img>
                            </v-avatar>
                          </v-btn>
                        </v-card>
                      </v-menu>
                      <!-- //////////////////////////////////////////////////////////////////////////////answer on post_comment/////////////////////////////////////////////////////////////////////// -->
                      <div class="">
                        <v-btn @click.stop x-small text rounded outlined class="caption primary--text font-weight-bold mt-1 ml-1" @click="focusInputAnswerOnComment(posts_comments)">
                          Odpovedať
                        </v-btn>
                      </div>

                    </v-row>
                    <!-- //////////////////////////////////////////////////////////////////////////////Count of answer on comment/////////////////////////////////////////////////////////////////////// -->
                    <v-row class="ml-0 mr-0 mt-0">
                      <v-btn @click="show_comments(posts_comments.id)" v-if="posts_comments.count_comments == 1" text small class="d-flex justify-start caption primary--text font-weight-bold"
                        v-bind:class="'btn_answers_on_comments' + posts_comments.id">
                        <!-- <v-icon class="mr-1" small color="primary">mdi-subdirectory-arrow-right</v-icon> -->
                        <v-icon class="" color="primary" size="20">mdi-alpha-l</v-icon>
                        {{posts_comments.count_comments}} Odpoveď
                        <v-icon class="ml-2" style="display:none" v-bind:class="'btn_answers_on_comments_arrow_down' + posts_comments.id" small>mdi-chevron-down</v-icon>
                        <v-icon class="ml-2" style="display:block" v-bind:class="'btn_answers_on_comments_arrow_up' + posts_comments.id" small>mdi-chevron-up</v-icon>
                      </v-btn>
                      <v-btn @click="show_comments(posts_comments.id)" v-if="posts_comments.count_comments > 1" text small class="d-flex justify-start caption primary--text font-weight-bold"
                        v-bind:class="'btn_answers_on_comments' + posts_comments.id">
                        <!-- <v-icon class="mr-1" small color="primary">mdi-subdirectory-arrow-right</v-icon> -->
                        <v-icon class="" color="primary" size="20">mdi-alpha-l</v-icon>
                        {{posts_comments.count_comments}} Odpovede
                        <v-icon class="ml-2" style="display:none" v-bind:class="'btn_answers_on_comments_arrow_down' + posts_comments.id" small>mdi-chevron-down</v-icon>
                        <v-icon class="ml-2" style="display:block" v-bind:class="'btn_answers_on_comments_arrow_up' + posts_comments.id" small>mdi-chevron-up</v-icon>
                      </v-btn>
                    </v-row>
                  </v-list-item-content>
                </v-list-item>
                <!-- </V-list-item-group> -->
                <!-- </template> -->
                <!-- //////////////////////////////////////////////////////////////////answer on comments///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
                <template v-for="(answers_on_comments, index) in $root.navigationDrawerCenter.getAllAnswersOnComments.answers_on_comments">
                  <div v-bind:class="'answers_on_comments' + posts_comments.id" style="display:none" :key="index">
                    <v-list-item v-if="posts_comments.id == answers_on_comments.comment_id" class="ml-16">
                      <v-list-item-avatar class="ml-0 mr-2">
                        <v-avatar color="primary" size="28" v-if="answers_on_comments.avatar == null">
                          <span v-if="answers_on_comments.name != null" class="text-uppercase white--text">{{ answers_on_comments.name.charAt(0) }}</span>
                        </v-avatar>
                        <v-avatar color="primary" size="28" v-else>
                          <v-img :lazy-src="`http://127.0.0.1:8000/storage/user-avatar/${answers_on_comments.avatar}`" :src="`http://127.0.0.1:8000/storage/user-avatar/${answers_on_comments.avatar}`" />
                        </v-avatar>
                      </v-list-item-avatar>
                      <v-list-item-content>
                        <v-card class="p-2 rounded-lg card-comments" elevation="0" color="#f5f5f5">
                          <v-list-item-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pl-1">
                            <v-chip small>
                              <v-icon left class="mr-1" small>mdi-account-circle-outline</v-icon>{{answers_on_comments.name}}
                            </v-chip>
                          </v-list-item-title>
                          <v-list-item-subtitle class="text-left primary--text font-weight-bold p-0 pl-1 pt-1">{{answers_on_comments.text}}</v-list-item-subtitle>
                          <v-list-item-subtitle class="text-left p-0 pl-1">
                            <span>
                              <!-- minutes -->
                              <v-tooltip bottom v-if="Math.round((new Date().getTime() - new Date(answers_on_comments.created_at).getTime()) / 60000) < 60">
                                <template v-slot:activator="{ on, attrs }">
                                  <v-btn v-bind="attrs" v-on="on" x-small text rounded plain class="d-flex justify-start p-0 pl-0">
                                    <span v-if="Math.round((new Date().getTime() - new Date(answers_on_comments.created_at).getTime()) / 60000) > 1">
                                      Pred {{Math.round((new Date().getTime() - new Date(answers_on_comments.created_at).getTime()) / 60000)}} minútami
                                    </span>
                                    <span v-else>
                                      Pred 1 minútou
                                    </span>
                                  </v-btn>
                                </template>
                                <span>
                                  <v-icon class="mr-1" small color="white">mdi-clock</v-icon>{{answers_on_comments.created_at.toLocaleString()}}
                                </span>
                              </v-tooltip>

                              <!-- hours -->
                              <v-tooltip bottom v-else-if="Math.round((new Date().getTime() - new Date(answers_on_comments.created_at).getTime()) / 60000) < 1440">
                                <template v-slot:activator="{ on, attrs }">
                                  <v-btn v-bind="attrs" v-on="on" x-small text rounded plain class="d-flex justify-start p-0 pl-0">
                                    <span v-if="Math.floor(Math.round((new Date().getTime() - new Date(answers_on_comments.created_at).getTime()) / 60000)/60) > 1">
                                      Pred {{Math.floor(Math.round((new Date().getTime() - new Date(answers_on_comments.created_at).getTime()) / 60000)/60)}} hodinami
                                    </span>
                                    <span v-else>
                                      Pred 1 hodinou
                                    </span>
                                  </v-btn>
                                </template>
                                <span>
                                  <v-icon class="mr-1" small color="white">mdi-clock</v-icon>{{answers_on_comments.created_at.toLocaleString()}}
                                </span>
                              </v-tooltip>

                              <!-- days -->
                              <v-tooltip bottom v-else-if="Math.round((new Date().getTime() - new Date(answers_on_comments.created_at).getTime()) / 60000) < 43200">
                                <template v-slot:activator="{ on, attrs }">
                                  <v-btn v-bind="attrs" v-on="on" x-small text rounded plain class="d-flex justify-start p-0 pl-0">
                                    <span v-if="Math.floor(Math.round((new Date().getTime() - new Date(answers_on_comments.created_at).getTime()) / 60000)/1440) > 1">
                                      Pred {{Math.floor(Math.round((new Date().getTime() - new Date(answers_on_comments.created_at).getTime()) / 60000)/1440)}} dňami
                                    </span>
                                    <span v-else>
                                      Pred 1 dňom
                                    </span>
                                  </v-btn>
                                </template>
                                <span>
                                  <v-icon class="mr-1" small color="white">mdi-clock</v-icon>{{answers_on_comments.created_at.toLocaleString()}}
                                </span>
                              </v-tooltip>

                              <!-- months -->
                              <v-tooltip bottom v-else-if="Math.round((new Date().getTime() - new Date(answers_on_comments.created_at).getTime()) / 60000) < 525600">
                                <template v-slot:activator="{ on, attrs }">
                                  <v-btn v-bind="attrs" v-on="on" x-small text rounded plain class="d-flex justify-start p-0 pl-0">
                                    <span v-if="Math.floor(Math.round((new Date().getTime() - new Date(answers_on_comments.created_at).getTime()) / 60000)/43200) > 1">
                                      Pred {{Math.floor(Math.round((new Date().getTime() - new Date(answers_on_comments.created_at).getTime()) / 60000)/43200)}} mesiacmi
                                    </span>
                                    <span v-else>
                                      Pred 1 mesiacom
                                    </span>
                                  </v-btn>
                                </template>
                                <span>
                                  <v-icon class="mr-1" small color="white">mdi-clock</v-icon>{{answers_on_comments.created_at.toLocaleString()}}
                                </span>
                              </v-tooltip>

                              <!-- years -->
                              <v-tooltip bottom v-else>
                                <template v-slot:activator="{ on, attrs }">
                                  <v-btn v-bind="attrs" v-on="on" x-small text rounded plain class="d-flex justify-start p-0 pl-0">
                                    <span v-if="Math.floor(Math.round((new Date().getTime() - new Date(answers_on_comments.created_at).getTime()) / 60000)/525600) > 1">
                                      Pred {{Math.floor(Math.round((new Date().getTime() - new Date(answers_on_comments.created_at).getTime()) / 60000)/525600)}} rokmi
                                    </span>
                                    <span v-else>
                                      Pred 1 rokom
                                    </span>
                                  </v-btn>
                                </template>
                                <span>
                                  <v-icon class="mr-1" small color="white">mdi-clock</v-icon>{{answers_on_comments.created_at.toLocaleString()}}
                                </span>
                              </v-tooltip>
                            </span>
                          </v-list-item-subtitle>

                          <div class="position-absolute mr-10" style="right:0;bottom:-20px">
                            <!-- ///////////////////////////////////////////////////////////////////////// -->
                            <v-dialog max-width="600" :retain-focus="false" v-if="answers_on_comments.count_reactions != 0">
                              <template v-slot:activator="{ on, attrs }">
                                <v-btn small text outlined class="hidden-sm-and-down primary--color rounded-pill" v-bind="attrs" v-on="on">
                                  <div class="">
                                    <v-menu open-on-hover bottom offset-y v-if="answers_on_comments.like != 0">
                                      <template v-slot:activator="{ on, attrs }">
                                        <v-avatar class="mr-1" size="20" v-bind="attrs" v-on="on">
                                          <v-img lazy-src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/thumbs-up-sign_1f44d.png"
                                            src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/thumbs-up-sign_1f44d.png" />
                                        </v-avatar>
                                      </template>

                                      <v-list class="p-2">
                                        <v-list-item-title class="font-weight-bold primary--text">Páči sa mi to</v-list-item-title>
                                        <template v-for="(item, index) in $root.navigationDrawerCenter.getAllCommentReactions.answers_reactions">
                                          <v-list-item style="min-height: 20px" v-if="item.reaction_type == 'like' && answers_on_comments.id == item.answer_on_comment_id" :key="'menuLike'+index">
                                            <v-list-item-subtitle class="m-0">{{ item.name }}</v-list-item-subtitle>
                                          </v-list-item>
                                        </template>
                                      </v-list>
                                    </v-menu>

                                    <v-menu open-on-hover bottom offset-y v-if="answers_on_comments.heart != 0">
                                      <template v-slot:activator="{ on, attrs }">
                                        <v-avatar class="mr-1" size="20" v-bind="attrs" v-on="on">
                                          <v-img lazy-src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/heavy-black-heart_2764.png"
                                            src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/heavy-black-heart_2764.png" />
                                        </v-avatar>
                                      </template>

                                      <v-list class="p-2">
                                        <v-list-item-title class="font-weight-bold primary--text">Super</v-list-item-title>
                                        <template v-for="(item, index) in $root.navigationDrawerCenter.getAllCommentReactions.answers_reactions">
                                          <v-list-item style="min-height: 20px" v-if="item.reaction_type == 'heart' && answers_on_comments.id == item.answer_on_comment_id" :key="'menuHeart'+index">
                                            <v-list-item-subtitle class="m-0">{{ item.name }}</v-list-item-subtitle>
                                          </v-list-item>
                                        </template>
                                      </v-list>
                                    </v-menu>

                                    <v-menu open-on-hover bottom offset-y v-if="answers_on_comments.funny != 0">
                                      <template v-slot:activator="{ on, attrs }">
                                        <v-avatar class="mr-1" size="20" v-bind="attrs" v-on="on">
                                          <v-img lazy-src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/smiling-face-with-open-mouth-and-smiling-eyes_1f604.png"
                                            src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/smiling-face-with-open-mouth-and-smiling-eyes_1f604.png" />
                                        </v-avatar>
                                      </template>

                                      <v-list class="p-2">
                                        <v-list-item-title class="font-weight-bold primary--text">Haha</v-list-item-title>
                                        <template v-for="(item, index) in $root.navigationDrawerCenter.getAllCommentReactions.answers_reactions">
                                          <v-list-item style="min-height: 20px" v-if="item.reaction_type == 'funny' && answers_on_comments.id == item.answer_on_comment_id" :key="'menuFunny'+index">
                                            <v-list-item-subtitle class="m-0">{{ item.name }}</v-list-item-subtitle>
                                          </v-list-item>
                                        </template>
                                      </v-list>
                                    </v-menu>

                                    <v-menu open-on-hover bottom offset-y v-if="answers_on_comments.surprise != 0">
                                      <template v-slot:activator="{ on, attrs }">
                                        <v-avatar class="mr-1" size="20" v-bind="attrs" v-on="on">
                                          <v-img lazy-src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/face-with-open-mouth_1f62e.png"
                                            src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/face-with-open-mouth_1f62e.png" />
                                        </v-avatar>
                                      </template>

                                      <v-list class="p-2">
                                        <v-list-item-title class="font-weight-bold primary--text">Žasnem</v-list-item-title>
                                        <template v-for="(item, index) in $root.navigationDrawerCenter.getAllCommentReactions.answers_reactions">
                                          <v-list-item style="min-height: 20px" v-if="item.reaction_type == 'surprise' && answers_on_comments.id == item.answer_on_comment_id" :key="'menuSurprise'+index">
                                            <v-list-item-subtitle class="m-0">{{ item.name }}</v-list-item-subtitle>
                                          </v-list-item>
                                        </template>
                                      </v-list>
                                    </v-menu>

                                    <v-menu open-on-hover bottom offset-y v-if="answers_on_comments.sad != 0">
                                      <template v-slot:activator="{ on, attrs }">
                                        <v-avatar class="mr-1" size="20" v-bind="attrs" v-on="on">
                                          <v-img lazy-src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/disappointed-but-relieved-face_1f625.png"
                                            src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/disappointed-but-relieved-face_1f625.png" />
                                        </v-avatar>
                                      </template>

                                      <v-list class="p-2">
                                        <v-list-item-title class="font-weight-bold primary--text">Je mi to ľúto</v-list-item-title>
                                        <template v-for="(item, index) in $root.navigationDrawerCenter.getAllCommentReactions.answers_reactions">
                                          <v-list-item style="min-height: 20px" v-if="item.reaction_type == 'sad' && answers_on_comments.id == item.answer_on_comment_id" :key="'menuSad'+index">
                                            <v-list-item-subtitle class="m-0">{{ item.name }}</v-list-item-subtitle>
                                          </v-list-item>
                                        </template>
                                      </v-list>
                                    </v-menu>

                                    <v-menu open-on-hover bottom offset-y v-if="answers_on_comments.angry != 0">
                                      <template v-slot:activator="{ on, attrs }">
                                        <v-avatar class="mr-1" size="20" v-bind="attrs" v-on="on">
                                          <v-img lazy-src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/pouting-face_1f621.png"
                                            src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/pouting-face_1f621.png" />
                                        </v-avatar>
                                      </template>

                                      <v-list class="p-2">
                                        <v-list-item-title class="font-weight-bold primary--text">Štve ma to</v-list-item-title>
                                        <template v-for="(item, index) in $root.navigationDrawerCenter.getAllCommentReactions.answers_reactions">
                                          <v-list-item style="min-height: 20px" v-if="item.reaction_type == 'angry' && answers_on_comments.id == item.answer_on_comment_id" :key="'menuAngry'+index">
                                            <v-list-item-subtitle class="m-0">{{ item.name }}</v-list-item-subtitle>
                                          </v-list-item>
                                        </template>
                                      </v-list>
                                    </v-menu>
                                  </div>
                                  <div class="primary--color">{{answers_on_comments.count_reactions}}</div>
                                </v-btn>
                                <v-btn text outlined class="hidden-md-and-up primary--color rounded-pill" :min-width="40" v-bind="attrs" v-on="on">
                                  <div class="">
                                    <v-menu open-on-hover bottom offset-y v-if="answers_on_comments.like != 0">
                                      <template v-slot:activator="{ on, attrs }">
                                        <v-avatar class="mr-1" size="20" v-bind="attrs" v-on="on">
                                          <v-img lazy-src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/thumbs-up-sign_1f44d.png"
                                            src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/thumbs-up-sign_1f44d.png" />
                                        </v-avatar>
                                      </template>

                                      <v-list class="p-2">
                                        <v-list-item-title class="font-weight-bold primary--text">Páči sa mi to</v-list-item-title>
                                        <template v-for="(item, index) in $root.navigationDrawerCenter.getAllCommentReactions.answers_reactions">
                                          <v-list-item style="min-height: 20px" v-if="item.reaction_type == 'like' && answers_on_comments.id == item.answer_on_comment_id" :key="'menuLike'+index">
                                            <v-list-item-subtitle class="m-0">{{ item.name }}</v-list-item-subtitle>
                                          </v-list-item>
                                        </template>
                                      </v-list>
                                    </v-menu>

                                    <v-menu open-on-hover bottom offset-y v-if="answers_on_comments.heart != 0">
                                      <template v-slot:activator="{ on, attrs }">
                                        <v-avatar class="mr-1" size="20" v-bind="attrs" v-on="on">
                                          <v-img lazy-src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/heavy-black-heart_2764.png"
                                            src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/heavy-black-heart_2764.png" />
                                        </v-avatar>
                                      </template>

                                      <v-list class="p-2">
                                        <v-list-item-title class="font-weight-bold primary--text">Super</v-list-item-title>
                                        <template v-for="(item, index) in $root.navigationDrawerCenter.getAllCommentReactions.answers_reactions">
                                          <v-list-item style="min-height: 20px" v-if="item.reaction_type == 'heart' && answers_on_comments.id == item.answer_on_comment_id" :key="'menuHeart'+index">
                                            <v-list-item-subtitle class="m-0">{{ item.name }}</v-list-item-subtitle>
                                          </v-list-item>
                                        </template>
                                      </v-list>
                                    </v-menu>

                                    <v-menu open-on-hover bottom offset-y v-if="answers_on_comments.funny != 0">
                                      <template v-slot:activator="{ on, attrs }">
                                        <v-avatar class="mr-1" size="20" v-bind="attrs" v-on="on">
                                          <v-img lazy-src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/smiling-face-with-open-mouth-and-smiling-eyes_1f604.png"
                                            src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/smiling-face-with-open-mouth-and-smiling-eyes_1f604.png" />
                                        </v-avatar>
                                      </template>

                                      <v-list class="p-2">
                                        <v-list-item-title class="font-weight-bold primary--text">Haha</v-list-item-title>
                                        <template v-for="(item, index) in $root.navigationDrawerCenter.getAllCommentReactions.answers_reactions">
                                          <v-list-item style="min-height: 20px" v-if="item.reaction_type == 'funny' && answers_on_comments.id == item.answer_on_comment_id" :key="'menuFunny'+index">
                                            <v-list-item-subtitle class="m-0">{{ item.name }}</v-list-item-subtitle>
                                          </v-list-item>
                                        </template>
                                      </v-list>
                                    </v-menu>

                                    <v-menu open-on-hover bottom offset-y v-if="answers_on_comments.surprise != 0">
                                      <template v-slot:activator="{ on, attrs }">
                                        <v-avatar class="mr-1" size="20" v-bind="attrs" v-on="on">
                                          <v-img lazy-src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/face-with-open-mouth_1f62e.png"
                                            src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/face-with-open-mouth_1f62e.png" />
                                        </v-avatar>
                                      </template>

                                      <v-list class="p-2">
                                        <v-list-item-title class="font-weight-bold primary--text">Žasnem</v-list-item-title>
                                        <template v-for="(item, index) in $root.navigationDrawerCenter.getAllCommentReactions.answers_reactions">
                                          <v-list-item style="min-height: 20px" v-if="item.reaction_type == 'surprise' && answers_on_comments.id == item.answer_on_comment_id" :key="'menuSurprise'+index">
                                            <v-list-item-subtitle class="m-0">{{ item.name }}</v-list-item-subtitle>
                                          </v-list-item>
                                        </template>
                                      </v-list>
                                    </v-menu>

                                    <v-menu open-on-hover bottom offset-y v-if="answers_on_comments.sad != 0">
                                      <template v-slot:activator="{ on, attrs }">
                                        <v-avatar class="mr-1" size="20" v-bind="attrs" v-on="on">
                                          <v-img lazy-src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/disappointed-but-relieved-face_1f625.png"
                                            src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/disappointed-but-relieved-face_1f625.png" />
                                        </v-avatar>
                                      </template>

                                      <v-list class="p-2">
                                        <v-list-item-title class="font-weight-bold primary--text">Je mi to ľúto</v-list-item-title>
                                        <template v-for="(item, index) in $root.navigationDrawerCenter.getAllCommentReactions.answers_reactions">
                                          <v-list-item style="min-height: 20px" v-if="item.reaction_type == 'sad' && answers_on_comments.id == item.answer_on_comment_id" :key="'menuSad'+index">
                                            <v-list-item-subtitle class="m-0">{{ item.name }}</v-list-item-subtitle>
                                          </v-list-item>
                                        </template>
                                      </v-list>
                                    </v-menu>

                                    <v-menu open-on-hover bottom offset-y v-if="answers_on_comments.angry != 0">
                                      <template v-slot:activator="{ on, attrs }">
                                        <v-avatar class="mr-1" size="20" v-bind="attrs" v-on="on">
                                          <v-img lazy-src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/pouting-face_1f621.png"
                                            src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/pouting-face_1f621.png" />
                                        </v-avatar>
                                      </template>

                                      <v-list class="p-2">
                                        <v-list-item-title class="font-weight-bold primary--text">Štve ma to</v-list-item-title>
                                        <template v-for="(item, index) in $root.navigationDrawerCenter.getAllCommentReactions.answers_reactions">
                                          <v-list-item style="min-height: 20px" v-if="item.reaction_type == 'angry' && posts_comments.id == item.answer_on_comment_id" :key="'menuAngry'+index">
                                            <v-list-item-subtitle class="m-0">{{ item.name }}</v-list-item-subtitle>
                                          </v-list-item>
                                        </template>
                                      </v-list>
                                    </v-menu>
                                  </div>
                                  <div class="">
                                    {{answers_on_comments.count_reactions}}
                                  </div>
                                </v-btn>
                              </template>

                              <template v-slot:default="dialogReactionsAnswerOnComment">
                                <v-card>
                                  <v-toolbar color="primary" dark v-if="selectedDialogReactionsAnswerOnComment == null">
                                    <v-tabs v-model="tabReactionsAnswerOnComment[answers_on_comments.id]" background-color="primary white--text" dark :show-arrows="true">
                                      <v-tab>
                                        <span>Všetko</span>
                                        <span class="ml-1">{{answers_on_comments.count_reactions}}</span>
                                      </v-tab>

                                      <v-tab class="h-100" v-if="answers_on_comments.like != 0">
                                        <v-img max-height="20" max-width="20" lazy-src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/thumbs-up-sign_1f44d.png"
                                          src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/thumbs-up-sign_1f44d.png" />
                                        <span class="ml-1">{{answers_on_comments.like}}</span>
                                      </v-tab>

                                      <v-tab class="h-100" v-if="answers_on_comments.heart != 0">
                                        <v-img max-height="20" max-width="20" lazy-src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/heavy-black-heart_2764.png"
                                          src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/heavy-black-heart_2764.png" />
                                        <span class="ml-1">{{answers_on_comments.heart}}</span>
                                      </v-tab>

                                      <v-tab class="h-100" v-if="answers_on_comments.funny != 0">
                                        <v-img max-height="20" max-width="20" lazy-src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/smiling-face-with-open-mouth-and-smiling-eyes_1f604.png"
                                          src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/smiling-face-with-open-mouth-and-smiling-eyes_1f604.png" />
                                        <span class="ml-1">{{answers_on_comments.funny}}</span>
                                      </v-tab>

                                      <v-tab class="h-100" v-if="answers_on_comments.surprise != 0">
                                        <v-img max-height="20" max-width="20" lazy-src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/face-with-open-mouth_1f62e.png"
                                          src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/face-with-open-mouth_1f62e.png" />
                                        <span class="ml-1">{{answers_on_comments.surprise}}</span>
                                      </v-tab>

                                      <v-tab class="h-100" v-if="answers_on_comments.sad != 0">
                                        <v-img max-height="20" max-width="20" lazy-src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/disappointed-but-relieved-face_1f625.png"
                                          src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/disappointed-but-relieved-face_1f625.png" />
                                        <span class="ml-1">{{answers_on_comments.sad}}</span>
                                      </v-tab>

                                      <v-tab class="h-100" v-if="answers_on_comments.angry != 0">
                                        <v-img max-height="20" max-width="20" lazy-src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/pouting-face_1f621.png"
                                          src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/pouting-face_1f621.png" />
                                        <span class="ml-1">{{answers_on_comments.angry}}</span>
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

                                  <v-tabs-items v-model="tabReactionsAnswerOnComment[answers_on_comments.id]">
                                    <v-tab-item>
                                      <v-card flat>
                                        <v-card-text class="p-0">
                                          <v-list-item-group v-model="reactionsAnswerOnCommentAll" color="primary" v-for="(item, index) in $root.navigationDrawerCenter.getAllCommentReactions.answers_reactions" :key="'all'+index">
                                            <v-list-item v-if="answers_on_comments.id == item.answer_on_comment_id">
                                              <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                                <v-avatar color="primary" size="40" v-if="item.avatar == null">
                                                  <span class="text-uppercase white--text">{{ item.name.charAt(0) }}</span>
                                                </v-avatar>
                                                <v-avatar color="primary" size="40" v-else>
                                                  <v-img :lazy-src="`http://127.0.0.1:8000/storage/user-avatar/${item.avatar}`" :src="`http://127.0.0.1:8000/storage/user-avatar/${item.avatar}`" />
                                                </v-avatar>
                                              </v-badge>
                                              <v-list-item-content>
                                                <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.name"></v-list-item-title>
                                                <v-list-item-subtitle class="d-flex justify-start questrial caption grey--text" v-text="item.text"></v-list-item-subtitle>
                                                <v-list-item-subtitle class="d-flex justify-start font-weight-bold primary--text">
                                                  <v-icon v-if="item.text2" small color="primary">mdi-check-circle</v-icon> {{item.text2}}
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
                                    </v-tab-item>

                                    <v-tab-item v-if="answers_on_comments.like != 0">
                                      <v-card flat>
                                        <v-card-text class="p-0">
                                          <v-list-item-group v-model="reactionsAnswerOnCommentLike" color="primary" v-for="(item, index) in $root.navigationDrawerCenter.getAllCommentReactions.answers_reactions" :key="'like'+index">
                                            <v-list-item v-if="item.reaction_type == 'like' && answers_on_comments.id == item.answer_on_comment_id">
                                              <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                                <v-avatar color="primary" size="40" v-if="item.avatar == null">
                                                  <span class="text-uppercase white--text">{{ item.name.charAt(0) }}</span>
                                                </v-avatar>
                                                <v-avatar color="primary" size="40" v-else>
                                                  <v-img :lazy-src="`http://127.0.0.1:8000/storage/user-avatar/${item.avatar}`" :src="`http://127.0.0.1:8000/storage/user-avatar/${item.avatar}`" />
                                                </v-avatar>
                                              </v-badge>
                                              <v-list-item-content>
                                                <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.name"></v-list-item-title>
                                                <v-list-item-subtitle class="d-flex justify-start questrial caption grey--text" v-text="item.text"></v-list-item-subtitle>
                                                <v-list-item-subtitle class="d-flex justify-start font-weight-bold primary--text">
                                                  <v-icon v-if="item.text2" small color="primary">mdi-check-circle</v-icon> {{item.text2}}
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
                                    </v-tab-item>

                                    <v-tab-item v-if="answers_on_comments.heart != 0">
                                      <v-card flat>
                                        <v-card-text class="p-0">
                                          <v-list-item-group v-model="reactionsAnswerOnCommentHeart" color="primary" v-for="(item, index) in $root.navigationDrawerCenter.getAllCommentReactions.answers_reactions" :key="'heart'+index">
                                            <v-list-item v-if="item.reaction_type == 'heart' && answers_on_comments.id == item.answer_on_comment_id">
                                              <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                                <v-avatar color="primary" size="40" v-if="item.avatar == null">
                                                  <span class="text-uppercase white--text">{{ item.name.charAt(0) }}</span>
                                                </v-avatar>
                                                <v-avatar color="primary" size="40" v-else>
                                                  <v-img :lazy-src="`http://127.0.0.1:8000/storage/user-avatar/${item.avatar}`" :src="`http://127.0.0.1:8000/storage/user-avatar/${item.avatar}`" />
                                                </v-avatar>
                                              </v-badge>
                                              <v-list-item-content>
                                                <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.name"></v-list-item-title>
                                                <v-list-item-subtitle class="d-flex justify-start questrial caption grey--text" v-text="item.text"></v-list-item-subtitle>
                                                <v-list-item-subtitle class="d-flex justify-start font-weight-bold primary--text">
                                                  <v-icon v-if="item.text2" small color="primary">mdi-check-circle</v-icon> {{item.text2}}
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
                                    </v-tab-item>

                                    <v-tab-item v-if="answers_on_comments.funny != 0">
                                      <v-card flat>
                                        <v-card-text class="p-0">
                                          <v-list-item-group v-model="reactionsAnswerOnCommentFunny" color="primary" v-for="(item, index) in $root.navigationDrawerCenter.getAllCommentReactions.answers_reactions" :key="'funny'+index">
                                            <v-list-item v-if="item.reaction_type == 'funny' && answers_on_comments.id == item.answer_on_comment_id">
                                              <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                                <v-avatar color="primary" size="40" v-if="item.avatar == null">
                                                  <span class="text-uppercase white--text">{{ item.name.charAt(0) }}</span>
                                                </v-avatar>
                                                <v-avatar color="primary" size="40" v-else>
                                                  <v-img :lazy-src="`http://127.0.0.1:8000/storage/user-avatar/${item.avatar}`" :src="`http://127.0.0.1:8000/storage/user-avatar/${item.avatar}`" />
                                                </v-avatar>
                                              </v-badge>
                                              <v-list-item-content>
                                                <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.name"></v-list-item-title>
                                                <v-list-item-subtitle class="d-flex justify-start questrial caption grey--text" v-text="item.text"></v-list-item-subtitle>
                                                <v-list-item-subtitle class="d-flex justify-start font-weight-bold primary--text">
                                                  <v-icon v-if="item.text2" small color="primary">mdi-check-circle</v-icon> {{item.text2}}
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
                                    </v-tab-item>

                                    <v-tab-item v-if="answers_on_comments.surprise != 0">
                                      <v-card flat>
                                        <v-card-text class="p-0">
                                          <v-list-item-group v-model="reactionsAnswerOnCommentSurprise" color="primary" v-for="(item, index) in $root.navigationDrawerCenter.getAllCommentReactions.answers_reactions" :key="'surprise'+index">
                                            <v-list-item v-if="item.reaction_type == 'surprise' && answers_on_comments.id == item.answer_on_comment_id">
                                              <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                                <v-avatar color="primary" size="40" v-if="item.avatar == null">
                                                  <span class="text-uppercase white--text">{{ item.name.charAt(0) }}</span>
                                                </v-avatar>
                                                <v-avatar color="primary" size="40" v-else>
                                                  <v-img :lazy-src="`http://127.0.0.1:8000/storage/user-avatar/${item.avatar}`" :src="`http://127.0.0.1:8000/storage/user-avatar/${item.avatar}`" />
                                                </v-avatar>
                                              </v-badge>
                                              <v-list-item-content>
                                                <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.name"></v-list-item-title>
                                                <v-list-item-subtitle class="d-flex justify-start questrial caption grey--text" v-text="item.text"></v-list-item-subtitle>
                                                <v-list-item-subtitle class="d-flex justify-start font-weight-bold primary--text">
                                                  <v-icon v-if="item.text2" small color="primary">mdi-check-circle</v-icon> {{item.text2}}
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
                                    </v-tab-item>

                                    <v-tab-item v-if="answers_on_comments.sad != 0">
                                      <v-card flat>
                                        <v-card-text class="p-0">
                                          <v-list-item-group v-model="reactionsAnswerOnCommentSad" color="primary" v-for="(item, index) in $root.navigationDrawerCenter.getAllCommentReactions.answers_reactions" :key="'sad'+index">
                                            <v-list-item v-if="item.reaction_type == 'sad' && answers_on_comments.id == item.answer_on_comment_id">
                                              <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                                <v-avatar color="primary" size="40" v-if="item.avatar == null">
                                                  <span class="text-uppercase white--text">{{ item.name.charAt(0) }}</span>
                                                </v-avatar>
                                                <v-avatar color="primary" size="40" v-else>
                                                  <v-img :lazy-src="`http://127.0.0.1:8000/storage/user-avatar/${item.avatar}`" :src="`http://127.0.0.1:8000/storage/user-avatar/${item.avatar}`" />
                                                </v-avatar>
                                              </v-badge>
                                              <v-list-item-content>
                                                <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.name"></v-list-item-title>
                                                <v-list-item-subtitle class="d-flex justify-start questrial caption grey--text" v-text="item.text"></v-list-item-subtitle>
                                                <v-list-item-subtitle class="d-flex justify-start font-weight-bold primary--text">
                                                  <v-icon v-if="item.text2" small color="primary">mdi-check-circle</v-icon> {{item.text2}}
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
                                    </v-tab-item>

                                    <v-tab-item v-if="answers_on_comments.angry != 0">
                                      <v-card flat>
                                        <v-card-text class="p-0">
                                          <v-list-item-group v-model="reactionsAnswerOnCommentAngry" color="primary" v-for="(item, index) in $root.navigationDrawerCenter.getAllCommentReactions.answers_reactions" :key="'angry'+index">
                                            <v-list-item v-if="item.reaction_type == 'angry' && answers_on_comments.id == item.answer_on_comment_id">
                                              <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                                <v-avatar color="primary" size="40" v-if="item.avatar == null">
                                                  <span class="text-uppercase white--text">{{ item.name.charAt(0) }}</span>
                                                </v-avatar>
                                                <v-avatar color="primary" size="40" v-else>
                                                  <v-img :lazy-src="`http://127.0.0.1:8000/storage/user-avatar/${item.avatar}`" :src="`http://127.0.0.1:8000/storage/user-avatar/${item.avatar}`" />
                                                </v-avatar>
                                              </v-badge>
                                              <v-list-item-content>
                                                <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.name"></v-list-item-title>
                                                <v-list-item-subtitle class="d-flex justify-start questrial caption grey--text" v-text="item.text"></v-list-item-subtitle>
                                                <v-list-item-subtitle class="d-flex justify-start font-weight-bold primary--text">
                                                  <v-icon v-if="item.text2" small color="primary">mdi-check-circle</v-icon> {{item.text2}}
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
                                    </v-tab-item>
                                  </v-tabs-items>
                                  <!-- <v-divider class="mt-0 mb-0"></v-divider>

                                  <v-card-actions class="justify-end">
                                    <v-btn text outlined class="primary white--text" @click="dialogReactionsAnswerOnComment.value = false">Zavrieť</v-btn>
                                  </v-card-actions> -->
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
                              <v-btn class="mt-1 caption" x-small text rounded outlined v-on="on">
                                Páči sa mi to
                              </v-btn>
                            </template>

                            <v-card class="rounded-pill p-2" elevation="2">
                              <v-btn icon @click="rateComment('like', answers_on_comments.id, 'answers_on_comments')">
                                <v-avatar size="20">
                                  <v-img src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/thumbs-up-sign_1f44d.png">
                                  </v-img>
                                </v-avatar>
                              </v-btn>
                              <v-btn icon @click="rateComment('heart', answers_on_comments.id, 'answers_on_comments')">
                                <v-avatar size="20">
                                  <v-img src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/heavy-black-heart_2764.png">
                                  </v-img>
                                </v-avatar>
                              </v-btn>
                              <v-btn icon @click="rateComment('funny', answers_on_comments.id, 'answers_on_comments')">
                                <v-avatar size="20">
                                  <v-img src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/smiling-face-with-open-mouth-and-smiling-eyes_1f604.png">
                                  </v-img>
                                </v-avatar>
                              </v-btn>

                              <v-btn icon @click="rateComment('surprise', answers_on_comments.id, 'answers_on_comments')">
                                <v-avatar size="20">
                                  <v-img src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/face-with-open-mouth_1f62e.png">
                                  </v-img>
                                </v-avatar>
                              </v-btn>

                              <v-btn icon @click="rateComment('sad', answers_on_comments.id, 'answers_on_comments')">
                                <v-avatar size="20">
                                  <v-img src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/disappointed-but-relieved-face_1f625.png">
                                  </v-img>
                                </v-avatar>
                              </v-btn>

                              <v-btn icon @click="rateComment('angry', answers_on_comments.id, 'answers_on_comments')">
                                <v-avatar size="20">
                                  <v-img src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/facebook/65/pouting-face_1f621.png">
                                  </v-img>
                                </v-avatar>
                              </v-btn>
                            </v-card>
                          </v-menu>

                          <!-- //////////////////////////////////////////////////////////////////////////////answer on comment/////////////////////////////////////////////////////////////////////// -->
                          <v-btn @click.stop x-small text rounded outlined class="caption primary--text font-weight-bold mt-1 ml-1" @click="focusInputAnswerOnComment(answers_on_comments)">
                            Odpovedať
                          </v-btn>
                        </v-row>

                        <v-row class="ml-0 mr-0 mt-0">
                          <v-btn v-if="answers_on_comments.count_comments == 1" text small class="d-flex justify-start caption primary--text font-weight-bold">
                            <v-icon class="mr-1" small color="primary">mdi-subdirectory-arrow-right</v-icon>
                            {{answers_on_comments.count_comments}} Odpoveď
                          </v-btn>
                          <v-btn v-if="answers_on_comments.count_comments > 1" text small class="d-flex justify-start caption primary--text font-weight-bold">
                            <v-icon class="mr-1" small color="primary">mdi-subdirectory-arrow-right</v-icon>
                            {{answers_on_comments.count_comments}} Odpovede
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

                <!-- <v-container v-if="itemsAnswerOnComment" :id="`itemsAnswerOnComment${answers_on_comments.id}`" class="pr-0 pl-0" >
                    <v-list-item style="margin-left:116px">
                      <v-list-item-avatar>
                        <v-avatar color="primary" size="28" v-if="post.avatar == null">
                          <span v-if="post.name != null" class="text-uppercase white--text">{{ post.name.charAt(0) }}</span>
                        </v-avatar>
                        <v-avatar color="primary" size="28" v-else>
                          <v-img lazy-src="`http://127.0.0.1:8000/storage/user-avatar/${post.avatar}`" :src="`http://127.0.0.1:8000/storage/user-avatar/${post.avatar}`" />
                        </v-avatar>
                      </v-list-item-avatar>
                      <v-list-item-content>
                        <v-text-field :hide-details="true" ref="commentInputParent" filled rounded dense clearable clear-icon="mdi-close" label="Koment" v-model="textNewAnswerOnComment" @click:append-outer="test" @click:prepend="changeIcon"
                          @click:clear="clearAnswerOnComment(answers_on_comments)" @keydown.enter="answerOnComment(answers_on_comments.id)">
                          <template slot="append">
                            <v-menu :close-on-content-click="false" v-model="autoselectMenu" offset-y :position-y="60">
                              <template v-slot:activator="{ on, attrs }">
                                <v-icon class="mr-2" v-bind="attrs" v-on="on">
                                  mdi-emoticon
                                </v-icon>
                              </template>
                              <picker :showSearch="false" :showPreview="false" :set="'messenger'" :showSkinTones="true" :emojiTooltip="true" :infiniteScroll="false" @select="selectEmoji" :i18n="i18n" />
                            </v-menu>
                            <v-menu class="menu-append-icon" offset-y v-model="autoselectMenu2" rounded="pill">
                              <template v-slot:activator="{ on, attrs }">
                                <v-icon v-bind="attrs" v-on="on">
                                  mdi-dots-grid
                                </v-icon>
                              </template>
                              <v-card class="rounded-pill p-2" elevation="2">
                                <v-btn icon class="mr-2">
                                  <v-icon>
                                    mdi-camera
                                  </v-icon>
                                </v-btn>
                                <v-btn icon class="mr-2">
                                  <v-icon>
                                    mdi-image-area
                                  </v-icon>
                                </v-btn>
                                <v-btn icon>
                                  <v-icon>
                                    mdi-paperclip
                                  </v-icon>
                                </v-btn>
                              </v-card>
                            </v-menu>
                          </template>
                        </v-text-field>
                      </v-list-item-content>
                    </v-list-item>
                    <v-divider v-if="posts_comments.length > 1" />
                  </v-container> -->

                <!-- odpoved na post -->
                <v-container :id="`itemsPostComment${posts_comments.id}`" class="pr-0 pl-0 pt-0" style="display:none">
                  <!--v-if="itemsPostComment"  -->
                  <v-list-item class="ml-16">
                    <v-list-item-avatar>
                      <v-avatar color="primary" size="28" v-if="post.avatar == null">
                        <span v-if="post.name != null" class="text-uppercase white--text">{{ post.name.charAt(0) }}</span>
                      </v-avatar>
                      <v-avatar color="primary" size="28" v-else>
                        <v-img :lazy-src="`http://127.0.0.1:8000/storage/user-avatar/${post.avatar}`" :src="`http://127.0.0.1:8000/storage/user-avatar/${post.avatar}`" />
                      </v-avatar>
                    </v-list-item-avatar>
                    <v-list-item-content>
                      <!-- <v-menu class="p-2" bottom max-width="400px" rounded offset-y v-model="showDialogFriendsAnswerOnComment"> -->
                      <!-- <template v-slot:activator="{ on }"> -->
                      <!-- v-on="on"  -->
                      <v-text-field @focus="focusedAnswerOnComment(posts_comments.id)" :hide-details="true" ref="commentInputChild" filled rounded dense clearable clear-icon="mdi-close" label="Koment" v-model="textNewAnswerOnComment"
                        @click:clear="clearAnswerOnComment(posts_comments.id)" @keydown.enter="answerOnComment(posts_comments.id)">
                        <template v-slot:prepend-inner v-if="chipTextAnswerOnComment != ''">
                          <v-chip class="ma-1" close @click:close="closeChipAnswerOnComment">{{chipTextAnswerOnComment}}</v-chip>
                        </template>
                        <template slot="append">
                          <v-menu :close-on-content-click="false" v-model="autoselectMenuComments[posts_comments.id]" offset-y :position-y="60">
                            <template v-slot:activator="{ on, attrs }">
                              <v-icon class="mr-2" v-bind="attrs" v-on="on" @click="setEmojiID(post.id)">
                                mdi-emoticon
                              </v-icon>
                            </template>
                            <picker :showSearch="false" :showPreview="false" :set="'messenger'" :showSkinTones="true" :emojiTooltip="true" :infiniteScroll="false" @select="selectEmojiAnswerOnComment" :i18n="i18n" />
                          </v-menu>
                          <v-menu class="menu-append-icon" offset-y v-model="autoselectMenu2Comments[posts_comments.id]" rounded="pill">
                            <template v-slot:activator="{ on, attrs }">
                              <v-icon v-bind="attrs" v-on="on">
                                mdi-dots-grid
                              </v-icon>
                            </template>
                            <v-card class="rounded-pill p-2" elevation="2">
                              <v-btn icon class="mr-2">
                                <v-icon>
                                  mdi-camera
                                </v-icon>
                              </v-btn>
                              <v-btn icon class="mr-2">
                                <v-icon>
                                  mdi-image-area
                                </v-icon>
                              </v-btn>
                              <v-btn icon>
                                <v-icon>
                                  mdi-paperclip
                                </v-icon>
                              </v-btn>
                            </v-card>
                          </v-menu>
                        </template>
                      </v-text-field>
                      <!-- </template>
                        <v-list class="p-0" dense>
                          <v-list-item-group color="primary">
                            <v-subheader>Vybrať priateľa</v-subheader>
                            <template v-for="item in displayFriendsAnswerOnComment">
                              <v-list-item :key="item.id" @click="selectDataAboutUser(item)">
                                <v-badge bottom dot bordered :color="getColor(item.status)" offset-x="10" offset-y="10" class="mr-2">
                                  <v-avatar color="primary" size="40" v-if="item.avatar == null">
                                    <span class="text-uppercase white--text">{{ item.name.charAt(0) }}</span>
                                  </v-avatar>
                                  <v-avatar color="primary" size="40" v-else>
                                    <img :lazy-src="`http://127.0.0.1:8000/storage/user-avatar/${item.avatar}`" :src="`http://127.0.0.1:8000/storage/user-avatar/${item.avatar}`">
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
                      </v-menu> -->
                    </v-list-item-content>
                  </v-list-item>
                  <v-divider />
                </v-container>

              </v-container>
              <!-- v-list-group -->
            </template>
          </v-list>
        </v-container>
      </v-card>
    </v-flex>
</template>
</v-container>
</template>

<script>
import axios from 'axios';
import {
  Picker
} from 'emoji-mart-vue'
// import moment from 'moment';

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
      imageUrl: null,
      videoUrl: null,
      imageUrlPostComment: null,
      videoUrlPostComment: null,
      imageUrlAnswerOnComment: null,
      videoUrlAnswerOnComment: null,

      showDragAndDropFile: false,
      files: [],
      urls: [],
      photo: null,

      // posts: [],
      // posts_reactions: [],
      // posts_shares: [],
      // user_posts_shares: [],
      // posts_comments: [],
      // answers_on_comments: [],
      // answers_reactions: [],
      // overlay: true,

      dialogPostShares: {},
      message: '',
      answerParent: '',
      answerChild: '',
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
      moreTest: {},
      openCommentsFiltermenu: false,
      // showCommentsFilter: false,
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

      disabedSharesDialog: false,

      bn: '',
      switch1: false,
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

      //slide show
      slideShow: null,
      chipTextPostComment: [],
      chipTextAnswerOnComment: '',
      displayFriendsPostComment: [],
      displayFriendsAnswerOnComment: [],
      showDialogFriendsPostComment: [],
      showDialogFriendsAnswerOnComment: false,
      selectUserFriendsPostComment: '',
      focusedInputPostComment: null,
      focusedInputAnswerOnComment: null,
      overlayDisplayFriendsPostComment: true,
    }
  },

  computed: {},

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
  },

  mounted() {
    // this.getDataOfMe();
    // this.getAllPosts();
    // this.getAllPostsReactions();
    // this.getAllPostsShares();
    // this.getUserPostsShares();
    // this.getAllPostsComments();
    // this.getAllAnswersOnComments();
    // this.getAllCommentReactions();
  },

  methods: {
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
      this.chipTextPostComment.splice(id, 1)
      this.selectUserFriendsPostComment = '';
      this.$nextTick(() => this.$refs[`commentInput${id}`][0].focus());
    },

    closeChipAnswerOnComment() {
      this.chipTextAnswerOnComment = '';
      this.$nextTick(() => this.$refs.commentInputChild[0].focus());
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

        document.getElementById(`showPostDetailsArrowDown${id}`).style.display = 'none';
        document.getElementById(`showPostDetailsArrowUp${id}`).style.display = 'block';
      } else {
        document.getElementById(`showPostDetails${id}`).style.display = 'block';

        document.getElementById(`showPostDetailsArrowDown${id}`).style.display = 'block';
        document.getElementById(`showPostDetailsArrowUp${id}`).style.display = 'none';
      }

    },

    focusInput(id) {
      if (document.getElementById(`showPostDetails${id}`).style.display != 'block') {
        document.getElementById(`showPostDetails${id}`).style.display = 'block';
        if (document.getElementById(`showPostDetailsArrowDown${id}`)) {
          document.getElementById(`showPostDetailsArrowDown${id}`).style.display = 'block';
          document.getElementById(`showPostDetailsArrowUp${id}`).style.display = 'none';
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
        .then(() => {
          // function getAllPostsReactions
          const api = `${process.env.VUE_APP_API_URL}/get_all_posts_reactions`;
          const config = {
            headers: {
              Accept: "application/json",
              Authorization: "Bearer " + localStorage.getItem("authToken"),
            },
          };
          axios.get(api, config)
            .then(res => {
              this.$root.navigationDrawerCenter.getAllPostsReactions.posts_reactions = res.data;
            });
        })
        .catch((error) => {
          console.log(error)
        })
    },



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
        .then(() => {
          //function getAllPostsShares
          const api = `${process.env.VUE_APP_API_URL}/get_all_posts_shares`;
          const config = {
            headers: {
              Accept: "application/json",
              Authorization: "Bearer " + localStorage.getItem("authToken"),
            },
          };
          axios.get(api, config)
            .then(res => {
              this.$root.navigationDrawerCenter.getAllPostsShares.posts_shares = res.data;
            })
        })
        .catch((error) => {
          console.log(error)
        })
    },

    setEmojiID(id) {
      console.log(id);
      this.emojiID = id;
    },
    // emoji input char
    selectEmoji(emoji) {
      console.log(emoji);
      console.log(this.emojiID);
      if (this.textNewPostComment[this.emojiID] != null) {
        this.textNewPostComment[this.emojiID] += emoji.native;
      } else {
        this.textNewPostComment[this.emojiID] = emoji.native;
      }
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
      console.log("tutut");
      this.textNewPostComment[id] = [];
      this.itemsPostComment = false;
    },

    clearAnswerOnComment() {
      this.textNewAnswerOnComment = '';
      this.itemsPostComment = false;
      for (var i = 0; i < this.$root.navigationDrawerCenter.getAllPostsComments.posts_comments.length; i++) {
        document.getElementById(`itemsPostComment${this.$root.navigationDrawerCenter.getAllPostsComments.posts_comments[i].id}`).style.display = 'none';
      }
      for (var x = 0; x < this.$root.navigationDrawerCenter.getAllAnswersOnComments.answers_on_comments.length; x++) {
        document.getElementById(`itemsPostComment${this.$root.navigationDrawerCenter.getAllAnswersOnComments.answers_on_comments[x].comment_id}`).style.display = 'none';
      }
    },

    focusInputAnswerOnComment(item) {
      if (item.post_id != undefined) {
        if (document.getElementById(`itemsPostComment${item.id}`).style != null) {
          for (var i = 0; i < this.$root.navigationDrawerCenter.getAllPostsComments.posts_comments.length; i++) {
            document.getElementById(`itemsPostComment${this.$root.navigationDrawerCenter.getAllPostsComments.posts_comments[i].id}`).style.display = 'none';
          }
          if (document.getElementById(`itemsPostComment${item.id}`).style.display == 'none') {
            document.getElementById(`itemsPostComment${item.id}`).style.display = 'block';
            // this.textNewAnswerOnComment = item.name + ' ';
            this.chipTextAnswerOnComment = item.name + ' ';
            this.textNewAnswerOnComment = '';
            this.$nextTick(() => this.$refs.commentInputChild[0].focus());
            this.showDialogFriendsAnswerOnComment = false;
            this.$refs.commentInputChild[0].focus();
          }
        }
      } else {
        if (document.getElementById(`itemsPostComment${item.comment_id}`).style != null) {
          for (var x = 0; x < this.$root.navigationDrawerCenter.getAllAnswersOnComments.answers_on_comments.length; x++) {
            document.getElementById(`itemsPostComment${this.$root.navigationDrawerCenter.getAllAnswersOnComments.answers_on_comments[x].comment_id}`).style.display = 'none';
          }
          if (document.getElementById(`itemsPostComment${item.comment_id}`).style.display == 'none') {
            document.getElementById(`itemsPostComment${item.comment_id}`).style.display = 'block';
            // this.textNewAnswerOnComment = item.name + ' ';
            this.chipTextAnswerOnComment = item.name + ' ';
            this.textNewAnswerOnComment = '';
            this.$nextTick(() => this.$refs.commentInputChild[0].focus());
            this.showDialogFriendsAnswerOnComment = false;
            this.$refs.commentInputChild[0].focus();
          }
        }
      }
    },
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //////////////////////////////////////////////////photo
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
    ///////////////////////////////////////////////////////
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
        .then((res) => {
          console.log(res);
          // this.getAllPosts();
        })
        .catch((error) => {
          console.log(error)
        })
    },
    ///////////////////////////////////////////////////////////////////////////////


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
      axios.post(api, {
            post_id: index,
            text: this.textNewPostComment[index],
            image_url: this.imageUrlPostComment,
            video_url: this.videoUrlPostComment
          },
          config
        )
        .then(() => {
          //function getAllPostsComments
          const api = `${process.env.VUE_APP_API_URL}/get_all_posts_comments`;
          const config = {
            headers: {
              Accept: "application/json",
              Authorization: "Bearer " + localStorage.getItem("authToken"),
            },
          };
          axios.get(api, config)
            .then((res) => {
              this.$root.navigationDrawerCenter.getAllPostsComments.posts_comments = res.data;
            });
          this.clearPostComment(index);
        })
        .catch((error) => {
          console.log(error)
        })
    },

    //answer on comment

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
      axios.post(api, {
            comment_id: index,
            text: this.textNewAnswerOnComment,
            image_url: this.imageUrlAnswerOnComment,
            video_url: this.videoUrlAnswerOnComment
          },
          config
        )
        .then(() => {
          // function getAllAnswersOnComments
          const api = `${process.env.VUE_APP_API_URL}/get_all_answers_on_comments`;
          const config = {
            headers: {
              Accept: "application/json",
              Authorization: "Bearer " + localStorage.getItem("authToken"),
            },
          };
          axios.get(api, config)
            .then(res => {
              this.$root.navigationDrawerCenter.getAllAnswersOnComments.answers_on_comments = res.data;
            });
          this.clearAnswerOnComment();
        })
        .catch((error) => {
          console.log(error)
        })
    },



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
        .then(() => {
          //function getAllCommentReactions
          const api = `${process.env.VUE_APP_API_URL}/get_all_comments_reactions`;
          const config = {
            headers: {
              Accept: "application/json",
              Authorization: "Bearer " + localStorage.getItem("authToken"),
            },
          };
          axios.get(api, config)
            .then((res) => {
              this.$root.navigationDrawerCenter.getAllCommentReactions.answers_reactions = res.data;
            });
        })
        .catch((error) => {
          console.log(error)
        })
    },

    deletePost(item) {
      console.log(item);
    },

    show_comments(post_comments_id) {
      var length = document.getElementsByClassName("answers_on_comments" + post_comments_id).length;
      for (var i = 0; i < length; i++) {
        if (document.getElementsByClassName("answers_on_comments" + post_comments_id)[i].style.display == 'none') {


          document.getElementsByClassName("answers_on_comments" + post_comments_id)[i].style.display = 'block';
          document.getElementsByClassName("btn_answers_on_comments_arrow_down" + post_comments_id)[0].style.display = 'block';
          document.getElementsByClassName("btn_answers_on_comments_arrow_up" + post_comments_id)[0].style.display = 'none';
        } else {
          document.getElementsByClassName("answers_on_comments" + post_comments_id)[i].style.display = 'none';
          document.getElementsByClassName("btn_answers_on_comments_arrow_down" + post_comments_id)[0].style.display = 'none';
          document.getElementsByClassName("btn_answers_on_comments_arrow_up" + post_comments_id)[0].style.display = 'block';
        }
      }
    },
  },

  updated() {},
};
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

.center .v-text-field--enclosed.v-input--dense:not(.v-text-field--solo) .v-input__prepend-inner {
  margin-top: 6px !important;
}
</style>
