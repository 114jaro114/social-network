<template>
<div class="conversation h-auto">
  <v-snackbar v-model="snackbar" :multi-line="multiLine" :color="snackbar_color" :timeout="snackbar_timeout" bottom left class="m-3">
    <v-icon>{{snackbar_icon}}</v-icon>
    {{ snackbar_text }}
    <template v-slot:action="{ attrs }">
      <v-btn color="white" fab text small v-bind="attrs" @click="snackbar = false">
        <v-icon>mdi-close-circle</v-icon>
      </v-btn>
    </template>
  </v-snackbar>

  <v-overlay class="addAcc" :value="dialogAddToContract">
    <v-dialog v-model="dialogAddToContract" width="500">
      <v-card>
        <v-card-title class="text-h5 primary white--text">
          Pridanie používateľského účtu
        </v-card-title>

        <v-card-text class="pt-5">
          Ak chcete pokračovať, musíme svoj účet pridať do blockchainu kliknutím na tlačidlo "uložiť" a úspešne odoslať transakciu pomocou MetaMask peňaženky. Tento úkon je potrebné vykonať iba raz pre registráciu účtu.
        </v-card-text>

        <v-divider class="mt-0 mb-0" />

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn :loading="btnloading" color="primary" text @click="save()">
            Uložiť
          </v-btn>
          <v-btn color="primary" to="/messenger" @click="dialogAddToContract = false">
            späť na messenger
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-overlay>

  <!-- <v-overlay class="pt-16" style="margin-top:60vh" v-if="messenger2.loggedInToMetamask == false" :opacity="0"> -->
  <v-progress-circular class="pt-16" v-if="messenger2.loggedInToMetamask == false && messenger2.contract != 'Non-Ethereum browser detected'" indeterminate size="24" color="primary"></v-progress-circular>
  <!-- </v-overlay> -->
  <div v-else-if="messenger2.loggedInToMetamask && loggedInToMetamaskText == 'User logged into MetaMask'">
    <v-navigation-drawer width="300" :floating="false" left clipped app :permanent="$vuetify.breakpoint.mdAndUp">
      <v-card class="m-3 mb-0 rounded" elevation="0" color="success">
        <v-card-text class="white--text pt-0 pb-0" style="font-size:9px">{{messenger2.currentAccount}}</v-card-text>
        <v-card-title class="justify-content-center white--text pb-0 pt-0">Zostatok v peňaženke</v-card-title>
        <v-card-text class="white--text pt-0">{{messenger2.balance}}</v-card-text>
      </v-card>

      <v-container class="pt-0 friend-list">
        <v-card elevation="0" rounded>
          <v-list-item class="p-0" v-if="messenger2.users.length == 0">
            <v-text-field class="mt-6" v-model="search" label="Vyhľadať" filled rounded dense append-icon="mdi-magnify" clearable disabled></v-text-field>
          </v-list-item>
          <v-list-item class="p-0" v-else>
            <v-text-field class="mt-6" v-model="search" label="Vyhľadať" filled rounded dense append-icon="mdi-magnify" clearable></v-text-field>
          </v-list-item>
          <v-divider class="mt-0 mb-0"></v-divider>

          <v-list class="p-0" dense>
            <v-list-item v-if="messenger2.loadingusers != false" inactive disabled>
              <v-overlay :value="messenger2.loadingusers" :absolute="true" :opacity="0">
                <v-progress-circular indeterminate size="24" color="primary"></v-progress-circular>
              </v-overlay>
            </v-list-item>
            <v-list-item class="justify-center pl-0 pr-0" v-if="filteredUsers.length == 0 && messenger2.users.length != 0" disabled>
              <v-list-item-content>
                <v-card class="rounded-lg card-comments" elevation="0">
                  <v-btn color="primary" icon fab>
                    <v-icon style="font-size: 24px">mdi-cancel</v-icon>
                    <span class="ml-2">Žiadne výsledky</span>
                  </v-btn>
                </v-card>
              </v-list-item-content>
            </v-list-item>

            <v-list-item class="justify-center pl-0 pr-0" v-if="messenger2.users.length == 0 && messenger2.loadingusers == false" disabled>
              <v-list-item-content>
                <v-card class="rounded-lg card-comments" elevation="0">
                  <v-btn color="primary" icon fab>
                    <span>Žiadny priatelia</span>
                  </v-btn>
                </v-card>
              </v-list-item-content>
            </v-list-item>

            <v-list-item-group color="primary">
              <template v-for="(item, index) in filteredUsers">
                <v-list-item class="p-0" :key="index" @click="selectContact(item)">
                  <!-- <v-badge bottom dot bordered :color="getColor(item.status)" offset-x="10" offset-y="10" class="mr-2"> -->
                  <v-avatar color="primary" size="40" class="mr-2" v-if="item.avatar == 'null'">
                    <span class="text-uppercase white--text">{{ item.name.charAt(0) }}</span>
                  </v-avatar>
                  <v-avatar color="primary" size="40" class="mr-2" v-else>
                    <img :lazy-src="`${$root.envUrlNoApi}/storage/user-avatar/${item.avatar}`" :src="`${$root.envUrlNoApi}/storage/user-avatar/${item.avatar}`">
                  </v-avatar>
                  <!-- </v-badge> -->

                  <v-list-item-content>
                    <v-card class="p-2 rounded-lg card-comments" elevation="0">
                      <v-list-item-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pl-1">
                        <v-chip style="height:28px">
                          <v-icon left class="mr-1" small>mdi-account-circle-outline</v-icon>{{item.name}}
                        </v-chip>
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
        <div class="pa-2">
          <v-btn class="primary" to="/messenger" block>messenger</v-btn>
        </div>
      </template>
    </v-navigation-drawer>

    <v-row class="m-0" v-if="chat == false">
      <!-- Upload Interface -->
      <!-- <v-col cols="12" xl="4">
        <v-card class="mb-3" elevation="2" color="primary white--text" rounded>
          <v-card-title>Môj profil</v-card-title>
          <v-card-subtitle>
            <v-card class="mb-2 mt-2" elevation="0" color="success">
              <v-card-title class="white--text pb-0">Zostatok v peňaženke</v-card-title>
              <v-card-text class="white--text pt-0">{{messenger2.balance}}</v-card-text>
            </v-card>
          </v-card-subtitle>

          <v-card-text class="white--text">
            Id:
            <v-text-field v-model="messenger2.myProfile.id" filled />
            Name:
            <v-text-field v-model="messenger2.myProfile.name" filled />
            Email:
            <v-text-field v-model="messenger2.myProfile.email" filled />
            Avatar:
            <v-text-field v-model="messenger2.myProfile.avatar" filled />
          </v-card-text>
          <v-card-actions class="p-3 pt-0">
            <v-btn text rounded class="secondary" @click="saveSetProfile()">Uložiť</v-btn>

            <v-btn text rounded class="secondary" @click="updateProfile()">Aktualizovať</v-btn>
          </v-card-actions>
        </v-card>
      </v-col> -->
      <v-col cols="12" xl="6">
        <v-card elevation="2" class="primary white--text" v-if="messenger2.loading === false" rounded>
          <v-card-title class="white--text">Pridanie príspevku</v-card-title>
          <v-card-subtitle class="white--text">Zdieľajte svoje spomienky.</v-card-subtitle>

          <v-card-text>
            <!-- Form for file choose, caption text and submission -->
            <form>
              <file-pond credits={false} allowImageCrop={true} allowFileEncode={true} class="filepond" name="filepond" ref="pond" type="input" :files="files" v-on:removefile="handleFileRemoved" v-on:addfile="handleFileAdded" style="width:100%"
                label-idle="Sem presuňte súbory..." allow-multiple="true" max-files="10" />
              <!-- v-on:init="handleFilePondInit" -->
              <v-textarea v-model="message" filled></v-textarea>
              <v-btn block text class="secondary" @click="handleOk('post')">
                Pridať príspevok
              </v-btn>
            </form>
          </v-card-text>
        </v-card>
        <v-card elevation="2" color="primary white--text" v-if="messenger2.loading === true" rounded>
          <v-card-text class="text-center">
            <v-progress-circular indeterminate size="24" color="white"></v-progress-circular>
            <span class="white--text ml-2">{{alertText}}</span>
          </v-card-text>
        </v-card>
      </v-col>
      <v-col cols="12" xl="6">
        <v-card elevation="2" color="primary white--text" rounded>
          <v-card-text class="text-center">
            <v-progress-circular indeterminate size="24" color="white" v-if="messenger2.loading === true"></v-progress-circular>
            <div v-if="messenger2.currentPosts.length == 0 && messenger2.loading === false">
              <v-card class="d-flex justify-content-center" elevation="0" outlined color="error" rounded>
                <v-card-title class="white--text"> Žiadne príspevky</v-card-title>
              </v-card>
            </div>
            <div v-for="(item, key, index) in groupByDay" :key="index">
              <div class="pb-3">
                <v-btn large outlined block class="primary text-center white--text">{{key}}</v-btn>
              </div>
              <v-card v-for="(post, index2) in item" :key="index2" class="mb-3" elevation="0" rounded>
                <vue-plyr v-if="post.videosrc != 'QmbF6AKvzZ2fV7GubWq9v2P6qLMXyL6Y4ebZY8hrBng7QU'">
                  <video controls playsinline>
                    <source size="1080" :src="`https://gateway.ipfs.io/ipfs/${post.videosrc}`" type="video/mp4">
                  </video>
                </vue-plyr>
                <v-img v-if="post.src != 'QmbF6AKvzZ2fV7GubWq9v2P6qLMXyL6Y4ebZY8hrBng7QU'" :src="`https://gateway.ipfs.io/ipfs/${post.src}`"></v-img>
                <v-card-title class="p-2">
                  <v-chip class="mr-1">{{post.owner}}</v-chip>
                  <v-spacer />
                  <span style="font-size:10px">{{new Date(post.timestamp).toLocaleTimeString('sk', {hour: '2-digit',minute: '2-digit',second: '2-digit'})}}</span>
                </v-card-title>
                <v-divider v-if="post.text != 'null'" class=" mt-0 mb-0" />
                <v-card-text v-if="post.text != 'null'" class="text-left p-2"><span>{{post.text}}</span></v-card-text>
              </v-card>
            </div>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
    <!-- chat with user -->
    <div class="m-0" v-else>
      <v-card class="p-0" elevation="0" rounded>
        <v-app-bar class="fixed-bar" flat v-if="chat == true">
          <v-toolbar-title>
            <!-- <v-tooltip right> -->
            <!-- <template v-slot:activator="{ on, attrs }"> -->
            <!-- v-bind="attrs" v-on="on" -->
            <v-chip>
              <!-- <v-badge bottom dot bordered :color="getColor(contact.status)" offset-x="5" offset-y="10" class="mr-2"> -->
              <v-avatar color="primary" size="16" v-if="chatWith.avatar == 'null'">
                <span class="text-uppercase white--text">{{ chatWith.name.charAt(0) }}</span>
              </v-avatar>
              <v-avatar color="primary" size="16" v-else>
                <img :lazy-src="`${$root.envUrlNoApi}/storage/user-avatar/${chatWith.avatar}`" :src="`${$root.envUrlNoApi}/storage/user-avatar/${chatWith.avatar}`">
              </v-avatar>
              <!-- </v-badge> -->
              <span class="ml-1">{{chatWith.name}}</span>
            </v-chip>
            <!-- </template> -->
            <!-- <span>Offline</span> -->
            <!-- </v-tooltip> -->
          </v-toolbar-title>

          <v-spacer></v-spacer>

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

        <div class="feed">
          <!-- style="height: calc(100vh - 192px)"  -->
          <!-- :typingStatus="typingStatus" :contact="contact" :messages="messages" -->
          <v-card elevation="0" style="height:100vh">
            <v-list class="mt-3 pt-3 pb-3 overflow-y-auto" style="height:100vh" v-if="loadingconversation == true">
              <v-overlay :value="loadingconversation" :absolute="true" :opacity="0">
                <v-progress-circular indeterminate size="24" color="primary"></v-progress-circular>
              </v-overlay>
            </v-list>
            <v-list class="pt-3 pb-3 overflow-y-auto" ref="card_body" v-if="chatWith.id && loadingconversation == false">
              <div v-for="(item, key, index) in groupByDayConversation" :key="index" class="pt-4 pb-4 pl-2 pr-2">
                <v-col class="pl-2 pr-5 pb-3 d-flex justify-content-center w-100 mb-2">
                  <v-btn large text block class="text-center primary--text">{{key}}</v-btn>
                </v-col>
                <v-list-item v-for="(message, index2) in item" :key="index2" class="pt-1 pb-4 pl-2 pr-2">
                  <!-- messages friend -->
                  <v-col cols="12" class="p-0" v-if="message.owner != messenger2.myProfile.name">
                    <div class="bubble_friend2">
                      <span>{{ new Date(message.timestamp).toLocaleTimeString('sk', {hour: '2-digit',minute: '2-digit'})}}</span>
                    </div>
                    <v-list-item class="p-0 float-left pr-5" style="max-width: 100%;">
                      <v-list-item-avatar size="30" class="mr-2">
                        <v-avatar color="secondary" size="30" v-if="chatWith.avatar == 'null'">
                          <span class="text-uppercase white--text">{{ chatWith.name.charAt(0) }}</span>
                        </v-avatar>
                        <v-avatar color="secondary" size="30" v-else>
                          <v-img :lazy-src="`${$root.envUrlNoApi}/storage/user-avatar/${chatWith.avatar}`" :src="`${$root.envUrlNoApi}/storage/user-avatar/${chatWith.avatar}`" />
                        </v-avatar>
                      </v-list-item-avatar>

                      <v-list-item-content class="p-0">
                        <v-card class="rounded-lg float-left" :id="`message${message.id}`" elevation="0" :style="{background: $vuetify.theme.themes[isDark].secondary}">
                          <v-card-text class="text-left font-weight-bold p-0 text-wrap">
                            <v-list-item class="p-0" v-if="message.src != 'QmbF6AKvzZ2fV7GubWq9v2P6qLMXyL6Y4ebZY8hrBng7QU'" style="max-width:400px">
                              <v-list-item-content class="p-0">
                                <v-img style="border-radius: 8px;" :lazy-src="`https://gateway.ipfs.io/ipfs/${message.src}`" :src="`https://gateway.ipfs.io/ipfs/${message.src}`" />
                              </v-list-item-content>
                            </v-list-item>

                            <v-list-item class="p-0" v-if="message.videosrc != 'QmbF6AKvzZ2fV7GubWq9v2P6qLMXyL6Y4ebZY8hrBng7QU'" style="max-width:400px">
                              <v-list-item-content class="p-0" style="border-radius: 8px;">
                                <vue-plyr>
                                  <video controls playsinline>
                                    <source size="1080" :src="`https://gateway.ipfs.io/ipfs/${message.videosrc}`" type="video/mp4">
                                  </video>
                                </vue-plyr>
                              </v-list-item-content>
                            </v-list-item>

                            <v-list-item class="p-0" v-if="message.text != 'null'">
                              <v-list-item-content class="p-0">
                                <v-list-item-subtitle class="text-wrap">
                                  <div class="p-2 text-left white--text bubble_friend">{{ message.text }}</div>
                                </v-list-item-subtitle>
                              </v-list-item-content>
                            </v-list-item>
                          </v-card-text>
                        </v-card>
                      </v-list-item-content>
                    </v-list-item>
                  </v-col>
                  <v-col cols="12" class="p-0" v-else>
                    <!-- messages me -->
                    <div class="bubble_me2">
                      <span>{{new Date(message.timestamp).toLocaleTimeString('sk', {hour: '2-digit',minute: '2-digit'})}}</span>
                    </div>
                    <v-list-item class="p-0 float-right pl-12 pr-3" style="max-width: 100%;">
                      <v-list-item-content class="p-0" style="border-radius: 8px;">
                        <v-card class="rounded-lg float-right" :id="`message${message.id}`" elevation="0" :style="{background: $vuetify.theme.themes[isDark].primary}">
                          <v-card-text class="text-left font-weight-bold p-0 text-wrap">
                            <v-list-item class="p-0" v-if="message.src != 'QmbF6AKvzZ2fV7GubWq9v2P6qLMXyL6Y4ebZY8hrBng7QU'" style="max-width:400px">
                              <v-list-item-content class="p-0">
                                <v-img style="border-radius: 8px;" :lazy-src="`https://gateway.ipfs.io/ipfs/${message.src}`" :src="`https://gateway.ipfs.io/ipfs/${message.src}`" />
                              </v-list-item-content>
                            </v-list-item>

                            <v-list-item class="p-0" v-if="message.videosrc != 'QmbF6AKvzZ2fV7GubWq9v2P6qLMXyL6Y4ebZY8hrBng7QU'" style="max-width:400px">
                              <v-list-item-content class="p-0" style="border-radius: 8px;">
                                <vue-plyr>
                                  <video controls playsinline>
                                    <source size="1080" :src="`https://gateway.ipfs.io/ipfs/${message.videosrc}`" type="video/mp4">
                                  </video>
                                </vue-plyr>
                              </v-list-item-content>
                            </v-list-item>

                            <v-list-item class="p-0" v-if="message.text != 'null'">
                              <v-list-item-content class="p-0">
                                <v-list-item-subtitle class="text-wrap">
                                  <div class="p-2 text-left white--text bubble_me">{{ message.text }}</div>
                                </v-list-item-subtitle>
                              </v-list-item-content>
                            </v-list-item>
                          </v-card-text>
                        </v-card>
                      </v-list-item-content>
                    </v-list-item>
                  </v-col>
                </v-list-item>
              </div>
              <!-- </v-list-item> -->
            </v-list>
          </v-card>
        </div>
      </v-card>
      <!-- answer -->
      <v-bottom-navigation class="fixed-bottom-bar p-2 d-flex" v-bind:style="{ 'height': 'auto', 'flex-direction': 'column', 'bottom': valueOfBottom + 'px' }" content-class="elevation-0">
        <!-- file input -->
        <v-list-item class="mx-md-15 px-md-15 mx-lg-15 px-lg-15 mx-2 px-2" v-if="showDragAndDropFile">
          <!-- ml-15 mr-15 pl-15 pr-15  -->
          <file-pond credits={false} allowImageCrop={true} allowFileEncode={true} class="filepond" name="filepond" ref="pond" type="input" :files="files" v-on:removefile="handleFileRemoved" v-on:addfile="handleFileAdded" style="width:100%"
            label-idle="Sem presuňte súbor..." allow-multiple="true" max-files="1" />
        </v-list-item>
        <v-text-field :append-icon="show2 ? 'mdi-eye' : 'mdi-eye-off'" @click:append="show2 = !show2" :type="show2 ? 'text' : 'password'" :append-outer-icon="(((message) || (files.length != 0)) ? 'mdi-send' : 'mdi-thumb-up')" :hide-details="true"
          filled rounded dense clearable clear-icon="mdi-close" label="Správa" v-model="message" @click:append-outer="test" @click:clear="clearMessage" @keyup.enter="handleOk('msg')">
          <!-- @keydown="sendTypingEvent" -->
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
    </div>
  </div>
  <div class="p-3" v-else-if="messenger2.loggedInToMetamask && loggedInToMetamaskText == 'User is not logged in to MetaMask'">
    <v-alert border="left" type="error">
      <!-- <v-alert v-if="isDisabled" border="left" type="success">Success! A pending promise for ethereum.request() was created.</v-alert> -->
      <span>Prosíme vás, priháste sa najprv do Metamask penaženky.</span>
      <!-- <v-btn :disabled="isDisabled" text rounded class="secondary ml-2" @click="loginWithMetaMask()">Prihlásiť sa pomocou MetaMask</v-btn> -->
      <v-divider />
      <v-btn text rounded outlined class="ml-2" to="/messenger">
        <v-icon>mdi-arrow-left-circle</v-icon>Späť na messenger
      </v-btn>
    </v-alert>
  </div>
  <div class="p-3" v-else-if="messenger2.contract == 'Non-Ethereum browser detected'">
    <v-alert border="left" type="error"><span>Nebola detekovaná metamask peňaženka.</span>
      <v-btn text rounded class="secondary ml-2" @click="popup()">Stiahnúť MetaMask peňaženku</v-btn>
      <v-divider />
      <v-btn text rounded outlined class="ml-2" to="/messenger">
        <v-icon>mdi-arrow-left-circle</v-icon>Späť na messenger
      </v-btn>
    </v-alert>
  </div>
