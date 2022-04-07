<template>
  <form action="">
    <div class="modal-card" style="">
      <header class="modal-card-head">
        <p class="modal-card-title">Send Mailing</p>
        <button type="button" class="delete" @click="$emit('close')" />
      </header>
      <section class="modal-card-body">
        <b-message type="is-info" has-icon>
          With this form mass mailing can be sent to all owners of accounts
          utilizing servers selected below.
        </b-message>
        <b-notification
          v-if="error"
          type="is-danger"
          has-icon
          aria-close-label="Close notification"
        >
          {{ error }}
        </b-notification>

        <b-field label="Servers (required)">
          <b-taginput
            style=""
            v-model="serversSelected"
            :data="serversFiltered"
            autocomplete
            open-on-focus
            field="name"
            icon="label"
            placeholder="Type server name"
            @typing="getFilteredTags"
            maxtags="3"
            size="is-small"
            type="is-info"
            @input="checkRecipients"
          >
          </b-taginput>
        </b-field>
        <b-field label="Service Statuses" class="servicestatuses">
          <span v-for="(status, index) in hostingstatuses" :key="index">
            <b-checkbox v-model="checkboxGroup" :native-value="status"  @input="checkRecipients">
              {{ status }}
            </b-checkbox>
          </span>
        </b-field>
         <b-field label="Estimated recipients">
            {{ recipients }}
         </b-field>
      </section>
      <footer class="modal-card-foot">
        <b-button label="Close" @click="$emit('close')" />
        <b-button
          label="Send"
          @click="AddTemplate"
          icon-left="send"
          type="is-primary"
        />
      </footer>
    </div>
  </form>
</template>
<style>
.servicestatuses .has-addons {
  flex-direction: column;
}
.dropdown-content {
}
.modal-card-title {
  background-color: inherit !important;
}
.tplmaintable .table-footer th {
  color: white;
  vertical-align: middle;
  border: 0;
}
</style>
<style scoped>
.columns div {
  padding: 10px 5px;
}
</style>
<script>
import { mapActions, mapState } from "vuex";
// @ is an alias to /src
//import HelloWorld from '@/components/HelloWorld.vue'
import requestHelper from "../../mixins/requestHelper";
export default {
  name: "SendModal",
  mixins: [],
  components: {},
  props: {
    group: {
      type: Number,
      required: true,
    },
  },
  methods: {
    ...mapActions({
      getServers: "ServersStore/getServers",
    }),
    checkRecipients()
    {
     
       if(this.serversSelected.length === 0)
       {
         return
       }
      const params = requestHelper.generateParamsForRequest("Servers", [
        "a=calculateRecipients",
      ]);
      this.error = "";
      this.$api
        .post("addonmodules.php?" + params, {
          hostingstatuses: this.checkboxGroup,
          servers: this.serversSelected,
        })
        .then((response) => {
         this.recipients =  response.data
        })
    },
    getFilteredTags(text) {
      this.serversFiltered = this.servers.filter((option) => {
        return (
          option.name.toString().toLowerCase().indexOf(text.toLowerCase()) >= 0
        );
      });
    },
    AddTemplate() {
      if (this.templatename == "" || this.group == "") return;
      const params = requestHelper.generateParamsForRequest("Templates", [
        "a=AddNewTemplate",
      ]);
      this.error = "";
      this.$api
        .post("addonmodules.php?" + params, {
          group: this.group,
          name: this.templatename,
        })
        .then((response) => {
          if (response.data.response === "success") {
            window.open(
              this.$baseurl +
                "configemailtemplates.php?action=edit&id" +
                response.data.templateid +
                "&new=true",
              "_blank"
            );
            return;
          } else {
            this.error = response.data.msg;
          }
        });
    },
  },
  mounted() {
    this.getServers();
    //this.points = this.calculatePointsFromTags(this.tags, this.invoiceStatus)
  },
  beforeUpdate() {
    // this.points = this.calculatePointsFromTags(this.tags, this.invoiceStatus)
  },
  computed: {
    ...mapState("ServersStore", ["servers"]),
  },
  data() {
    return {
      recipients: 0,
      templatename: "",
      error: "",
      serversFiltered: this.servers,
      serversSelected: [],
      hostingstatuses: [
        "Active",
        "Pending",
        "Completed",
        "Suspended",
        "Terminated",
        "Cancelled",
        "Fraud",
      ],
      checkboxGroup: [],
    };
  },
  watch: {},
};
</script>
