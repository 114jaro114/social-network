<template>
<div class="LoginForm padding-top-height">
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

      <v-snackbar v-model="snackbarRegister" :multi-line="multiLine" color="success" bottom left class="m-3">
        <v-icon>mdi-check-circle</v-icon>
        <span>Používateľský účet bol úspešne vytvorený! Na zadaný email bol odoslaný aktivačný link pre aktiváciu účtu.</span>
        <template v-slot:action="{ attrs }">
          <v-btn color="white" fab text small v-bind="attrs" @click="snackbarRegister = false">
            <v-icon>mdi-close-circle</v-icon>
          </v-btn>
        </template>
      </v-snackbar>

      <v-snackbar v-model="snackbarActivateAcc" :multi-line="multiLine" color="success" bottom left class="m-3">
        <v-icon>mdi-check-circle</v-icon>
        <span>Používateľský účet bol úspešne aktivovaný!</span>
        <template v-slot:action="{ attrs }">
          <v-btn color="white" fab text small v-bind="attrs" @click="snackbarActivateAcc = false">
            <v-icon>mdi-close-circle</v-icon>
          </v-btn>
        </template>
      </v-snackbar>
      <div class="col-md-6">
        <v-card class="rounded" :loading="myloadingvariable" elevation="0">
          <v-card-title>
            <v-row justify="center" class="p-3">
              <span class="font-weight-bold text-center primary--text">Prihlásenie</span>
              <router-link :to="{ name: 'Welcome' }">
                <button type="button" class="primary--text close">&times;</button>
              </router-link>
            </v-row>

          </v-card-title>
          <hr class="mt-0 mb-0 custom-hr">
          <v-form ref="form" v-model="valid" @keyup.native.enter="login" lazy-validation>
            <v-card-text class="p-3">
              <v-text-field tabindex="1" prepend-icon="mdi-email" v-model="email" :error-messages="error" :rules="emailRules" label="Email" filled clearable clear-icon="mdi-close" counter></v-text-field>

              <v-text-field tabindex="1" prepend-icon="mdi-lock" v-model="password" :append-icon="togglePassword ? 'mdi-eye' : 'mdi-eye-off'" :error-messages="error" autocomplete="off" :rules="passwordRules"
                :type="togglePassword ? 'text' : 'password'" label="Heslo" hint="Minimálne 4 znaky" counter @click:append="togglePassword = !togglePassword" filled clearable clear-icon="mdi-close"></v-text-field>
              <v-row justify="center">
                <v-col class="text-center pb-0 pt-5">
                  <v-checkbox v-model="remember" name="remember" id="remember" label="Zapamätať"></v-checkbox>
                </v-col>
                <v-col class="pb-0 pt-5 float-right text-right">
                  <router-link :to="{ name: 'ForgotPassword' }">
                    <span class="forgot-pass primary--text font-weight-bold">Zabudnuté <span class="primary-color font-weight-bold">heslo?</span></span>
                  </router-link>
                </v-col>
              </v-row>
              <!-- <vue-recaptcha ref="recaptcha" @verify="onCaptchaVerified" @expired="onCaptchaExpired" size="invisible" sitekey="6LeaSgQcAAAAAHltKQ2SLzqgH476BcGhP9nb0K_u">
              </vue-recaptcha> -->
              <!-- <v-btn @click="recaptcha">Recaptcha</v-btn> -->
              <div id="inline-badge"></div>
              <v-divider class="mx-0"></v-divider>
              <div class="row">
                <div class="col text-center">
                  Ešte nemáš účet?
                  <router-link :to="{ name: 'Register' }">
                    <span class="primary--text font-weight-bold">Zaregistruj sa</span>
                  </router-link>
                </div>
              </div>
            </v-card-text>
            <hr class="mt-0 custom-hr">
            <v-card-actions>
              <v-btn color="primary" @click="login" block>
                Prihlásiť sa
              </v-btn>
            </v-card-actions>
          </v-form>
          <v-container align="center" class="p-4 pt-3">
            <v-row align="center">
              <v-divider />
              <span class="ml-3 mr-3">Alebo</span>
              <v-divider />
            </v-row>
            <v-row class="pt-1" style="justify-content: center">
              <v-btn fab small color="blue" @click="AuthProvider('facebook')">
                <v-icon color="white">
                  mdi-facebook
                </v-icon>
              </v-btn>

              <v-btn class="ml-5" fab small color="red" @click="AuthProvider('google')">
                <v-icon color="white">
                  mdi-google
                </v-icon>
              </v-btn>
            </v-row>
          </v-container>
        </v-card>
      </div>
    </div>
  </div>
