<template>
  <div class="panel-block loading-container" style="border: 0">
    <article class="panel column is-full" style="width: 100% !important">
      <p class="panel-heading" style="width: auto">Queued Email</p>
      <b-message type="is-danger" has-icon v-if="error">
            {{ error }}
        </b-message>
      <div class="columns is-desktop columnsvisibility">
        <div class="column tplmaintable">
          <b-table :data="templates" :mobile-cards="false">
           
            <b-table-column
              centered
              label="Template"
              field="tpl"
              v-slot="props"
              width="15%"
            >
              {{ props.row.name }}
            </b-table-column>
            <b-table-column centered label="Group" field="name" v-slot="props">
                {{ props.row.name }}
            </b-table-column>

            <b-table-column
              centered
              label="Actions"
              field="name"
              v-slot="props"
            >
                {{ props.row.name }}
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
import DelTplModal from "../components/Templates/DelTplModal.vue";
import SendModal from '../components/Templates/SendModal.vue';
import CloneTplModal from '../components/Templates/CloneTpl.vue'
export default {
  name: "Queue",
  components: {},
  methods: {
    cloneTpl(tplid)
    {
       this.$buefy.modal.open({
        parent: this,
        component: CloneTplModal,
        hasModalCard: true,
        trapFocus: true,
        props: { tplid },
      });
    },
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
    editTpl(tplid) {
      requestHelper.OpenTplEdit(this.baseurl, tplid);
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
    deleteTpl(group) {
      this.$buefy.modal.open({
        parent: this,
        component: DelTplModal,
        hasModalCard: true,
        trapFocus: true,
        props: { group },
      });
    },
    OpenSendModal(group) {
      this.$buefy.modal.open({
        parent: this,
        component: SendModal,
        hasModalCard: true,
        trapFocus: true,
        props: { group },
      });
    },
    ...mapActions({
      getTemplates: "TemplatesStore/getTemplates",
      getServers: "ServersStore/getServers",
    }),


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
    this.error=''
    this.getQueue()
    .then((r)=>
    {
      console.log(r)
    })
    .catch(error => this.error = error.message+'. Please check if you are logged in or have permissions.');
    
  },
  computed: {
    ...mapState("ServersStore", ["servers"]),
    ...mapState("TemplatesStore", ["templates"]),
    ...mapState(["baseurl"]),
  },
  data() {
    return {
      groupname: "",
      newgroupname: "",
      error: '',
      table: []
      // table: [
      //   { id: 5, name: "group1", tpls: [{ id: 5, name: "tpl1" }] },
      //   { id: 5, name: "group2", tpls: [{ id: 5, name: "tpl1" }] },
      //   { id: 5, name: "group3", tpls: [{ id: 5, name: "tpl1" }] },
      // ],
    };
  },
};
</script>
