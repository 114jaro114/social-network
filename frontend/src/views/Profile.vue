<template>
<div class="profile mt-16 pb-16">

  <v-overlay :value="isFriendOrNoOrMe_overlay" :absolute="true" :opacity="0" v-if="isFriendOrNoOrMe_overlay">
    <v-progress-circular indeterminate size="24" color="primary"></v-progress-circular>
  </v-overlay>

  <div v-else>
    <v-container v-if="isFriendOrNoOrMe == -1">
      <v-card elevation="0">
        <v-img class="position-relative" height="200px" src="https://cdn.pixabay.com/photo/2020/07/12/07/47/bee-5396362_1280.jpg">
          <v-btn class="position-absolute m-2" style="right:0;bottom:0" text color="white" outlined rounded>
            Upraviť titulnú fotku
          </v-btn>
        </v-img>
        <v-card-actions>
          <v-row class="ml-0 mr-0">
            <v-col cols="12" xl="6" lg="6" md="6" sm="6" xs="12" class="d-flex justify-start">
              <v-snackbar v-model="snackbarProfilePicture" :multi-line="multiLine" color="success" bottom left class="m-3">
                <v-icon>mdi-check-circle</v-icon>
                <span>{{snackbarText}}</span>
                <template v-slot:action="{ attrs }">
                  <v-btn color="white" fab text small v-bind="attrs" @click="snackbarProfilePicture = false">
                    <v-icon>mdi-close-circle</v-icon>
                  </v-btn>
                </template>
              </v-snackbar>

              <v-card-title v-if="$root.me.overlayAvatar == true">
                <v-avatar color="primary" size="64">
                  <v-overlay :value="$root.me.overlayAvatar" :absolute="true" :opacity="0">
                    <v-progress-circular indeterminate size="24" color="white"></v-progress-circular>
                  </v-overlay>
                </v-avatar>
              </v-card-title>

              <v-card-title v-else>
                <v-dialog v-model="dialog" width="500">
                  <template v-slot:activator="{ on: onDialog, attrs }">
                    <div class="position-relative" v-if="$root.me.avatar == null">
                      <v-tooltip bottom>
                        <template v-slot:activator="{ on: onTooltip }">
                          <v-btn v-bind="attrs" v-on="{ ...onTooltip, ...onDialog}" class="position-absolute" style="bottom: -5px; right: -5px; z-index: 1" height="25px" width="25px" elevation="0" fab offset-x="20" offset-y="15">
                            <v-icon size="18px">mdi-camera</v-icon>
                          </v-btn>
                        </template>
                        <span>Pridať profilovú fotku</span>
                      </v-tooltip>

                      <v-avatar color="primary" size="64">
                        <span class="text-uppercase white--text">{{ $root.me.name.charAt(0) }}</span>
                      </v-avatar>
                    </div>

                    <div class="position-relative" v-else>
                      <v-tooltip bottom>
                        <template class="image-upload" v-slot:activator="{ on: onTooltip }">
                          <v-btn v-bind="attrs" v-on="{ ...onTooltip, ...onDialog}" class="position-absolute" style="bottom: -5px; right: -5px; z-index: 1" height="25px" width="25px" elevation="0" fab for="file-input" offset-x="20" offset-y="15">
                            <v-icon size="18px">mdi-camera</v-icon>
                          </v-btn>
                        </template>
                        <span>Zmeniť profilovú fotku</span>
                      </v-tooltip>

                      <v-avatar color="primary" size="64">
                        <v-img :src="`${$root.envUrlNoApi}/storage/user-avatar/${newProfile}`" />
                      </v-avatar>
                    </div>
                  </template>

                  <v-card>
                    <v-toolbar dark color="primary" elevation="0">
                      <v-toolbar-title>Profilová fotka</v-toolbar-title>
                      <v-spacer />
                      <v-btn icon dark @click="dialog = false">
                        <v-icon>mdi-close</v-icon>
                      </v-btn>
                    </v-toolbar>
                    <!-- <my-upload :noRotate="false" langType="en" field="img" @crop-success="cropSuccess" @crop-upload-success="cropUploadSuccess" @crop-upload-fail="cropUploadFail" v-model="show" :width="300" :height="300" :params="params"
                      :headers="headers" img-format="png">
                    </my-upload>
                    <img ref="image" :src="imgDataUrl"> -->
                    <div>
                      <v-row class="m-0 p-3 justify-center">
                        <vue-avatar :width="256" :height="256" :border="10" :rotation="rotation" :borderRadius="200" :scale="scale" ref="vueavatar" @vue-avatar-editor:image-ready="onImageReady">
                        </vue-avatar>
                      </v-row>

                      <v-row class="m-0 p-3 justify-center">
                        <v-slider :disabled="disabledSlidersAndBtn" prepend-icon="mdi-magnify" color="primary" track-color="primary" track-fill-color="primary" v-model="scale" thumb-label="always" :thumb-size="28" max="3" min="1" step="0.02">
                        </v-slider>
                      </v-row>

                      <v-row class="m-0 p-3 pb-0 justify-center">
                        <v-slider :disabled="disabledSlidersAndBtn" prepend-icon="mdi-rotate-3d-variant" color="primary" track-color="primary" track-fill-color="primary" v-model="rotation" thumb-label="always" :thumb-size="28" max="360" min="0"
                          step="1">
                        </v-slider>
                      </v-row>

                      <!-- <v-row class="m-0 p-3 justify-center">
                        <v-btn v-on:click="saveClicked">Náhľad</v-btn>
                      </v-row> -->
                      <img class="rounded-circle" ref="image">
                      <v-divider class="mt-0 mb-0" />
                      <v-row class="m-0 p-3 justify-center">
                        <v-btn :disabled="disabledIfvatarNotExist" color="primary" @click="deleteAvatar()">Odstrániť profilovú fotku</v-btn>
                        <v-spacer />
                        <v-btn :disabled="disabledSlidersAndBtn" color="primary" @click="updateAvatar()">Uložiť</v-btn>
                      </v-row>
                    </div>
                  </v-card>
                </v-dialog>
                <span class="ml-3">
                  {{$root.me.name}}
                </span>
              </v-card-title>
            </v-col>
          </v-row>
        </v-card-actions>
        <v-row class="m-0">
          <v-tabs grow>
            <v-tab v-for="tab in tabs" :key="tab.id" :to="tab.route" exact>{{ tab.name }}</v-tab>
          </v-tabs>
        </v-row>
      </v-card>

      <v-row class="mt-3 mb-3 m-0">
        <v-card class="w-100 rounded" elevation="0">
          <v-tabs-items class="" v-model="activeTab" @change="updateRouter($event)" grow>
            <v-tab-item v-for="tab in tabs" :key="tab.id" :value="tab.route">
              <router-view :key="'/profile/'+$route.params.user_id" />
            </v-tab-item>
          </v-tabs-items>
        </v-card>
      </v-row>
    </v-container>
    <!--///////////////////////////////////// other user ////////////////////////////////////////////////////-->
    <v-container v-else>
      <v-card elevation="0">
        <v-img class="position-relative" height="200px" src="https://cdn.pixabay.com/photo/2020/07/12/07/47/bee-5396362_1280.jpg"></v-img>
        <!-- <v-card-actions> -->
        <v-row class="m-0">
          <v-col cols="12" xl="6" lg="6" md="6" sm="6" xs="12" class="d-flex justify-start p-0">
            <v-card-title>
              <v-avatar color="primary" size="64" v-if="userDataProfile.avatar == null">
                <span v-if="userDataProfile.name != null" class="text-uppercase white--text">{{ userDataProfile.name.charAt(0) }}</span>
              </v-avatar>
              <v-avatar size="64" v-else>
                <img :lazy-src="`${$root.envUrlNoApi}/storage/user-avatar/${userDataProfile.avatar}`" :src="`${$root.envUrlNoApi}/storage/user-avatar/${userDataProfile.avatar}`">
              </v-avatar>
              <span class="ml-3">
                {{userDataProfile.name}}
              </span>
            </v-card-title>
          </v-col>

          <v-col cols="12" xl="6" lg="6" md="6" sm="12" xs="12" class="d-flex justify-center justify-lg-end justify-md-end p-0">
            <v-card-title v-if="isFriendOrNoOrMe != -1">
              <!-- if friends -->
              <div v-if="isFriendOrNoOrMe == 1">
                <v-btn color="primary" outlined rounded>
                  <v-icon small class="mr-1">mdi-account-check</v-icon>
                  Priatelia
                </v-btn>
                <v-btn class="ml-3" color="primary" rounded :to="'/messenger?name='+userDataProfile.name">
                  <v-icon small class="mr-1">mdi-forum</v-icon>
                  Správa
                </v-btn>
              </div>
              <!-- if no friends -->
              <div v-else>
                <v-btn class="ml-3" color="primary" rounded>
                  <v-icon small class="mr-1">mdi-account-plus</v-icon>
                  Pridať priateľa
                </v-btn>
                <v-btn class="ml-3" color="primary" outlined rounded disabled>
                  <v-icon small class="mr-1">mdi-forum</v-icon>
                  Správa
                </v-btn>
              </div>
            </v-card-title>
          </v-col>
        </v-row>
        <!-- </v-card-actions> -->
        <v-row class="m-0">
          <v-tabs grow>
            <v-tab v-for="tab in tabs" :key="tab.id" :to="tab.route" exact>{{ tab.name }}</v-tab>
          </v-tabs>
        </v-row>
      </v-card>

      <v-row class="mt-3 mb-3 m-0">
        <v-card class="w-100 rounded" elevation="0">
          <v-tabs-items class="" v-model="activeTab" @change="updateRouter($event)" grow>
            <v-tab-item v-for="tab in tabs" :key="tab.id" :value="tab.route">
              <router-view :key="'/profile/'+$route.params.user_id" />
            </v-tab-item>
          </v-tabs-items>
        </v-card>
      </v-row>
    </v-container>
  </div>
