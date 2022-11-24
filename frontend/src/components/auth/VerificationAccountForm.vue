<template>
<div class="VerificationAccountForm padding-top-height">
  <div id="blur-effect">
    <div class="p-3 position-ref body-height">
      <div class="row justify-content-center">
        <v-snackbar v-model="snackbar" :multi-line="multiLine" :color="snackbar_color" :timeout="snackbar_timeout" bottom left class="m-3">
          <v-icon>{{snackbar_icon}}</v-icon>
          {{ text }}
          <template v-slot:action="{ attrs }">
            <v-btn color="white" fab text small v-bind="attrs" @click="snackbar = false">
              <v-icon>mdi-close-circle</v-icon>
            </v-btn>
          </template>
        </v-snackbar>
        <div class="col-md-6">
          <v-card class="rounded" :loading="myloadingvariable" elevation="0">
            <v-card-title>
              <v-row justify="center" class="p-3">
                <span class="font-weight-bold text-center primary--text">Aktivácia účtu</span>
              </v-row>
            </v-card-title>
            <hr class="mt-0 mb-0 custom-hr">
            <v-card-text class="p-3">
              <v-btn color="primary" elevation="2" large x-large @click="verif_account">Aktivovať účet</v-btn>
            </v-card-text>

            <hr class="mt-0 mb-0 custom-hr">

            <v-card-actions class="p-3">
              <div class="row">
                <div class="col text-center">
                  Prejsť na stránku s
                  <router-link :to="{ name: 'Login' }">
                    <span class="primary--text font-weight-bold">prihlásením</span>
                  </router-link>
                </div>
              </div>
            </v-card-actions>
          </v-card>
        </div>
      </div>
    </div>
  </div>
</div>
</template>

<script>
import axios from 'axios';
export default {
  names: 'VerificationAccountForm',
  props: {},
  data() {
    return {
      snackbar: false,
      multiLine: true,
      snackbar_color: '',
      snackbar_icon: '',
      snackbar_timeout: '',
      text: '',
      myloadingvariable: false,
      token: this.$route.params.token,
    }
  },

  methods: {
    verif_account() {
      console.log(this.token);
      this.myloadingvariable = true;
      // call API
      axios.post(`${process.env.VUE_APP_API_URL}/auth/verification/account/`, {
          token: this.$route.params.token,
        })
        .then(() => {
          this.myloadingvariable = false;
          this.$store.dispatch('activatedAccount', {
            state: true
          });
          this.$router.push("/login");
        })
        .catch(e => {
          this.snackbar = true;
          this.myloadingvariable = false;
          if (e.response.status == 401) {
            this.text = 'Používateľský účet už bol aktivovaný.';
            this.snackbar_color = 'orange';
            this.snackbar_icon = 'mdi-information';
            this.snackbar_timeout = '-1';
          } else {
            this.text = "Vyskytol sa nejaký problém. Prosím, skúste to znova.";
            this.snackbar_color = 'red';
            this.snackbar_icon = 'mdi-alert-circle';
            this.snackbar_timeout = '-1';
          }
          this.errors.push(e);
        })
    },
  },
  mounted() {
    // console.log('Component Reset mounted.');
  },

  created() {
    // console.log('Component Reset created')
  }
};
</script>

<style lang="css">
  .md-title{
    color: #007bff;
  }
  .padding-top-height {
    padding-top: 5vh;
  }
  .md-checkbox {
    display: flex;
    margin: 0 !important;
  }
  .md-field:last-child {
    margin-bottom: 40px;
  }
  .md-field.md-theme-default>.md-icon:after {
    background-color: rgb(255,255,255) !important;
  }
  .md-button.md-theme-default.md-primary {
    background-color: #007bff;
  }
  .forgot-pass {
    color: black;
    /* margin: 16px 0px 16px 16px; */
  }
  .md-button {
    width: 100%;
  }
  .md-icon.fa-envelope {
    font-size: 18px !important;
  }
  .custom-hr {
    border-width: 3px;
  }
  .close {
    margin-right: 5px;
    position: absolute;
    top: 10px;
    right: 10px;
  }

  .md-theme-default a:not(.md-button):hover {
    text-decoration: unset !important;
  }

  .md-error-pl {
    padding-left: 36px;
  }

  .md-invalid .md-icon {
    color: #ff1744 !important;
  }
</style>
