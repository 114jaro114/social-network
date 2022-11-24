<template>
<v-card id="create">
  <v-speed-dial v-if="this.$store.getters['speedDialState'].state" v-model="fab" :bottom="bottom" :right="right" :direction="direction" :open-on-hover="hover" :transition="transition">
    <template v-slot:activator>
      <v-tooltip left>
        <template v-slot:activator="{ on }">
          <v-btn v-on="on" color="primary" v-model="fab" fab medium>
            <v-icon v-if="fab"> mdi-close </v-icon>
            <v-icon v-else> mdi-gesture-tap </v-icon>
          </v-btn>
        </template>
        <span>speed dial</span>
      </v-tooltip>
    </template>

    <v-tooltip left v-if="this.$route.name != 'Settings'">
      <template v-slot:activator="{ on }">
        <v-btn v-on="on" color="primary" fab small to="/settings">
          <v-icon>mdi-cog</v-icon>
        </v-btn>
      </template>
      <span>prejsť na stránku nastavení</span>
    </v-tooltip>

    <v-tooltip left v-if="this.$route.name != 'Notifications'">
      <template v-slot:activator="{ on }">
        <v-btn v-on="on" color="primary" fab small to="/notifications">
          <v-icon>mdi-bell</v-icon>
        </v-btn>
      </template>
      <span>prejsť na stránku s notifikáciami</span>
    </v-tooltip>

    <v-tooltip left v-if="this.$route.name != 'Messenger'">
      <template v-slot:activator="{ on }">
        <v-btn v-on="on" color="primary" fab small to="/messenger">
          <v-icon>mdi-forum</v-icon>
        </v-btn>
      </template>
      <span>prejsť na stránku s messengerom</span>
    </v-tooltip>
  </v-speed-dial>
</v-card>
</template>
<script>
export default {
  data: () => ({
    direction: 'top',
    fab: false,
    fling: false,
    hover: false,
    tabs: null,
    top: false,
    right: true,
    bottom: true,
    transition: 'scale-transition',
  }),

  mounted() {
    //do something after mounting vue instance
    // console.log("SpeedDial mounted");

    if (JSON.parse(localStorage.getItem("speed_dial")) == true) {
      this.$store.dispatch('speedDialState', {
        status: true
      });
    } else {
      this.$store.dispatch('speedDialState', {
        status: false
      });
    }
  },

  updated() {
    //do something after updating vue instance
    // console.log("SpeedDial updated");
  }
}
</script>
<style>
/* This is for documentation purposes and will not be needed in your application */
#create .v-speed-dial {
  position: fixed;
}

#create .v-btn--floating {
  position: relative;
}

.v-speed-dial {
  z-index: 12 !important;
}

.v-speed-dial--bottom {
  bottom: 160px !important;
}
</style>
