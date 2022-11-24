<template>
<v-container class="about p-0">
  <v-row class="m-0">
    <v-col xl="12" lg="12" md="12" sm="12" xs="12" class="pl-0 pr-0 pb-0">
      <v-row class="ml-0 mr-0 mb-3">
        <v-card class="w-100 rounded" elevation="0" min-height="336">
          <v-tabs id="tabs" :vertical="vertical" class="verticalTabs vertical-tabs-border text-align-left">
            <!-- show-arrows -->
            <v-tab class="justify-content-start">
              Prehľad
            </v-tab>

            <v-tab class="justify-content-start">
              Zamestnanie a vzdelanie
            </v-tab>

            <v-tab class="justify-content-start">
              Bydliská
            </v-tab>

            <v-tab class="justify-content-start">
              Kontaktné a základné údaje
            </v-tab>

            <v-tab class="justify-content-start">
              Rodina a vzťahy
            </v-tab>

            <v-tab class="justify-content-start">
              Informácie o Vás
            </v-tab>

            <v-tab class="justify-content-start">
              Životné udalosti
            </v-tab>
            <!-- /////////////////////////////////////////////////////////////////////////////////////////////////////// overview -->
            <v-tab-item>
              <v-card class="rounded" flat min-height="336">
                <v-card-text class="p-0">
                  <v-list class="pt-0 pb-0" dense style="border-top-right-radius: 100px;border-bottom-right-radius: 100px;">
                    <v-list-item-group color="primary">
                      <v-list-item rounded>
                        <v-icon class="mr-7">mdi-briefcase</v-icon>
                        <v-list-item-content>
                          <v-card class="p-2 rounded-lg card-comments" elevation="0">
                            <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-3 pl-1">
                              <span>Práca</span>
                              <v-spacer />
                              <v-tooltip bottom v-if="$root.profile.data.returnVariable == -1">
                                <template v-slot:activator="{ on, attrs }">
                                  <v-btn v-bind="attrs" v-on="on" icon fab x-small color="primary" @click.prevent="" @mousedown.stop @touchstart.native.stop>
                                    <v-icon>mdi-account-supervisor</v-icon>
                                  </v-btn>
                                </template>
                                <span>Iba priatelia</span>
                              </v-tooltip>
                            </v-card-title>
                            <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">
                              <span>Informácie o Vašej práci</span>
                            </v-card-subtitle>
                          </v-card>
                        </v-list-item-content>
                      </v-list-item>

                      <v-list-item>
                        <v-icon class="mr-7">mdi-school</v-icon>
                        <v-list-item-content>
                          <v-card class="p-2 rounded-lg card-comments" elevation="0">
                            <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-3 pl-1">
                              <span>Študuje</span>
                              <v-spacer />
                              <v-tooltip bottom v-if="$root.profile.data.returnVariable == -1">
                                <template v-slot:activator="{ on, attrs }">
                                  <v-btn v-bind="attrs" v-on="on" icon fab x-small color="primary" @click.prevent="" @mousedown.stop @touchstart.native.stop>
                                    <v-icon>mdi-earth</v-icon>
                                  </v-btn>
                                </template>
                                <span>Verejné</span>
                              </v-tooltip>
                            </v-card-title>
                            <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">
                              <span>Informácie o Vašom štúdiu</span>
                            </v-card-subtitle>
                          </v-card>
                        </v-list-item-content>
                      </v-list-item>

                      <v-list-item>
                        <v-icon class="mr-7">mdi-home</v-icon>
                        <v-list-item-content>
                          <v-card class="p-2 rounded-lg card-comments" elevation="0">
                            <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-3 pl-1">
                              <span>Žije v</span>
                              <v-spacer />
                              <v-tooltip bottom v-if="$root.profile.data.returnVariable == -1">
                                <template v-slot:activator="{ on, attrs }">
                                  <v-btn v-bind="attrs" v-on="on" icon fab x-small color="primary" @click.prevent="" @mousedown.stop @touchstart.native.stop>
                                    <v-icon>mdi-lock</v-icon>
                                  </v-btn>
                                </template>
                                <span>Iba ja</span>
                              </v-tooltip>
                            </v-card-title>
                            <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">
                              <span>Informácie o Vašom bydlisku</span>
                            </v-card-subtitle>
                          </v-card>
                        </v-list-item-content>
                      </v-list-item>

                      <v-list-item>
                        <v-icon class="mr-7">mdi-map-marker-radius</v-icon>
                        <v-list-item-content>
                          <v-card class="p-2 rounded-lg card-comments" elevation="0">
                            <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-3 pl-1">
                              <span>Rodné mesto</span>
                              <v-spacer />
                              <v-tooltip bottom v-if="$root.profile.data.returnVariable == -1">
                                <template v-slot:activator="{ on, attrs }">
                                  <v-btn v-bind="attrs" v-on="on" icon fab x-small color="primary" @click.prevent="" @mousedown.stop @touchstart.native.stop>
                                    <v-icon>mdi-lock</v-icon>
                                  </v-btn>
                                </template>
                                <span>Iba ja</span>
                              </v-tooltip>
                            </v-card-title>
                            <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">
                              <span>Informácie o Vašom rodnom meste</span>
                            </v-card-subtitle>
                          </v-card>
                        </v-list-item-content>
                      </v-list-item>

                      <v-list-item>
                        <v-icon class="mr-7">mdi-heart</v-icon>
                        <v-list-item-content>
                          <v-card class="p-2 rounded-lg card-comments" elevation="0">
                            <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-3 pl-1">
                              <span>Stav</span>
                              <v-spacer />
                              <v-tooltip bottom v-if="$root.profile.data.returnVariable == -1">
                                <template v-slot:activator="{ on, attrs }">
                                  <v-btn v-bind="attrs" v-on="on" icon fab x-small color="primary" @click.prevent="" @mousedown.stop @touchstart.native.stop>
                                    <v-icon>mdi-lock</v-icon>
                                  </v-btn>
                                </template>
                                <span>Iba ja</span>
                              </v-tooltip>
                            </v-card-title>
                            <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">
                              <span>Informácie o Vašom rodinnom stave</span>
                            </v-card-subtitle>
                          </v-card>
                        </v-list-item-content>
                      </v-list-item>

                      <v-list-item>
                        <v-icon class="mr-7">mdi-phone</v-icon>
                        <v-list-item-content>
                          <v-card class="p-2 rounded-lg card-comments" elevation="0">
                            <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-3 pl-1">
                              <span>Kontakt</span>
                              <v-spacer />
                              <v-tooltip bottom v-if="$root.profile.data.returnVariable == -1">
                                <template v-slot:activator="{ on, attrs }">
                                  <v-btn v-bind="attrs" v-on="on" icon fab x-small color="primary" @click.prevent="" @mousedown.stop @touchstart.native.stop>
                                    <v-icon>mdi-lock</v-icon>
                                  </v-btn>
                                </template>
                                <span>Iba ja</span>
                              </v-tooltip>
                            </v-card-title>
                            <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">
                              <span>Informácie o Vašom kontakte</span>
                            </v-card-subtitle>
                          </v-card>
                        </v-list-item-content>
                      </v-list-item>
                    </v-list-item-group>
                  </v-list>
                </v-card-text>
              </v-card>
            </v-tab-item>

            <!--  /////////////////////////////////////////////////////////////////////////////////////////////////////// work and aducation-->
            <v-tab-item>
              <v-card flat min-height="336">
                <v-card-text class="">
                  <v-row class="pl-3 d-flex justify-content-start">
                    <v-col cols="12" class="pl-0 pr-0">
                      <span class="d-flex justify-content-start">Práca</span>
                    </v-col>

                    <v-col cols="12" class="pl-0 pr-0 d-flex justify-content-start" v-if="showFormWork == false && $root.profile.data.returnVariable == -1">
                      <v-btn rounded outlined color="primary" @click="showFormWork = true; showFormWork2 = false; showFormWork3 = false; showFormWork4 = false; shareLike = 0">
                        <v-icon class="mr-1">mdi-plus-circle</v-icon>
                        <span>Pridajte pracovisko</span>
                      </v-btn>
                    </v-col>

                    <v-col cols="12" class="pl-0" v-else-if="showFormWork == true && $root.profile.data.returnVariable == -1">
                      <v-form ref="form" lazy-validation>
                        <v-text-field v-model="company" label="Spoločnosť" filled required></v-text-field>

                        <v-text-field v-model="job_function" label="Pracovná funkcia" filled required></v-text-field>

                        <v-text-field v-model="town_village" label="Mesto/obec" filled required></v-text-field>

                        <v-textarea v-model="work_description" label="Popis" filled required></v-textarea>

                        <span class="d-flex justify-content-start pb-2">Časové obdobie</span>
                        <v-checkbox v-model="workThereToNow" @click="resetDate()" label="Momentálne tu pracujem" filled required></v-checkbox>

                        <v-col cols="12" md="6" lg="4" class="pl-0 pr-0" v-if="!workThereToNow">
                          <v-menu ref="menu" v-model="menu" :close-on-content-click="false" :return-value.sync="date" transition="scale-transition" offset-y min-width="auto">
                            <template v-slot:activator="{ on, attrs }">
                              <v-text-field v-model="date" label="Od - do" prepend-icon="mdi-calendar" readonly filled v-bind="attrs" v-on="on"></v-text-field>
                            </template>
                            <v-date-picker v-model="date" no-title scrollable range color="primary">
                              <v-spacer></v-spacer>
                              <v-btn text color="primary" @click="menu = false">
                                Cancel
                              </v-btn>
                              <v-btn text color="primary" @click="$refs.menu.save(date)">
                                OK
                              </v-btn>
                            </v-date-picker>
                          </v-menu>
                        </v-col>

                        <v-col cols="12" md="6" lg="4" class="pl-0 pr-0" v-else>
                          <v-menu ref="menu" v-model="menu" :close-on-content-click="false" :return-value.sync="date" transition="scale-transition" offset-y min-width="auto">
                            <template v-slot:activator="{ on, attrs }">
                              <v-text-field v-model="date" label="Od" prepend-icon="mdi-calendar" readonly filled v-bind="attrs" v-on="on"></v-text-field>
                            </template>
                            <v-date-picker v-model="date" no-title scrollable color="primary">
                              <v-spacer></v-spacer>
                              <v-btn text color="primary" @click="menu = false">
                                Cancel
                              </v-btn>
                              <v-btn text color="primary" @click="$refs.menu.save(date)">
                                OK
                              </v-btn>
                            </v-date-picker>
                          </v-menu>
                        </v-col>

                        <v-divider />
                        <div class="d-flex">
                          <v-dialog v-model="dialogShareLike" max-width="290">
                            <template v-slot:activator="{ on: dialog }">
                              <v-tooltip right>
                                <template v-slot:activator="{ on: tooltip }">
                                  <v-btn class="mr-auto" color="primary" icon v-on="{ ...tooltip, ...dialog }">
                                    <span v-if="shareLike == 0">
                                      <v-icon>mdi-earth</v-icon>
                                    </span>
                                    <span v-if="shareLike == 1">
                                      <v-icon>mdi-lock</v-icon>
                                    </span>
                                    <span v-if="shareLike == 2">
                                      <v-icon>mdi-account-supervisor</v-icon>
                                    </span>
                                  </v-btn>
                                </template>
                                <span v-if="shareLike == 0">
                                  Verejné
                                </span>
                                <span v-if="shareLike == 1">
                                  Iba ja
                                </span>
                                <span v-if="shareLike == 2">
                                  Iba priatelia
                                </span>
                              </v-tooltip>
                            </template>

                            <v-card>
                              <v-list dense>
                                <v-list-item-group color="primary" v-model="shareLike">
                                  <v-list-item>
                                    <v-icon class="mr-7">mdi-earth</v-icon>
                                    <v-list-item-content>
                                      <v-card class="p-2 rounded-lg card-comments" elevation="0">
                                        <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-3 pl-1">
                                          Verejné
                                        </v-card-title>
                                        <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">Túto informáciu môže vidieť každý</v-card-subtitle>
                                      </v-card>
                                    </v-list-item-content>
                                  </v-list-item>

                                  <v-list-item>
                                    <v-icon class="mr-7">mdi-lock</v-icon>
                                    <v-list-item-content>
                                      <v-card class="p-2 rounded-lg card-comments" elevation="0">
                                        <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-3 pl-1">
                                          Iba ja
                                        </v-card-title>
                                        <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">Túto informáciu uvidíte len vy</v-card-subtitle>
                                      </v-card>
                                    </v-list-item-content>
                                  </v-list-item>

                                  <v-list-item>
                                    <v-icon class="mr-7">mdi-account-supervisor</v-icon>
                                    <v-list-item-content>
                                      <v-card class="p-2 rounded-lg card-comments" elevation="0">
                                        <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-3 pl-1">
                                          Iba priatelia
                                        </v-card-title>
                                        <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">Túto informáciu môžu vidieť len vaší priatelia a vy</v-card-subtitle>
                                      </v-card>
                                    </v-list-item-content>
                                  </v-list-item>
                                </v-list-item-group>
                              </v-list>
                            </v-card>
                          </v-dialog>

                          <v-btn color="error" class="mr-4" @click="reset;showFormWork = false" rounded>
                            Zrušiť
                          </v-btn>

                          <v-btn color="primary" outlined rounded>
                            Uložiť
                          </v-btn>
                        </div>
                      </v-form>
                    </v-col>

                    <v-col cols="12" class="pl-0 pt-0 d-flex justify-content-start" v-else>
                      <v-list-item rounded class="p-0">
                        <v-card class="p-2 rounded-lg card-comments" elevation="0">
                          <v-icon class="mr-3">mdi-briefcase</v-icon>
                          <span class="caption grey--text">Žiadne informácie o práci</span>
                        </v-card>
                      </v-list-item>
                    </v-col>
                  </v-row>

                  <v-divider />

                  <v-row class="pl-3 d-flex justify-content-start">
                    <v-col cols="12" class="pl-0 pr-0 d-flex justify-content-start">
                      <span class="justify-content-start">Vysoká škola</span>
                    </v-col>

                    <v-col cols="12" class="pl-0 pr-0 d-flex justify-content-start" v-if="showFormWork2 == false && $root.profile.data.returnVariable == -1">
                      <v-btn rounded outlined color="primary" @click="showFormWork2 = true; showFormWork = false; showFormWork3 = false; showFormWork4 = false; shareLike = 0">
                        <v-icon class="mr-1">mdi-plus-circle</v-icon>
                        <span>Pridajte vysokú školu</span>
                      </v-btn>
                    </v-col>

                    <v-col cols="12" class="pl-0" v-else-if="showFormWork2 == true && $root.profile.data.returnVariable == -1">
                      <v-form ref="form" lazy-validation>
                        <v-text-field v-model="school" label="Názov školy" filled required></v-text-field>

                        <v-text-field v-model="school_division" label="Odbor" filled required></v-text-field>

                        <span class="d-flex justify-content-start pb-2">Časové obdobie</span>
                        <v-checkbox v-model="studyThereToNow" @click="resetDate()" label="Momentálne tu študujem" filled required></v-checkbox>

                        <v-col cols="12" sm="6" md="4" class="pl-0 pr-0" v-if="!studyThereToNow">
                          <v-menu ref="menu" v-model="menu2" :close-on-content-click="false" :return-value.sync="date2" transition="scale-transition" offset-y min-width="auto">
                            <template v-slot:activator="{ on, attrs }">
                              <v-text-field v-model="date2" label="Od - do" prepend-icon="mdi-calendar" readonly filled v-bind="attrs" v-on="on"></v-text-field>
                            </template>
                            <v-date-picker v-model="date2" no-title scrollable range color="primary">
                              <v-spacer></v-spacer>
                              <v-btn text color="primary" @click="menu2 = false">
                                Cancel
                              </v-btn>
                              <v-btn text color="primary" @click="$refs.menu.save(date2)">
                                OK
                              </v-btn>
                            </v-date-picker>
                          </v-menu>
                        </v-col>

                        <v-col cols="12" sm="6" md="4" class="pl-0 pr-0" v-else>
                          <v-menu ref="menu" v-model="menu2" :close-on-content-click="false" :return-value.sync="date2" transition="scale-transition" offset-y min-width="auto">
                            <template v-slot:activator="{ on, attrs }">
                              <v-text-field v-model="date2" label="Od" prepend-icon="mdi-calendar" readonly filled v-bind="attrs" v-on="on"></v-text-field>
                            </template>
                            <v-date-picker v-model="date2" no-title scrollable color="primary">
                              <v-spacer></v-spacer>
                              <v-btn text color="primary" @click="menu2 = false">
                                Cancel
                              </v-btn>
                              <v-btn text color="primary" @click="$refs.menu.save(date2)">
                                OK
                              </v-btn>
                            </v-date-picker>
                          </v-menu>
                        </v-col>

                        <v-divider />
                        <div class="d-flex">
                          <v-dialog v-model="dialogShareLike" max-width="290">
                            <template v-slot:activator="{ on: dialog }">
                              <v-tooltip right>
                                <template v-slot:activator="{ on: tooltip }">
                                  <v-btn class="mr-auto" color="primary" icon v-on="{ ...tooltip, ...dialog }">
                                    <span v-if="shareLike == 0">
                                      <v-icon>mdi-earth</v-icon>
                                    </span>
                                    <span v-if="shareLike == 1">
                                      <v-icon>mdi-lock</v-icon>
                                    </span>
                                    <span v-if="shareLike == 2">
                                      <v-icon>mdi-account-supervisor</v-icon>
                                    </span>
                                  </v-btn>
                                </template>
                                <span v-if="shareLike == 0">
                                  Verejné
                                </span>
                                <span v-if="shareLike == 1">
                                  Iba ja
                                </span>
                                <span v-if="shareLike == 2">
                                  Iba priatelia
                                </span>
                              </v-tooltip>
                            </template>

                            <v-card class="v-card-logout">
                              <v-list dense>
                                <v-list-item-group color="primary" v-model="shareLike">
                                  <v-list-item>
                                    <v-icon class="mr-7">mdi-earth</v-icon>
                                    <v-list-item-content>
                                      <v-card class="p-2 rounded-lg card-comments" elevation="0">
                                        <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-3 pl-1">
                                          Verejné
                                        </v-card-title>
                                        <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">Túto informáciu môže vidieť každý</v-card-subtitle>
                                      </v-card>
                                    </v-list-item-content>
                                  </v-list-item>

                                  <v-list-item>
                                    <v-icon class="mr-7">mdi-lock</v-icon>
                                    <v-list-item-content>
                                      <v-card class="p-2 rounded-lg card-comments" elevation="0">
                                        <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-3 pl-1">
                                          Iba ja
                                        </v-card-title>
                                        <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">Túto informáciu uvidíte len vy</v-card-subtitle>
                                      </v-card>
                                    </v-list-item-content>
                                  </v-list-item>

                                  <v-list-item>
                                    <v-icon class="mr-7">mdi-account-supervisor</v-icon>
                                    <v-list-item-content>
                                      <v-card class="p-2 rounded-lg card-comments" elevation="0">
                                        <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-3 pl-1">
                                          Iba priatelia
                                        </v-card-title>
                                        <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">Túto informáciu môžu vidieť len vaší priatelia a vy</v-card-subtitle>
                                      </v-card>
                                    </v-list-item-content>
                                  </v-list-item>
                                </v-list-item-group>
                              </v-list>
                            </v-card>
                          </v-dialog>

                          <v-btn color="error" class="mr-4" @click="reset;showFormWork2 = false" rounded>
                            Zrušiť
                          </v-btn>

                          <v-btn color="primary" outlined rounded>
                            Uložiť
                          </v-btn>
                        </div>
                      </v-form>
                    </v-col>

                    <v-col cols="12" class="pl-0 pt-0 d-flex justify-content-start" v-else>
                      <v-list-item rounded class="p-0">
                        <v-card class="p-2 rounded-lg card-comments" elevation="0">
                          <v-icon class="mr-3">mdi-school</v-icon>
                          <span class="caption grey--text">Žiadne informácie o vysokej škole</span>
                        </v-card>
                      </v-list-item>
                    </v-col>
                  </v-row>

                  <v-divider />

                  <v-row class="pl-3 d-flex justify-content-start">
                    <v-col cols="12" class="pl-0 pr-0 d-flex justify-content-start">
                      <span class="justify-content-start">Stredná škola</span>
                    </v-col>

                    <v-col cols="12" class="pl-0 pr-0 d-flex justify-content-start" v-if="showFormWork3 == false && $root.profile.data.returnVariable == -1">
                      <v-btn rounded outlined color="primary" @click="showFormWork3 = true; showFormWork = false; showFormWork2 = false; showFormWork4 = false; shareLike = 0">
                        <v-icon class="mr-1">mdi-plus-circle</v-icon>
                        <span>Pridajte strednú školu</span>
                      </v-btn>
                    </v-col>

                    <v-col cols="12" class="pl-0" v-else-if="showFormWork3 == true && $root.profile.data.returnVariable == -1">
                      <v-form ref="form" lazy-validation>
                        <v-text-field v-model="school" label="Názov školy" filled required></v-text-field>

                        <v-text-field v-model="school_division" label="Odbor" filled required></v-text-field>

                        <span class="d-flex justify-content-start pb-2">Časové obdobie</span>
                        <v-checkbox v-model="studyThereToNow" @click="resetDate()" label="Momentálne tu študujem" filled required></v-checkbox>

                        <v-col cols="12" sm="6" md="4" class="pl-0 pr-0" v-if="!studyThereToNow">
                          <v-menu ref="menu" v-model="menu3" :close-on-content-click="false" :return-value.sync="date3" transition="scale-transition" offset-y min-width="auto">
                            <template v-slot:activator="{ on, attrs }">
                              <v-text-field v-model="date3" label="Od - do" prepend-icon="mdi-calendar" readonly filled v-bind="attrs" v-on="on"></v-text-field>
                            </template>
                            <v-date-picker v-model="date3" no-title scrollable range color="primary">
                              <v-spacer></v-spacer>
                              <v-btn text color="primary" @click="menu3 = false">
                                Cancel
                              </v-btn>
                              <v-btn text color="primary" @click="$refs.menu.save(date3)">
                                OK
                              </v-btn>
                            </v-date-picker>
                          </v-menu>
                        </v-col>

                        <v-col cols="12" sm="6" md="4" class="pl-0 pr-0" v-else>
                          <v-menu ref="menu" v-model="menu3" :close-on-content-click="false" :return-value.sync="date3" transition="scale-transition" offset-y min-width="auto">
                            <template v-slot:activator="{ on, attrs }">
                              <v-text-field v-model="date3" label="Od" prepend-icon="mdi-calendar" readonly filled v-bind="attrs" v-on="on"></v-text-field>
                            </template>
                            <v-date-picker v-model="date3" no-title scrollable color="primary">
                              <v-spacer></v-spacer>
                              <v-btn text color="primary" @click="menu3 = false">
                                Cancel
                              </v-btn>
                              <v-btn text color="primary" @click="$refs.menu.save(date3)">
                                OK
                              </v-btn>
                            </v-date-picker>
                          </v-menu>
                        </v-col>

                        <v-divider />
                        <div class="d-flex">
                          <v-dialog v-model="dialogShareLike" max-width="290">
                            <template v-slot:activator="{ on: dialog }">
                              <v-tooltip right>
                                <template v-slot:activator="{ on: tooltip }">
                                  <v-btn class="mr-auto" color="primary" icon v-on="{ ...tooltip, ...dialog }">
                                    <span v-if="shareLike == 0">
                                      <v-icon>mdi-earth</v-icon>
                                    </span>
                                    <span v-if="shareLike == 1">
                                      <v-icon>mdi-lock</v-icon>
                                    </span>
                                    <span v-if="shareLike == 2">
                                      <v-icon>mdi-account-supervisor</v-icon>
                                    </span>
                                  </v-btn>
                                </template>
                                <span v-if="shareLike == 0">
                                  Verejné
                                </span>
                                <span v-if="shareLike == 1">
                                  Iba ja
                                </span>
                                <span v-if="shareLike == 2">
                                  Iba priatelia
                                </span>
                              </v-tooltip>
                            </template>

                            <v-card class="v-card-logout">
                              <v-list dense>
                                <v-list-item-group color="primary" v-model="shareLike">
                                  <v-list-item>
                                    <v-icon class="mr-7">mdi-earth</v-icon>
                                    <v-list-item-content>
                                      <v-card class="p-2 rounded-lg card-comments" elevation="0">
                                        <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-3 pl-1">
                                          Verejné
                                        </v-card-title>
                                        <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">Túto informáciu môže vidieť každý</v-card-subtitle>
                                      </v-card>
                                    </v-list-item-content>
                                  </v-list-item>

                                  <v-list-item>
                                    <v-icon class="mr-7">mdi-lock</v-icon>
                                    <v-list-item-content>
                                      <v-card class="p-2 rounded-lg card-comments" elevation="0">
                                        <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-3 pl-1">
                                          Iba ja
                                        </v-card-title>
                                        <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">Túto informáciu uvidíte len vy</v-card-subtitle>
                                      </v-card>
                                    </v-list-item-content>
                                  </v-list-item>

                                  <v-list-item>
                                    <v-icon class="mr-7">mdi-account-supervisor</v-icon>
                                    <v-list-item-content>
                                      <v-card class="p-2 rounded-lg card-comments" elevation="0">
                                        <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-3 pl-1">
                                          Iba priatelia
                                        </v-card-title>
                                        <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">Túto informáciu môžu vidieť len vaší priatelia a vy</v-card-subtitle>
                                      </v-card>
                                    </v-list-item-content>
                                  </v-list-item>
                                </v-list-item-group>
                              </v-list>
                            </v-card>
                          </v-dialog>

                          <v-btn color="error" class="mr-4" @click="reset;showFormWork3 = false" rounded>
                            Zrušiť
                          </v-btn>

                          <v-btn color="primary" outlined rounded>
                            Uložiť
                          </v-btn>
                        </div>
                      </v-form>
                    </v-col>

                    <v-col cols="12" class="pl-0 pt-0 d-flex justify-content-start" v-else>
                      <v-list-item rounded class="p-0">
                        <v-card class="p-2 rounded-lg card-comments" elevation="0">
                          <v-icon class="mr-3">mdi-school</v-icon>
                          <span class="caption grey--text">Žiadne informácie o strednej škole</span>
                        </v-card>
                      </v-list-item>
                    </v-col>
                  </v-row>

                  <v-divider />

                  <v-row class="pl-3 d-flex justify-content-start">
                    <v-col cols="12" class="pl-0 pr-0 d-flex justify-content-start">
                      <span class="justify-content-start">Základná škola</span>
                    </v-col>

                    <v-col cols="12" class="pl-0 pr-0 d-flex justify-content-start" v-if="showFormWork4 == false && $root.profile.data.returnVariable == -1">
                      <v-btn rounded outlined color="primary" @click="showFormWork4 = true; showFormWork = false; showFormWork2 = false; showFormWork3 = false; shareLike = 0">
                        <v-icon class="mr-1">mdi-plus-circle</v-icon>
                        <span>Pridajte základnú školu</span>
                      </v-btn>
                    </v-col>

                    <v-col cols="12" class="pl-0" v-else-if="showFormWork4 == true && $root.profile.data.returnVariable == -1">
                      <v-form ref="form" lazy-validation>
                        <v-text-field v-model="school" label="Názov školy" filled required></v-text-field>

                        <v-text-field v-model="school_division" label="Odbor" filled required></v-text-field>

                        <span class="d-flex justify-content-start pb-2">Časové obdobie</span>
                        <v-checkbox v-model="studyThereToNow" @click="resetDate()" label="Momentálne tu študujem" filled required></v-checkbox>

                        <v-col cols="12" sm="6" md="4" class="pl-0 pr-0" v-if="!studyThereToNow">
                          <v-menu ref="menu" v-model="menu4" :close-on-content-click="false" :return-value.sync="date4" transition="scale-transition" offset-y min-width="auto">
                            <template v-slot:activator="{ on, attrs }">
                              <v-text-field v-model="date4" label="Od - do" prepend-icon="mdi-calendar" readonly filled v-bind="attrs" v-on="on"></v-text-field>
                            </template>
                            <v-date-picker v-model="date4" no-title scrollable range color="primary">
                              <v-spacer></v-spacer>
                              <v-btn text color="primary" @click="menu4 = false">
                                Cancel
                              </v-btn>
                              <v-btn text color="primary" @click="$refs.menu.save(date4)">
                                OK
                              </v-btn>
                            </v-date-picker>
                          </v-menu>
                        </v-col>

                        <v-col cols="12" sm="6" md="4" class="pl-0 pr-0" v-else>
                          <v-menu ref="menu" v-model="menu4" :close-on-content-click="false" :return-value.sync="date4" transition="scale-transition" offset-y min-width="auto">
                            <template v-slot:activator="{ on, attrs }">
                              <v-text-field v-model="date4" label="Od" prepend-icon="mdi-calendar" readonly filled v-bind="attrs" v-on="on"></v-text-field>
                            </template>
                            <v-date-picker v-model="date4" no-title scrollable color="primary">
                              <v-spacer></v-spacer>
                              <v-btn text color="primary" @click="menu4 = false">
                                Cancel
                              </v-btn>
                              <v-btn text color="primary" @click="$refs.menu.save(date4)">
                                OK
                              </v-btn>
                            </v-date-picker>
                          </v-menu>
                        </v-col>

                        <v-divider />
                        <div class="d-flex">
                          <v-dialog v-model="dialogShareLike" max-width="290">
                            <template v-slot:activator="{ on: dialog }">
                              <v-tooltip right>
                                <template v-slot:activator="{ on: tooltip }">
                                  <v-btn class="mr-auto" color="primary" icon v-on="{ ...tooltip, ...dialog }">
                                    <span v-if="shareLike == 0">
                                      <v-icon>mdi-earth</v-icon>
                                    </span>
                                    <span v-if="shareLike == 1">
                                      <v-icon>mdi-lock</v-icon>
                                    </span>
                                    <span v-if="shareLike == 2">
                                      <v-icon>mdi-account-supervisor</v-icon>
                                    </span>
                                  </v-btn>
                                </template>
                                <span v-if="shareLike == 0">
                                  Verejné
                                </span>
                                <span v-if="shareLike == 1">
                                  Iba ja
                                </span>
                                <span v-if="shareLike == 2">
                                  Iba priatelia
                                </span>
                              </v-tooltip>
                            </template>

                            <v-card class="v-card-logout">
                              <v-list dense>
                                <v-list-item-group color="primary" v-model="shareLike">
                                  <v-list-item>
                                    <v-icon class="mr-7">mdi-earth</v-icon>
                                    <v-list-item-content>
                                      <v-card class="p-2 rounded-lg card-comments" elevation="0">
                                        <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-3 pl-1">
                                          Verejné
                                        </v-card-title>
                                        <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">Túto informáciu môže vidieť každý</v-card-subtitle>
                                      </v-card>
                                    </v-list-item-content>
                                  </v-list-item>

                                  <v-list-item>
                                    <v-icon class="mr-7">mdi-lock</v-icon>
                                    <v-list-item-content>
                                      <v-card class="p-2 rounded-lg card-comments" elevation="0">
                                        <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-3 pl-1">
                                          Iba ja
                                        </v-card-title>
                                        <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">Túto informáciu uvidíte len vy</v-card-subtitle>
                                      </v-card>
                                    </v-list-item-content>
                                  </v-list-item>

                                  <v-list-item>
                                    <v-icon class="mr-7">mdi-account-supervisor</v-icon>
                                    <v-list-item-content>
                                      <v-card class="p-2 rounded-lg card-comments" elevation="0">
                                        <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-3 pl-1">
                                          Iba priatelia
                                        </v-card-title>
                                        <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">Túto informáciu môžu vidieť len vaší priatelia a vy</v-card-subtitle>
                                      </v-card>
                                    </v-list-item-content>
                                  </v-list-item>
                                </v-list-item-group>
                              </v-list>
                            </v-card>
                          </v-dialog>

                          <v-btn color="error" class="mr-4" @click="reset;showFormWork4 = false" rounded>
                            Zrušiť
                          </v-btn>

                          <v-btn color="primary" outlined rounded>
                            Uložiť
                          </v-btn>
                        </div>
                      </v-form>
                    </v-col>

                    <v-col cols="12" class="pl-0 pt-0 d-flex justify-content-start" v-else>
                      <v-list-item rounded class="p-0">
                        <v-card class="p-2 rounded-lg card-comments" elevation="0">
                          <v-icon class="mr-3">mdi-school</v-icon>
                          <span class="caption grey--text">Žiadne informácie o základnej škole</span>
                        </v-card>
                      </v-list-item>
                    </v-col>
                  </v-row>
                </v-card-text>
              </v-card>
            </v-tab-item>

            <!-- /////////////////////////////////////////////////////////////////////////////////////////////////////// residence -->
            <v-tab-item>
              <v-card flat min-height="336">
                <v-card-text>
                  <v-row class="pl-3 d-flex justify-content-start">
                    <v-col cols="12" class="pl-0 pr-0 d-flex justify-content-start">
                      <span class="justify-content-start">Bydliská</span>
                    </v-col>

                    <v-col cols="12" class="pl-0 pr-0 d-flex justify-content-start" v-if="showResidences == false && $root.profile.data.returnVariable == -1">
                      <v-btn rounded outlined color="primary" @click="showResidences = true; shareLike = 0">
                        <v-icon class="mr-1">mdi-plus-circle</v-icon>
                        <span>Pridajte mesto</span>
                      </v-btn>
                    </v-col>

                    <v-col cols="12" class="pl-0" v-else-if="showResidences == true && $root.profile.data.returnVariable == -1">
                      <div class="pb-3" v-if="hometown">
                        <v-btn color="primary" class="mr-auto" rounded @click="hometown = true">
                          Rodné mesto
                        </v-btn>
                        <v-btn color="primary" class="mr-auto ml-3" rounded outlined @click="hometown = false">
                          Terajšie bydlisko
                        </v-btn>
                      </div>

                      <div class="pb-3" v-else>
                        <v-btn color="primary" class="mr-auto" rounded outlined @click="hometown = true">
                          Rodné mesto
                        </v-btn>
                        <v-btn color="primary" class="mr-auto ml-3" rounded @click="hometown = false">
                          Terajšie bydlisko
                        </v-btn>
                      </div>

                      <v-form ref="form" lazy-validation>
                        <v-text-field v-model="town" label="Názov mesta" filled required></v-text-field>
                        <span class="d-flex justify-content-start pb-2" v-if="!hometown">Dátum presťahovania</span>

                        <v-col cols="12" sm="6" md="4" class="pl-0 pr-0" v-if="!hometown">
                          <v-menu ref="menu" v-model="menu5" :close-on-content-click="false" :return-value.sync="date5" transition="scale-transition" offset-y min-width="auto">
                            <template v-slot:activator="{ on, attrs }">
                              <v-text-field v-model="date5" label="Od" prepend-icon="mdi-calendar" readonly filled v-bind="attrs" v-on="on"></v-text-field>
                            </template>
                            <v-date-picker v-model="date5" no-title scrollable color="primary">
                              <v-spacer></v-spacer>
                              <v-btn text color="primary" @click="menu5 = false">
                                Cancel
                              </v-btn>
                              <v-btn text color="primary" @click="$refs.menu.save(date5)">
                                OK
                              </v-btn>
                            </v-date-picker>
                          </v-menu>
                        </v-col>

                        <v-divider />
                        <div class="d-flex">
                          <v-dialog v-model="dialogShareLike" max-width="290">
                            <template v-slot:activator="{ on: dialog }">
                              <v-tooltip right>
                                <template v-slot:activator="{ on: tooltip }">
                                  <v-btn class="mr-auto" color="primary" icon v-on="{ ...tooltip, ...dialog }">
                                    <span v-if="shareLike == 0">
                                      <v-icon>mdi-earth</v-icon>
                                    </span>
                                    <span v-if="shareLike == 1">
                                      <v-icon>mdi-lock</v-icon>
                                    </span>
                                    <span v-if="shareLike == 2">
                                      <v-icon>mdi-account-supervisor</v-icon>
                                    </span>
                                  </v-btn>
                                </template>
                                <span v-if="shareLike == 0">
                                  Verejné
                                </span>
                                <span v-if="shareLike == 1">
                                  Iba ja
                                </span>
                                <span v-if="shareLike == 2">
                                  Iba priatelia
                                </span>
                              </v-tooltip>
                            </template>

                            <v-card class="v-card-logout">
                              <v-list dense>
                                <v-list-item-group color="primary" v-model="shareLike">
                                  <v-list-item>
                                    <v-icon class="mr-7">mdi-earth</v-icon>
                                    <v-list-item-content>
                                      <v-card class="p-2 rounded-lg card-comments" elevation="0">
                                        <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-3 pl-1">
                                          Verejné
                                        </v-card-title>
                                        <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">Túto informáciu môže vidieť každý</v-card-subtitle>
                                      </v-card>
                                    </v-list-item-content>
                                  </v-list-item>

                                  <v-list-item>
                                    <v-icon class="mr-7">mdi-lock</v-icon>
                                    <v-list-item-content>
                                      <v-card class="p-2 rounded-lg card-comments" elevation="0">
                                        <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-3 pl-1">
                                          Iba ja
                                        </v-card-title>
                                        <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">Túto informáciu uvidíte len vy</v-card-subtitle>
                                      </v-card>
                                    </v-list-item-content>
                                  </v-list-item>

                                  <v-list-item>
                                    <v-icon class="mr-7">mdi-account-supervisor</v-icon>
                                    <v-list-item-content>
                                      <v-card class="p-2 rounded-lg card-comments" elevation="0">
                                        <v-card-title class="d-flex justify-start text-subtitle-2 font-weight-bold p-0 pb-3 pl-1">
                                          Iba priatelia
                                        </v-card-title>
                                        <v-card-subtitle class="d-flex text-left caption grey--text p-0 pl-1">Túto informáciu môžu vidieť len vaší priatelia a vy</v-card-subtitle>
                                      </v-card>
                                    </v-list-item-content>
                                  </v-list-item>
                                </v-list-item-group>
                              </v-list>
                            </v-card>
                          </v-dialog>

                          <v-btn color="error" class="mr-4" @click="reset;showResidences = false" rounded>
                            Zrušiť
                          </v-btn>

                          <v-btn color="primary" outlined rounded>
                            Uložiť
                          </v-btn>
                        </div>
                      </v-form>
                    </v-col>

                    <v-col cols="12" class="pl-0 pt-0 d-flex justify-content-start" v-else>
                      <v-list-item rounded class="p-0">
                        <v-card class="p-2 rounded-lg card-comments" elevation="0">
                          <v-icon class="mr-3">mdi-home</v-icon>
                          <span class="caption grey--text">Žiadne informácie o bydlisku a rodnom meste</span>
                        </v-card>
                      </v-list-item>
                    </v-col>
                  </v-row>
                </v-card-text>
              </v-card>
            </v-tab-item>

            <!-- /////////////////////////////////////////////////////////////////////////////////////////////////////// contact informations -->
            <v-tab-item>
              <v-card flat min-height="336">
                <v-card-text>
                  <v-row class="pl-3 d-flex justify-content-start">
                    <!-- contact informations -->
                    <v-col cols="12" class="pl-0 pr-0 d-flex justify-content-start">
                      <span class="justify-content-start">Kontaktné údaje</span>
                    </v-col>

                    <v-col cols="12" class="pl-0 pr-0 d-flex justify-content-start" v-if="showContactAndBasicInfo == false && $root.profile.data.returnVariable == -1">
                      <v-btn rounded outlined color="primary" @click="showContactAndBasicInfo = true">
                        <v-icon class="mr-1">mdi-plus-circle</v-icon>
                        <span>Pridajte svoju adresu</span>
                      </v-btn>
                    </v-col>

                    <v-col cols="12" class="pl-0 pr-0 d-flex justify-content-start" v-if="showContactAndBasicInfo2 == false && $root.profile.data.returnVariable == -1">
                      <v-btn rounded outlined color="primary" @click="showContactAndBasicInfo2 = true">
                        <v-icon class="mr-1">mdi-plus-circle</v-icon>
                        <span>Pridajte svoje číslo</span>
                      </v-btn>
                    </v-col>

                    <v-col cols="12" class="pl-0 pr-0 d-flex justify-content-start" v-if="showContactAndBasicInfo3 == false && $root.profile.data.returnVariable == -1">
                      <v-btn rounded outlined color="primary" @click="showContactAndBasicInfo3 = true">
                        <v-icon class="mr-1">mdi-plus-circle</v-icon>
                        <span>Pridajte svoj email</span>
                      </v-btn>
                    </v-col>

                    <v-col cols="12" class="pl-0 pt-0 d-flex justify-content-start">
                      <v-list-item rounded class="p-0">
                        <v-card class="p-2 rounded-lg card-comments" elevation="0">
                          <v-icon class="mr-3">mdi-phone</v-icon>
                          <span class="caption grey--text">Žiadne kontaktné informácie</span>
                        </v-card>
                      </v-list-item>
                    </v-col>
                  </v-row>

                  <v-divider />

                  <v-row class="pl-3 d-flex justify-content-start">
                    <!-- webs -->
                    <v-col cols="12" class="pl-0 pr-0 d-flex justify-content-start">
                      <span class="justify-content-start">Weby</span>
                    </v-col>

                    <v-col cols="12" class="pl-0 pr-0 d-flex justify-content-start" v-if="showContactAndBasicInfo4 == false && $root.profile.data.returnVariable == -1">
                      <v-btn rounded outlined color="primary" @click="showContactAndBasicInfo4 = true">
                        <v-icon class="mr-1">mdi-plus-circle</v-icon>
                        <span>Pridajte svoj web</span>
                      </v-btn>
                    </v-col>

                    <v-col cols="12" class="pl-0 pt-0 d-flex justify-content-start">
                      <v-list-item rounded class="p-0">
                        <v-card class="p-2 rounded-lg card-comments" elevation="0">
                          <v-icon class="mr-3">mdi-web</v-icon>
                          <span class="caption grey--text">Žiadne weby</span>
                        </v-card>
                      </v-list-item>
                    </v-col>
                  </v-row>

                  <v-divider />
                  <v-row class="pl-3 d-flex justify-content-start">
                    <!-- basic informations -->
                    <v-col cols="12" class="pl-0 pr-0 d-flex justify-content-start">
                      <span class="justify-content-start">Základné informácie</span>
                    </v-col>

                    <v-col cols="12" class="pl-0 pr-0 d-flex justify-content-start" v-if="showContactAndBasicInfo5 == false && $root.profile.data.returnVariable == -1">
                      <v-btn rounded outlined color="primary" @click="showContactAndBasicInfo5 = true">
                        <v-icon class="mr-1">mdi-plus-circle</v-icon>
                        <span>Pridajte pohlavie</span>
                      </v-btn>
                    </v-col>

                    <v-col cols="12" class="pl-0 pr-0 d-flex justify-content-start" v-if="showContactAndBasicInfo6 == false && $root.profile.data.returnVariable == -1">
                      <v-btn rounded outlined color="primary" @click="showContactAndBasicInfo6 = true">
                        <v-icon class="mr-1">mdi-plus-circle</v-icon>
                        <span>Pridajte dátum narodenia</span>
                      </v-btn>
                    </v-col>

                    <v-col cols="12" class="pl-0 pr-0 d-flex justify-content-start" v-if="showContactAndBasicInfo7 == false && $root.profile.data.returnVariable == -1">
                      <v-btn rounded outlined color="primary" @click="showContactAndBasicInfo7 = true">
                        <v-icon class="mr-1">mdi-plus-circle</v-icon>
                        <span>Pridajte jazyk</span>
                      </v-btn>
                    </v-col>

                    <v-col cols="12" class="pl-0 pt-0 d-flex justify-content-start">
                      <v-list-item rounded class="p-0">
                        <v-card class="p-2 rounded-lg card-comments" elevation="0">
                          <v-icon class="mr-3">mdi-card-account-details</v-icon>
                          <span class="caption grey--text">Žiadne základné informácie</span>
                        </v-card>
                      </v-list-item>
                    </v-col>
                  </v-row>
                </v-card-text>
              </v-card>
            </v-tab-item>

            <!-- /////////////////////////////////////////////////////////////////////////////////////////////////////// family and relationships -->
            <v-tab-item>
              <v-card flat min-height="336">
                <v-card-text>
                  <!-- relationship -->
                  <v-row class="pl-3 d-flex justify-content-start">
                    <v-col cols="12" class="pl-0 pr-0 d-flex justify-content-start">
                      <span class="justify-content-start">Vzťah</span>
                    </v-col>

                    <v-col cols="12" class="pl-0 pr-0 d-flex justify-content-start" v-if="showFamilyAndRelationshipsInfo == false && $root.profile.data.returnVariable == -1">
                      <v-btn rounded outlined color="primary" @click="showFamilyAndRelationshipsInfo = true">
                        <v-icon class="mr-1">mdi-plus-circle</v-icon>
                        <span>Pridajte vzťah</span>
                      </v-btn>
                    </v-col>

                    <v-col cols="12" class="pl-0 pt-0 d-flex justify-content-start">
                      <v-list-item rounded class="p-0">
                        <v-card class="p-2 rounded-lg card-comments" elevation="0">
                          <v-icon class="mr-3">mdi-heart</v-icon>
                          <span class="caption grey--text">Žiadne informácie o vzťahu</span>
                        </v-card>
                      </v-list-item>
                    </v-col>
                  </v-row>

                  <v-divider />

                  <!-- family members -->
                  <v-row class="pl-3 d-flex justify-content-start">
                    <v-col cols="12" class="pl-0 pr-0 d-flex justify-content-start">
                      <span class="justify-content-start">Členovia rodiny</span>
                    </v-col>

                    <v-col cols="12" class="pl-0 pr-0 d-flex justify-content-start" v-if="showFamilyAndRelationshipsInfo2 == false && $root.profile.data.returnVariable == -1">
                      <v-btn rounded outlined color="primary" @click="showFamilyAndRelationshipsInfo2 = true">
                        <v-icon class="mr-1">mdi-plus-circle</v-icon>
                        <span>Pridajte člena rodiny</span>
                      </v-btn>
                    </v-col>

                    <v-col cols="12" class="pl-0 pt-0 d-flex justify-content-start" v-else>
                      <v-list-item rounded class="p-0">
                        <v-card class="p-2 rounded-lg card-comments" elevation="0">
                          <v-icon class="mr-3">mdi-family-tree</v-icon>
                          <span class="caption grey--text">Žiadne informácie o členoch rodiny</span>
                        </v-card>
                      </v-list-item>
                    </v-col>
                  </v-row>
                </v-card-text>
              </v-card>
            </v-tab-item>

            <!-- /////////////////////////////////////////////////////////////////////////////////////////////////////// information about you -->
            <v-tab-item>
              <v-card flat min-height="336">
                <v-card-text>
                  <!-- information about you -->
                  <v-row class="pl-3 d-flex justify-content-start">
                    <v-col cols="12" class="pl-0 pr-0 d-flex justify-content-start">
                      <span class="justify-content-start">O vás</span>
                    </v-col>

                    <v-col cols="12" class="pl-0 pr-0 d-flex justify-content-start" v-if="showInfoAboutYou == false && $root.profile.data.returnVariable == -1">
                      <v-btn rounded outlined color="primary" @click="showInfoAboutYou = true">
                        <v-icon class="mr-1">mdi-plus-circle</v-icon>
                        <span>Napíšte nejaké podrobnosti o sebe</span>
                      </v-btn>
                    </v-col>

                    <v-col cols="12" class="pl-0 pt-0 d-flex justify-content-start">
                      <v-list-item rounded class="p-0">
                        <v-card class="p-2 rounded-lg card-comments" elevation="0">
                          <v-icon class="mr-3">mdi-account-details</v-icon>
                          <span class="caption grey--text">Žiadne podrobnosti o používateľovi</span>
                        </v-card>
                      </v-list-item>
                    </v-col>
                  </v-row>

                  <v-divider />

                  <v-row class="pl-3 d-flex justify-content-start">
                    <v-col cols="12" class="pl-0 pr-0 d-flex justify-content-start">
                      <span class="justify-content-start">Ďalšie mená</span>
                    </v-col>

                    <v-col cols="12" class="pl-0 pr-0 d-flex justify-content-start" v-if="showInfoAboutYou2 == false && $root.profile.data.returnVariable == -1">
                      <v-btn rounded outlined color="primary" @click="showInfoAboutYou2 = true">
                        <v-icon class="mr-1">mdi-plus-circle</v-icon>
                        <span>Pridajte prezývku, meno za slobodna...</span>
                      </v-btn>
                    </v-col>

                    <v-col cols="12" class="pl-0 pt-0 d-flex justify-content-start">
                      <v-list-item rounded class="p-0">
                        <v-card class="p-2 rounded-lg card-comments" elevation="0">
                          <v-icon class="mr-3">mdi-text-account</v-icon>
                          <span class="caption grey--text">Žiadne mená/prezývky používateľa</span>
                        </v-card>
                      </v-list-item>
                    </v-col>
                  </v-row>
                </v-card-text>
              </v-card>
            </v-tab-item>

            <v-tab-item>
              <v-card flat min-height="336">
                <v-card-text>
                  <v-row class="pl-3 d-flex justify-content-start">
                    <v-col cols="12" class="pl-0 pr-0 d-flex justify-content-start">
                      <span class="justify-content-start">Životné udalosti</span>
                    </v-col>

                    <v-col cols="12" class="pl-0 pr-0 d-flex justify-content-start" v-if="showLifeEvents == false && $root.profile.data.returnVariable == -1">
                      <v-btn rounded outlined color="primary" @click="showLifeEvents = true">
                        <v-icon class="mr-1">mdi-plus-circle</v-icon>
                        <span>Pridajte životnú udalosť</span>
                      </v-btn>
                    </v-col>

                    <v-col cols="12" class="pl-0 pt-0 d-flex justify-content-start">
                      <v-list-item rounded class="p-0">
                        <v-card class="p-2 rounded-lg card-comments" elevation="0">
                          <v-icon class="mr-3">mdi-calendar</v-icon>
                          <span class="caption grey--text">Žiadne informácie o udalostiach</span>
                        </v-card>
                      </v-list-item>
                    </v-col>
                  </v-row>
                </v-card-text>
              </v-card>
            </v-tab-item>
          </v-tabs>
        </v-card>
      </v-row>

      <!-- /////////////////////////////////////////////////////////////////////////////////////////////////////// photos -->
      <v-row class="ml-0 mr-0 mb-3">
        <v-card class="w-100 rounded" elevation="0" min-height="245">
          <v-toolbar elevation="0" dense>
            <v-tabs v-model="tabPhotos">
              <v-tab>
                Fotky, na ktorých ste
              </v-tab>
              <v-tab>
                Vaše fotky
              </v-tab>
              <v-tab>
                Albumy
              </v-tab>
            </v-tabs>
          </v-toolbar>

          <v-divider v-if="!$vuetify.theme.dark" class="mt-0 mb-0" />

          <v-tabs-items v-model="tabPhotos">
            <v-tab-item>
              <v-card flat min-height="200">
                <v-list-item class="justify-center p-3" disabled>
                  <v-list-item-content class="pb-0">
                    <v-card class="rounded-lg card-comments" elevation="0">
                      <v-btn color="primary" icon fab>
                        <span>Žiadne výsledky</span>
                      </v-btn>
                    </v-card>
                  </v-list-item-content>
                </v-list-item>
              </v-card>
            </v-tab-item>
            <v-tab-item>
              <v-card flat min-height="200">
                <v-list-item class="justify-center p-3" disabled>
                  <v-list-item-content class="pb-0">
                    <v-card class="rounded-lg card-comments" elevation="0">
                      <v-btn color="primary" icon fab>
                        <span>Žiadne výsledky</span>
                      </v-btn>
                    </v-card>
                  </v-list-item-content>
                </v-list-item>
              </v-card>
            </v-tab-item>
            <v-tab-item>
              <v-card flat min-height="200">
                <v-list-item class="justify-center p-3" disabled>
                  <v-list-item-content class="pb-0">
                    <v-card class="rounded-lg card-comments" elevation="0">
                      <v-btn color="primary" icon fab>
                        <span>Žiadne výsledky</span>
                      </v-btn>
                    </v-card>
                  </v-list-item-content>
                </v-list-item>
              </v-card>
            </v-tab-item>
          </v-tabs-items>
        </v-card>
      </v-row>

      <!-- /////////////////////////////////////////////////////////////////////////////////////////////////////// videos -->
      <v-row class="ml-0 mr-0 mb-3">
        <v-card class="w-100 rounded" elevation="0" min-height="245">
          <v-toolbar elevation="0" dense>
            <v-tabs v-model="tabVideos">
              <v-tab>
                Videá, na ktorých ste
              </v-tab>
              <v-tab>
                Vaše videá
              </v-tab>
            </v-tabs>
          </v-toolbar>

          <v-divider v-if="!$vuetify.theme.dark" class="mt-0 mb-0" />

          <v-tabs-items v-model="tabVideos">
            <v-tab-item>
              <v-card flat min-height="200">
                <v-list-item class="justify-center p-3" disabled>
                  <v-list-item-content class="pb-0">
                    <v-card class="rounded-lg card-comments" elevation="0">
                      <v-btn color="primary" icon fab>
                        <span>Žiadne výsledky</span>
                      </v-btn>
                    </v-card>
                  </v-list-item-content>
                </v-list-item>
              </v-card>
            </v-tab-item>
            <v-tab-item>
              <v-card flat min-height="200">
                <v-list-item class="justify-center p-3" disabled>
                  <v-list-item-content class="pb-0">
                    <v-card class="rounded-lg card-comments" elevation="0">
                      <v-btn color="primary" icon fab>
                        <span>Žiadne výsledky</span>
                      </v-btn>
                    </v-card>
                  </v-list-item-content>
                </v-list-item>
              </v-card>
            </v-tab-item>
            <v-tab-item>
              <v-card flat min-height="200">
                <v-list-item class="justify-center p-3" disabled>
                  <v-list-item-content class="pb-0">
                    <v-card class="rounded-lg card-comments" elevation="0">
                      <v-btn color="primary" icon fab>
                        <span>Žiadne výsledky</span>
                      </v-btn>
                    </v-card>
                  </v-list-item-content>
                </v-list-item>
              </v-card>
            </v-tab-item>
          </v-tabs-items>
        </v-card>
      </v-row>
    </v-col>
  </v-row>
