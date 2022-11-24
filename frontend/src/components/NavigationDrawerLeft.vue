<template id="NavigationDrawerLeft">
<v-navigation-drawer width="600px" floating left clipped app :permanent="$vuetify.breakpoint.mdAndUp">
  <v-container>
    <v-card class="mb-5" rounded elevation="0">
      <v-list dense>
        <div v-if="$root.me.overlayAvatar == true && $root.navigationDrawerLeft.numberOfUserPosts == null && $root.navigationDrawerLeft.numberOfFriends == null">
          <v-row class="mb-0 ml-1 mr-1 mt-0 mb-2" style="padding-top:4px;padding-bottom:3px">
            <v-col cols="4" class="pb-0 pt-2 pl-3">
              <v-skeleton-loader height="40" width="40" type="avatar"></v-skeleton-loader>
            </v-col>

            <v-col class="p-0">
              <v-row class="m-0">
                <v-col class="pb-0 pl-0 pr-0">
                  <v-skeleton-loader type="text"></v-skeleton-loader>
                </v-col>
              </v-row>
              <v-row class="m-0">
                <v-col class="pt-0 pb-0 pl-0 pr-0">
                  <v-skeleton-loader type="text"></v-skeleton-loader>
                </v-col>
              </v-row>
            </v-col>

            <v-col cols="3" class="pb-0 pt-4 pr-7 pl-6">
              <v-skeleton-loader size="40" type="button"></v-skeleton-loader>
            </v-col>
          </v-row>

          <v-row class="m-0">
            <v-col class="pl-0 pr-0 pt-0 pb-0">
              <v-skeleton-loader type="divider"></v-skeleton-loader>
            </v-col>
          </v-row>

          <v-row class="ml-0 mr-0 mb-0" style="margin-top:14px">
            <v-col class="pl-9 pt-0 pb-0">
              <v-skeleton-loader type="text"></v-skeleton-loader>
            </v-col>
            <v-col class="pr-9 pt-0 pb-0">
              <v-skeleton-loader type="text"></v-skeleton-loader>
            </v-col>
          </v-row>

          <v-divider class="mb-0" style="margin-top: 8px"></v-divider>
        </div>

        <div v-else>
          <v-list-item>
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

            <v-list-item-content class="ml-3">
              <v-card class="p-2 rounded-lg card-comments" elevation="0">
                <v-list-item-title>
                  {{$root.me.name}}
                  <!-- <v-chip small>
                    <v-icon left class="mr-1" small>mdi-account-circle-outline</v-icon>{{$root.me.name}}
                  </v-chip> -->
                </v-list-item-title>
                <v-list-item-subtitle>{{$root.me.email}}</v-list-item-subtitle>
              </v-card>
            </v-list-item-content>

            <v-list-item-action class="ml-3">
              <v-btn icon fab small>
                <v-icon>mdi-dots-vertical</v-icon>
              </v-btn>
            </v-list-item-action>
          </v-list-item>

          <v-divider class="mt-0 mb-0"></v-divider>

          <v-list-item>
            <v-list-item-content v-if="$root.navigationDrawerLeft.numberOfUserPosts > 1">
              <v-list-item-title>{{$root.navigationDrawerLeft.numberOfUserPosts}} príspevky</v-list-item-title>
            </v-list-item-content>
            <v-list-item-content v-else-if="$root.navigationDrawerLeft.numberOfUserPosts == 0">
              <v-list-item-title>{{$root.navigationDrawerLeft.numberOfUserPosts}} príspevkov</v-list-item-title>
            </v-list-item-content>
            <v-list-item-content v-else>
              <v-list-item-title>{{$root.navigationDrawerLeft.numberOfUserPosts}} príspevok</v-list-item-title>
            </v-list-item-content>

            <v-list-item-content v-if="$root.navigationDrawerLeft.numberOfFriends > 4 || $root.navigationDrawerLeft.numberOfFriends == 0">
              <v-list-item-title>{{$root.navigationDrawerLeft.numberOfFriends}} priateľov</v-list-item-title>
            </v-list-item-content>
            <v-list-item-content v-else-if="$root.navigationDrawerLeft.numberOfFriends > 1 && $root.navigationDrawerLeft.numberOfFriends < 5">
              <v-list-item-title>{{$root.navigationDrawerLeft.numberOfFriends}} priatelia</v-list-item-title>
            </v-list-item-content>
            <v-list-item-content v-else>
              <v-list-item-title>{{$root.navigationDrawerLeft.numberOfFriends}} priateľ</v-list-item-title>
            </v-list-item-content>
          </v-list-item>

          <v-divider class="mt-0 mb-0"></v-divider>
        </div>
      </v-list>


      <v-list dense>
        <v-subheader>Ďalšie</v-subheader>
        <v-list-item-group v-model="selectedItem" color="primary">
          <v-list-item v-for="(item, i) in items" :key="i">
            <v-list-item-icon>
              <v-icon v-text="item.icon"></v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title v-text="item.text"></v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list-item-group>
      </v-list>
    </v-card>
  </v-container>

  <v-divider class="mt-0 mb-0" />

  <v-container>
    <v-card class="mb-5" rounded elevation="0" s>
      <v-card-title>
        <div class="questrial body-2 font-weight-bold">Novinky</div>
        <v-spacer></v-spacer>
        <v-btn icon fab small>
          <v-icon>mdi-dots-horizontal</v-icon>
        </v-btn>
      </v-card-title>
      <v-divider class="mt-0 mb-0" />
      <v-list dense>
        <v-list-item-group color="primary">
          <v-list-item v-for="(item, i) in news" :key="i">
            <v-list-item-icon>
              <v-icon>{{item.icon}}</v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-card class="p-2 rounded-lg card-comments" elevation="0">
                <v-card-title class="d-flex text-start text-subtitle-2 font-weight-bold p-0 pb-3 pl-1">
                  {{item.name}}
                </v-card-title>
                <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">{{item.text}}</v-card-subtitle>
                <v-card-actions class="p-0 justify-start">
                  <v-btn text small class="caption primary--text font-weight-bold pr-1 pl-1">
                    Pozrieť detaily
                  </v-btn>

                </v-card-actions>
              </v-card>
              <!-- <div class="questrial caption text--grey">{{item.text}}
                <div class="primary--text font-weight-bold"> {{item.text2}}</div>
              </div> -->
            </v-list-item-content>
            <!-- <divider class="mt-0 mb-0"></divider> -->
          </v-list-item>
        </v-list-item-group>
      </v-list>
    </v-card>
  </v-container>

  <v-divider class="mt-0 mb-0" />

  <v-container>
    <v-card class="mb-5" rounded elevation="0">
      <v-card-title>
        <div class="questrial body-2 font-weight-bold">Galéria</div>
        <v-spacer></v-spacer>
        <v-btn icon fab small>
          <v-icon>mdi-dots-horizontal</v-icon>
        </v-btn>
      </v-card-title>

      <v-divider class="mt-0 mb-0" />

      <v-row class="ml-0 mr-0 mt-3">
        <v-col v-for="n in 9" :key="n" class=" p-1 d-flex child-flex" cols="4">
          <v-img :src="`https://picsum.photos/500/300?image=${n * 5 + 10}`" :lazy-src="`https://picsum.photos/10/6?image=${n * 5 + 10}`" aspect-ratio="1" class="grey lighten-2">
            <template v-slot:placeholder>
              <v-row class="fill-height ma-0" align="center" justify="center">
                <v-progress-circular indeterminate color="grey lighten-5"></v-progress-circular>
              </v-row>
            </template>
          </v-img>
        </v-col>
      </v-row>
    </v-card>
  </v-container>
