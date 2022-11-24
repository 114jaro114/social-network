<template>
<v-container class="profile p-0">
  <v-row class="m-0">
    <v-col xl="4" lg="4" md="12" sm="12" xs="12" class="pl-0 pr-0 pb-0">
      <v-row class="ml-0 mr-0 mb-3">
        <v-card class="w-100 rounded" elevation="0" min-height="200">
          <v-card-text class="p-0">
            <v-list class="pt-0 pb-0" dense style="border-top-right-radius: 100px;border-bottom-right-radius: 100px;">
              <v-list-item-group color="primary">
                <v-list-item rounded>
                  <v-icon class="mr-7">mdi-briefcase</v-icon>
                  <v-list-item-content>
                    <v-card class="p-2 rounded-lg card-comments" elevation="0">
                      <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-3 pl-1">
                        <span>Práca</span>
                      </v-card-title>
                      <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">Informácie o Vašej práci</v-card-subtitle>
                    </v-card>
                  </v-list-item-content>
                </v-list-item>

                <v-list-item>
                  <v-icon class="mr-7">mdi-school</v-icon>
                  <v-list-item-content>
                    <v-card class="p-2 rounded-lg card-comments" elevation="0">
                      <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-3 pl-1">
                        <span>Študuje</span>
                      </v-card-title>
                      <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">
                        <span>Informácie o Vašom štúdiu</span>
                      </v-card-subtitle>
                    </v-card>
                  </v-list-item-content>
                </v-list-item>

                <v-list-item>
                  <v-icon class="mr-7">mdi-home</v-icon>
                  <v-list-item-content>
                    <v-card class="p-2 rounded-lg card-comments" elevation="0">
                      <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-3 pl-1">
                        <span>Žije v</span>
                      </v-card-title>
                      <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">Informácie o Vašom bydlisku</v-card-subtitle>
                    </v-card>
                  </v-list-item-content>
                </v-list-item>

                <v-list-item>
                  <v-icon class="mr-7">mdi-map-marker-radius</v-icon>
                  <v-list-item-content>
                    <v-card class="p-2 rounded-lg card-comments" elevation="0">
                      <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-3 pl-1">
                        Rodné mesto
                      </v-card-title>
                      <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">Informácie o Vašom rodnom meste</v-card-subtitle>
                    </v-card>
                  </v-list-item-content>
                </v-list-item>

                <v-list-item>
                  <v-icon class="mr-7">mdi-heart</v-icon>
                  <v-list-item-content>
                    <v-card class="p-2 rounded-lg card-comments" elevation="0">
                      <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-3 pl-1">
                        Stav
                      </v-card-title>
                      <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">Informácie o Vašom rodinnom stave</v-card-subtitle>
                    </v-card>
                  </v-list-item-content>
                </v-list-item>

                <v-list-item>
                  <v-icon class="mr-7">mdi-phone</v-icon>
                  <v-list-item-content>
                    <v-card class="p-2 rounded-lg card-comments" elevation="0">
                      <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-3 pl-1">
                        Kontakt
                      </v-card-title>
                      <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">Informácie o Vašom kontakte</v-card-subtitle>
                    </v-card>
                  </v-list-item-content>
                </v-list-item>
              </v-list-item-group>
            </v-list>
          </v-card-text>

          <v-divider class="mt-2" v-if="$root.profile.data.returnVariable == -1" />

          <v-btn text block color="primary" :to="'/profile/' + this.$route.params.user_id + '/about'" @click="reveal = true" v-if="$root.profile.data.returnVariable == -1">
            Upraviť informácie
          </v-btn>

          <v-btn text block color="primary" @click="reveal = true" v-if="$root.profile.data.returnVariable == -1">
            Pridať hobby
          </v-btn>
        </v-card>
      </v-row>

      <v-row class="ml-0 mr-0 mb-3">
        <v-card class="w-100 rounded p-2" rounded elevation="0">
          <v-card-title class="p-0">
            <div class="ml-1 font-weight-bold">Fotky</div>
            <v-spacer></v-spacer>
            <v-btn icon fab small v-if="$root.profile.data.returnVariable == -1">
              <v-icon>mdi-dots-horizontal</v-icon>
            </v-btn>
          </v-card-title>

          <!-- <v-divider class="mt-0 mb-0" /> -->
          <v-card-text class="mb-3 p-0">
            <v-row class="position-relative ml-0 mr-0 mt-3">
              <v-col v-for="n in 9" :key="n" class="p-1 d-flex child-flex" cols="4">
                <v-img :src="`https://picsum.photos/500/300?image=${n * 5 + 10}`" :lazy-src="`https://picsum.photos/10/6?image=${n * 5 + 10}`" aspect-ratio="1" class="grey lighten-2">
                  <template v-slot:placeholder>
                    <v-row class="fill-height ma-0" align="center" justify="center">
                      <v-progress-circular indeterminate color="grey lighten-5"></v-progress-circular>
                    </v-row>
                  </template>
                </v-img>
              </v-col>
            </v-row>
          </v-card-text>
        </v-card>
      </v-row>

      <v-row class="ml-0 mr-0 mb-3">
        <v-card class="w-100 rounded p-2" rounded elevation="0">
          <v-card-title class="p-0">
            <div class="ml-1 font-weight-bold">Priatelia</div>
            <v-spacer></v-spacer>
            <v-chip outlined small color="primary" v-if="$root.profile.friendships.numberOfFriends == 0">Žiadny priatelia</v-chip>
            <v-chip outlined small color="primary" v-else-if="$root.profile.friendships.numberOfFriends == 1">{{$root.profile.friendships.numberOfFriends}} priateľ</v-chip>
            <v-chip outlined small color="primary" v-else>{{$root.profile.friendships.numberOfFriends}} priatelia</v-chip>

            <v-chip small color="primary" class="ml-3" :to="'/profile/' + this.$route.params.user_id + '/friends'">Zobraziť všetkých</v-chip>
          </v-card-title>

          <v-divider class="mt-2 mb-2" />
          <v-card-text class="mb-3 p-0">
            <v-row class="position-relative ml-0 mr-0 mt-0">
              <!-- {{$root.profile.friendships.friends}} -->
              <!-- if no friends -->

              <v-list-item class="justify-center p-0" disabled v-if="$root.profile.friendships.numberOfFriends == 0">
                <v-list-item-content class="pb-0">
                  <v-card class="rounded-lg card-comments" elevation="0">
                    <v-btn color="primary" icon fab>
                      <span>Žiadny priatelia</span>
                    </v-btn>
                  </v-card>
                </v-list-item-content>
              </v-list-item>
              <!-- else there are friends -->
              <v-menu top offset-y transition="scale-transition" :close-on-content-click="false" v-else>
                <template v-slot:activator="{ on, attrs }">
                  <v-col @click="mouseOver(item)" v-bind="attrs" v-on="on" v-for="(item, index) in $root.profile.friendships.friends" :key="index" class="p-1 d-flex child-flex primary--color" cols="4">
                    <!-- v-on:mouseover.prevent.stop="mouseOver(item)" v-on:mouseleave.prevent.stop="mouseLeave()" -->
                    <!-- <template v-if="index <= 5"> -->
                    <v-card class="profile-friend-card" rounded elevation="0">
                      <v-card-text class="p-1 pt-2 pb-0">
                        <v-avatar color="primary" size="60" v-if="item.avatar == null">
                          <span class="text-uppercase white--text font-weight-bold display-1">{{ item.name.charAt(0) }}</span>
                        </v-avatar>
                        <v-avatar size="60" v-else>
                          <img :src="`${$root.envUrlNoApi}/storage/user-avatar/${item.avatar}`">
                        </v-avatar>
                      </v-card-text>
                      <v-card-text class="p-1">
                        <v-chip color="primary" class="mt-1 mb-2" style="height:28px" @click="getFriendProfileData(item)">
                          <!-- <v-icon left class="mr-1" small>mdi-account-circle-outline</v-icon> -->
                          {{item.name}}
                        </v-chip>
                      </v-card-text>
                    </v-card>
                    <!-- </template> -->
                  </v-col>
                </template>

                <v-card v-if="dataAboutUserOnHover != null">
                  <v-list>
                    <v-list-item>
                      <v-avatar color="primary" size="60" v-if="dataAboutUserOnHover.avatar == null">
                        <span class="text-uppercase white--text">{{ dataAboutUserOnHover.name.charAt(0) }}</span>
                      </v-avatar>
                      <v-avatar color="primary" size="60" v-else>
                        <img :src="`${$root.envUrlNoApi}/storage/user-avatar/${dataAboutUserOnHover.avatar}`">
                      </v-avatar>

                      <v-list-item-content class="ml-3">
                        <v-card class="p-2 rounded-lg card-comments" elevation="0">
                          <v-list-item-title>
                            {{dataAboutUserOnHover.name}}
                          </v-list-item-title>
                          <v-list-item-subtitle>{{dataAboutUserOnHover.email}}</v-list-item-subtitle>
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
                      <div v-if="$root.me.id == dataAboutUserOnHover.id">
                        <v-btn class="ml-3" color="primary" outlined rounded>
                          <v-icon small class="mr-1">mdi-account</v-icon>
                          Vy
                        </v-btn>
                        <v-btn class="ml-3" color="primary" rounded :to="'/profile/'+$root.me.id">
                          <v-icon small class="mr-1">mdi-forum</v-icon>
                          Zobraziť môj profil
                        </v-btn>
                      </div>
                      <!-- my friends -->
                      <div v-else>
                        <v-btn class="ml-3" color="primary" outlined rounded>
                          <v-icon small class="mr-1">mdi-account-check</v-icon>
                          Priatelia
                        </v-btn>
                        <v-btn class="ml-3" color="primary" rounded :to="'/messenger?name='+dataAboutUserOnHover.name">
                          <v-icon small class="mr-1">mdi-forum</v-icon>
                          Správa
                        </v-btn>
                      </div>
                    </div>
                    <div v-else>
                      <div v-if="$root.me.id == dataAboutUserOnHover.id">
                        <v-btn class="ml-3" color="primary" outlined rounded>
                          <v-icon small class="mr-1">mdi-account</v-icon>
                          Vy
                        </v-btn>
                        <v-btn class="ml-3" color="primary" rounded :to="'/profile/'+$root.me.id">
                          <v-icon small class="mr-1">mdi-forum</v-icon>
                          Zobraziť môj profil
                        </v-btn>
                      </div>
                      <div v-else-if="isFriendOrNoOrMe == 1">
                        <v-btn class="ml-3" color="primary" outlined rounded>
                          <v-icon small class="mr-1">mdi-account-check</v-icon>
                          Priatelia
                        </v-btn>
                        <v-btn class="ml-3" color="primary" rounded :to="'/messenger?name='+dataAboutUserOnHover.name">
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
                    </div>
                  </v-card-actions>
                </v-card>
              </v-menu>
            </v-row>
          </v-card-text>
        </v-card>
      </v-row>
    </v-col>
    <v-col xl="8" lg="8" md="12" sm="12" xs="12" class="pl-lg-3 pl-xl-3 pl-md-3 pl-0 pr-0 pb-0" min-height="245">
      <ProfileUserPosts />
    </v-col>
  </v-row>