</v-container>
</template>

<script>
// import axios from 'axios';
// import _ from 'lodash';
export default {
  name: "About",
  props: [],
  components: {},
  data() {
    return {
      tabPhotos: null,
      tabVideos: null,
      //work and education
      showFormWork: false,
      showFormWork2: false,
      showFormWork3: false,
      showFormWork4: false,
      showResidences: false,
      showContactAndBasicInfo: false,
      showContactAndBasicInfo2: false,
      showContactAndBasicInfo3: false,
      showContactAndBasicInfo4: false,
      showContactAndBasicInfo5: false,
      showContactAndBasicInfo6: false,
      showContactAndBasicInfo7: false,
      showFamilyAndRelationshipsInfo: false,
      showFamilyAndRelationshipsInfo2: false,
      showInfoAboutYou: false,
      showInfoAboutYou2: false,
      showLifeEvents: false,

      company: '',
      job_function: '',
      town_village: '',
      work_description: '',
      select: null,
      workThereToNow: false,
      date: null,
      menu: false,
      vertical: true,

      school: '',
      school_division: '',
      studyThereToNow: false,
      date2: null,
      menu2: false,

      date3: null,
      menu3: false,

      date4: null,
      menu4: false,

      date5: null,
      menu5: false,

      town: null,
      hometown: true,

      shareLike: 0,
      dialogShareLike: false,
    }
  },

  updated() {
    this.changeTypeOfTabs();
  },

  methods: {
    reset() {
      this.$refs.form.reset()
    },

    resetDate() {
      this.date = null;
      this.date2 = null;
    },

    changeTypeOfTabs() {
      window.addEventListener('resize', () => {
        if (window.innerWidth < 768) {
          this.vertical = false;
          if (document.getElementById('tabs').classList.contains('vertical-tabs-border')) {
            document.getElementById('tabs').classList.remove('vertical-tabs-border');
          }
        } else {
          this.vertical = true;
          document.getElementById('tabs').classList.add('vertical-tabs-border');
        }
      });
    },
  },

  mounted() {
    if (window.innerWidth < 768) {
      this.vertical = false;
      setTimeout(function() {
        if (document.getElementById('tabs').classList.contains('vertical-tabs-border')) {
          document.getElementById('tabs').classList.remove('vertical-tabs-border');
        }
      }, 50);
    } else {
      this.vertical = true;
      setTimeout(function() {
        document.getElementById('tabs').classList.add('vertical-tabs-border');
      }, 50);
    }
  },
}
</script>

<style>
.vertical-tabs-border .v-slide-group__wrapper {
  border-right: 1px solid lightgrey;
}

.verticalTabs .theme--dark .v-slide-group__wrapper {
  background-color: #272727 !important;
}

.theme--dark .vertical-tabs-border .v-slide-group__wrapper {
  border-right: unset !important;
}

div.tabs [role="tab"] {
  justify-content: flex-start;
}
</style>
