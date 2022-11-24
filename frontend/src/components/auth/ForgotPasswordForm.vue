<template>
<div class="ForgotPasswordForm padding-top-height">
  <div id="blur-effect">
    <div class="p-3 position-ref body-height">
      <div class="row justify-content-center">
        <v-snackbar v-model="snackbar" :multi-line="multiLine" color="success" bottom left class="m-3">
          <v-icon>mdi-check-circle</v-icon>
          <span>Na zadaný email bol odoslaný link na vytvorenie nového hesla!</span>
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
                <span class="font-weight-bold text-center primary--text">Obnova hesla</span>
                <router-link :to="{ name: 'Login' }">
                  <button type="button" class="primary--text close">&times;</button>
                </router-link>
              </v-row>
            </v-card-title>
            <hr class="mt-0 mb-0 custom-hr">
            <v-form ref="form" v-model="valid" @keyup.native.enter="requestResetPassword" lazy-validation>
              <!-- @submit.prevent -->
              <v-card-text class="p-3">
                <v-text-field tabindex="1" prepend-icon="mdi-email" v-model="email" :rules="emailRules" label="Email" filled clearable clear-icon="mdi-close"></v-text-field>
                <div class="row">
                  <div class="col text-center">
                    Ešte nemáš účet?
                    <router-link :to="{ name: 'Register' }">
                      <span class="primary--text font-weight-bold">Zaregistruj sa</span>
                    </router-link>
                  </div>
                </div>
              </v-card-text>
              <hr class="mt-0 mb-0 custom-hr">
              <v-card-actions>
                <v-btn color="primary" @click="requestResetPassword" block>
                  Odoslať link na obnovenie hesla
                </v-btn>
              </v-card-actions>
            </v-form>
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
  names: ['ForgotPasswordForm', 'FormValidation'],
  props: {},
  data() {
    return {
      valid: true,
      myloadingvariable: false,
      snackbar: false,
      email: '',
      emailRules: [
        v => !!v || 'E-mail je povinný',
        v => /.+@.+\..+/.test(v) || 'E-mail musí mať valídny tvar',
      ],
      multiLine: true,
    }
  },

  methods: {
    validate() {
      return this.$refs.form.validate();
    },
    reset() {
      this.$refs.form.reset()
    },

    requestResetPassword() {
      this.$vuetify.goTo(0);
      if (this.validate()) {
        this.myloadingvariable = true;
        // call API
        axios.post(`${process.env.VUE_APP_API_URL}/auth/reset-password`, {
            email: this.email,
          })
          .then(() => {
            this.myloadingvariable = false;
            this.snackbar = true;
            this.reset();
          })
          .catch(e => {
            this.myloadingvariable = false;
            this.errors.push(e)
          })
      }
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