</v-navigation-drawer>
</template>
<script>
// import axios from 'axios';
export default {
  name: "NavigationDrawerLeft",
  components: {},
  data() {
    return {
      selectedItem: '',
      numberOfFriends: null,
      numberOfUserPosts: null,
      items: [{
          text: 'Novinky',
          icon: 'mdi-newspaper'
        }, {
          text: 'Stránky',
          icon: 'mdi-web'
        }, {
          text: 'Eventy',
          icon: 'mdi-calendar'
        },
        {
          text: 'Uložené',
          icon: 'mdi-bookmark'
        }, {
          text: 'Odporúčania',
          icon: 'mdi-checkbox-multiple-marked-circle'
        }, {
          text: 'Spomienky',
          icon: 'mdi-memory'
        },
      ],
      news: [{
        text: 'V piatok o 20:45 sa koná stretnutie vo vašom meste.',
        text2: 'Pozrieť detaily',
        icon: 'mdi-video'
      }, {
        text: 'V piatok o 20:45 sa koná stretnutie vo vašom meste.',
        text2: 'Pozrieť detaily',
        icon: 'mdi-video'
      }],
    }
  },

  mounted() {
    // this.getNumberOfFriends();
    // this.getNumberOfUserPosts();
  },

  methods: {
    // getNumberOfFriends() {
    //   const api = `${process.env.VUE_APP_API_URL}/getNumberOfFriends`;
    //   const config = {
    //     headers: {
    //       Accept: "application/json",
    //       Authorization: "Bearer " + localStorage.getItem("authToken"),
    //     },
    //   };
    //   axios.get(api, config)
    //     .then(res => {
    //       this.numberOfFriends = res.data;
    //     });
    // },
    //
    // getNumberOfUserPosts() {
    //   const api = `${process.env.VUE_APP_API_URL}/get_number_of_user_posts`;
    //   const config = {
    //     headers: {
    //       Accept: "application/json",
    //       Authorization: "Bearer " + localStorage.getItem("authToken"),
    //     },
    //   };
    //   axios.get(api, config)
    //     .then(res => {
    //       this.numberOfUserPosts = res.data;
    //     });
    // },
  },

  updated() {
    //do something after updating vue instance
  },

  computed: {
    scrollbarTheme() {
      return this.$vuetify.theme.dark ? 'dark' : 'light';
    },
  },
}
</script>
<style type="scss">
.v-skeleton-loader__avatar {
  border-radius: 50%;
  height: 40px !important;
  width: 40px !important;
  ;
}

.v-skeleton-loader__button {
  width: 24px !important;
  height: 24px !important;
}
</style>
