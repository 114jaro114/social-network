<template>
<div class="ResetPasswordForm padding-top-height">
  <div id="blur-effect">
    <div class="p-3 position-ref body-height">
      <div class="row justify-content-center">
        <v-snackbar v-model="snackbar" :multi-line="multiLine" color="error" bottom left class="m-3">
          <v-icon>mdi-alert-circle</v-icon>
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
                <span class="font-weight-bold text-center primary--text">Nové heslo</span>
                <router-link :to="{ name: 'Login' }">
                  <button type="button" class="primary--text close">&times;</button>
                </router-link>
              </v-row>
            </v-card-title>
            <hr class="mt-0 mb-0 custom-hr">
            <v-form ref="form" v-model="valid" @keyup.native.enter="resetPassword" lazy-validation>
              <v-card-text class="p-3">
                <v-text-field tabindex="1" prepend-icon="mdi-email" v-model="email" :rules="emailRules" label="Email" filled clearable clear-icon="mdi-close"></v-text-field>
                <v-text-field tabindex="1" prepend-icon="mdi-lock" v-model="password" :append-icon="togglePassword ? 'mdi-eye' : 'mdi-eye-off'" :rules="passwordRules" :type="togglePassword ? 'text' : 'password'" label="Nové heslo"
                  hint="Minimálne 4 znaky" counter @click:append="togglePassword = !togglePassword" filled clearable clear-icon="mdi-close"></v-text-field>
                <v-text-field tabindex="1" prepend-icon="mdi-lock" v-model="confirmPassword" :append-icon="togglePasswordConfirm ? 'mdi-eye' : 'mdi-eye-off'" :rules="confirmPasswordRules.concat(passwordConfirmationRule)"
                  :type="togglePasswordConfirm ? 'text' : 'password'" label="Nové heslo znova" hint="Minimálne 4 znaky" counter @click:append="togglePasswordConfirm = !togglePasswordConfirm" filled clearable clear-icon="mdi-close"></v-text-field>
              </v-card-text>
              <hr class="mt-0 mb-0 custom-hr">
              <v-card-actions>
                <v-btn color="primary" @click="resetPassword" block>
                  Uložiť nové heslo
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
  names: ['ResetPasswordForm', 'FormValidation'],
  props: {},
  data() {
    return {
      valid: true,
      myloadingvariable: false,
      togglePassword: false,
      togglePasswordConfirm: false,

      snackbar: false,
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
      token: null,
      multiLine: true,
      text: '',
    }
  },

  computed: {
    passwordConfirmationRule() {
      return this.password === this.confirmPassword || "Zadané hesla sa nezhodujú";
    }
  },

  methods: {
    validate() {
      return this.$refs.form.validate();
    },
    reset() {
      this.$refs.form.reset()
    },

    resetPassword() {
      if (this.validate()) {
        this.myloadingvariable = true;
        // call API
        axios.post(`${process.env.VUE_APP_API_URL}/auth/reset/password/`, {
            token: this.$route.params.token,
            email: this.email,
            password: this.password,
            password_confirmation: this.password_confirmation
          })
          .then(() => {
            this.myloadingvariable = false;
            this.reset();
            this.$store.dispatch('updatedPassword', {
              state: true
            });
            this.$router.push("/login");
          })
          .catch(e => {
            this.myloadingvariable = false;
            this.snackbar = true;
            this.text = "Vyskytol sa nejaký problém. Prosím skúste to znova.";
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
