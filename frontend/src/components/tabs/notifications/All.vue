<template>
<v-container class="all p-0">
  <v-row justify="center" class="ml-0 mr-0">
    <v-col class="pl-0 pr-0">
      <v-card elevation="0">
        <v-toolbar class="notiftoolbar" color="primary" flat dark>
          <div class="w-75" v-if="selected.length == '0'">
            <v-text-field color="white white--color" v-model="search" append-icon="mdi-magnify" label="Vyhľadať" single-line hide-details clearable disabled v-if="overlay || notif.length == 0"></v-text-field>
            <v-text-field color="white white--color" v-model="search" append-icon="mdi-magnify" label="Vyhľadať" single-line hide-details clearable v-if="!overlay && notif.length != 0"></v-text-field>
          </div>

          <div v-else>
            <v-tooltip bottom>
              <template v-slot:activator="{ on, attrs }">
                <v-btn fab icon small @click="addToSaved = !addToSaved; deleteNotif = false" v-bind="attrs" v-on="on">
                  <v-icon>mdi-bookmark-multiple</v-icon>
                </v-btn>
              </template>
              <span>Pridať do uložených</span>
            </v-tooltip>
            <v-tooltip bottom>
              <template v-slot:activator="{ on, attrs }">
                <v-btn fab icon small @click="deleteNotif = !deleteNotif; addToSaved = false" v-bind="attrs" v-on="on">
                  <v-icon>mdi-delete</v-icon>
                </v-btn>
              </template>
              <span>Vymazať</span>
            </v-tooltip>
          </div>

          <v-spacer></v-spacer>

          <span style="font-size:12px">Označiť všetko</span>
          <v-checkbox class="mt-5 ml-3" color="white white--text" @change="checkUncheckAll($event);" disabled v-if="overlay || notif.length == 0"></v-checkbox>
          <v-checkbox class="mt-5 ml-3" color="white white--text" @change="checkUncheckAll($event);" v-model=" item_1.checked" :indeterminate="item_1.indeterminate" v-if="!overlay && notif.length != 0"></v-checkbox>
        </v-toolbar>

        <v-list class="pb-0 pt-0" three-line>
          <v-list-item-group v-model="selected" active-class="primary--text" multiple>
            <!-- <v-list-item class="justify-center" v-if="myloadingvariable" disabled>
              Načítavanie... Prosím počkajte
            </v-list-item> -->
            <v-list-item v-if="overlay != false" inactive disabled>
              <v-overlay :value="overlay" :absolute="true" :opacity="0">
                <v-progress-circular indeterminate size="24" color="primary"></v-progress-circular>
              </v-overlay>
            </v-list-item>
            <v-list-item class="justify-center" v-if="filteredItems.length == 0 && !overlay && notif.length != 0" disabled>
              <v-btn color="primary" icon width="50px" height="50px">
                <v-icon style="font-size: 50px">mdi-cancel</v-icon>
                <span class="ml-3">Žiadne výsledky</span>
              </v-btn>
            </v-list-item>

            <v-list-item class="justify-center" v-else-if="notif.length == 0 && !overlay" disabled>
              <v-btn color="primary" icon width="50px" height="50px">
                <v-icon style="font-size: 50px">mdi-bell-cancel</v-icon>
                <span class="ml-3">Žiadne notifikácie</span>
              </v-btn>
            </v-list-item>
            <template v-else v-for="(item, index) in filteredItems">
              <!-- list notif -->
              <v-list-item class="p-0" :key="item.name" v-if="!overlay">
                <template v-slot:default="{ active }">
                  <v-list-item-content class="pt-5 pb-5 pl-3" @click="checkUncheck(item, active)">
                    <v-list-item-title v-text="item.title"></v-list-item-title>

                    <v-list-item-subtitle class="text--primary" v-text="item.subtitle"></v-list-item-subtitle>

                    <v-list-item-subtitle v-text="item.text"></v-list-item-subtitle>
                  </v-list-item-content>

                  <v-list-item-action class="m-0 p-3" @click="checkUncheck(item, active)">
                    <v-list-item-action-text class="pb-3" v-text="item.date"></v-list-item-action-text>

                    <v-dialog v-model="dialogNotifDetail" fullscreen hide-overlay transition="dialog-bottom-transition">
                      <template v-slot:activator="{ on: menu, attrs }">
                        <v-btn class="p-absolute" style="left:10px" icon large color="primary" v-bind="attrs" v-on="{ ...menu }" @click="getNotifDetail(item, active)">
                          <v-icon size="24">mdi-eye</v-icon>
                        </v-btn>
                      </template>
                      <v-card>
                        <v-toolbar dark color="primary" class="rounded-0" elevation="0">
                          <v-btn icon dark @click="dialogNotifDetail = false">
                            <v-icon>mdi-close</v-icon>
                          </v-btn>
                          <v-toolbar-title class="justify-center">Detail</v-toolbar-title>
                        </v-toolbar>
                      </v-card>
                    </v-dialog>

                    <v-checkbox value v-if="!active"></v-checkbox>

                    <v-checkbox input-value="true" value v-else></v-checkbox>
                  </v-list-item-action>
                </template>
              </v-list-item>
              <v-divider class="m-0" v-if="index < notif.length - 1 && !overlay" :key="index"></v-divider>
            </template>
            <!-- snackbard -->
            <v-snackbar :timeout="-1" :value="deleteNotif" absolute centered color="primary" elevation="24">
              Naozaj chcete vymazať túto notifikáciu?
              <v-btn class="ml-2 mt-3" color="error error--text" text small @click="deleteNotif = false; deleteNotification(item)">
                <!-- <v-icon>mdi-check</v-icon> -->
                Áno
              </v-btn>
              <v-btn class="ml-2 mt-3" color="white white--text" text small outlined @click="deleteNotif = false">
                <!-- <v-icon>mdi-close</v-icon> -->
                Nie
              </v-btn>
            </v-snackbar>

            <v-snackbar :timeout="-1" :value="addToSaved" absolute centered color="primary" elevation="24">
              Naozaj chcete Uložiť notifikáciu do uložených?
              <v-btn class="ml-2 mt-3" color="error error--text" text small @click="addToSaved = false; addNotificationsToSaved(item)">
                <!-- <v-icon>mdi-check</v-icon> -->
                Áno
              </v-btn>
              <v-btn class="ml-2 mt-3" color="white white--text" text small outlined @click="addToSaved = false">
                <!-- <v-icon>mdi-close</v-icon> -->
                Nie
              </v-btn>
            </v-snackbar>

            <v-snackbar v-model="snackbar" :multi-line="multiLine" color="success" :left="true">
              <v-icon>mdi-check-circle</v-icon>
              {{ text }}
              <template v-slot:action="{ attrs }">
                <v-btn color="white" text v-bind="attrs" @click="snackbar = false">
                  <v-icon>mdi-close-circle</v-icon>
                </v-btn>
              </template>
            </v-snackbar>
            <!-- ///////// -->
          </v-list-item-group>
        </v-list>
      </v-card>
    </v-col>
  </v-row>
