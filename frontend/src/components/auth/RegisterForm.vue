<template>
<div class="RegisterForm padding-top-height">
  <div class="p-3 position-ref body-height">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <v-card class="rounded" :loading="myloadingvariable" elevation="0">
          <v-card-title>
            <v-row justify="center" class="p-3">
              <span class="font-weight-bold text-center primary--text">Registrácia</span>
              <router-link :to="{ name: 'Welcome' }">
                <button type="button" class="primary--text close">&times;</button>
              </router-link>
            </v-row>
          </v-card-title>
          <hr class="mt-0 mb-0 custom-hr">
          <v-form ref="form" v-model="valid" @keyup.native.enter="register" lazy-validation>
            <v-card-text class="p-3">

              <v-text-field tabindex="1" prepend-icon="mdi-account" v-model="name" :rules="nameRules" :error-messages="errorUsername" label="Meno" filled clearable @click:clear="callItBackUsername()" clear-icon="mdi-close" counter></v-text-field>

              <v-text-field tabindex="1" ref="email" prepend-icon="mdi-email" v-model="email" :rules="emailRules" :error-messages="errorEmail" label="Email" filled clearable @click:clear="callItBackEMail()" clear-icon="mdi-close" counter>
              </v-text-field>

              <v-text-field tabindex="1" prepend-icon="mdi-lock" v-model="password" :append-icon="togglePassword ? 'mdi-eye' : 'mdi-eye-off'" autocomplete="off" :rules="passwordRules" :type="togglePassword ? 'text' : 'password'" label="Heslo"
                hint="Minimálne 4 znaky" counter @click:append="togglePassword = !togglePassword" filled clearable clear-icon="mdi-close"></v-text-field>
              <v-text-field tabindex="1" prepend-icon="mdi-lock" v-model="confirmPassword" :append-icon="togglePasswordConfirm ? 'mdi-eye' : 'mdi-eye-off'" :rules="confirmPasswordRules.concat(passwordConfirmationRule)"
                :type="togglePasswordConfirm ? 'text' : 'password'" label="Heslo znova" hint="Minimálne 4 znaky" autocomplete="off" counter @click:append="togglePasswordConfirm = !togglePasswordConfirm" filled clearable clear-icon="mdi-close">
              </v-text-field>

              <div class="row">
                <div class="col text-center">
                  Už máš účet?
                  <router-link :to="{ name: 'Login' }">
                    <span class="primary--text font-weight-bold">Prihlás sa</span>
                  </router-link>
                </div>
              </div>
            </v-card-text>
            <hr class="mt-0 custom-hr">
            <v-card-actions>
              <v-btn color="primary" @click="register" block>
                Zaregistrovať sa
              </v-btn>
            </v-card-actions>
          </v-form>
          <v-container align="center" class="p-4 pt-3">
            <v-row align="center">
              <v-divider />
              Alebo
              <v-divider />
            </v-row>
            <v-row class="pt-1" style="justify-content: center">
              <v-btn fab small color="blue">
                <v-icon color="white">
                  mdi-facebook
                </v-icon>
              </v-btn>

              <v-btn class="ml-5" fab small color="red">
                <v-icon color="white">
                  mdi-google
                </v-icon>
              </v-btn>

              <!-- <v-btn class="ml-5" fab small color="light-blue">
                <v-icon color="white">
                  mdi-twitter
                </v-icon>
              </v-btn>

              <v-btn class="ml-5" fab small color="brown">
                <v-icon color="white">
                  mdi-github
                </v-icon>
              </v-btn> -->
            </v-row>
          </v-container>
        </v-card>
      </div>
    </div>
  </div>
