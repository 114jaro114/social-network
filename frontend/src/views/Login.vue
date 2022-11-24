<template>
<div class="login">
  <v-lazy :options="{
          threshold: .4
        }" min-height="100vh" transition-group="scale-transition">
    <LoginForm />
  </v-lazy>
</div>
</template>

<script>
// @ is an alias to /src
import LoginForm from "@/components/auth/LoginForm.vue";

export default {
  name: "Login",
  components: {
    LoginForm,
  },
};
</script>