</v-container>
</template>

<script>
import axios from 'axios'
import _ from 'lodash';
export default {
  name: "All",
  components: {},
  data() {
    return {
      search: '',
      selected: [],
      selectedAll: [],
      notif: [],
      dialogNotifDetail: false,
      overlay: true,
      checkbox: true,
      item_1: {
        name: 'test 1',
        checked: false,
        indeterminate: false
      },
      // notif snackbars
      deleteNotif: false,
      addToSaved: false,
      snackbar: false,
      multiLine: true,
      text: '',
    }
  },

  methods: {
    checkUncheck(item, active) {
      if (active) {
        for (var i = 0; i < this.selectedAll.length; i++) {
          if (this.selectedAll[i] == item.id) {
            this.selectedAll.splice(i, 1);
          }
        }
        if (this.item_1.checked == true) {
          this.deleteNotif = false;
          this.item_1.checked = false;
          this.item_1.indeterminate = true;
        }
        if (this.selectedAll.length == this.notif.length) {
          this.item_1.indeterminate = false;
          this.item_1.checked = true;
        }
        if (this.selectedAll.length == "0") {
          this.item_1.indeterminate = false;
          this.item_1.checked = false;
          this.deleteNotif = false;
        }
      } else {
        this.selectedAll.push(item.id);
        if (this.selectedAll.length == this.notif.length) {
          this.item_1.indeterminate = false;
          this.item_1.checked = true;
        }
      }
    },

    getNotifDetail(item, active) {
      console.log(item);
      console.log(active);
    },

    checkUncheckAll(event) {
      if (event == true) {
        this.item_1.checked = true;
        this.item_1.indeterminate = false;

        for (var i = 0; i < this.notif.length; i++) {
          this.selected.push(i);
        }

        for (var x = 0; x < this.notif.length; x++) {
          this.selectedAll.push(this.notif[x].id);
        }
      } else {
        this.item_1.checked = false;
        this.item_1.indeterminate = false;
        this.deleteNotif = false;
        this.selected = [];
        this.selectedAll = [];
      }
    },

    deleteNotification() {
      this.snackbar = true;
      this.text = "Notifikácia bola úspešne vymazaná";
      const api = `${process.env.VUE_APP_API_URL}/deleteNotification`;
      const config = {
        headers: {
          Accept: "application/json",
          Authorization: "Bearer " + localStorage.getItem("authToken"),
        },
      };
      axios.post(api, {
          ids: this.selectedAll,
        }, config)
        .then(() => {
          this.getNotifications();
        })
    },

    addNotificationsToSaved() {
      this.snackbar = true;
      this.text = "Notifikácia bola premiestnená do zoznamu 'uložené'";
      const api = `${process.env.VUE_APP_API_URL}/addToSaved`;
      const config = {
        headers: {
          Accept: "application/json",
          Authorization: "Bearer " + localStorage.getItem("authToken"),
        },
      };

      axios.post(api, {
          ids: this.selectedAll,
        }, config)
        .then(() => {
          this.getNotifications();
        })
    },

    getNotifications() {
      const api = `${process.env.VUE_APP_API_URL}/getNotificationAll/${localStorage.getItem('user_id')}`;
      const config = {
        headers: {
          Accept: "application/json",
          Authorization: "Bearer " + localStorage.getItem("authToken"),
        },
      };
      axios.get(api, config)
        .then(res => {
          this.notif = res.data;
          this.overlay = false;
        });
    }
  },

  mounted() {
    //do something after mounting vue instance
    this.getNotifications();
  },

  computed: {
    filteredItems() {
      return _.orderBy(this.notif.filter(item => {
        if (!this.search) return this.notif;
        return (item.title.toLowerCase()
          .includes(this.search.toLowerCase()) ||
          item.date.toLowerCase()
          .includes(this.search.toLowerCase()) ||
          item.text.toLowerCase()
          .includes(this.search.toLowerCase()) ||
          item.subtitle.toLowerCase()
          .includes(this.search.toLowerCase()));
      }), 'headline');
    }
  },
}
</script>

<style type="scss">

</style>