</div>
</template>
<script>
// import VueRecaptcha from 'vue-recaptcha';
import axios from 'axios';

export default {
  names: ['LoginForm', 'CheckboxHueColors', 'FormValidation'],
  props: {},
  components: {
    // 'vue-recaptcha': VueRecaptcha,
  },
  data() {
    return {
      myloadingvariable: false,
      togglePassword: false,
      valid: true,
      name: '',
      nameRules: [
        v => !!v || 'Meno je povinné',
        v => (v && v.length <= 10) || 'Meno musí obsahovať max 10 znakov',
      ],
      email: '',
      emailRules: [
        v => !!v || 'E-mail je povinný',
        v => /.+@.+\..+/.test(v) || 'E-mail musí mať valídny tvar',
      ],
      password: '',
      passwordRules: [
        v => !!v || 'Heslo je povinné',
        v => (v && v.length >= 4) || 'Heslo musí obsahovať minimálne 4 znaky',
      ],
      remember: true,
      error: '',

      multiLine: true,
      snackbar: false,
      snackbarRegister: false,
      snackbarActivateAcc: false,
      snackbar_color: '',
      snackbar_icon: '',
      snackbar_timeout: '-1',
      text: '',

      recaptchaVerified: false,
    }
  },

  computed: {},

  methods: {
    validate() {
      return this.$refs.form.validate();
    },
    reset() {
      this.$refs.form.reset()
    },
    // resetValidation() {
    //   this.$refs.form.resetValidation()
    // },

    // recaptcha() {
    //   this.$recaptcha('login').then((token) => {
    //     console.log(token) // Will print the token
    //     axios.post(
    //         `https://www.google.com/recaptcha/api/siteverify?secret=6LfbpgIcAAAAAFMpeCHNKMRnodKzrVXwja7QmU9i&response=${token}`, {}, {
    //           headers: {
    //             "Content-Type": "application/x-www-form-urlencoded; charset=utf-8"
    //           },
    //         })
    //       .then(resp => {
    //         console.log(resp);
    //       })
    //   })
    // },
    //
    // onCaptchaVerified() {
    //   this.$recaptcha('login').then((token) => {
    //     const secretkey = '6LeaSgQcAAAAAEU_BHXN5pJyUWBglQsZqxwox_Ri';
    //     this.$refs.recaptcha.reset();
    //     console.log(token);
    //     axios.post(
    //         `https://www.google.com/recaptcha/api/siteverify?secret=${secretkey}&response=${token}`, {}, {
    //           headers: {
    //             "Content-Type": "application/x-www-form-urlencoded; charset=utf-8"
    //           },
    //         })
    //       .then(resp => {
    //         console.log(resp);
    //       })
    //   })
    // },

    // onCaptchaExpired: function() {
    //   this.$refs.recaptcha.reset();
    // },

    login() {
      this.$vuetify.goTo(0);
      // this.onCaptchaVerified();
      if (this.validate()) {
        this.myloadingvariable = true;
        this.error = '';
        this.snackbar = false;
        // call API
        axios.post(`${process.env.VUE_APP_API_URL}/auth/login`, {
            email: this.email,
            password: this.password,
            remember: this.remember,
            status: "online"
          })
          .then(resp => {
            this.myloadingvariable = false;
            localStorage.setItem('username', resp.data.user.name);
            localStorage.setItem('user_id', resp.data.user.id);
            localStorage.setItem('authToken', resp.data.token);
            // this.$router.push("/home");
            window.location.href = "/home";
            if (localStorage.getItem('speed_dial') == undefined) {
              localStorage.setItem('speed_dial', true);
              this.$store.dispatch('speedDialState', {
                status: true
              });
            }
            if (localStorage.getItem('bottom_navigation') == undefined) {
              localStorage.setItem('bottom_navigation', true);
              this.$store.dispatch('bottomNavigationState', {
                status: true
              });
            }
            // this.reset();
          })
          .catch(e => {
            this.myloadingvariable = false;
            this.snackbar = true;
            if (e.response.status == 500) {
              console.log(e.response.status);
              this.text = 'Pred prvotným prihlásením je potrebné aktivovať účet pomocou mailu.';
            } else if (e.response.status == 403) {
              this.text = 'Vyskytol sa nejaký problém. Skúste to prosím znova.';
            } else {
              this.text = 'Nesprávny email alebo heslo.';
            }
            this.snackbar_color = 'red';
            this.snackbar_icon = 'mdi-alert-circle';
            this.snackbar_timeout = '-1';
            this.error = ' ';
            this.errors.push(e)
          })
      } else {
        return;
      }
    },
    // VueSocialauth
    AuthProvider(provider) {
      var self = this
      this.$auth.authenticate(provider)
        .then(response => {
          console.log('1');
          self.SocialLogin(provider, response)
        })
        .catch(err => {
          console.log('1error');
          console.log({
            err: err
          })
        })
    },

    SocialLogin(provider, response) {
      axios.post(`${process.env.VUE_APP_API_URL}/sociallogin/` + provider, response)
        .then(response => {
          console.log('2');
          console.log(response.data);
          this.$router.replace("/home");
        })
        .catch(err => {
          console.log('2error');
          console.log({
            err: err
          })
        })
      // this.$http.post('http://127.0.0.1:8000/api/sociallogin/' + provider, response)
      //   .then(response => {
      //     console.log(response.data)
      //   })
      //   .catch(err => {
      //     console.log({
      //       err: err
      //     })
      //   })
    },
  },

  mounted() {
    if (this.$store.getters['updatedPassword'] != false) {
      this.snackbar = true;
      this.text = "Heslo bolo úspešne zmenené";
      this.snackbar_color = 'green';
      this.snackbar_icon = 'mdi-check-circle';
      this.snackbar_timeout = '5000';

      this.$store.dispatch('updatedPassword', {
        state: false
      });
    } else {
      this.snackbar = false;
    }

    if (localStorage.getItem('logout') == "true") {
      this.snackbar = true;
      this.text = `${localStorage.getItem('username')}, bol si úspešne odhlásený.`;
      this.snackbar_color = 'green';
      this.snackbar_icon = 'mdi-check-circle';
      this.snackbar_timeout = '5000';
      localStorage.removeItem("logout");
      localStorage.removeItem("username");
    }

    // if (this.$store.getters['isLoggedOut'].logout != false) {
    //   this.snackbar = true;
    //   this.text = `${this.$store.getters['isLoggedOut'].username}, bol si úspešne odhlásený.`;
    //   this.snackbar_color = 'green';
    //   this.snackbar_icon = 'mdi-check-circle';
    //   this.snackbar_timeout = '5000';
    //
    //   this.$store.dispatch('isLoggedOut', {
    //     username: '',
    //     logout: false
    //   });
    // } else {
    //   this.snackbar = false;
    // }

    // console.log('Component login mounted.')
  },
  created() {
    //add text when user was successfully registered
    this.snackbarRegister = this.$store.getters['successRegisterAlert'].success;
    this.$store.dispatch('successRegister', {
      success: false
    });
    this.snackbarActivateAcc = this.$store.getters['activatedAccount'];
    this.$store.dispatch('activatedAccount', {
      state: false
    });
  }
};
</script>
<style lang="css">
.v-input--selection-controls {
  margin-top: 0 !important;
  padding-top: 0 !important;
}
label {
  margin-bottom: 0 !important;
}
</style>