</div>
</template>

<script>
import axios from 'axios';
// import myUpload from 'vue-image-crop-upload';
import {
  VueAvatar
} from 'vue-avatar-editor-improved';
export default {
  name: "Profile",
  props: [],
  components: {
    VueAvatar,
    // 'my-upload': myUpload
  },
  data() {
    return {
      username: localStorage.getItem('username'),
      email: localStorage.getItem('email'),
      //search new friends
      me: [],
      overlayAvatar: true,
      dialog: false,
      snackbarProfilePicture: false,
      snackbarText: '',
      multiLine: true,
      disabledSlidersAndBtn: true,
      disabledIfvatarNotExist: true,
      rotation: 0,
      scale: 1,
      //profile photo
      //tabs
      tab: null,
      activeTab: `/profile/${this.$route.params.user_id}`,
      tabs: [{
        id: 1,
        name: 'Príspevky',
        route: `/profile/${this.$route.params.user_id}`
      }, {
        id: 2,
        name: 'Informácie',
        route: `/profile/${this.$route.params.user_id}/about`
      }, {
        id: 3,
        name: 'Priatelia',
        route: `/profile/${this.$route.params.user_id}/friends`
      }],
      isFriendOrNoOrMe: '',
      isFriendOrNoOrMe_overlay: true,
      userDataProfile: null,
    }
  },

  watch: {
    '$route.params.user_id'() {
      this.checkIfUserIsMyFriend();
      this.getListOfUserFriends();
      this.activeTab = `/profile/${this.$route.params.user_id}`;
      this.tabs[0].route = `/profile/${this.$route.params.user_id}`;
      this.tabs[1].route = `/profile/${this.$route.params.user_id}/about`;
      this.tabs[2].route = `/profile/${this.$route.params.user_id}/friends`;
    }
  },

  updated() {
    // this.checkIfUserIsMyFriend();
    // this.saveClicked()
    // if (this.$refs.vueavatar != undefined) {
    //   if (this.$refs.vueavatar[0].$refs.input.files.length != 0) {
    //     console.log('updated');
    //     this.disabledSlidersAndBtn = false;
    //   }
    // }
  },

  created() {
    this.checkIfUserIsMyFriend();
    this.getListOfUserFriends();
  },

  methods: {
    checkIfUserIsMyFriend() {
      this.isFriendOrNoOrMe_overlay = true;
      const api = `${process.env.VUE_APP_API_URL}/checkIfUserIsMyFriend/${this.$route.params.user_id}`;
      const config = {
        headers: {
          Accept: "application/json",
          Authorization: "Bearer " + localStorage.getItem("authToken"),
        },
      };
      axios.get(api, config)
        .then(res => {
          this.isFriendOrNoOrMe = res.data[0];
          this.userDataProfile = res.data[1][0];
          this.$root.profile.data.returnVariable = res.data[0];
          this.$root.profile.data.userData = res.data[1][0];
          this.isFriendOrNoOrMe_overlay = false;
        });
    },

    getListOfUserFriends() {
      this.$root.profile.friendships.friends_overlay = true;
      const api = `${process.env.VUE_APP_API_URL}/friendshipsProfile/${this.$route.params.user_id}`;
      const config = {
        headers: {
          Accept: "application/json",
          Authorization: "Bearer " + localStorage.getItem("authToken"),
        },
      };
      axios.get(api, config)
        .then(res => {
          this.$root.profile.friendships.friends = res.data[0];
          this.$root.profile.friendships.numberOfFriends = res.data[1];
          this.$root.profile.friendships.friends_overlay = false;
        });
    },

    updateRouter(tab) {
      this.$router.push(tab)
    },

    saveClicked() {
      if (this.$refs.image != undefined) {
        var img = this.$refs.vueavatar[0].getImageScaled()
        this.$refs.image[0].src = img.toDataURL()
      }
    },
    onImageReady() {
      this.scale = 1
      this.rotation = 0
      this.disabledSlidersAndBtn = false;
    },


    toggleShow() {
      this.show = !this.show;
    },

    cropSuccess(imgDataUrl, field) {
      console.log(field);
      console.log('-------- crop success --------');
      this.imgDataUrl = imgDataUrl;
      let formData = new FormData();
      formData.append('avatar', imgDataUrl);
      const api = `${process.env.VUE_APP_API_URL}/upload_avatar`;
      const config = {
        headers: {
          Accept: "application/json",
          // 'Content-Type': 'multipart/form-data',
          Authorization: "Bearer " + localStorage.getItem("authToken"),
        },
      };
      axios.post(api, formData, config)
        .then((response) => {
          console.log(response.data);
          this.avatarImageUrl = response.data.avatar_url
          this.$emit('imageUrl', response.data.secure_url)
        })
        .catch((error) => {
          if (error.response) {
            console.log(error.message)
          } else {
            console.log(error)
          }
        })
    },
    cropUploadSuccess(jsonData, field) {
      console.log('-------- upload success --------');
      console.log(jsonData);
      console.log('field: ' + field);
    },
    cropUploadFail(status, field) {
      console.log('-------- upload fail --------');
      console.log(status);
      console.log('field: ' + field);
    },

    // getDataOfMe() {
    //   const api = `${process.env.VUE_APP_API_URL}/auth/user`;
    //   const config = {
    //     headers: {
    //       Accept: "application/json",
    //       Authorization: "Bearer " + localStorage.getItem("authToken"),
    //     },
    //   };
    //   axios.get(api, config)
    //     .then(res => {
    //       console.log(res.data);
    //       this.$root.me.overlayAvatar = false;
    //       this.$root.me = res.data;
    //     });
    // },

    deleteAvatar() {
      const api = `${process.env.VUE_APP_API_URL}/delete_avatar`;
      const config = {
        headers: {
          Accept: "application/json",
          Authorization: "Bearer " + localStorage.getItem("authToken"),
        },
      };
      axios.post(api, {
            avatar: null
          },
          config
        )
        .then((response) => {
          this.dialog = false;
          this.snackbarProfilePicture = true;
          this.snackbarText = 'Profilové fotka bola úspešne zmazaná!';
          this.$root.me.avatar = response.data.avatar_name;
          this.$emit('imageUrl', response.data.secure_url);

          this.$root.navigationDrawerCenter.getAllPosts.allPosts.forEach((elem) => {
            if (elem.user_id == localStorage.getItem("user_id")) {
              elem.userpostmodel.avatar = null;
            }
          })

          this.$root.profilePosts.getAllPosts.allPosts.forEach((elem) => {
            if (elem.user_id == localStorage.getItem("user_id")) {
              elem.userpostmodel.avatar = null;
            }
          })
          // this.getDataOfMe();
          this.disabledIfvatarNotExist = true;
        })
        .catch((error) => {
          if (error.response) {
            console.log(error.message)
          } else {
            console.log(error)
          }
        })
    },
    // Profile
    updateAvatar() {
      if (this.$refs.vueavatar.$refs.input.files[0].name) {
        // this.$refs.vueavatar[0].$refs.input.files[0].name
        let formData = new FormData();
        formData.append('avatar', this.$refs.vueavatar.getImageScaled().toDataURL());
        // this.$refs.vueavatar[0].getImageScaled().toDataURL()
        const api = `${process.env.VUE_APP_API_URL}/upload_avatar`;
        const config = {
          headers: {
            Accept: "application/json",
            'Content-Type': 'multipart/form-data',
            Authorization: "Bearer " + localStorage.getItem("authToken"),
          },
        };

        this.$root.me.overlayAvatar = true;
        axios.post(api, formData, config)
          .then((response) => {
            console.log(response);
            if (this.$root.me.avatar != null) {
              this.snackbarText = 'Profilové fotka bola úspešne zmenená!';
            } else {
              this.snackbarText = 'Profilové fotka bola úspešne pridaná!';
            }
            this.dialog = false;
            this.snackbarProfilePicture = true;
            this.$root.me.avatar = response.data.avatar;
            this.$root.me.overlayAvatar = false;
            this.disabledIfvatarNotExist = false;
          })
          .catch((error) => {
            if (error.response) {
              console.log(error.message)
            } else {
              console.log(error)
            }
          })
      }
    }
  },


  computed: {
    newProfile() {
      return this.$root.me.avatar;
    }
  },

  mounted() {
    const api = `${process.env.VUE_APP_API_URL}/user`;
    const config = {
      headers: {
        Accept: "application/json",
        Authorization: "Bearer " + localStorage.getItem("authToken"),
      },
    };
    axios.get(api, config)
      .then((response) => {
        if (response.data.avatar) {
          this.avatarImageUrl = this.$root.envUrlNoApi + "/storage/user-avatar/" + response.data.avatar;
          this.disabledIfvatarNotExist = false;
        }
      })
      .catch((error) => {
        console.log(error)
      })
  },

}
</script>

<style>
.image-upload>input {
  display: none;
}

img {
  image-orientation: from-image;
}

.vue-rectangle-stencil__preview {
  border-radius: 200px;
}

.vue-line-wrapper--south {
  top: 0 !important;
  width: 0 !important;
}

.vue-line-wrapper--north {
  top: 0 !important;
  width: 0 !important;
}

.vue-line-wrapper--east {
  top: 0 !important;
}

.vue-line-wrapper--west {
  top: 0 !important;
}


.vue-simple-line--south {
  border-top-width: 0px !important;
}


.vue-simple-line--north {
  border-top-width: 0px !important;
}


.vue-simple-line--east {
  border-right-width: 0px !important;
}

.vue-simple-line--west {
  border-left-width: 0px !important;
}

.vue-simple-handler {
  width: 0 !important;
  height: 0 !important;
}

.theme--dark.v-tabs-items {
  background-color: #121212 !important;
}

.theme--light.v-tabs-items {
  background-color: #f5f5f5 !important;
}
</style>
