<template id="Conversation">
<v-card elevation="0" v-if="contact.id != null" rounded>
  <v-app-bar class="fixed-bar" flat v-if="dialogOpen == false">
    <v-toolbar-title>
      <v-tooltip right>
        <template v-slot:activator="{ on, attrs }">
          <v-chip v-bind="attrs" v-on="on">
            <v-badge bottom dot bordered :color="getColor(contact.status)" offset-x="5" offset-y="10" class="mr-2">
              <v-avatar color="primary" size="16" v-if="contact.avatar == null">
                <span class="text-uppercase white--text pt-1">{{ contact.name.charAt(0) }}</span>
              </v-avatar>
              <v-avatar color="primary" size="16" v-else>
                <img :src="`${$root.envUrlNoApi}/storage/user-avatar/${contact.avatar}`">
              </v-avatar>
            </v-badge>
            {{contact.name}}
          </v-chip>
        </template>
        <span>Offline</span>
      </v-tooltip>
    </v-toolbar-title>

    <v-spacer></v-spacer>

    <v-tooltip bottom>
      <template v-slot:activator="{ on, attrs }">
        <v-btn icon v-bind="attrs" v-on="on">
          <v-icon>mdi-phone</v-icon>
        </v-btn>
      </template>
      <span>Hovor</span>
    </v-tooltip>

    <v-tooltip bottom>
      <template v-slot:activator="{ on, attrs }">
        <v-btn icon v-bind="attrs" v-on="on">
          <v-icon>mdi-video</v-icon>
        </v-btn>
      </template>
      <span>Videohovor</span>
    </v-tooltip>

    <v-tooltip bottom>
      <template v-slot:activator="{ on, attrs }">
        <v-btn icon v-bind="attrs" v-on="on">
          <v-icon>mdi-dots-vertical</v-icon>
        </v-btn>
      </template>
      <span>Nastavenia</span>
    </v-tooltip>

    <v-tooltip bottom>
      <template v-slot:activator="{ on, attrs }">
        <v-btn icon v-bind="attrs" v-on="on" @click="closeChat()">
          <v-icon>mdi-close</v-icon>
        </v-btn>
      </template>
      <span>Zavrieť chat</span>
    </v-tooltip>
  </v-app-bar>

  <v-app-bar class="fixed-bar" flat v-else>
    <v-btn text rounded outlined primary @click="answerOnMessage()">Odpovedať</v-btn>
    <v-spacer></v-spacer>
    <v-tooltip bottom>
      <template v-slot:activator="{ on, attrs }">
        <v-btn icon v-bind="attrs" v-on="on" @click="dialogOpen = false; replyOnMessageBtn = false; replyOnMessage = null">
          <v-icon>mdi-close</v-icon>
        </v-btn>
      </template>
      <span>Zavrieť chat</span>
    </v-tooltip>
  </v-app-bar>

  <!-- <v-divider v-if="!$vuetify.theme.dark" class="mt-0 mb-0" /> -->

  <div id="feed" :class="feed" :typingStatus="typingStatus" :contact="contact" :messages="messages">
    <v-card elevation="0">
      <v-list class="pt-3 pb-3 overflow-y-auto" style="height:100vh" v-if="this.overlayMessages == true">
        <v-overlay :value="this.overlayMessages" :absolute="true" :opacity="0">
          <v-progress-circular indeterminate size="24" color="primary"></v-progress-circular>
        </v-overlay>
      </v-list>
      <v-list class="pt-3 pb-3 overflow-y-auto" ref="card_body" v-if="contact && this.overlayMessages == false">
        <!-- v-chat-scroll="{smooth: true, notSmoothOnInit: true}" -->
        <!-- <v-list-item v-for="message in messages" class="pt-4 pb-4 pl-2 pr-2" :key="message.id"> -->
        <div v-for="(item, key, index) in groupByDayConversation" :key="index">
          <v-col class="pl-3 pr-6 pb-3 d-flex justify-content-center w-100 mb-2">
            <v-btn large text block class="text-center primary--text">{{new Date(Date.parse(key)).toLocaleDateString('sk', { year: 'numeric', month: 'long', day: '2-digit' })}}</v-btn>
          </v-col>
          <!-- `message${message.to = contact.id ? ' sent' : ' received'}  -->
          <v-list-item v-for="(message, index2) in item" :key="index2" class="pt-4 pb-4 pl-2 pr-2">
            <v-col cols="12" class="p-0" v-if="message.from != user_id">
              <div class="bubble_friend2">
                <span>{{ message.created_at.substr(11, 5) }}</span>
              </div>
              <v-list-item class="p-0 float-left pr-5" style="max-width: 100%;">
                <v-list-item-avatar size="30" class="mr-2">
                  <v-avatar color="secondary" size="30" v-if="message.from_contact.avatar == null">
                    <span v-if="message.from_contact.name != null" class="text-uppercase white--text">{{ message.from_contact.name.charAt(0) }}</span>
                  </v-avatar>
                  <v-avatar color="secondary" size="30" v-else>
                    <v-img :lazy-src="`${$root.envUrlNoApi}/storage/user-avatar/${message.from_contact.avatar}`" :src="`${$root.envUrlNoApi}/storage/user-avatar/${message.from_contact.avatar}`" />
                  </v-avatar>
                </v-list-item-avatar>

                <v-list-item-content class="p-0">
                  <v-menu bottom offset-y min-width="200px" rounded="pill" z-index="4">
                    <!-- open-on-hover  -->
                    <template v-slot:activator="{ on }">
                      <v-card v-on="on" class="rounded-lg float-left" :id="`message${message.id}`" elevation="0" :style="{background: $vuetify.theme.themes[isDark].secondary}" @click="skuska(message)">
                        <v-card-text class="text-left font-weight-bold p-0 text-wrap">
                          <v-list-item class="p-0 pr-2 pl-1" v-if="message.reply_id != null" style="max-width:400px; background:rgba(0,0,0,.15)">
                            <v-icon size="20" class="white--text mr-2">mdi-share-outline</v-icon>
                            <v-list-item-content>
                              <v-list-item-title class="text-left p-0" v-if="message.reply_message.from == message.from">
                                <span class="white--text"><small>Odpoveď na svoju správu.</small></span>
                              </v-list-item-title>
                              <v-list-item-title class="text-left p-0" v-else>
                                <span class="white--text"><small>Odpoveď na správu používateľa <span class="font-weight-bold">{{ message.reply_message.from_contact.name }}</span></small></span>
                              </v-list-item-title>

                              <v-list-item class="p-0" v-if="message.reply_message.image_url != null" style="max-width:150px ">
                                <v-list-item-content class="p-0 pb-1 pt-2">
                                  <v-img style="border-radius: 8px;" :lazy-src="`${$root.envUrlNoApi}/storage/post_img_video/${message.reply_message.image_url}`"
                                    :src="`${$root.envUrlNoApi}/storage/post_img_video/${message.reply_message.image_url}`" />
                                </v-list-item-content>
                              </v-list-item>

                              <v-list-item class="p-0" v-if="message.reply_message.video_url != null" style="max-width:150px">
                                <v-list-item-content class="p-0 pb-1 pt-2" style="border-radius: 8px;">
                                  <vue-plyr>
                                    <video controls playsinline>
                                      <source size="1080" :src="$root.envUrlNoApi + '/storage/post_img_video/' +  message.reply_message.video_url" type="video/mp4">
                                    </video>
                                  </vue-plyr>
                                </v-list-item-content>
                              </v-list-item>

                              <!-- <v-list-item-subtitle class="text-left white--text font-weight-bold p-0 text-wrap" > -->
                              <!-- <span v-if="message.reply_message.text != null">{{message.reply_message.text}}</span> -->
                              <div class="white--text text-wrap" v-if="message.reply_message.text != null">{{ message.reply_message.text }}</div>
                              <!-- </v-list-item-subtitle> -->
                            </v-list-item-content>
                          </v-list-item>


                          <v-list-item class="p-0" v-if="message.image_url != null" style="max-width:400px">
                            <v-list-item-content class="p-0">
                              <v-img style="border-radius: 8px;" :lazy-src="`${$root.envUrlNoApi}/storage/post_img_video/${message.image_url}`" :src="`${$root.envUrlNoApi}/storage/post_img_video/${message.image_url}`" />
                            </v-list-item-content>
                          </v-list-item>

                          <v-list-item class="p-0" v-if="message.video_url != null" style="max-width:400px">
                            <v-list-item-content class="p-0" style="border-radius: 8px;">
                              <vue-plyr>
                                <video controls playsinline>
                                  <source size="1080" :src="$root.envUrlNoApi + '/storage/post_img_video/' +  message.video_url" type="video/mp4">
                                </video>
                              </vue-plyr>
                            </v-list-item-content>
                          </v-list-item>

                          <v-list-item class="p-0" v-if="message.text != null">
                            <v-list-item-content class="p-0">
                              <v-list-item-subtitle class="text-wrap">
                                <div class="p-2 text-left white--text bubble_friend">{{ message.text }}</div>
                              </v-list-item-subtitle>
                            </v-list-item-content>
                          </v-list-item>
                        </v-card-text>
                      </v-card>
                    </template>

                    <v-card class="rounded-pill p-2" elevation="2">
                      <v-btn icon @click="rate('like', message.id)">
                        <v-avatar size="20">
                          <v-img :lazy-src="require('../../assets/img/emoticons/thumbs_up.png')" :src="require('../../assets/img/emoticons/thumbs_up.png')" />
                        </v-avatar>
                      </v-btn>
                      <v-btn icon @click="rate('heart', message.id)">
                        <v-avatar size="20">
                          <v-img :lazy-src="require('../../assets/img/emoticons/heart.png')" :src="require('../../assets/img/emoticons/heart.png')" />
                        </v-avatar>
                      </v-btn>
                      <v-btn icon @click="rate('funny', message.id)">
                        <v-avatar size="20">
                          <v-img :lazy-src="require('../../assets/img/emoticons/face_open_mouth_smiling_eyes.png')" :src="require('../../assets/img/emoticons/face_open_mouth_smiling_eyes.png')" />
                        </v-avatar>
                      </v-btn>
                      <v-btn icon @click="rate('surprise', message.id)">
                        <v-avatar size="20">
                          <v-img :lazy-src="require('../../assets/img/emoticons/face_open_mouth.png')" :src="require('../../assets/img/emoticons/face_open_mouth.png')" />
                        </v-avatar>
                      </v-btn>
                      <v-btn icon @click="rate('sad', message.id)">
                        <v-avatar size="20">
                          <v-img :lazy-src="require('../../assets/img/emoticons/disappointed_relieved_face.png')" :src="require('../../assets/img/emoticons/disappointed_relieved_face.png')" />
                        </v-avatar>
                      </v-btn>
                      <v-btn icon @click="rate('angry', message.id)">
                        <v-avatar size="20">
                          <v-img :lazy-src="require('../../assets/img/emoticons/pouting_face.png')" :src="require('../../assets/img/emoticons/pouting_face.png')" />
                        </v-avatar>
                      </v-btn>
                      <v-btn icon v-if="isThereMyReaction(message.messagereactionmodel)" @click="removeRate(message.id)">
                        <v-icon>mdi-close</v-icon>
                      </v-btn>
                    </v-card>
                  </v-menu>
                </v-list-item-content>
              </v-list-item>

              <!-- reactions///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
              <div class="position-absolute mr-2" style="left:46px;bottom:-21px">
                <v-dialog max-width="600" :retain-focus="false" v-if="message.count_reactions != 0">
                  <template v-slot:activator="{ on, attrs }">
                    <v-btn x-small text outlined class=" rounded-pill mb-2 p-0" :min-width="40" v-bind="attrs" v-on="on">
                      <div class="">
                        <v-avatar class="mr-1" size="14" v-if="message.like != 0">
                          <v-img :lazy-src="require('../../assets/img/emoticons/thumbs_up.png')" :src="require('../../assets/img/emoticons/thumbs_up.png')" />
                        </v-avatar>
                        <v-avatar class="mr-1" size="14" v-if="message.heart != 0">
                          <v-img :lazy-src="require('../../assets/img/emoticons/heart.png')" :src="require('../../assets/img/emoticons/heart.png')" />
                        </v-avatar>
                        <v-avatar class="mr-1" size="14" v-if="message.funny != 0">
                          <v-img :lazy-src="require('../../assets/img/emoticons/face_open_mouth_smiling_eyes.png')" :src="require('../../assets/img/emoticons/face_open_mouth_smiling_eyes.png')" />
                        </v-avatar>
                        <v-avatar class="mr-1" size="14" v-if="message.surprise != 0">
                          <v-img :lazy-src="require('../../assets/img/emoticons/face_open_mouth.png')" :src="require('../../assets/img/emoticons/face_open_mouth.png')" />
                        </v-avatar>
                        <v-avatar class="mr-1" size="14" v-if="message.sad != 0">
                          <v-img :lazy-src="require('../../assets/img/emoticons/disappointed_relieved_face.png')" :src="require('../../assets/img/emoticons/disappointed_relieved_face.png')" />
                        </v-avatar>
                        <v-avatar class="mr-1" size="14" v-if="message.angry != 0">
                          <v-img :lazy-src="require('../../assets/img/emoticons/pouting_face.png')" :src="require('../../assets/img/emoticons/pouting_face.png')" />
                        </v-avatar>
                      </div>
                      <div class="pt-1 secondary--text">
                        {{message.count_reactions}}
                      </div>
                    </v-btn>
                  </template>
                  <!-- ///////////////////////////////////////////////////////////////////////////////////////////////////// -->
                  <template v-slot:default="dialogReactionsMessage">
                    <v-card>
                      <v-toolbar color="primary" dark v-if="selectedDialogReactionsMessage == null">
                        <v-tabs v-model="tabReactionsMessage[message.id]" background-color="primary white--text" dark :show-arrows="true">
                          <v-tab>
                            <span>Všetko</span>
                            <span class="ml-1">{{message.count_reactions}}</span>
                          </v-tab>

                          <v-tab class="h-100" v-if="message.like != 0">
                            <v-avatar class="mr-1" size="20">
                              <v-img :lazy-src="require('../../assets/img/emoticons/thumbs_up.png')" :src="require('../../assets/img/emoticons/thumbs_up.png')" />
                            </v-avatar>
                            <span>{{message.like}}</span>
                          </v-tab>

                          <v-tab class="h-100" v-if="message.heart != 0">
                            <v-avatar class="mr-1" size="20">
                              <v-img :lazy-src="require('../../assets/img/emoticons/heart.png')" :src="require('../../assets/img/emoticons/heart.png')" />
                            </v-avatar>
                            <span>{{message.heart}}</span>
                          </v-tab>

                          <v-tab class="h-100" v-if="message.funny != 0">
                            <v-avatar class="mr-1" size="20">
                              <v-img :lazy-src="require('../../assets/img/emoticons/face_open_mouth_smiling_eyes.png')" :src="require('../../assets/img/emoticons/face_open_mouth_smiling_eyes.png')" />
                            </v-avatar>
                            <span>{{message.funny}}</span>
                          </v-tab>

                          <v-tab class="h-100" v-if="message.surprise != 0">
                            <v-avatar class="mr-1" size="20">
                              <v-img :lazy-src="require('../../assets/img/emoticons/face_open_mouth.png')" :src="require('../../assets/img/emoticons/face_open_mouth.png')" />
                            </v-avatar>
                            <span>{{message.surprise}}</span>
                          </v-tab>

                          <v-tab class="h-100" v-if="message.sad != 0">
                            <v-avatar class="mr-1" size="20">
                              <v-img :lazy-src="require('../../assets/img/emoticons/disappointed_relieved_face.png')" :src="require('../../assets/img/emoticons/disappointed_relieved_face.png')" />
                            </v-avatar>
                            <span>{{message.sad}}</span>
                          </v-tab>

                          <v-tab class="h-100" v-if="message.angry != 0">
                            <v-avatar class="mr-1" size="20">
                              <v-img :lazy-src="require('../../assets/img/emoticons/pouting_face.png')" :src="require('../../assets/img/emoticons/pouting_face.png')" />
                            </v-avatar>
                            <span>{{message.angry}}</span>
                          </v-tab>
                        </v-tabs>

                        <v-spacer />
                        <v-btn icon dark @click="dialogReactionsMessage.value = false">
                          <v-icon>mdi-close</v-icon>
                        </v-btn>
                      </v-toolbar>

                      <v-tabs-items v-model="tabReactionsMessage[message.id]">
                        <v-tab-item>
                          <v-card flat>
                            <v-card-text class="p-0">
                              <v-list-item-group v-model="reactionsMessageAll" color="primary" v-for="(item, index) in message.messagereactionmodel" :key="'all'+index">
                                <v-list-item v-if="message.id == item.message_id">
                                  <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                    <v-avatar color="primary" size="40" v-if="item.usermessagereactionmodel.avatar == null">
                                      <span class="text-uppercase white--text">{{ item.usermessagereactionmodel.name.charAt(0) }}</span>
                                    </v-avatar>
                                    <v-avatar color="primary" size="40" v-else>
                                      <v-img :lazy-src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usermessagereactionmodel.avatar}`" :src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usermessagereactionmodel.avatar}`" />
                                    </v-avatar>
                                  </v-badge>
                                  <v-list-item-content>
                                    <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.usermessagereactionmodel.name"></v-list-item-title>
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

                        <v-tab-item v-if="message.like != 0">
                          <v-card flat>
                            <v-card-text class="p-0">
                              <v-list-item-group v-model="reactionsMessageLike" color="primary" v-for="(item, index) in message.messagereactionmodel" :key="'like'+index">
                                <v-list-item v-if="item.reaction_type == 'like' && message.id == item.message_id">
                                  <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                    <v-avatar color="primary" size="40" v-if="item.usermessagereactionmodel.avatar == null">
                                      <span class="text-uppercase white--text">{{ item.usermessagereactionmodel.name.charAt(0) }}</span>
                                    </v-avatar>
                                    <v-avatar color="primary" size="40" v-else>
                                      <v-img :lazy-src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usermessagereactionmodel.avatar}`" :src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usermessagereactionmodel.avatar}`" />
                                    </v-avatar>
                                  </v-badge>
                                  <v-list-item-content>
                                    <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.usermessagereactionmodel.name"></v-list-item-title>
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

                        <v-tab-item v-if="message.heart != 0">
                          <v-card flat>
                            <v-card-text class="p-0">
                              <v-list-item-group v-model="reactionsMessageHeart" color="primary" v-for="(item, index) in message.messagereactionmodel" :key="'heart'+index">
                                <v-list-item v-if="item.reaction_type == 'heart' && message.id == item.message_id">
                                  <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                    <v-avatar color="primary" size="40" v-if="item.usermessagereactionmodel.avatar == null">
                                      <span class="text-uppercase white--text">{{ item.usermessagereactionmodel.name.charAt(0) }}</span>
                                    </v-avatar>
                                    <v-avatar color="primary" size="40" v-else>
                                      <v-img :lazy-src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usermessagereactionmodel.avatar}`" :src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usermessagereactionmodel.avatar}`" />
                                    </v-avatar>
                                  </v-badge>
                                  <v-list-item-content>
                                    <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.usermessagereactionmodel.name"></v-list-item-title>
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

                        <v-tab-item v-if="message.funny != 0">
                          <v-card flat>
                            <v-card-text class="p-0">
                              <v-list-item-group v-model="reactionsMessageFunny" color="primary" v-for="(item, index) in message.messagereactionmodel" :key="'funny'+index">
                                <v-list-item v-if="item.reaction_type == 'funny' && message.id == item.message_id">
                                  <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                    <v-avatar color="primary" size="40" v-if="item.usermessagereactionmodel.avatar == null">
                                      <span class="text-uppercase white--text">{{ item.usermessagereactionmodel.name.charAt(0) }}</span>
                                    </v-avatar>
                                    <v-avatar color="primary" size="40" v-else>
                                      <v-img :lazy-src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usermessagereactionmodel.avatar}`" :src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usermessagereactionmodel.avatar}`" />
                                    </v-avatar>
                                  </v-badge>
                                  <v-list-item-content>
                                    <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.usermessagereactionmodel.name"></v-list-item-title>
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

                        <v-tab-item v-if="message.surprise != 0">
                          <v-card flat>
                            <v-card-text class="p-0">
                              <v-list-item-group v-model="reactionsMessageSurprise" color="primary" v-for="(item, index) in message.messagereactionmodel" :key="'surprise'+index">
                                <v-list-item v-if="item.reaction_type == 'surprise' && message.id == item.message_id">
                                  <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                    <v-avatar color="primary" size="40" v-if="item.usermessagereactionmodel.avatar == null">
                                      <span class="text-uppercase white--text">{{ item.usermessagereactionmodel.name.charAt(0) }}</span>
                                    </v-avatar>
                                    <v-avatar color="primary" size="40" v-else>
                                      <v-img :lazy-src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usermessagereactionmodel.avatar}`" :src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usermessagereactionmodel.avatar}`" />
                                    </v-avatar>
                                  </v-badge>
                                  <v-list-item-content>
                                    <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.usermessagereactionmodel.name"></v-list-item-title>
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

                        <v-tab-item v-if="message.sad != 0">
                          <v-card flat>
                            <v-card-text class="p-0">
                              <v-list-item-group v-model="reactionsMessageSad" color="primary" v-for="(item, index) in message.messagereactionmodel" :key="'sad'+index">
                                <v-list-item v-if="item.reaction_type == 'sad' && message.id == item.message_id">
                                  <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                    <v-avatar color="primary" size="40" v-if="item.usermessagereactionmodel.avatar == null">
                                      <span class="text-uppercase white--text">{{ item.usermessagereactionmodel.name.charAt(0) }}</span>
                                    </v-avatar>
                                    <v-avatar color="primary" size="40" v-else>
                                      <v-img :lazy-src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usermessagereactionmodel.avatar}`" :src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usermessagereactionmodel.avatar}`" />
                                    </v-avatar>
                                  </v-badge>
                                  <v-list-item-content>
                                    <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.usermessagereactionmodel.name"></v-list-item-title>
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

                        <v-tab-item v-if="message.angry != 0">
                          <v-card flat>
                            <v-card-text class="p-0">
                              <v-list-item-group v-model="reactionsMessageAngry" color="primary" v-for="(item, index) in message.messagereactionmodel" :key="'angry'+index">
                                <v-list-item v-if="item.reaction_type == 'angry' && message.id == item.message_id">
                                  <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                    <v-avatar color="primary" size="40" v-if="item.usermessagereactionmodel.avatar == null">
                                      <span class="text-uppercase white--text">{{ item.usermessagereactionmodel.name.charAt(0) }}</span>
                                    </v-avatar>
                                    <v-avatar color="primary" size="40" v-else>
                                      <v-img :lazy-src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usermessagereactionmodel.avatar}`" :src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usermessagereactionmodel.avatar}`" />
                                    </v-avatar>
                                  </v-badge>
                                  <v-list-item-content>
                                    <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.usermessagereactionmodel.name"></v-list-item-title>
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
              <!-- reactions////////////////////////////////////////////////////////// -->
            </v-col>

            <!-- ////////////////////////////////////////////////////-message me/////////////////////////////////////////////////////////// -->
            <v-col cols="12" class="p-0" v-else>
              <div class="bubble_me2">
                <span>{{ message.created_at.substr(11, 5) }}</span>
              </div>
              <v-list-item class="p-0 float-right pl-12 pr-5" style="max-width: 100%;">
                <v-list-item-avatar class="m-0 bubble_me3" size="18">
                  <div v-if="message.read == 0">
                    <v-avatar color="primary" size="18">
                      <v-icon x-small color="white">mdi-check</v-icon>
                    </v-avatar>
                  </div>
                  <div v-else>
                    <v-avatar color="primary" size="18" v-if="message.to_contact.avatar == null">
                      <span v-if="message.to_contact.name != null" class="text-uppercase white--text" style="font-size: 10px">{{ message.to_contact.name.charAt(0) }}</span>
                    </v-avatar>
                    <v-avatar color="primary" size="18" v-else>
                      <v-img :lazy-src="`${$root.envUrlNoApi}/storage/user-avatar/${message.to_contact.avatar}`" :src="`${$root.envUrlNoApi}/storage/user-avatar/${message.to_contact.avatar}`" />
                    </v-avatar>
                  </div>
                </v-list-item-avatar>
                <v-list-item-content class="p-0" style="border-radius: 8px;">
                  <v-menu bottom offset-y min-width="200px" rounded="pill" z-index="4">
                    <!-- open-on-hover  class="mb-3" -->
                    <template v-slot:activator="{ on }">
                      <v-card v-on="on" class="rounded-lg float-right" :id="`message${message.id}`" elevation="0" :style="{background: $vuetify.theme.themes[isDark].primary}" @click="skuska(message)">
                        <v-card-text class="text-left font-weight-bold p-0 text-wrap">
                          <v-list-item class="p-0 pr-2 pl-1" v-if="message.reply_id != null" style="max-width:400px; background:rgba(0,0,0,.15)">
                            <v-icon size="20" class="white--text mr-2">mdi-share-outline</v-icon>
                            <v-list-item-content>
                              <v-list-item-title class="text-left p-0" v-if="message.reply_message.from == message.from">
                                <span class="white--text"><small>Odpoveď na svoju správu.</small></span>
                              </v-list-item-title>
                              <v-list-item-title class="text-left p-0" v-else>
                                <span class="white--text"><small>Odpoveď na správu používateľa <span class="font-weight-bold">{{ message.reply_message.from_contact.name }}</span></small></span>
                              </v-list-item-title>

                              <v-list-item class="p-0" v-if="message.reply_message.image_url != null" style="max-width:150px">
                                <v-list-item-content class="p-0 pb-1 pt-2">
                                  <v-img style="border-radius: 8px;" :lazy-src="`${$root.envUrlNoApi}/storage/post_img_video/${message.reply_message.image_url}`"
                                    :src="`${$root.envUrlNoApi}/storage/post_img_video/${message.reply_message.image_url}`" />
                                </v-list-item-content>
                              </v-list-item>

                              <v-list-item class="p-0" v-if="message.reply_message.video_url != null" style="max-width:150px">
                                <v-list-item-content class="p-0 pb-1 pt-2" style="border-radius: 8px;">
                                  <vue-plyr>
                                    <video controls playsinline>
                                      <source size="1080" :src="$root.envUrlNoApi + '/storage/post_img_video/' +  message.reply_message.video_url" type="video/mp4">
                                    </video>
                                  </vue-plyr>
                                </v-list-item-content>
                              </v-list-item>
                              <div class="white--text text-wrap" v-if="message.reply_message.text != null">{{ message.reply_message.text }}</div>
                            </v-list-item-content>
                          </v-list-item>

                          <v-list-item class="p-0" v-if="message.image_url != null" style="max-width:400px">
                            <v-list-item-content class="p-0">
                              <v-img style="border-radius: 8px;" :lazy-src="`${$root.envUrlNoApi}/storage/post_img_video/${message.image_url}`" :src="`${$root.envUrlNoApi}/storage/post_img_video/${message.image_url}`" />
                            </v-list-item-content>
                          </v-list-item>

                          <v-list-item class="p-0" v-if="message.video_url != null" style="max-width:400px">
                            <v-list-item-content class="p-0" style="border-radius: 8px;">
                              <vue-plyr>
                                <video controls playsinline>
                                  <source size="1080" :src="$root.envUrlNoApi + '/storage/post_img_video/' +  message.video_url" type="video/mp4">
                                </video>
                              </vue-plyr>
                            </v-list-item-content>
                          </v-list-item>

                          <v-list-item class="p-0" v-if="message.text != null">
                            <v-list-item-content class="p-0">
                              <v-list-item-subtitle class="text-wrap">
                                <div class="p-2 text-left white--text bubble_me">{{ message.text }}</div>
                              </v-list-item-subtitle>
                            </v-list-item-content>
                          </v-list-item>

                          <!-- <div class="p-2 white--text bubble_friend text-wrap" v-if="message.text != null">{{ message.text }}</div> -->
                          <!-- <span class="white--text pt-1" v-if="message.text">{{ message.text }}</span> -->
                        </v-card-text>
                      </v-card>
                    </template>

                    <v-card class="rounded-pill p-2" elevation="2">
                      <v-btn icon @click="rate('like', message.id)">
                        <v-avatar size="20">
                          <v-img :lazy-src="require('../../assets/img/emoticons/thumbs_up.png')" :src="require('../../assets/img/emoticons/thumbs_up.png')" />
                        </v-avatar>
                      </v-btn>
                      <v-btn icon @click="rate('heart', message.id)">
                        <v-avatar size="20">
                          <v-img :lazy-src="require('../../assets/img/emoticons/heart.png')" :src="require('../../assets/img/emoticons/heart.png')" />
                        </v-avatar>
                      </v-btn>
                      <v-btn icon @click="rate('funny', message.id)">
                        <v-avatar size="20">
                          <v-img :lazy-src="require('../../assets/img/emoticons/face_open_mouth_smiling_eyes.png')" :src="require('../../assets/img/emoticons/face_open_mouth_smiling_eyes.png')" />
                        </v-avatar>
                      </v-btn>
                      <v-btn icon @click="rate('surprise', message.id)">
                        <v-avatar size="20">
                          <v-img :lazy-src="require('../../assets/img/emoticons/face_open_mouth.png')" :src="require('../../assets/img/emoticons/face_open_mouth.png')" />
                        </v-avatar>
                      </v-btn>
                      <v-btn icon @click="rate('sad', message.id)">
                        <v-avatar size="20">
                          <v-img :lazy-src="require('../../assets/img/emoticons/disappointed_relieved_face.png')" :src="require('../../assets/img/emoticons/disappointed_relieved_face.png')" />
                        </v-avatar>
                      </v-btn>
                      <v-btn icon @click="rate('angry', message.id)">
                        <v-avatar size="20">
                          <v-img :lazy-src="require('../../assets/img/emoticons/pouting_face.png')" :src="require('../../assets/img/emoticons/pouting_face.png')" />
                        </v-avatar>
                      </v-btn>
                      <v-btn icon v-if="isThereMyReaction(message.messagereactionmodel)" @click="removeRate(message.id)">
                        <v-icon>mdi-close</v-icon>
                      </v-btn>
                    </v-card>
                  </v-menu>
                </v-list-item-content>
              </v-list-item>

              <!-- reactions///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
              <div class="position-absolute mr-2" style="right:12px;bottom:-21px">
                <v-dialog max-width="600" :retain-focus="false" v-if="message.count_reactions != 0">
                  <template v-slot:activator="{ on, attrs }">
                    <v-btn x-small text outlined color="primary" class=" rounded-pill mb-2 p-0" :min-width="40" v-bind="attrs" v-on="on">
                      <div class="">
                        <v-avatar class="mr-1" size="14" v-if="message.like != 0">
                          <v-img :lazy-src="require('../../assets/img/emoticons/thumbs_up.png')" :src="require('../../assets/img/emoticons/thumbs_up.png')" />
                        </v-avatar>
                        <v-avatar class="mr-1" size="14" v-if="message.heart != 0">
                          <v-img :lazy-src="require('../../assets/img/emoticons/heart.png')" :src="require('../../assets/img/emoticons/heart.png')" />
                        </v-avatar>
                        <v-avatar class="mr-1" size="14" v-if="message.funny != 0">
                          <v-img :lazy-src="require('../../assets/img/emoticons/face_open_mouth_smiling_eyes.png')" :src="require('../../assets/img/emoticons/face_open_mouth_smiling_eyes.png')" />
                        </v-avatar>
                        <v-avatar class="mr-1" size="14" v-if="message.surprise != 0">
                          <v-img :lazy-src="require('../../assets/img/emoticons/face_open_mouth.png')" :src="require('../../assets/img/emoticons/face_open_mouth.png')" />
                        </v-avatar>
                        <v-avatar class="mr-1" size="14" v-if="message.sad != 0">
                          <v-img :lazy-src="require('../../assets/img/emoticons/disappointed_relieved_face.png')" :src="require('../../assets/img/emoticons/disappointed_relieved_face.png')" />
                        </v-avatar>
                        <v-avatar class="mr-1" size="14" v-if="message.angry != 0">
                          <v-img :lazy-src="require('../../assets/img/emoticons/pouting_face.png')" :src="require('../../assets/img/emoticons/pouting_face.png')" />
                        </v-avatar>
                      </div>
                      <div class="pt-1 primary--text">
                        {{message.count_reactions}}
                      </div>
                    </v-btn>
                  </template>
                  <!-- ///////////////////////////////////////////////////////////////////////////////////////////////////// -->
                  <template v-slot:default="dialogReactionsMessage">
                    <v-card>
                      <v-toolbar color="primary" dark v-if="selectedDialogReactionsMessage == null">
                        <v-tabs v-model="tabReactionsMessage[message.id]" background-color="primary white--text" dark :show-arrows="true">
                          <v-tab>
                            <span>Všetko</span>
                            <span class="ml-1">{{message.count_reactions}}</span>
                          </v-tab>

                          <v-tab class="h-100" v-if="message.like != 0">
                            <v-avatar class="mr-1" size="20">
                              <v-img :lazy-src="require('../../assets/img/emoticons/thumbs_up.png')" :src="require('../../assets/img/emoticons/thumbs_up.png')" />
                            </v-avatar>
                            <span>{{message.like}}</span>
                          </v-tab>

                          <v-tab class="h-100" v-if="message.heart != 0">
                            <v-avatar class="mr-1" size="20">
                              <v-img :lazy-src="require('../../assets/img/emoticons/heart.png')" :src="require('../../assets/img/emoticons/heart.png')" />
                            </v-avatar>
                            <span>{{message.heart}}</span>
                          </v-tab>

                          <v-tab class="h-100" v-if="message.funny != 0">
                            <v-avatar class="mr-1" size="20">
                              <v-img :lazy-src="require('../../assets/img/emoticons/face_open_mouth_smiling_eyes.png')" :src="require('../../assets/img/emoticons/face_open_mouth_smiling_eyes.png')" />
                            </v-avatar>
                            <span>{{message.funny}}</span>
                          </v-tab>

                          <v-tab class="h-100" v-if="message.surprise != 0">
                            <v-avatar class="mr-1" size="20">
                              <v-img :lazy-src="require('../../assets/img/emoticons/face_open_mouth.png')" :src="require('../../assets/img/emoticons/face_open_mouth.png')" />
                            </v-avatar>
                            <span>{{message.surprise}}</span>
                          </v-tab>

                          <v-tab class="h-100" v-if="message.sad != 0">
                            <v-avatar class="mr-1" size="20">
                              <v-img :lazy-src="require('../../assets/img/emoticons/disappointed_relieved_face.png')" :src="require('../../assets/img/emoticons/disappointed_relieved_face.png')" />
                            </v-avatar>
                            <span>{{message.sad}}</span>
                          </v-tab>

                          <v-tab class="h-100" v-if="message.angry != 0">
                            <v-avatar class="mr-1" size="20">
                              <v-img :lazy-src="require('../../assets/img/emoticons/pouting_face.png')" :src="require('../../assets/img/emoticons/pouting_face.png')" />
                            </v-avatar>
                            <span>{{message.angry}}</span>
                          </v-tab>
                        </v-tabs>

                        <v-spacer />
                        <v-btn icon dark @click="dialogReactionsMessage.value = false">
                          <v-icon>mdi-close</v-icon>
                        </v-btn>
                      </v-toolbar>

                      <v-tabs-items v-model="tabReactionsMessage[message.id]">
                        <v-tab-item>
                          <v-card flat>
                            <v-card-text class="p-0">
                              <v-list-item-group v-model="reactionsMessageAll" color="primary" v-for="(item, index) in message.messagereactionmodel" :key="'all'+index">
                                <v-list-item v-if="message.id == item.message_id">
                                  <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                    <v-avatar color="primary" size="40" v-if="item.usermessagereactionmodel.avatar == null">
                                      <span class="text-uppercase white--text">{{ item.usermessagereactionmodel.name.charAt(0) }}</span>
                                    </v-avatar>
                                    <v-avatar color="primary" size="40" v-else>
                                      <v-img :lazy-src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usermessagereactionmodel.avatar}`" :src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usermessagereactionmodel.avatar}`" />
                                    </v-avatar>
                                  </v-badge>
                                  <v-list-item-content>
                                    <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.usermessagereactionmodel.name"></v-list-item-title>
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

                        <v-tab-item v-if="message.like != 0">
                          <v-card flat>
                            <v-card-text class="p-0">
                              <v-list-item-group v-model="reactionsMessageLike" color="primary" v-for="(item, index) in message.messagereactionmodel" :key="'like'+index">
                                <v-list-item v-if="item.reaction_type == 'like' && message.id == item.message_id">
                                  <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                    <v-avatar color="primary" size="40" v-if="item.usermessagereactionmodel.avatar == null">
                                      <span class="text-uppercase white--text">{{ item.usermessagereactionmodel.name.charAt(0) }}</span>
                                    </v-avatar>
                                    <v-avatar color="primary" size="40" v-else>
                                      <v-img :lazy-src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usermessagereactionmodel.avatar}`" :src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usermessagereactionmodel.avatar}`" />
                                    </v-avatar>
                                  </v-badge>
                                  <v-list-item-content>
                                    <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.usermessagereactionmodel.name"></v-list-item-title>
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

                        <v-tab-item v-if="message.heart != 0">
                          <v-card flat>
                            <v-card-text class="p-0">
                              <v-list-item-group v-model="reactionsMessageHeart" color="primary" v-for="(item, index) in message.messagereactionmodel" :key="'heart'+index">
                                <v-list-item v-if="item.reaction_type == 'heart' && message.id == item.message_id">
                                  <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                    <v-avatar color="primary" size="40" v-if="item.usermessagereactionmodel.avatar == null">
                                      <span class="text-uppercase white--text">{{ item.usermessagereactionmodel.name.charAt(0) }}</span>
                                    </v-avatar>
                                    <v-avatar color="primary" size="40" v-else>
                                      <v-img :lazy-src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usermessagereactionmodel.avatar}`" :src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usermessagereactionmodel.avatar}`" />
                                    </v-avatar>
                                  </v-badge>
                                  <v-list-item-content>
                                    <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.usermessagereactionmodel.name"></v-list-item-title>
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

                        <v-tab-item v-if="message.funny != 0">
                          <v-card flat>
                            <v-card-text class="p-0">
                              <v-list-item-group v-model="reactionsMessageFunny" color="primary" v-for="(item, index) in message.messagereactionmodel" :key="'funny'+index">
                                <v-list-item v-if="item.reaction_type == 'funny' && message.id == item.message_id">
                                  <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                    <v-avatar color="primary" size="40" v-if="item.usermessagereactionmodel.avatar == null">
                                      <span class="text-uppercase white--text">{{ item.usermessagereactionmodel.name.charAt(0) }}</span>
                                    </v-avatar>
                                    <v-avatar color="primary" size="40" v-else>
                                      <v-img :lazy-src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usermessagereactionmodel.avatar}`" :src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usermessagereactionmodel.avatar}`" />
                                    </v-avatar>
                                  </v-badge>
                                  <v-list-item-content>
                                    <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.usermessagereactionmodel.name"></v-list-item-title>
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

                        <v-tab-item v-if="message.surprise != 0">
                          <v-card flat>
                            <v-card-text class="p-0">
                              <v-list-item-group v-model="reactionsMessageSurprise" color="primary" v-for="(item, index) in message.messagereactionmodel" :key="'surprise'+index">
                                <v-list-item v-if="item.reaction_type == 'surprise' && message.id == item.message_id">
                                  <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                    <v-avatar color="primary" size="40" v-if="item.usermessagereactionmodel.avatar == null">
                                      <span class="text-uppercase white--text">{{ item.usermessagereactionmodel.name.charAt(0) }}</span>
                                    </v-avatar>
                                    <v-avatar color="primary" size="40" v-else>
                                      <v-img :lazy-src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usermessagereactionmodel.avatar}`" :src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usermessagereactionmodel.avatar}`" />
                                    </v-avatar>
                                  </v-badge>
                                  <v-list-item-content>
                                    <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.usermessagereactionmodel.name"></v-list-item-title>
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

                        <v-tab-item v-if="message.sad != 0">
                          <v-card flat>
                            <v-card-text class="p-0">
                              <v-list-item-group v-model="reactionsMessageSad" color="primary" v-for="(item, index) in message.messagereactionmodel" :key="'sad'+index">
                                <v-list-item v-if="item.reaction_type == 'sad' && message.id == item.message_id">
                                  <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                    <v-avatar color="primary" size="40" v-if="item.usermessagereactionmodel.avatar == null">
                                      <span class="text-uppercase white--text">{{ item.usermessagereactionmodel.name.charAt(0) }}</span>
                                    </v-avatar>
                                    <v-avatar color="primary" size="40" v-else>
                                      <v-img :lazy-src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usermessagereactionmodel.avatar}`" :src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usermessagereactionmodel.avatar}`" />
                                    </v-avatar>
                                  </v-badge>
                                  <v-list-item-content>
                                    <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.usermessagereactionmodel.name"></v-list-item-title>
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

                        <v-tab-item v-if="message.angry != 0">
                          <v-card flat>
                            <v-card-text class="p-0">
                              <v-list-item-group v-model="reactionsMessageAngry" color="primary" v-for="(item, index) in message.messagereactionmodel" :key="'angry'+index">
                                <v-list-item v-if="item.reaction_type == 'angry' && message.id == item.message_id">
                                  <v-badge bottom bordered :icon="getIcon(item.reaction_type)" offset-x="15" offset-y="15" class="mr-2">
                                    <v-avatar color="primary" size="40" v-if="item.usermessagereactionmodel.avatar == null">
                                      <span class="text-uppercase white--text">{{ item.usermessagereactionmodel.name.charAt(0) }}</span>
                                    </v-avatar>
                                    <v-avatar color="primary" size="40" v-else>
                                      <v-img :lazy-src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usermessagereactionmodel.avatar}`" :src="`${$root.envUrlNoApi}/storage/user-avatar/${item.usermessagereactionmodel.avatar}`" />
                                    </v-avatar>
                                  </v-badge>
                                  <v-list-item-content>
                                    <v-list-item-title class="d-flex justify-start font-weight-bold" v-text="item.usermessagereactionmodel.name"></v-list-item-title>
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
              <!-- reactions////////////////////////////////////////////////////////// -->
            </v-col>
          </v-list-item>
        </div>

        <v-list-item class="pt-4 pl-2 pr-2" v-if="typingStatusFunction">
          <v-col cols="12" class="p-0">
            <v-card class="rounded-lg p-2 float-left" elevation="0" :style="{background: $vuetify.theme.themes[isDark].secondary}">
              <v-card-text class="text-left font-weight-bold p-0 text-wrap">
                <span class="text-muted float-left">
                  <span class="white--text pr-1">{{ writingUser }}</span>
                  <div class="bubble white--text">
                    <div class="ellipsis one" style="background: white"></div>
                    <div class="ellipsis two" style="background: white"></div>
                    <div class="ellipsis three" style="background: white"></div>
                  </div>
                </span>
              </v-card-text>
            </v-card>
          </v-col>
        </v-list-item>
      </v-list>
    </v-card>

    <div class="w-100">
      <v-fab-transition>
        <v-btn class="goToTop" v-scroll="onScroll" v-show="fab" fab x-small dark fixed v-bind:style="{'bottom': valueOfBottomArrowDown + 'px'}" bottom color="primary" @click="toBottom(pageHeight)">
          <v-icon>mdi-arrow-down</v-icon>
        </v-btn>
      </v-fab-transition>
    </div>
  </div>

  <!-- <v-divider class="mt-0 mb-0" /> -->

  <!-- answer -->
  <v-bottom-navigation class="fixed-bottom-bar p-2 d-flex" v-bind:style="{ 'height': 'auto', 'flex-direction': 'column', 'bottom': valueOfBottom + 'px' }" content-class="elevation-0">
    <v-list-item class="mx-md-15 px-md-15 mx-lg-15 px-lg-15 mx-2 px-2" v-if="replyOnMessageBtn == true">
      <v-icon size="20" class="primary--text">mdi-share-outline</v-icon>
      <v-list-item-content style="border-radius: 8px;">
        <v-card class="p-2 rounded-lg card-comments" elevation="0">
          <v-list-item-title class="d-flex justify-start text-left p-0 pb-1" v-if="replyOnMessage.from == $root.me.id">
            <span class="primary--text"><small>Odpovedáte na svoju správu.</small></span>
            <v-spacer />
            <v-tooltip bottom>
              <template v-slot:activator="{ on }">
                <v-chip color="primary" v-on="on" small outlined @click="goToComment()">
                  <v-icon class="mr-1" small>mdi-format-align-top</v-icon>
                </v-chip>
              </template>
              <span>Skrolovať na správu</span>
            </v-tooltip>
          </v-list-item-title>
          <v-list-item-title class="d-flex justify-start pb-1" v-if="replyOnMessage.from != $root.me.id">
            <span class="primary--text"><small>Odpovedáte na správu používateľa <span class="font-weight-bold">{{ replyOnMessage.from_contact.name }}</span></small></span>
            <v-spacer />
            <v-tooltip bottom>
              <template v-slot:activator="{ on }">
                <v-chip color="primary" v-on="on" small outlined @click="goToComment()">
                  <v-icon class="mr-1" small>mdi-format-align-top</v-icon>
                </v-chip>
              </template>
              <span>Skrolovať na správu</span>
            </v-tooltip>
          </v-list-item-title>

          <v-list-item class="p-0" v-if="replyOnMessage.image_url != null" style="max-width:300px">
            <v-list-item-content class="p-0">
              <v-img style="border-radius: 8px;" :lazy-src="`${$root.envUrlNoApi}/storage/post_img_video/${replyOnMessage.image_url}`" :src="`${$root.envUrlNoApi}/storage/post_img_video/${replyOnMessage.image_url}`" />
            </v-list-item-content>
          </v-list-item>

          <v-list-item class="p-0" v-if="replyOnMessage.video_url != null" style="max-width:300px">
            <v-list-item-content class="p-0" style="border-radius: 8px;">
              <vue-plyr>
                <video controls playsinline>
                  <source size="1080" :src="$root.envUrlNoApi + '/storage/post_img_video/' +  replyOnMessage.video_url" type="video/mp4">
                </video>
              </vue-plyr>
            </v-list-item-content>
          </v-list-item>

          <v-list-item-subtitle class="text-wrap" v-if="replyOnMessage.text != null">
            <div class="text-left primary--text bubble_me text-wrap">{{ replyOnMessage.text }}</div>
          </v-list-item-subtitle>
        </v-card>
      </v-list-item-content>
    </v-list-item>
    <!-- file input -->

    <v-list-item class="mx-md-15 px-md-15 mx-lg-15 px-lg-15 mx-2 px-2" v-if="showDragAndDropFile">
      <!-- ml-15 mr-15 pl-15 pr-15  -->
      <file-pond allowImageCrop={true} allowFileEncode={true} class="filepond" name="filepond" ref="pond" type="input" :files="files" v-on:removefile="handleFileRemoved" v-on:addfile="handleFileAdded" v-on:init="handleFilePondInit" style="width:100%"
        label-idle="Sem presuňte súbor..." allow-multiple="true" max-files="1" />
    </v-list-item>
    <v-text-field :append-icon="show2 ? 'mdi-eye' : 'mdi-eye-off'" @click:append="show2 = !show2" :type="show2 ? 'text' : 'password'" :append-outer-icon="(((message) || (files.length != 0)) ? 'mdi-send' : 'mdi-thumb-up')" :hide-details="true" filled
      rounded dense clearable clear-icon="mdi-close" label="Správa" v-model="message" @click:append-outer="test" @click:clear="clearMessage" @keyup.enter="sendMessage()" @keydown="sendTypingEvent">
      <template slot="prepend">
        <v-icon class="mr-2" @click="showFileInputComment()">
          mdi-paperclip
        </v-icon>
        <v-icon @click="autoselectMenu = !autoselectMenu; toBottom(pageHeight)">
          mdi-emoticon
        </v-icon>
      </template>
    </v-text-field>

    <div v-if="autoselectMenu">
      <v-divider class="mt-2 mb-0" />
      <v-container class="pr-0 pl-0 messenger-emoji-mart">
        <picker :showSearch="false" :showPreview="false" :set="'messenger'" :showSkinTones="true" :emojiTooltip="true" :infiniteScroll="true" @select="selectEmoji" :i18n="i18n" />
      </v-container>
    </div>
    <!-- </v-row> -->
  </v-bottom-navigation>
</v-card>
</template>

<script>
let test = 0;
import axios from 'axios';
import VuePlyr from 'vue-plyr';
import 'vue-plyr/dist/vue-plyr.css';

import {
  Picker
} from 'emoji-mart-vue'
export default {
  name: "Conversation",
  components: {
    Picker,
    'vue-plyr': VuePlyr,
  },

  props: {
    contact: {
      type: Object,
      default: null
    },
    messages: {
      type: Array,
      default: () => []
    },
    overlayMessages: {
      type: Boolean,
      default: null
    },
  },

  data() {
    return {
      typingStatus: true,
      contactIdExist: null,
      ///messages feed
      user_id: localStorage.getItem("user_id"),
      writingUser: null,
      typingTimer: false,
      typingStatusNew: false,
      msgWasSent: false,
      interval: 2000,
      overlay: true,
      //messages composer
      message: '',
      // chatInput
      show: false,
      show2: true,
      autoselectMenu: false,
      autoselectMenu2: false,
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

      showDragAndDropFile: false,
      dialogOpen: false,
      files: [],
      replyOnMessage: null,
      replyOnMessageBtn: false,

      tabReactionsMessage: {},
      selectedDialogReactionsMessage: null,
      dialogReactionsMessage: false,
      reactionsMessageAll: null,
      reactionsMessageLike: null,
      reactionsMessageHeart: null,
      reactionsMessageFunny: null,
      reactionsMessageSurprise: null,
      reactionsMessageSad: null,
      reactionsMessageAngry: null,

      valueOfBottom: null,
      valueOfBottomArrowDown: null,
      fab: false,
      feed: 'classWithoutBottomNavigate',
    }
  },

  updated() {
    this.typingStatus = this.mutableTypingStatusNew;
    // messages feed

    this.bottomNavigationStyle();
    if (this.overlayMessages == false) {
      test++;
      if (test == 1) {
        setTimeout(() => {
          this.$vuetify.goTo(document.body.offsetHeight);
        }, 2000);
      }
    }
  },

  created() {
    //do something after creating vue instance
    window.Echo.join('pchat')
      .here(user => {
        console.log('here', user);
      })
      .joining(user => {
        console.log('joining', user);
      })
      .leaving(user => {
        console.log('leaving', user);
      })
      .listenForWhisper('typing', (user) => {
        this.writingUser = user[0];
        this.typingStatusNew = true;
        if ((Math.round(window.innerHeight + window.scrollY)) >= document.body.offsetHeight - 100) {
          this.$vuetify.goTo(document.body.offsetHeight);
        }

        if (this.typingTimer) {
          clearTimeout(this.typingTimer);
        }

        this.typingTimer = setTimeout(() => {
          this.typingStatusNew = false;
        }, user[1]);
      })

    console.log();
    if (localStorage.getItem('bottom_navigation') == 'true') {
      this.feed = 'classWithBottomNavigate';
    } else {
      this.classWithoutBottomNavigate = 'classWithoutBottomNavigate';
    }
  },

  computed: {
    typingStatusFunction() {
      return this.typingStatusNew;
    },

    isDark() {
      return (this.$vuetify.theme.dark) ? 'dark' : 'light'
    },

    pageHeight() {
      return document.body.scrollHeight;
    },

    groupByDayConversation() {
      let msg = {};
      this.messages.forEach(item => {
        console.log(item.created_at);
        let date = item.created_at.substring(0, 10);
        console.log(date);
        if (msg[date]) {
          msg[date].push(item);
        } else {
          msg[date] = [];
          msg[date].push(item);
        }
      });
      return msg;
    },
  },

  methods: {
    onScroll() {
      if (typeof window === 'undefined') return
      if ((Math.round(window.innerHeight + window.scrollY)) >= document.body.offsetHeight - 100) {
        this.fab = false;
      } else {
        this.fab = true;
      }
    },

    bottomNavigationStyle() {
      if (window.innerWidth <= 480 && this.$store.getters['bottomNavigationState'].state == true) {
        this.valueOfBottom = '56';
        this.valueOfBottomArrowDown = '88';
      } else {
        this.valueOfBottom = '0';
        this.valueOfBottomArrowDown = '32';
      }
      window.addEventListener('resize', () => {
        if (window.innerWidth <= 480 && this.$store.getters['bottomNavigationState'].state == true) {
          this.valueOfBottom = '56';
          this.valueOfBottomArrowDown = '88';
        } else {
          this.valueOfBottom = '0';
          this.valueOfBottomArrowDown = '32';
        }
      });
    },

    answerOnMessage() {
      this.replyOnMessageBtn = !this.replyOnMessageBtn;
    },

    skuska(message) {
      if (this.replyOnMessageBtn) {
        this.replyOnMessageBtn = false;
      }

      if (this.replyOnMessage != null) {
        if (this.replyOnMessage.id != message.id) {
          this.dialogOpen = true;
          this.replyOnMessage = message;
        } else {
          this.dialogOpen = !this.dialogOpen;
          this.replyOnMessage = message;
        }
      } else {
        this.dialogOpen = true;
        this.replyOnMessage = message;
      }
    },

    goToComment() {
      // scroll to your element
      document.querySelector("#message" + this.replyOnMessage.id).scrollIntoView(true);
      // now - fixed header
      if (window.scrollY) {
        window.scroll(0, window.scrollY - 64);
      }
    },

    showFileInputComment() {
      this.files = [];
      this.showDragAndDropFile = !this.showDragAndDropFile;
    },

    getColor(e) {
      if (e == 'offline') return 'red'
      else return 'green'
    },

    closeChat() {
      this.$router.push('/messenger');
      this.removeSelectedContact();
      this.$emit('closeChat', 'cau');
    },

    removeSelectedContact() {
      // console.log("hmm");
      // this.$emit('selected', this.contact);
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

    //messages feed
    scrollToBottom() {
      setTimeout(() => {
        if (this.$refs.card_body != null) {
          this.$refs.card_body.scrollTop = this.$refs.card_body.scrollHeight;
        }
      }, 50);
    },

    //messages composer
    test(a) {
      if (a.target.className == 'v-icon notranslate v-icon--link mdi mdi-send theme--light' || a.target.className == 'v-icon notranslate v-icon--link mdi mdi-send theme--dark') {
        // this.$emit('send', this.message);
        this.sendMessage(this.message);
        this.resetIcon();
        this.clearMessage();
      } else {
        this.message = '👍';
        this.sendMessage(this.message);
      }
    },
    // emoji input char
    selectEmoji(emoji) {
      if (this.message != null) {
        this.message += emoji.native;
      } else {
        this.message = emoji.native;
        // console.log(emoji.native);
      }
    },

    sendTypingEvent(e) {
      if (e.key != 'Enter') {
        window.Echo.join('pchat')
          .whisper('typing', [localStorage.getItem('username'), 2000]);
      } else {
        window.Echo.join('pchat')
          .whisper('typing', [localStorage.getItem('username'), 500]);
      }
    },

    // chatInput
    toggleMarker() {
      this.marker = !this.marker
    },

    sendMessage(message) {
      // message.preventDefault();
      if (message == '') {
        return;
      }
      if (!this.contact) {
        return;
      }

      const api = `${process.env.VUE_APP_API_URL}/conversation/send`;
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

      if (this.message != null) {
        formData.append('text', this.message);
      }

      if (this.replyOnMessageBtn != false) {
        formData.append('reply_id', this.replyOnMessage.id);
      } else {
        formData.append('reply_id', '0');
      }

      formData.append('contact_id', this.contact.id);

      axios.post(api, formData, config)
        .then((res) => {
          // console.log(res);
          this.files = [];
          this.showDragAndDropFile = false;
          this.dialogOpen = false;
          this.replyOnMessage = null;
          this.replyOnMessageBtn = false;

          this.$emit('new', res.data);
          this.$vuetify.goTo(document.body.offsetHeight);
        })
      // this.$emit('send', this.message);
      this.resetIcon()
      this.clearMessage()
    },
    clearMessage() {
      this.message = ''
    },
    resetIcon() {
      this.iconIndex = 0
    },
    toBottom(e) {
      this.$vuetify.goTo(e);
    },

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

    isThereMyReaction(data) {
      return data.find(message => message.user_id === this.$root.me.id) !== undefined;
    },

    // add rate, remove rate ..............................................................................
    rate(item, index) {
      const api = `${process.env.VUE_APP_API_URL}/add_message_reaction`;
      const config = {
        headers: {
          Accept: "application/json",
          Authorization: "Bearer " + localStorage.getItem("authToken"),
        },
      };
      axios.post(api, {
            reaction_type: item,
            message_id: index,
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
      const api = `${process.env.VUE_APP_API_URL}/delete_message_reaction`;
      const config = {
        headers: {
          Accept: "application/json",
          Authorization: "Bearer " + localStorage.getItem("authToken"),
        },
      };
      axios.post(api, {
            message_id: index,
          },
          config
        )
        .then(res => {
          console.log(res.data);
        })
        .catch((error) => {
          console.log(error)
        })
    },
  }
}
</script>

<style lang="scss" scoped>
.v-window__container {
    padding-bottom: 480px !important;
}

//messages feed
.bubble_friend {
    border-radius: 10px;
    // max-width: 230px;
}
.bubble_friend2 {
    font-size: 10px;
    color: lightgrey;
    position: absolute;
    left: 38px;
    top: -13px;
}

.bubble_me {
    border-radius: 10px;
    // max-width: 230px;
}

.bubble_me2 {
    font-size: 10px;
    color: lightblue;
    position: absolute;
    right: 20px;
    top: -13px;
}

.bubble_me3 {
    position: absolute;
    right: -3px;
    bottom: 5px;
}

.bubble {
    position: relative;
    display: inline-block;
    color: rgb(255,255,255);
    font-size: 0.7em;
}
.v-window__container {
    padding-bottom: 480px !important;
}

.messenger-emoji-mart .emoji-mart {
    width: 100% !important;
}

.fixed-bar {
    position: sticky;
    position: -webkit-sticky;
    top: 64px;
    z-index: 5;
}

.v-item-group.v-bottom-navigation.fixed-bottom-bar {
    position: sticky;
    position: -webkit-sticky;
    bottom: 0;
    width: 100%;
    z-index: 3 !important;
    box-shadow: none !important;

    // height: auto;
    // flex-direction: column;
}

.theme--light .fixed-bottom-bar {
    background-color: #f5f5f5;
}

.ellipsis {
    width: 5px;
    height: 5px;
    display: inline-block;
    // background: lighten(rgb(0,0,0), 25%);
    border-radius: 50%;
    animation: bounce 1.3s linear infinite;
}

.one {
    animation-delay: 0.6s;
}

.two {
    animation-delay: 0.5s;
}

.three {
    animation-delay: 0.8s;
}
@keyframes bounce {
    30% {
        transform: translateY(-2px);
    }

    60% {
        transform: translateY(0px);
    }

    80% {
        transform: translateY(2px);
    }

    100% {
        transform: translateY(0px);
        opacity: 0.5;
    }
}

.classWithoutBottomNavigate {
    min-height: calc(100vh - 196px) !important;
}

.classWithBottomNavigate {
    min-height: calc(100vh - 252px) !important;
}
</style>