</div>
</template>

<script>
window.userWalletAddress = null
//web3 import
import web3 from '../../contracts/web3';
//contract import
import contract from '../../contracts/contractInstance';
import _ from 'lodash';
import ipfs from '../../contracts/ipfs';
import {
  ethers
} from 'ethers';


import VuePlyr from 'vue-plyr';
import 'vue-plyr/dist/vue-plyr.css';
import vueFilePond from 'vue-filepond';
// Import FilePond styles
import 'filepond/dist/filepond.min.css';
// Import image preview plugin styles
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css';
// Import image preview and file type validation plugins
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
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

import {
  Picker
} from 'emoji-mart-vue'

export default {
  name: 'Posts',
  components: {
    FilePond,
    Picker,
    'vue-plyr': VuePlyr,
  },

  data() {
    return {
      conversation: [],
      loadingconversation: false,
      files: [],
      buffer: '',
      alertText: '',

      userWallet: '',
      multiLine: true,
      snackbar: false,
      snackbar_color: '',
      snackbar_icon: '',
      snackbar_timeout: '-1',
      snackbar_text: '',
      search: '',
      chat: false,
      isDisabled: false,

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

      chatWith: {
        avatar: '',
        email: '',
        id: '',
        name: ''
      },

      //messages composer
      message: '',
      // chatInput
      show: false,
      show2: true,
      autoselectMenu: false,
      autoselectMenu2: false,

      valueOfBottom: null,
      showDragAndDropFile: false,

      dialogAddToContract: false,
      btnloading: false,


      messenger2: {
        currentPosts: [],
        users: [],
        amILogged: false,
        currentAccount: '',
        loading: true,
        loadingusers: true,
        loggedInToMetamask: false,
        loggedInToMetamaskText: '',
        isMetamaskDetected: false,
        myProfile: {
          id: '',
          name: '',
          email: '',
          avatar: '',
        },
        balance: '',
        contract,
      },
    };
  },

  async created() {
    if (contract != 'Non-Ethereum browser detected') {
      await this.updateAccount();
      await this.getActualBalance();
      await this.initMyProfile();
      await this.getUsers();
      await this.getPosts();
    }
  },

  updated() {
    this.bottomNavigationStyle();
    if (this.messenger2.amILogged == false && this.messenger2.loadingusers == false) {
      this.dialogAddToContract = true;
    } else {
      this.dialogAddToContract = false;
    }
  },

  mounted() {
    this.chat = false;
    if (this.messenger2.amILogged == false && this.messenger2.loadingusers == false) {
      return this.dialogAddToContract = true;
    } else {
      return this.dialogAddToContract = false;
    }
  },

  computed: {
    isDark() {
      return (this.$vuetify.theme.dark) ? 'dark' : 'light'
    },

    pageHeight() {
      return document.body.scrollHeight;
    },

    groupByDay() {
      let g = {};
      this.messenger2.currentPosts.forEach(item => {
        let d = new Date(item.timestamp).toLocaleDateString('sk', {
          year: 'numeric',
          month: 'long',
          day: '2-digit'
        });

        if (g[d]) {
          g[d].push(item);
        } else {
          g[d] = [];
          g[d].push(item);
        }
      });
      return g;
    },

    groupByDayConversation() {
      let msg = {};
      this.conversation.forEach(item => {
        let date = new Date(item.timestamp).toLocaleDateString('sk', {
          year: 'numeric',
          month: 'long',
          day: '2-digit'
        });
        if (msg[date]) {
          msg[date].push(item);
        } else {
          msg[date] = [];
          msg[date].push(item);
        }
      });
      return msg;
    },

    filteredUsers() {
      return _.orderBy(this.messenger2.users.filter(item => {
        if (!this.search) return this.messenger2.users;
        return (item.name.toLowerCase()
          .includes(this.search.toLowerCase()));
      }), 'headline');
    },
  },

  methods: {
    popup() {
      window.open('https://metamask.io/download/', '_blank');
    },
    /**
     * gets current account on web3 and
     * store it on currentAccount variable.
     */
    async updateAccount() {
      const accounts = await web3.eth.getAccounts();
      if (accounts.length == 0) {
        console.log("User is not logged in to MetaMask");
        this.messenger2.loggedInToMetamask = true;
        this.loggedInToMetamaskText = "User is not logged in to MetaMask";
      } else {
        this.messenger2.loggedInToMetamask = true;
        this.loggedInToMetamaskText = "User logged into MetaMask";
        const account = accounts[0];
        this.messenger2.currentAccount = account;
        web3.eth.defaultAccount = this.messenger2.currentAccount;
      }
    },

    async getActualBalance() {
      const accounts = await web3.eth.getAccounts();
      if (accounts.length != 0) {
        await web3.eth.getBalance(this.messenger2.currentAccount, (err, balance) => {
          this.messenger2.balance = web3.utils.fromWei(balance, "ether") + " ETH"
        });
      }
    },

    async initMyProfile() {
      const accounts = await web3.eth.getAccounts();
      if (accounts.length != 0) {
        // const myProfile = await contract.methods.userInfo(this.messenger2.currentAccount).call({
        //   from: this.messenger2.currentAccount,
        // });

        const myProfile = await contract.methods.getMe(this.web3StringToBytes32(localStorage.getItem('user_id'))).call();
        // console.log(myProfile);
        this.messenger2.myProfile.id = this.Bytes32ToWeb2String(myProfile[0]);
        this.messenger2.myProfile.name = this.Bytes32ToWeb2String(myProfile[1]);
        this.messenger2.myProfile.email = this.Bytes32ToWeb2String(myProfile[2]);
        this.messenger2.myProfile.avatar = this.Bytes32ToWeb2String(myProfile[3]);
      }
    },

    async getUsers() {
      this.messenger2.users = [];
      const users = await contract.methods.getUsers().call({
        from: this.messenger2.currentAccount,
      });
      for (var i = 0; i < users.length; i++) {
        if (this.Bytes32ToWeb2String(users[i].id) == localStorage.getItem('user_id')) {
          this.messenger2.amILogged = true;
        }

        if (this.messenger2.myProfile.name != this.Bytes32ToWeb2String(users[i].name)) {
          this.messenger2.users.push({
            id: this.Bytes32ToWeb2String(users[i].id),
            name: this.Bytes32ToWeb2String(users[i].name),
            email: this.Bytes32ToWeb2String(users[i].email),
            avatar: this.Bytes32ToWeb2String(users[i].avatar),
          })
        }
      }
      this.messenger2.loadingusers = false;
    },

    async getPosts() {
      const accounts = await web3.eth.getAccounts();
      if (accounts.length != 0) {
        this.messenger2.currentPosts = [];
        this.messenger2.loading = true;
        const counter = await contract.methods.getCounter().call({
          from: this.messenger2.currentAccount,
        });

        if (counter !== null) {
          const hashes = [];
          for (let i = counter; i >= 1; i -= 1) {
            hashes.push(contract.methods.getHash(i).call({
              from: this.messenger2.currentAccount,
            }));
          }

          const postHashes = await Promise.all(hashes);
          for (let i = 0; i < postHashes.length; i += 1) {
            if (this.Bytes32ToWeb2String(postHashes[i].touserid) == 'null') {
              // const myProfile = await contract.methods.userInfo(postHashes[i].owner).call({
              //   from: this.messenger2.currentAccount,
              // });
              // const myProfile = await contract.methods.getMe(this.web3StringToBytes32(localStorage.getItem('user_id'))).call();
              const captions = await Promise.resolve(fetch(`https://gateway.ipfs.io/ipfs/${postHashes[i].text}`)
                .then(res => res.text()));

              this.messenger2.currentPosts.push({
                id: i,
                key: `key${i}`,
                text: captions,
                timestamp: new Date(postHashes[i].date * 1000).toString(),
                videosrc: postHashes[i].video,
                src: postHashes[i].img,
                owner: this.Bytes32ToWeb2String(postHashes[i].owner),
                ownerid: this.Bytes32ToWeb2String(postHashes[i].ownerid),
                toUser: this.Bytes32ToWeb2String(postHashes[i].touserid)
              });
            }
          }
          this.messenger2.loading = false;
        }
      }
    },
    // --------------------------------------------------------------------------------------------------------------------------------
    self() {
      return this
    },

    Bytes32ToWeb2String(text) {
      var result = ethers.utils.parseBytes32String(text);
      return result;
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
        this.handleOk('msg');
        this.resetIcon();
      } else {
        this.message = '👍';
        this.handleOk('msg');
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

    // sendTypingEvent(e) {
    //   if (e.key != 'Enter') {
    //     window.Echo.join('pchat')
    //       .whisper('typing', [localStorage.getItem('username'), 2000]);
    //   } else {
    //     window.Echo.join('pchat')
    //       .whisper('typing', [localStorage.getItem('username'), 500]);
    //   }
    // },

    // chatInput
    toggleMarker() {
      this.marker = !this.marker
    },

    showFileInputComment() {
      this.files = [];
      this.showDragAndDropFile = !this.showDragAndDropFile;
    },

    bottomNavigationStyle() {
      if (window.innerWidth <= 480 && this.$store.getters['bottomNavigationState'].state == true) {
        this.valueOfBottom = '56';
      } else {
        this.valueOfBottom = '0';
      }
      window.addEventListener('resize', () => {
        if (window.innerWidth <= 480 && this.$store.getters['bottomNavigationState'].state == true) {
          this.valueOfBottom = '56';
        } else {
          this.valueOfBottom = '0';
        }
      });
    },

    async loginWithMetaMask() {
      this.isDisabled = true;

      const accounts = await window.ethereum.request({
          method: 'eth_requestAccounts'
        })
        .catch(() => {
          return
        })
      if (!accounts) {
        return
      }

      window.userWalletAddress = accounts[0];
      this.userWallet = window.userWalletAddress;
    },

    signOutMetaMask() {
      window.userWalletAddress = null;
      this.userWallet = '';
    },

    selectContact(item) {
      this.chatWith.id = item.id;
      this.chatWith.name = item.name;
      this.chatWith.email = item.email;
      this.chatWith.avatar = item.avatar;
      this.getPostConversation();
      this.chat = true;
    },

    closeChat() {
      this.chatWith.id = '';
      this.chatWith.name = '';
      this.chatWith.email = '';
      this.chatWith.avatar = '';
      this.chat = false;
    },

    async getPostConversation() {
      let meid = this.messenger2.myProfile.id;
      let userid = this.chatWith.id
      const accounts = await web3.eth.getAccounts();
      if (accounts.length != 0) {
        this.conversation = [];
        this.loadingconversation = true;
        const counter = await this.messenger2.contract.methods.getCounter().call({
          from: this.messenger2.currentAccount,
        });

        if (counter !== null) {
          const hashes = [];
          // const captions = [];
          for (let i = 0; i <= counter; i++) {
            hashes.push(this.messenger2.contract.methods.getHash(i).call({
              from: this.currentAccount,
            }));
          }
          const postHashes = await Promise.all(hashes);

          for (let i = 0; i < postHashes.length; i++) {
            if ((this.Bytes32ToWeb2String(postHashes[i].ownerid) == meid && this.Bytes32ToWeb2String(postHashes[i].touserid) == userid) ||
              (this.Bytes32ToWeb2String(postHashes[i].ownerid) == userid && this.Bytes32ToWeb2String(postHashes[i].touserid) == meid)) {
              // const myProfile = await this.messenger2.contract.methods.userInfo(postHashes[i].owner).call({
              //   from: this.currentAccount,
              // });

              const captions = await Promise.resolve(fetch(`https://gateway.ipfs.io/ipfs/${postHashes[i].text}`)
                .then(res => res.text()));

              this.conversation.push({
                id: i,
                key: `key${i}`,
                text: captions,
                timestamp: new Date(postHashes[i].date * 1000).toString(),
                videosrc: postHashes[i].video,
                src: postHashes[i].img,
                owner: this.Bytes32ToWeb2String(postHashes[i].owner),
                ownerid: this.Bytes32ToWeb2String(postHashes[i].ownerid),
                toUser: this.Bytes32ToWeb2String(postHashes[i].touserid)
              });
            }
          }
          this.loadingconversation = false;
        }
      }
    },

    web3StringToBytes32(text) {
      var result = ethers.utils.formatBytes32String(text);
      return result;
    },

    saveSetProfile() {
      this.messenger2.contract.methods.setProfile(this.web3StringToBytes32(this.messenger2.myProfile.id), this.web3StringToBytes32(this.messenger2.myProfile.name), this.web3StringToBytes32(this.messenger2.myProfile.email),
        this.web3StringToBytes32(this.messenger2.myProfile.avatar)).send({
          from: this.messenger2.currentAccount
        },
        (error, transactionHash) => {
          console.log(error);
          console.log(transactionHash);
        })
    },

    save() {
      let avatar;
      if (this.$root.me.avatar != null) {
        avatar = this.web3StringToBytes32(this.$root.me.avatar)
        this.clearMessage();
      } else {
        avatar = this.web3StringToBytes32('null');
      }

      this.btnloading = true;
      this.messenger2.contract.methods.setProfile(this.web3StringToBytes32(localStorage.getItem('user_id')), this.web3StringToBytes32(this.$root.me.name), this.web3StringToBytes32(this.$root.me.email),
        avatar).send({
          from: this.messenger2.currentAccount
        },
        (error, transactionHash) => {
          if (typeof transactionHash !== 'undefined') {
            // this.alertText = 'Ukladanie na Ethereu...';
            contract.once('setUserInfo', {
                from: this.messenger2.currentAccount
              },
              (error) => {
                if (!error) {
                  this.snackbar = true;
                  this.snackbar_text = "Transakcia prebehla úspešne!";
                  this.snackbar_color = 'green';
                  this.snackbar_icon = 'mdi-check-circle';
                  this.snackbar_timeout = '5000';
                  this.btnloading = false;
                  this.dialogAddToContract = false;
                  this.messenger2.amILogged = true;
                } else {
                  this.snackbar = true;
                  this.snackbar_text = "Transakcia zlyhala!";
                  this.snackbar_color = 'red';
                  this.snackbar_icon = 'mdi-alert-circle';
                  this.snackbar_timeout = '5000';
                  this.btnloading = false;
                  this.dialogAddToContract = false;
                }
              });
          } else {
            this.btnloading = false;
            this.dialogAddToContract = true;
          }
        })
    },

    updateProfile() {
      this.messenger2.contract.methods.updateUserInfo(this.messenger2.currentAccount, this.web3StringToBytes32(this.messenger2.myProfile.avatar)).send({
          from: this.messenger2.currentAccount
        },
        (error, transactionHash) => {
          if (typeof transactionHash !== 'undefined') {
            this.alertText = 'Ukladanie na Ethereu...';
            this.messenger2.contract.once('UpdateUserInfo', {
                from: this.messenger2.currentAccount
              },
              (error) => {
                if (!error) {
                  this.getUsers();
                  this.snackbar = true;
                  this.snackbar_text = "Transakcia prebehla úspešne!";
                  this.snackbar_color = 'green';
                  this.snackbar_icon = 'mdi-check-circle';
                  this.snackbar_timeout = '5000';
                } else {
                  this.snackbar = true;
                  this.snackbar_text = "Transakcia zlyhala!";
                  this.snackbar_color = 'red';
                  this.snackbar_icon = 'mdi-alert-circle';
                  this.snackbar_timeout = '5000';
                }
              });
          } else {
            this.messenger2.loadingusers = false;
          }
        })
    },
    /**
     * converts ArrayBuffer to
     * Buffer for IPFS upload.
     */
    async convertToBuffer(reader) {
      return Buffer.from(reader);
    },
    /**
     * submits buffered image & text to IPFS
     * and retrieves the hashes, then store
     * it in the Contract via sendHash().
     */
    async onSubmit(data) {
      this.alertText = 'Uploading on IPFS...'
      if (data == 'post') {
        this.messenger2.loading = true;
      }
      // let imgHash;
      let addImg;
      let addVideo;
      let addMessage;
      let addUserId;
      let ownName;
      let ownId;

      if (this.files[0] != null) {
        if (this.files[0].type.slice(0, 5) == 'image') {
          addImg = await ipfs.add(this.files[0]);
          addVideo = await ipfs.add('null');
        } else {
          addVideo = await ipfs.add(this.files[0]);
          addImg = await ipfs.add('null');
        }
      } else {
        addVideo = await ipfs.add('null');
        addImg = await ipfs.add('null');
      }

      if (this.message != '') {
        addMessage = await ipfs.add(this.message);
        this.clearMessage();
      } else {
        addMessage = await ipfs.add('null');
      }

      if (this.chatWith.id != '') {
        addUserId = this.web3StringToBytes32(this.chatWith.id);
        this.clearMessage();
      } else {
        addUserId = this.web3StringToBytes32('null');
      }

      ownName = this.web3StringToBytes32(localStorage.getItem('username'));
      ownId = this.web3StringToBytes32(localStorage.getItem('user_id'));

      // myId = await ipfs.add(this.$root.myProfile2.id);
      //  myId.path,
      this.messenger2.contract.methods.sendHash(ownName, addImg.path, addVideo.path, addMessage.path, ownId, addUserId).send({
          from: this.messenger2.currentAccount
        },
        (error, transactionHash) => {
          if (typeof transactionHash !== 'undefined') {
            this.alertText = 'Storing on Ethereum...';
            this.files = [];
            this.message = '';
            // console.log("success");
            this.messenger2.contract.once('NewPost', {
                from: this.messenger2.currentAccount
              },
              (error) => {
                if (!error) {
                  // console.log('success');
                  this.snackbar = true;
                  this.snackbar_text = "Transakcia prebehla úspešne!";
                  this.snackbar_color = 'green';
                  this.snackbar_icon = 'mdi-check-circle';
                  this.snackbar_timeout = '3000';

                  if (data != 'post') {
                    console.log("here");
                    this.getPostConversation();
                  } else {
                    console.log("here");
                    this.getPosts();
                  }
                } else {
                  this.snackbar = true;
                  this.snackbar_text = "Transakcia zlyhala!";
                  this.snackbar_color = 'red';
                  this.snackbar_icon = 'mdi-alert-circle';
                  this.snackbar_timeout = '5000';
                }
              });
          } else {
            this.snackbar = true;
            this.snackbar_text = "Transakcia bola zrušená!";
            this.snackbar_color = 'red';
            this.snackbar_icon = 'mdi-alert-circle';
            this.snackbar_timeout = '5000';
            this.files = [];
            this.message = '';
            this.messenger2.loading = false;
          }
        });
    },
    /**
     * validates if image & captions
     * are filled before submission.
     */
    handleOk(data) {
      if (this.files.length == 0 && !this.message) {
        this.alertText = 'Please fill in the information.';
      } else {
        this.onSubmit(data);
      }
    },

    //filepond getFiles
    // handleFilePondInit: function() {
    //   console.log("FilePond has initialized");
    // },
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
  },
}
</script>

<style scoped>
.addAcc .v-overlay--active {
  -webkit-backdrop-filter: blur(10px) !important;
  backdrop-filter: blur(10px) !important;
  background-color: rgba(255, 255, 255, 0.5) !important;
}

/* .v-window__container {
  padding-bottom: 480px !important;
} */

/* messages feed */
.bubble_friend {
  border-radius: 10px;
  /* max-width: 230px; */
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
  /* max-width: 230px; */
}

.bubble_me2 {
  font-size: 10px;
  color: lightblue;
  position: absolute;
  right: 12px;
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
  color: rgb(255, 255, 255);
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

  /* height: auto;
  flex-direction: column; */
}

.theme--light .fixed-bottom-bar {
  background-color: #f5f5f5;
}
</style>
