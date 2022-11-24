<template>
<div class="register">
  <v-lazy :options="{
          threshold: .4
        }" min-height="100vh" transition-group="scale-transition">
    <RegisterForm />
  </v-lazy>
</div>
</template>

<script>
// @ is an alias to /src
import RegisterForm from "@/components/auth/RegisterForm.vue";

export default {
  name: "Register",
  components: {
    RegisterForm,
  },
};
</script>
