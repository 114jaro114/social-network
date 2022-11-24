<template>
<div class="Images mt-16 pb-16">
  <v-container>
    <!-- <v-row class="p-2 mt-0">
      <file-pond allowImageCrop={true} allowFileEncode={true} class="filepond" name="filepond" ref="pond" type="input" v-on:onprocessfile='onprocessfile' v-on:onaddfilestart="onaddfilestart" v-on:onremove="onremove" v-on:updatefiles="updatefiles"
        :files="files" v-on:onaddfileprogress="onaddfileprogress" v-on:removefile="handleFileRemoved" v-on:addfile="handleFileAdded" v-on:init="handleFilePondInit" style="width:100%" label-idle="Sem presuňte súbory..." allow-multiple="true"
        max-files="1" />
    </v-row>

    <v-row class="p-2 mt-0">
      <v-btn id="btn_submit" color="primary" :disabled="isDisabled" @click="addPost()" block>
        Uverejniť
      </v-btn>
    </v-row> -->
    <div class="pt-5">
      <v-text-field v-model="text" ref="commentInput" label="text" filled clearable clear-icon="mdi-close" counter>
        <template v-slot:prepend-inner v-if="chipText != ''">
          <v-chip class="ma-1" close @click:close="closeChip">{{chipText}}</v-chip>
        </template>
      </v-text-field>
      <!-- <v-menu offset-y v-model="open"> -->
      <v-list class="p-0" dense v-if="showDialogFriends">
        <v-list-item-group color="primary">
          <v-subheader>Vybrať priateľa</v-subheader>
          <!-- <v-list-item v-if="overlay != false" inactive disabled>
            <v-overlay :value="overlay" :absolute="true" :opacity="0">
              <v-progress-circular indeterminate size="24" color="primary"></v-progress-circular>
            </v-overlay>
          </v-list-item> -->
          <template v-for="item in displayFriends">
            <v-list-item :key="item.id" @click="selectDataAboutUser(item)">
              <v-badge bottom dot bordered :color="getColor(item.status)" offset-x="10" offset-y="10" class="mr-2">
                <v-avatar color="primary" size="40" v-if="item.avatar == null">
                  <span class="text-uppercase white--text">{{ item.name.charAt(0) }}</span>
                </v-avatar>
                <v-avatar color="primary" size="40" v-else>
                  <img :lazy-src="`http://127.0.0.1:8000/storage/user-avatar/${item.avatar}`" :src="`http://127.0.0.1:8000/storage/user-avatar/${item.avatar}`">
                </v-avatar>
              </v-badge>
              <v-list-item-content>
                <!-- <v-list-item-title v-text="item.name"></v-list-item-title> -->
                <v-card class="p-2 rounded-lg card-comments" elevation="0">
                  <div class="d-flex flex-no-wrap justify-space-between">
                    <div>
                      <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pl-1">
                        {{item.name}}
                      </v-card-title>
                      <v-card-subtitle class="d-flex text-left caption grey--text p-0 pt-3 pl-1">{{item.text}}</v-card-subtitle>
                    </div>
                  </div>
                </v-card>
              </v-list-item-content>
            </v-list-item>
          </template>
        </v-list-item-group>
      </v-list>
    </div>

    <div class="">
      <v-btn @click="getAllDataAboutposts">get data</v-btn>
    </div>
  </v-container>
</div>
</template>

<script>
import axios from 'axios';
// Import Vue FilePond
// import vueFilePond from 'vue-filepond'
// // Import FilePond styles
// import 'filepond/dist/filepond.min.css'
// // Import image preview plugin styles
// import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css'
// // Import image preview and file type validation plugins
// import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type'
// import FilePondPluginImagePreview from 'filepond-plugin-image-preview'
// // Import the plugin code
// import FilePondPluginFileEncode from 'filepond-plugin-file-encode';
// import FilePondPluginImageCrop from 'filepond-plugin-image-crop';
// // Import the plugin code
// import FilePondPluginImageEdit from 'filepond-plugin-image-edit';
// import FilePondPluginImageResize from 'filepond-plugin-image-resize';
// import FilePondPluginImageTransform from 'filepond-plugin-image-transform';
// // Import the plugin styles
// import 'filepond-plugin-image-edit/dist/filepond-plugin-image-edit.css';
// // Create component
// const FilePond = vueFilePond(
//   FilePondPluginFileValidateType,
//   FilePondPluginImagePreview,
//   FilePondPluginFileEncode,
//   FilePondPluginImageCrop,
//   FilePondPluginImageEdit,
//   FilePondPluginImageResize,
//   FilePondPluginImageTransform
// )

