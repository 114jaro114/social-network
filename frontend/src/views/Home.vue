<template>
<div class="home">
  <NavigationDrawerLeft />
  <NavigationDrawerRight />

  <v-main style="padding-top:64px">
    <NavigationDrawerCenter />
  </v-main>
</div>
</template>

<script>
import NavigationDrawerLeft from "../components/NavigationDrawerLeft.vue";
import NavigationDrawerRight from "../components/NavigationDrawerRight.vue";
import NavigationDrawerCenter from "../components/NavigationDrawerCenter.vue";

export default {
  name: "Home",
  props: [],
  components: {
    NavigationDrawerLeft,
    NavigationDrawerRight,
    NavigationDrawerCenter,
  },
  data() {
    return {}
  },
}
</script>

<style>
</style>
