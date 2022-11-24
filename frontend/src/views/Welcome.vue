<template>
<div class="">


  <v-lazy :options="{
        threshold: 0.9
      }" min-height="100vh" transition-group="scale-transition">
    <div class="welcome vh-100 p-3">

      <!-- SIGN CONTROLS -->
      <div class="sign-controls">
        <!-- SIGN IN -->
        <div class="login-content">
          <router-link :to="{ name: 'Login' }">
            <v-btn color="primary" class="text-uppercase font-weight-bold cursor-pointer text-decoration-none" elevation="0" x-large outlined rounded block>
              <v-icon class="pr-1">mdi-login</v-icon>Prihlásiť sa
            </v-btn>
          </router-link>
        </div>
        <div class="text pb-5">
        </div>
        <!-- GET STARTED -->
        <div class="register-content">
          <router-link :to="{ name: 'Register' }">
            <v-btn color="primary" class="text-uppercase font-weight-bold text-decoration-none" elevation="0" x-large rounded block>
              <v-icon class="pr-1">mdi-account-plus</v-icon>Zaregistrovať sa
            </v-btn>
          </router-link>
        </div>
      </div>

      <!-- content -->
      <div class="content verticalhorizontal">
        <v-row justify="center">
          <!-- <v-col> -->
          <v-col>
            <v-img :lazy-src="require('../assets/default.png')" :src="require('../assets/default.png')" :max-height="maxHeight" :max-width="maxWidth" class="m-auto w-100"></v-img>
          </v-col>
        </v-row>
      </div>
    </div>
  </v-lazy>
</div>
</template>

<script>
export default {
  name: "Welcome",
  components: {},
  data() {
    return {
      maxWidth: 600,
      maxHeight: 160,
    }
  },

  mounted() {
    //do something after mounting vue instance
    this.$store.dispatch('isLoggedOut', {
      username: '',
      logout: false
    });
    this.bottomNavigationStyle();
  },

  methods: {
    bottomNavigationStyle() {
      if (window.innerWidth <= 750 && window.innerWidth > 600) {
        this.maxWidth = 500;
        this.maxHeight = 140;
      } else if (window.innerWidth <= 600 && window.innerWidth > 500) {
        this.maxWidth = 400;
        this.maxHeight = 100;
      } else if (window.innerWidth <= 500 && window.innerWidth > 400) {
        this.maxWidth = 350;
        this.maxHeight = 100;
      } else if (window.innerWidth <= 400) {
        this.maxWidth = 300;
        this.maxHeight = 80;
      }
      window.addEventListener('resize', () => {
        if (window.innerWidth <= 750 && window.innerWidth > 600) {
          this.maxWidth = 500;
          this.maxHeight = 140;
        } else if (window.innerWidth <= 600 && window.innerWidth > 500) {
          this.maxWidth = 400;
          this.maxHeight = 100;
        } else if (window.innerWidth <= 500 && window.innerWidth > 400) {
          this.maxWidth = 350;
          this.maxHeight = 100;
        } else if (window.innerWidth <= 400) {
          this.maxWidth = 300;
          this.maxHeight = 80;
        }
      });
    }
  },

  updated() {
    this.bottomNavigationStyle();
  },

  created() {
    // console.log('Component Welcome created')
  },
};
</script>

<style lang="css">
  .sign-controls {
    position: fixed;
    right: 15px;
    display: flex;
    flex-wrap: wrap;
    padding-top: 1rem;
  }
  .flex-center {
    align-items: center;
    display: flex;
    justify-content: center;
  }
  .position-ref {
    position: relative;
  }
  .top-right {
    position: absolute;
    right: 10px;
    top: 18px;
  }
  .content {
    text-align: center;
    width: 85vw;
    margin: auto;
    display:flex;
    top: 30vh;
    position: relative;
    /* float: left; */
  }
  .login-content {
    padding: 0px 15px 0px 15px;
  }
  .register-content {
    padding: 0px 15px 0px 15px;
  }
  .text,
  .text2,
  .text3 {
    display: none;
  }

  .sign-controls button:focus {
    outline: unset !important;
  }
</style>
