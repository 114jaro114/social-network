<template id="NavigationDrawerRight">
<v-navigation-drawer width="600px" floating right clipped app :permanent="$vuetify.breakpoint.mdAndUp">
  <FriendsFriendshipRequests />
</v-navigation-drawer>
</template>
<script>
import FriendsFriendshipRequests from "./Friends_FriendshipRequests.vue";
export default {
  name: "NavigationDrawerRight",
  components: {
    FriendsFriendshipRequests
  },
  data() {
    return {}
  },

  created() {},

  mounted() {},

  updated() {
    //do something after updating vue instance
  }
}
</script>
<style type="scss">
</style>
