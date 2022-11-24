<template>
<v-navigation-drawer width="300" :floating="false" left clipped app :permanent="$vuetify.breakpoint.mdAndUp">
  <v-card class="m-3 mb-0" elevation="0" color="success">
    <v-card-text class="white--text pt-0 pb-0" style="font-size:9px">{{$root.messenger2.currentAccount}}</v-card-text>
    <v-card-title class="justify-content-center white--text pb-0">Zostatok v peňaženke</v-card-title>
    <v-card-text class="white--text pt-0">{{$root.messenger2.balance}}</v-card-text>
  </v-card>

  <v-container class="pt-0 friend-list">
    <v-card elevation="0" rounded>
      <v-list-item class="p-0" v-if="$root.messenger2.users.length == 0">
        <v-text-field class="mt-6" v-model="search" label="Vyhľadať" filled rounded dense append-icon="mdi-magnify" clearable disabled></v-text-field>
      </v-list-item>
      <v-list-item class="p-0" v-else>
        <v-text-field class="mt-6" v-model="search" label="Vyhľadať" filled rounded dense append-icon="mdi-magnify" clearable></v-text-field>
      </v-list-item>
      <v-divider class="mt-0 mb-0"></v-divider>

      <v-list class="p-0" dense>
        <v-list-item v-if="$root.messenger2.loadingusers != false" inactive disabled>
          <v-overlay :value="$root.messenger2.loadingusers" :absolute="true" :opacity="0">
            <v-progress-circular indeterminate size="24" color="primary"></v-progress-circular>
          </v-overlay>
        </v-list-item>
        <v-list-item class="justify-center pl-0 pr-0" v-if="filteredUsers.length == 0 && $root.messenger2.users.length != 0" disabled>
          <v-list-item-content>
            <v-card class="rounded-lg card-comments" elevation="0">
              <v-btn color="primary" icon fab>
                <v-icon style="font-size: 24px">mdi-cancel</v-icon>
                <span class="ml-2">Žiadne výsledky</span>
              </v-btn>
            </v-card>
          </v-list-item-content>
        </v-list-item>

        <v-list-item class="justify-center pl-0 pr-0" v-if="$root.messenger2.users.length == 0 && $root.messenger2.loadingusers == false" disabled>
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
              <v-avatar color="primary" size="40" v-if="item.avatar == ''">
                <span class="text-uppercase white--text">{{ item.name.charAt(0) }}</span>
              </v-avatar>
              <v-avatar color="primary" size="40" v-else>
                <img :src="item.avatar">
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
</v-navigation-drawer>
</template>

<script>
import _ from 'lodash';
export default {
  name: 'ContactsList',
  components: {},

  data() {
    return {}
  },

  computed: {
    filteredUsers() {
      return _.orderBy(this.$root.messenger2.users.filter(item => {
        if (!this.search) return this.$root.messenger2.users;
        return (item.name.toLowerCase()
          .includes(this.search.toLowerCase()));
      }), 'headline');
    },
  }

}
</script>

<style lang="css" scoped>
</style>
