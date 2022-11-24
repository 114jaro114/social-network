<template>
<div class="AvatarImageComponent">
  <v-container fluid>
    <v-row justify="center">
      <v-menu bottom min-width="200px" rounded offset-y>
        <template v-slot:activator="{ on }">
          <v-btn icon x-large v-on="on">
            <v-avatar color="primary" size="40" v-if="overlayAvatar == true">
              <v-overlay :value="overlayAvatar" :absolute="true" :opacity="0">
                <v-progress-circular indeterminate size="24" color="white"></v-progress-circular>
              </v-overlay>
            </v-avatar>
            <v-avatar color="white" size="40" v-else-if="!avatarImageUrl && overlayAvatar != true">
              <span class="primary--text">{{ usernameInitial }}</span>
            </v-avatar>
            <v-avatar color="primary" size="40" v-else>
              <v-img :lazy-src="avatarImageUrl" :src="avatarImageUrl" />
            </v-avatar>
          </v-btn>
        </template>
        <v-card class="v-card-logout">
          <v-list-item-content class="justify-center">
            <div class="mx-auto text-center">
              <v-avatar color="primary" size="48" v-if="overlayAvatar == true">
                <v-overlay :value="overlayAvatar" :absolute="true" :opacity="0">
                  <v-progress-circular indeterminate size="24" color="white"></v-progress-circular>
                </v-overlay>
              </v-avatar>
              <!-- <v-avatar color="background"> -->
              <v-avatar color="primary" size="48" v-else-if="!avatarImageUrl && overlayAvatar != true">
                <span class="text-uppercase white--text">{{ usernameInitial }}</span>
              </v-avatar>
              <v-avatar color="primary" size="48" v-else>
                <v-img :lazy-src="avatarImageUrl" :src="avatarImageUrl" />
              </v-avatar>
              <!-- </v-avatar> -->
              <h3>{{ username }}</h3>
              <p class="caption mt-1">
                {{ email }}
              </p>
              <v-divider class="my-3"></v-divider>
              <v-btn depressed rounded text to="/profile">
                <v-icon class="mr-1">mdi-clipboard-edit</v-icon> Môj profil
              </v-btn>
              <v-divider class="my-3"></v-divider>
              <v-btn depressed rounded text @click="logout">
                <v-icon class="mr-1">mdi-logout</v-icon>Odhlásiť sa
              </v-btn>
              <!-- <div class="image-upload">
                  <v-btn depressed rounded text for="file-input">
                    Add photo
                  </v-btn>
                  <input type="file" id="file-input" name="avatar" ref="photo" accept="image/*" @change="updateAvatar($event)">
                </div> -->
            </div>
          </v-list-item-content>
        </v-card>
      </v-menu>
    </v-row>
  </v-container>
  <!-- <div class="avatar">
      <div class="separator" v-if="!avatarImageUrl">
        <v-avatar color="primary" rounded size="40"></v-avatar>
      </div>
      <div class="separator" v-else>
        <v-avatar color="primary" size="40">
          <img :src="avatarImageUrl">
        </v-avatar>
      </div>
    </div>

    <div class="image-upload">
      <label class="file-input-icon" for="file-input">
        <v-icon class="avatar-img">mdi-camera-plus</v-icon>
      </label>

      <input type="file" id="file-input" name="avatar" ref="photo" accept="image/*" @change="updateAvatar($event)">
    </div>

    <div v-if="showUploadProgress">
      Uploading: {{ uploadPercent }} %
    </div> -->
</div>
</template>
<script>
import axios from 'axios'
export default {
  name: "AvatarImageComponent",
  props: ['avatarUrl'],
  data() {
    return {
      uploadPercent: 0,
      avatarImageUrl: "",
      showUploadProgress: false,
      processingUpload: false,
      overlayAvatar: true,
      email: localStorage.getItem('email'),
      username: localStorage.getItem('username'),
      usernameInitial: localStorage.getItem('username')
        .charAt(0),
    }
  },
  mounted() {
    this.avatarImageUrl = this.avatarUrl
    const api = `${process.env.VUE_APP_API_URL}/user`;
    const config = {
      headers: {
        Accept: "application/json",
        Authorization: "Bearer " + localStorage.getItem("authToken"),
      },
    };
    axios.get(api, config)
      .then((response) => {
        if (response.data.avatar) {
          // this.avatarImageUrl = "http://127.0.0.1:8000/storage/user-avatar/" + response.data.avatar;
          this.avatarImageUrl = this.$root.envUrlNoApi + "/storage/user-avatar/" + response.data.avatar;
          this.overlayAvatar = false;
        } else {
          this.overlayAvatar = false;
        }
      })
      .catch((error) => {
        console.log(error)
      })
  },
  methods: {
    logout() {
      this.$emit('childToParent', 'true');
      const api = `${process.env.VUE_APP_API_URL}/auth/logout`;
      const config = {
        headers: {
          Accept: "application/json",
          Authorization: "Bearer " + localStorage.getItem("authToken"),
        },
      };
      axios.post(api, null, config)
        .then(() => {
          this.$emit('childToParent', 'false');
          this.$store.dispatch('isLoggedOut', {
            username: localStorage.getItem("username"),
            logout: true
          });
          localStorage.removeItem("user_id");
          localStorage.removeItem("username");
          localStorage.removeItem("authToken");
          this.$store.dispatch('selectedUser', {
            id: null,
            name: null,
            email: null,
            status: null,
            avatar: null,
            created_at: null,
            unread: null,
          });
          this.$router.push("/login");
        })
        .catch(e => {
          this.$emit('childToParent', 'false'),
            console.log(e);
        })
    },
  }
}
</script>
<style>
input[type="file"] {
  opacity: 0;
  filter: aplha(opacity=0);
}

.file-input-icon {
  left: 45px;
  position: fixed;
  top: 35px;
}

.v-icon.avatar-img {
  font-size: 16px !important;
}

.image-upload>input {
  display: none;
}

.AvatarImageComponent button:focus {
  outline: 0px !important;
}

.v-card-logout .theme--dark.v-btn--active,
.theme--light.v-btn--active {
  color: #0D47A1;
}
</style>
