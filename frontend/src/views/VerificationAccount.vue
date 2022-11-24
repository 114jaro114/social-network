<template>
<div class="verification_account">
  <v-lazy :options="{
          threshold: .4
        }" min-height="100vh" transition-group="scale-transition">
    <VerificationAccountForm />
  </v-lazy>
</div>
</template>

<script>
// @ is an alias to /src
import VerificationAccountForm from "@/components/auth/VerificationAccountForm.vue";

export default {
  name: "VerificationAccount",
  components: {
    VerificationAccountForm,
  },
};
</script>