</v-container>
</template>

<script>
import axios from 'axios';
// import _ from 'lodash';
import ProfileUserPosts from "@/components/ProfileUserPosts.vue";
export default {
  name: "About",
  props: [],
  components: {
    ProfileUserPosts
  },
  data() {
    return {
      selectedChip: '',
      textNewPost: null,
      dialogNewPost: false,
      showDragAndDropFile: false,
      files: [],
      urls: [],
      photo: null,
      isDisabled: true,
      dataAboutUserOnHover: null,
      dataAboutUser: false,
      isFriendOrNoOrMe: '',
      isFriendOrNoOrMe_overlay: true,
      userDataProfile: null,
    }
  },

  updated() {},

  created() {
    this.getAllDataAboutposts();
    this.removeDataFromCreatedMain();
  },

  watch: {
    '$route.params.user_id'() {
      this.getAllDataAboutposts();
    }
  },

  methods: {
    getAllDataAboutposts() {
      this.$root.profilePosts.getAllPosts.loadingPosts = true;
      this.$root.profilePosts.getAllPosts.loadingPaginate = true;
      const api = `${process.env.VUE_APP_API_URL}/getAllDataAboutUserPosts/${this.$route.params.user_id}/0/1`;
      const config = {
        headers: {
          Accept: "application/json",
          Authorization: "Bearer " + localStorage.getItem("authToken"),
        },
      };
      axios.get(api, config)
        .then(res => {
          this.$root.profilePosts.getAllPosts.allPosts = res.data[0];
          this.$root.profilePosts.getAllPosts.countAllPosts = res.data[1];
          this.$root.profilePosts.getAllPosts.countActualPosts = res.data[0].length;
          this.$root.profilePosts.getAllPosts.loadingPosts = false;
          this.$root.profilePosts.getAllPosts.loadingPaginate = false;
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

    removeDataFromCreatedMain() {
      this.$root.profilePosts.getAllPosts.allPosts = undefined;
      this.$root.profilePosts.getAllPosts.countAllPosts = undefined;
      this.$root.profilePosts.getAllPosts.countActualPosts = undefined;
    },

    getFriendProfileData(item) {
      this.$router.push(`/profile/${item.id}`);
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

    // mouseLeave: function() {
    //   this.dataAboutUserOnHover = null;
    //   this.isFriendOrNoOrMe_overlay = true;
    //   this.dataAboutUser = false;
    // },
  },

  mounted() {},

  computed: {},
}
</script>

<style>
.theme--light.v-card.profile-friend-card {
  background: #f5f5f5;
}

.theme--dark.v-card.profile-friend-card {
  background: #363636;
}

.v-tabs:not(.v-tabs--vertical).v-tabs--right>.v-slide-group--is-overflowing.v-tabs-bar--is-mobile:not(.v-slide-group--has-affixes) .v-slide-group__next,
.v-tabs:not(.v-tabs--vertical):not(.v-tabs--right)>.v-slide-group--is-overflowing.v-tabs-bar--is-mobile:not(.v-slide-group--has-affixes) .v-slide-group__prev {
  display: none !important;
}
</style>
