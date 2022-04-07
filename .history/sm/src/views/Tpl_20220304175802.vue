<template>
  <div class="panel-block loading-container" style="border: 0">
    <article class="panel column is-full" style="width: 100% !important">
      <p class="panel-heading" style="width: auto">Templates</p>
      <div class="columns is-desktop columnsvisibility">
        <div class="column tplmaintable">
          <b-table :data="table" :mobile-cards="false">
            <template #footer>
              <th>Add New Group</th>
              <th style="padding: 20px">
                <b-input v-model="newgroupname" placeholder="Group name"></b-input>
              </th>
              <th>
                <b-button
                  @click="edit(props.row.id)"
                  type="is-danger"
                  icon-right="plus"
                  size="is-small"
                  >Add</b-button
                >
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
                <b-table-column centered label="Server" field="name"
                  ><div class="columns">
                    <div class="column is-four-fifths">
                      <b-field label="Enter server name">
                      <b-taginput
                        v-model="serversSelected[props.row.name]"
                        :data="serversFiltered"
                        autocomplete
                        open-on-focus
                        field="name"
                        icon="label"
                        placeholder="Add a tag"
                        @typing="getFilteredTags"
                        maxtags="3"
                        size="is-small'
                      >
                      </b-taginput></b-field>
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
                  <b-button
                    @click="edit(props2.row.id)"
                    type="is-link"
                    icon-right="send"
                    size="is-small"
                    >Send</b-button
                  >
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
                @click="addTpl(props.row.name)"
                type="is-link"
                icon-right="plus"
                size="is-small"
              ></b-button>
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
.tplmaintable > .table td {
  vertical-align: middle !important;
}
</style>
<script>
// @ is an alias to /src
//import HelloWorld from '@/components/HelloWorld.vue'
import { mapActions, mapState } from "vuex";

import "buefy/dist/buefy.css";
import AddTplField from "../components/Templates/AddTplField.vue";
export default {
  name: "Tpl",
  components: {},
  methods: {
    addTpl(group) {
      this.$buefy.modal.open({
        parent: this,
        component: AddTplField,
        hasModalCard: true,
        trapFocus: true,
        props: { group },
      });
    },
    ...mapActions({
      getTemplates: "",
      getServers: "ServersStore/getServers",
    }),
    getFilteredTags(text) {
      this.serversFiltered = this.servers.filter((option) => {
        return (
          option.name.toString().toLowerCase().indexOf(text.toLowerCase()) >= 0
        );
      });
    },

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
  mounted() {
    this.getServers();
  },
  computed: {
    ...mapState("ServersStore", ["servers"]),
  },
  data() {
    return {
      groupname: "",
      newgroupname: '',
      serversFiltered: this.servers,
      serversSelected: {},
      table: [
        { id: 5, name: "group1", tpls: [{ id: 5, name: "tpl1" }] },
        { id: 5, name: "group2", tpls: [{ id: 5, name: "tpl1" }] },
        { id: 5, name: "group3", tpls: [{ id: 5, name: "tpl1" }] },
      ],
    };
  },
};
</script>