export default {
  name: "Images",
  components: {
    // FilePond
  },

  data() {
    return {
      imageUrlAnswerOnComment: null,
      videoUrlAnswerOnComment: null,

      showDragAndDropFile: false,
      files: [],
      convertedFiles: [],
      urls: [],
      photo: null,
      isDisabled: true,
      text: '',
      chipText: '',
      displayFriends: [],
      showDialogFriends: false,
      open: true,
      overlay: true,

      allData: [],
    }
  },

  updated() {
    // if (this.text == null) {
    //   this.friends = [];
    // }
  },

  watch: {
    text() {
      const api = `${process.env.VUE_APP_API_URL}/getapf`;
      const config = {
        headers: {
          Accept: "application/json",
          Authorization: "Bearer " + localStorage.getItem("authToken"),
        },
      };
      axios.post(api, {
          text: this.text
        }, config)
        .then(valid => {
          if (valid.data.length != 0) {
            this.displayFriends = valid.data;
            this.showDialogFriends = true;
          } else {
            this.displayFriends = [];
            this.showDialogFriends = false;
          }
        });
    },
  },

  methods: {

    getAllDataAboutposts() {
      const api = `${process.env.VUE_APP_API_URL}/getAllDataAboutPosts`;
      const config = {
        headers: {
          Accept: "application/json",
          Authorization: "Bearer " + localStorage.getItem("authToken"),
        },
      };
      axios.get(api, config)
        .then(res => {
          console.log(res.data);
          this.allData = res.data
        });
    },

    getColor(e) {
      if (e == 'offline') return 'red'
      else return 'green'
    },

    closeChip() {
      this.chipText = '';
      this.$nextTick(() => this.$refs.commentInput.focus());
    },

    selectDataAboutUser(item) {
      this.chipText = item.name + ' ';
      this.text = '';
      this.$nextTick(() => this.$refs.commentInput.focus());
      this.showDialogFriends = false;
    },

    addPost() {
      const api = `${process.env.VUE_APP_API_URL}/add_post_test`;
      const config = {
        headers: {
          Accept: "application/json",
          Authorization: "Bearer " + localStorage.getItem("authToken"),
        },
      };
      let formData = new FormData()
      for (let i = 0; i < this.files.length; i++) {
        formData.append('image_video_url[]', this.files[i])
      }
      formData.append('text', this.textNewPost);
      axios.post(api, formData, config)
        .then((res) => {
          console.log(res);
        })
        .catch((error) => {
          console.log(error)
        })
    },

    handleFilePondInit: function() {
      console.log("FilePond has initialized");
    },
    handleFileAdded: function() {
      this.files = [];
      for (var i = 0; i < this.$refs.pond.getFiles().length; i++) {
        this.files.push(this.$refs.pond.getFiles()[i].file)
      }
      this.isDisabled = false;
      // this.onaddfilestart();
      // this.onprocessfile();
    },

    handleFileRemoved: function(error, file) {
      this.$refs.pond.removeFile(file.id);
      this.files = [];
      for (var i = 0; i < this.$refs.pond.getFiles().length; i++) {
        this.files.push(this.$refs.pond.getFiles()[i].file)
      }
    },

    onaddfilestart: function() {
      var isLoading = this.$refs.pond.getFiles().filter(x => x.status !== 5).length !== 0;
      if (isLoading) {
        console.log('onaddfilestarttrue');
        this.isDisabled = true;
        document.getElementById('btn_submit').setAttribute("disabled", "disabled");
      } else {
        console.log('onaddfilestartfalse');
        this.isDisabled = false;
        document.getElementById('btn_submit').removeAttribute("disabled");
      }
    },

    onprocessfile: function() {
      var isLoading = this.$refs.pond.getFiles().filter(x => x.status !== 5).length !== 0;
      if (isLoading) {
        console.log('onprocessfiletrue');
        this.isDisabled = true;
        document.getElementById('btn_submit').setAttribute("disabled", "disabled");
      } else {
        console.log('onprocessfilefalse');
        this.isDisabled = false;
        document.getElementById('btn_submit').removeAttribute("disabled");
      }
    },

    updatefiles: function(file) {
      var counter = 0;
      for (var i = 0; i < file.length; i++) {
        if (file[i].status == 2) {
          counter++;
        }
      }
      if (counter == file.length) {
        console.log("success added");
      }
    },

    onremove: function() {
      console.log("remove");
    },

    onaddfileprogress(file, progress) {
      console.log(`onaddfileprogress`);
      console.log(file);
      console.log(progress);
    },
  }
};
</script>
