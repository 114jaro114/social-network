<template>
<v-footer class="p-0 footer" style="bottom: 0" app bottom>
  <v-card flat tile width="100%" class="text-center">
    <v-card-text class="justify-center" justify="center">
      <v-icon color="primary">mdi-copyright</v-icon> {{ new Date().getFullYear() }} — <strong>beseduj.sk</strong>
      <v-spacer></v-spacer>
      <span>Made by — <strong>Jaroslav Balent</strong></span>
    </v-card-text>
  </v-card>
</v-footer>
</template>
<script type="text/javascript">
export default {
  name: "Footer",
  data() {
    return {}
  },
}
</script>
<style>
</style>
