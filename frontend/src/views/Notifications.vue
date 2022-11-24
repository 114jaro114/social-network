<template>
<v-container class="notifications mt-16">
  <v-row class="m-0">
    <v-tabs grow slider-size="2">
      <v-tab :to="tabs[0].route" exact>
        Všetky
      </v-tab>
      <v-tab :to="tabs[1].route">
        <v-badge :content="$store.getters['notificationCounter']" :value="$store.getters['notificationCounter']" color="orange">
          Nové
        </v-badge>
      </v-tab>
      <v-tab :to="tabs[2].route">
        Uložené
      </v-tab>
      <!-- <v-tab v-for="tab in tabs" :key="tab.id" :to="tab.route" exact>{{ tab.name }}</v-tab> -->
    </v-tabs>
  </v-row>

  <v-row class="m-0">
    <v-card class="w-100" elevation="0">
      <v-tabs-items class="" v-model="activeTab" @change="updateRouter($event)" grow>
        <v-tab-item v-for="tab in tabs" :key="tab.id" :value="tab.route">
          <router-view />
        </v-tab-item>
      </v-tabs-items>
    </v-card>
  </v-row>
</v-container>
</template>
<script>
export default {
  name: "Notifications",
  components: {},
  props: ['drawerNew'],
  data() {
    return {
      tab: null,
      activeTab: '/notifications',
      tabs: [{
        id: 1,
        name: 'Všetky',
        route: '/notifications'
      }, {
        id: 2,
        name: 'Nové',
        route: '/notifications/new'
      }, {
        id: 3,
        name: 'Uložené',
        route: '/notifications/saved'
      }],
      notifications: null,
      overlay: true,
    }
  },

  methods: {
    updateRouter(tab) {
      this.$router.push(tab)
    },
  },

  updated() {
    this.notifications = this.$store.getters['notificationCounter'];
  },
}
</script>
<style type="scss">
</style>