</div>
</template>
<script>
import axios from 'axios';
import moment from 'moment';
export default {
  name: "RegisterForm",
  props: {},
  data() {
    return {
      // form: {
      //   email: '',
      //   name: '',
      //   password: '',
      //   confirm_password: '',
      // },
      // sending: false,
      // takenNameError: false,
      // takenEmailError: false,
      // overlay: false,
      myloadingvariable: false,
      togglePassword: false,
      togglePasswordConfirm: false,
      alertSuccessRegister: false,
      alertFail: false,
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
      confirmPassword: '',
      confirmPasswordRules: [
        v => !!v || 'Potvrdenie hesla je povinné',
        v => (v && v.length >= 4) || 'Potvrdenie hesla musí obsahovať minimálne 4 znaky',
        v => v === this.password || 'Zadané hesla sa nezhodujú',
      ],
      remember: true,
      alertFailText: '',
      errorUsername: [],
      errorEmail: [],
    }
  },
  computed: {
    passwordConfirmationRule() {
      return this.password === this.confirmPassword || "Zadané hesla sa nezhodujú";
    }
  },
  watch: {
    name() {
      if (this.name) {
        axios.post(`${process.env.VUE_APP_API_URL}/checkIfUsernameExist`, {
            name: this.name
          })
          .then(valid => {
            this.errorUsername = valid.data;
            // this.errorUsername = valid ? [] : ['async error']
          });
      } else {
        this.errorUsername = '';
      }
    },

    email() {
      if (/.+@.+\..+/.test(this.email)) {
        axios.post(`${process.env.VUE_APP_API_URL}/checkIfEMailExist`, {
            email: this.email
          })
          .then(valid => {
            this.errorEmail = valid.data;
            // this.errorEmail = valid ? [] : ['async error']
          });
      } else {
        this.errorEmail = '';
      }
    }
  },
  methods: {
    validate() {
      return this.$refs.form.validate();
    },
    reset() {
      this.$refs.form.reset()
    },

    callItBackUsername() {
      this.errorUsername = [];
    },

    callItBackEmail() {
      this.errorEmail = [];
    },

    register() {
      this.$vuetify.goTo(0);
      //call API
      // console.log(this.$refs.form);
      if (this.validate() && this.errorUsername.length == 0 && this.errorEmail.length == 0) {
        this.myloadingvariable = true;
        axios.post(`${process.env.VUE_APP_API_URL}/auth/register`, {
            email: this.email,
            name: this.name,
            password: this.password
          })
          .then((res) => {
            const api = `${process.env.VUE_APP_API_URL}/sendNotification`;
            const config = {
              headers: {
                Accept: "application/json",
                Authorization: "Bearer " + localStorage.getItem("authToken"),
              },
            };
            axios.post(api, {
                from: 1,
                recipient: res.data.user.id,
                title: "Tím Bešeduj.sk",
                subtitle: "Vítame Vás na sociálnej sieti Bešeduj.sk",
                text: "Pre akékoľvek informácie nás neváhajte kontaktovať.",
                date: moment(new Date())
                  .format('YYYY-MM-DD HH:mm'),
                status: "new",
              }, config)
              .then(() => {})

            this.myloadingvariable = false;
            this.alertSuccess = true;
            this.$store.dispatch('successRegister', {
              success: true
            });
            // setTimeout(function() {
            this.$router.push("/Login");
            // }, 3000);
          })
          .catch(e => {
            this.alertFail = true;
            this.myloadingvariable = false;
            if (e.response.data.errors.name != null && e.response.data.errors.email != null) {
              this.alertFailText = 'Zadaný email a meno sú už použíté!';
            } else if (e.response.data.errors.email != null) {
              this.alertFailText = 'Zadaný email je už použítý!';
            } else if (e.response.data.errors.name != null) {
              this.alertFailText = 'Zadané meno je už použíté!';
            }
          })
      }
    },
  },

  mounted() {
    //do something after mounting vue instance
    // console.log('Component Register mounted.')
    this.$store.dispatch('isLoggedOut', {
      username: '',
      logout: false
    });
  },

  created() {
    // console.log('Component Register created')
  },
  updated() {
    console.log(this.password);
  }
};
</script>
<style lang="css" scoped>
  .padding-top-height {
    padding-top: 5vh;
  }

  .md-error-pl {
    padding-left: 36px;
  }

  .md-invalid .md-icon {
    color: #ff1744 !important;
  }

</style>
