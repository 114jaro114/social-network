<template>
<div class="messenger2 h-100">
  <!-- <ContactsList /> -->
  <!-- @refreshCloseChat="refreshCloseChat" :closeChat="closeChat" :contacts="contacts" @selected="startConversationWith"  -->
  <v-main class="mt-16 pt-0 pb-0">
    <Conversation />
  </v-main>
</div>
</template>
<script>
import Conversation from "../components/messenger2/Conversation.vue";
// import ContactsList from "../components/messenger2/ContactsList.vue";
export default {
  name: "Messenger2",
  components: {
    Conversation,
    // ContactsList,
  },
  props: ['drawerNew'],
  data() {
    return {}
  },
}
</script>
<style type="scss">
</style>
