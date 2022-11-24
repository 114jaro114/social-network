<template>
<div class="reset_password">
  <v-lazy :options="{
          threshold: .4
        }" min-height="100vh" transition-group="scale-transition">
    <ResetPasswordForm />
  </v-lazy>
</div>
</template>

<script>
// @ is an alias to /src
import ResetPasswordForm from "@/components/auth/ResetPasswordForm.vue";

export default {
  name: "ResetPassword",
  components: {
    ResetPasswordForm,
  },
};
</script>
