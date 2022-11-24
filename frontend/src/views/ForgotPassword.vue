<template>
<div class="forgot_password">
  <v-lazy :options="{
          threshold: .4
        }" min-height="100vh" transition-group="scale-transition">
    <ForgotPasswordForm />
  </v-lazy>
</div>
</template>

<script>
// @ is an alias to /src
import ForgotPasswordForm from "@/components/auth/ForgotPasswordForm.vue";

export default {
  name: "ForgotPassword",
  components: {
    ForgotPasswordForm,
  },
};
</script>
