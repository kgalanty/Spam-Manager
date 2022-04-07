<template>
  <div class="panel-block loading-container" style="border: 0">
    <article class="panel column is-full" style="width: 100% !important">
      <p class="panel-heading" style="width: auto">Templates</p>
      <div class="columns is-desktop columnsvisibility">
        <div class="column tplmaintable">
          <b-table :data="table">
            <template #footer>

               <th class="is-hidden-mobile ">
                    <div class="th-wrap">Add New Group</div>
                </th>
                <th class="is-hidden-mobile">
                    <div class="th-wrap"> <b-input v-model="name" placeholder="Group name" expanded></b-input></div>
                </th>
                <th class="is-hidden-mobile">
                    <div class="th-wrap">  <b-button
                @click="edit(props.row.id)"
                type="is-danger"
                icon-right="plus"
                size="is-small"
                >Add</b-button
              ></div>
               </th>

            </template>
            <b-table-column centered label="Group" field="name" v-slot="props">
              {{ props.row.name }}
            </b-table-column>
            <b-table-column centered label="Group" field="name" v-slot="props">
              <b-table :data="props.row.tpls" class="tpltable">
                <b-table-column
                  centered
                  label="Template"
                  field="name"
                  v-slot="props2"
                >
                  {{ props2.row.name }}
                </b-table-column>
                <b-table-column
                  centered
                  label="Send E-mail"
                  field="name"
                  v-slot="props2"
                  ><div class="columns">
                    <div class="column is-four-fifths">
                      <b-select placeholder="Select a server" expanded>
                        <optgroup label="Black Sails">
                          <option value="flint">Flint</option>
                          <option value="silver">Silver</option>
                          <option value="vane">Vane</option>
                          <option value="billy">Billy</option>
                          <option value="jack">Jack</option>
                        </optgroup>

                        <optgroup label="Breaking Bad">
                          <option value="heisenberg">Heisenberg</option>
                          <option value="jesse">Jesse</option>
                          <option value="saul">Saul</option>
                          <option value="mike">Mike</option>
                        </optgroup>

                        <optgroup label="Game of Thrones">
                          <option value="tyrion-lannister">
                            Tyrion Lannister
                          </option>
                          <option value="jamie-lannister">
                            Jamie Lannister
                          </option>
                          <option value="daenerys-targaryen">
                            Daenerys Targaryen
                          </option>
                          <option value="jon-snow">Jon Snow</option>
                        </optgroup>
                      </b-select>
                    </div>
                    <div class="column">
                      <b-button
                        @click="edit(props2.row.id)"
                        type="is-link"
                        icon-right="send"
                        >Send</b-button
                      >
                    </div>
                  </div>
                </b-table-column>
                <b-table-column
                  centered
                  label="Actions"
                  field="name"
                  v-slot="props2"
                  width="20%"
                >
                  <b-button
                    @click="edit(props2.row.id)"
                    type="is-info"
                    icon-right="pencil"
                    size="is-small"
                  ></b-button>
                  <b-button
                    @click="edit(props2.row.id)"
                    type="is-danger"
                    icon-right="delete"
                    size="is-small"
                  ></b-button>
                </b-table-column>
              </b-table>
            </b-table-column>

            <b-table-column
              centered
              label="Actions"
              field="name"
              v-slot="props"
            >
              <b-button
                @click="edit(props.row.id)"
                type="is-info"
                icon-right="pencil"
                size="is-small"
              ></b-button>
              <b-button
                @click="edit(props.row.id)"
                type="is-danger"
                icon-right="delete"
                size="is-small"
              ></b-button>
            </b-table-column>
          </b-table>
         
        </div>
      </div>
    </article>
  </div>
</template>
<style >
#newgroup {
  background-color: rgb(49, 57, 85);
  margin: 0 auto;
  color: white;
  
}
.tplmaintable > .table td{
  vertical-align: middle !important;
}
</style>
<script>
// @ is an alias to /src
//import HelloWorld from '@/components/HelloWorld.vue'
//import { mapActions, mapState } from "vuex";

import "buefy/dist/buefy.css";
export default {
  name: "Tpl",
  components: {},
  methods: {
    // ...mapActions([""]),
    // clearDateTime() {
    //   this.datetimeFilter = null;
    // },
    // constructParams() {
    //   const params = {
    //     perPage: this.perPage,
    //     Datefrom: this.datetimeFromFilter,
    //     Dateto: this.datetimeToFilter,
    //   };
    //   return params;
    // },
    // parseDateTime(dateTime) {
    //   return this.moment(dateTime).format("YYYY-MM-DD HH:DD:SS");
    // },
  },
  mounted() {},
  computed: {},
  data() {
    return {
      perPage: 25,
      table: [
        { id: 5, name: "group1", tpls: [{ id: 5, name: "tpl1" }] },
        { id: 5, name: "group2", tpls: [{ id: 5, name: "tpl1" }] },
        { id: 5, name: "group3", tpls: [{ id: 5, name: "tpl1" }] },
      ],
    };
  },
};
</script>
