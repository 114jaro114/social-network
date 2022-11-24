<template>
<v-container class="friends p-0">
  <div v-if="$root.profile.data.returnVariable == -1">
    <FriendsFriendshipRequests />
  </div>

  <div v-else>
    <FriendsProfile />
  </div>
</v-container>
</template>

<script>
import _ from 'lodash';
import FriendsFriendshipRequests from "../../../components/Friends_FriendshipRequests.vue";
import FriendsProfile from "../../../components/FriendsProfile.vue";
export default {
  name: "Friends",
  props: [],
  components: {
    FriendsFriendshipRequests,
    FriendsProfile
  },
  data() {
    return {
      //search new friends
      search: '',
      friends: [],
      overlay: true,
      friendshipRequests: false,
    }
  },

  updated() {},

  methods: {
    getColor(e) {
      if (e == 'offline') return 'red'
      else return 'green'
    },

    getFriendProfileData(item) {
      this.$router.push(`/profile/${item.id}`);
    },

    selectDataAboutUser(item) {
      this.$router.push(`/messenger?name=${item.name}`);
    },
  },

  mounted() {},

  computed: {},
}
</script>

<style>
</style>
