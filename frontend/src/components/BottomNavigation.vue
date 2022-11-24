<template>
<div class="phone-viewport w-100" :style="test">
  <!-- v-model="value" -->
  <v-bottom-navigation class="phone-bottom-bar" :input-value="this.$store.getters['bottomNavigationState'].state == true" color="primary" app shift grow style="box-shadow:none">
    <v-btn to="/home" value="home">
      <span>Domov</span>
      <v-icon>mdi-home</v-icon>
    </v-btn>
    <v-btn to="/notifications" value="notifications">
      <span>Notifikácie</span>
      <v-icon>mdi-bell</v-icon>
      <v-badge class="v-icon" :content="$store.getters['notificationCounter']" :value="$store.getters['notificationCounter']" overlap bordered color="orange" :offset-x="-2" :offset-y="14"></v-badge>
    </v-btn>

    <v-btn to="/messenger" value="messenger">
      <span>Správy</span>
      <v-icon>mdi-forum</v-icon>
      <v-badge class="v-icon" :content="$store.getters['msgUnreadCounter']" :value="$store.getters['msgUnreadCounter']" overlap bordered right color="orange" :offset-x="-5" :offset-y="14"></v-badge>
    </v-btn>
    <v-btn to="/settings" value="settings">
      <span>Nastavenia</span>
      <v-icon>mdi-cog</v-icon>
    </v-btn>
  </v-bottom-navigation>
</div>
</template>

<script>
export default {
  data() {
    return {
      value: '',
      num: 5,
      test: null,
    }
  },

  mounted() {
    // do something after mounting vue instance
    // console.log("BottomNavigation mounted");
    this.bottomNavigationStyle();

    if (JSON.parse(localStorage.getItem("bottom_navigation")) == true) {
      this.$store.dispatch('bottomNavigationState', {
        status: true
      });
    } else {
      this.$store.dispatch('bottomNavigationState', {
        status: false
      });
    }
  },

  updated() {
    //do something after updating vue instance
    // console.log("BottomNavigation updated");
    this.bottomNavigationStyle();
  },

  methods: {
    bottomNavigationStyle() {
      if (window.innerWidth <= 480 && this.$store.getters['bottomNavigationState'].state == true) {
        this.test = 'margin-top:56px;display:block';
      } else {
        this.test = 'margin-top:0px;display:none';
      }
      window.addEventListener('resize', () => {
        if (window.innerWidth <= 480 && this.$store.getters['bottomNavigationState'].state == true) {
          this.test = 'margin-top:56px;display:block';
        } else {
          this.test = 'margin-top:0px;display:none';
        }
      });
    }
    // home() {
    //   this.$router.push('/home');
    // },
    // messenger() {
    //   this.$router.push('/messenger');
    // },
    // reservation() {
    //   this.$router.push('/reservation');
    // },
    // administration() {
    //   this.$router.push('/administration');
    // },
    // settings() {
    //   this.$router.push('/settings');
    // },
  }
}
</script>

<style>
.bottom-nav {
  .btn:not(.btn--active) {
    filter: none;
    opacity: 1;

    i,
    span:first-child {
      filter: grayscale(100%);
      opacity: 0.5;
    }

    .badge__badge {
      span {
        filter: none;
        opacity: 1;
      }
    }
  }
}

.v-bottom-navigation {
  z-index: 12 !important;
}

.phone-viewport .theme--dark.v-badge .v-badge__badge:after {
  border-color: #2e2e2e;
}

.phone-viewport .theme--light.v-badge .v-badge__badge:after {
  border-color: #fff;
}

.phone-viewport .theme--light .phone-bottom-bar {
  background-color: #f5f5f5;
}
</style>
