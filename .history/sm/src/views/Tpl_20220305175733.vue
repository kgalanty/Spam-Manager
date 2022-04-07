<template>
  <div class="panel-block loading-container" style="border: 0">
    <article class="panel column is-full" style="width: 100% !important">
      <p class="panel-heading" style="width: auto">Templates</p>
      <div class="columns is-desktop columnsvisibility">
        <div class="column tplmaintable">
          <b-table :data="templates" :mobile-cards="false">
            <template #footer>
              <th>Add New Group</th>
              <th style="padding: 20px">
                <b-input
                  v-model="newgroupname"
                  placeholder="Group name"
                ></b-input>
              </th>
              <th>
                <b-button
                  @click="addNewGroup"
                  type="is-danger"
                  icon-right="plus"
                  size="is-small"
                  >Add</b-button
                >
              </th>
            </template>
            <b-table-column
              centered
              label="Group"
              field="name"
              v-slot="props"
              width="15%"
            >
              {{ props.row.name }}
            </b-table-column>
            <b-table-column centered label="Group" field="name" v-slot="props">
              <b-table :data="props.row.tpls" class="tpltable">
                <b-table-column
                  centered
                  label="Template"
                  field="name"
                  v-slot="props2"
                  width="15%"
                >
                  {{ props2.row.name }}
                </b-table-column>
                <!-- <b-table-column
                  centered
                  label="Server"
                  field="name"
                  width="60%"
                >
                  <b-taginput
                    style=""
                    v-model="serversSelected[props.row.name]"
                    :data="serversFiltered"
                    autocomplete
                    open-on-focus
                    field="name"
                    icon="label"
                    placeholder="Type server name"
                    @typing="getFilteredTags"
                    maxtags="3"
                    size="is-small"
                  >
                  </b-taginput>
                </b-table-column> -->
                <b-table-column
                  centered
                  label="Actions"
                  field="name"
                  v-slot="props2"
                  width="5%"
                >
                  <b-button
                    @click="editTpl(props2.row.id)"
                    type="is-info"
                    icon-right="pencil"
                    size="is-small"
                  >Edit</b-button>
                  <b-button
                    @click="edit(props2.row.id)"
                    type="is-danger"
                    icon-right="delete"
                    size="is-small"
                  >Delete</b-button>
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
              <b-tooltip
                label="Add New Template to This Group"
                position="is-left"
              >
                <b-button
                  @click="addTpl(props.row.name)"
                  type="is-link"
                  icon-right="plus"
                  size="is-small"
                ></b-button
              ></b-tooltip>
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
import requestHelper from "../mixins/requestHelper";
import "buefy/dist/buefy.css";
import AddTplField from "../components/Templates/AddTplField.vue";
export default {
  name: "Tpl",
  components: {},
  methods: {
    addNewGroup() {
      if (this.newgroupname == "") {
        this.$buefy.dialog.alert({
          title: "Error",
          message: "Group Name cannot be empty",
          type: "is-danger",
          hasIcon: true,
          icon: "alert-circle",
          iconPack: "mdi",
          ariaRole: "alertdialog",
          ariaModal: true,
        });
        return;
      }
      this.addTpl(this.newgroupname);
    },
    editTpl(tplid)
    {
      requestHelper.OpenTplEdit(this.baseurl, tplid)
    },
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
      getTemplates: "TemplatesStore/getTemplates",
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
    this.getServers()
    this.getTemplates()
  },
  computed: {
    ...mapState("ServersStore", ["servers"]),
    ...mapState("TemplatesStore", ["templates"]),
    ...mapState(['baseurl'])
  },
  data() {
    return {
      groupname: "",
      newgroupname: "",
      serversFiltered: this.servers,
      serversSelected: {},
      // table: [
      //   { id: 5, name: "group1", tpls: [{ id: 5, name: "tpl1" }] },
      //   { id: 5, name: "group2", tpls: [{ id: 5, name: "tpl1" }] },
      //   { id: 5, name: "group3", tpls: [{ id: 5, name: "tpl1" }] },
      // ],
    };
  },
};
</script>
