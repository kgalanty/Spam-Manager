<template>
  <form action="">
    <div class="modal-card" style="">
      <header class="modal-card-head">
        <p class="modal-card-title">Send</p>
        <button type="button" class="delete" @click="$emit('close')" />
      </header>

      <b-tabs v-model="activeTab">
        <b-tab-item label="Servers & Products">
          <section class="modal-card-body">
            <b-message type="is-info" has-icon>
              With this form mass mailing can be sent to all owners of accounts
              utilizing servers and/or products selected below.
            </b-message>
            <b-notification
              v-if="error"
              type="is-danger"
              has-icon
              aria-close-label="Close notification"
            >
              {{ error }}
            </b-notification>

            <b-field label="Servers">
              <b-taginput
                style=""
                v-model="serversSelected"
                :data="serversFiltered"
                autocomplete
                open-on-focus
                field="name"
                icon="label"
                placeholder="Type server name, empty for all"
                @typing="getFilteredTags"
                maxtags="3"
                size="is-small"
                type="is-info"
                @input="checkRecipients"
              >
              </b-taginput>
            </b-field>
            <b-field label="Products">
              <b-taginput
                style=""
                v-model="productsSelected"
                :data="productsFiltered"
                autocomplete
                open-on-focus
                icon="label"
                field="name"
                placeholder="Type product name, empty for all"
                @typing="getFilteredProducts"
                maxtags="3"
                size="is-small"
                type="is-info"
                @input="checkRecipients"
                ref="productstaginput"
              >
                <template slot-scope="props">
                  #{{ props.option.id }} {{ props.option.name }}
                  <span v-if="props.option.group"
                    >({{ props.option.group.name }})</span
                  >
                </template>
                <template #selected="props">
                  <b-tag
                    v-for="(tag, index) in props.tags"
                    :key="index"
                    type="is-primary"
                    rounded
                    :tabstop="false"
                    ellipsis
                    closable
                    @close="$refs.productstaginput.removeTag(index, $event)"
                  >
                    #{{ tag.id }} {{ tag.name }}
                  </b-tag>
                </template>
              </b-taginput>
            </b-field>
            <b-field
              label="Service Statuses (at least one required)"
              class="servicestatuses"
            >
              <span v-for="(status, index) in hostingstatuses" :key="index">
                <b-checkbox
                  v-model="checkboxGroup"
                  :native-value="status"
                  @input="checkRecipients"
                >
                  {{ status }}
                </b-checkbox>
              </span>
            </b-field>
            <b-field label="Estimated recipients">
              {{ recipients }}
            </b-field>
          </section>
        </b-tab-item>
        <b-tab-item label="Services">
          <section class="modal-card-body">
            <b-message type="is-info" has-icon>
              With this mass mailing form can be sent to all owners of services
              enumerated below by id.
            </b-message>
            <b-notification
              v-if="error"
              type="is-danger"
              has-icon
              aria-close-label="Close notification"
            >
              {{ error }}
            </b-notification>

            <b-field label="Service IDs list">
              <b-taginput
                style=""
                v-model="servicesSelected"
                :data="serversFiltered"
                allow-new
                field="service"
                icon="label"
                placeholder="Type service ID here"
                size="is-small"
                type="is-info"
                :before-adding="verifyIfIsNumeric"
              >
              </b-taginput>
            </b-field>
          </section>
        </b-tab-item>
      </b-tabs>
      <footer class="modal-card-foot">
        <b-button label="Close" @click="$emit('close')" />
        <b-button
          label="Send"
          @click="SendEmails"
          icon-left="send"
          type="is-primary"
          :loading="sendLoadingBtn"
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
.tab-content {
  padding: 0 !important;
}
.b-tabs:not(:last-child) {
  margin-bottom: 0 !important;
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
      getProducts: "ProductsStore/getProducts",
    }),
    verifyIfIsNumeric(tag) {
      const regex = /^[0-9]+$/;
      return (
        (typeof tag === "string" && regex.test(tag)) || Number.isInteger(tag)
      );
    },
    checkRecipients() {
      if (
        this.serversSelected.length === 0 &&
        this.productsSelected.length === 0
      ) {
        return;
      }
      const params = requestHelper.generateParamsForRequest("Recipients", [
        "a=calculateRecipients",
      ]);
      this.error = "";
      this.$api
        .post("addonmodules.php?" + params, {
          hostingstatuses: this.checkboxGroup,
          servers: this.serversSelected,
          products: this.productsSelected,
        })
        .then((response) => {
          this.recipients = response.data.recipients;
        });
    },
    getFilteredTags(text) {
      this.serversFiltered = this.servers.filter((option) => {
        return (
          option.name.toString().toLowerCase().indexOf(text.toLowerCase()) >= 0
        );
      });
    },
    getFilteredProducts(text) {
      this.productsFiltered = this.products.filter((option) => {
        return (
          option.name.toString().toLowerCase().indexOf(text.toLowerCase()) >= 0
        );
      });
    },
    SendEmails() {
      /*eslint no-unreachable: "off"*/
      if (this.activeTab === 1) {
        //handling sending mailing to specified service ids
        if (this.servicesSelected.length === 0) {
          this.$buefy.toast.open({
            message: "Services list is empty.",
            type: "is-danger",
            duration: 10000,
          });
          return;
        }

        this.sendLoadingBtn = true;
        const params = requestHelper.generateParamsForRequest("Email", []);
        this.error = "";
        this.$api
          .post("addonmodules.php?" + params, {
            template_id: this.group,
            services: this.servicesSelected,
          })
          .then((response) => {
            this.sendLoadingBtn = false;
            if (response.data.response === "success") {
              this.$emit("close");
              this.$buefy.toast.open({
                message: "Emails were successfuly queued for sending",
                type: "is-success",
              });
              return;
            } else {
              this.error = response.data.msg;
            }
          });
          return;
      }

      if (
        this.checkboxGroup.length === 0 &&
        this.serversSelected.length === 0
      ) {
        this.$buefy.toast.open({
          message:
            "You need to select at least one server or product and one status to proceed",
          type: "is-danger",
          duration: 10000,
        });
        return;
      }
      this.sendLoadingBtn = true;
      const params = requestHelper.generateParamsForRequest("Email", []);
      this.error = "";
      this.$api
        .post("addonmodules.php?" + params, {
          template_id: this.group,
          statuses: this.checkboxGroup,
          servers: this.serversSelected.map(({ id }) => id),
          products: this.productsSelected.map(({ id }) => id),
        })
        .then((response) => {
          this.sendLoadingBtn = false;
          if (response.data.response === "success") {
            this.$emit("close");
            this.$buefy.toast.open({
              message: "Emails were successfuly queued for sending",
              type: "is-success",
            });
            return;
          } else {
            this.error = response.data.msg;
          }
        });
    },
  },
  mounted() {
    this.getServers();
    this.getProducts();
    //this.points = this.calculatePointsFromTags(this.tags, this.invoiceStatus)
  },
  beforeUpdate() {
    // this.points = this.calculatePointsFromTags(this.tags, this.invoiceStatus)
  },
  computed: {
    ...mapState("ServersStore", ["servers"]),
    ...mapState("ProductsStore", ["products"]),
  },
  data() {
    return {
      servicesSelected: [],
      activeTab: 0,
      recipients: 0,
      templatename: "",
      error: "",
      serversFiltered: this.servers,
      serversSelected: [],
      productsFiltered: this.products,
      productsSelected: [],
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
      sendLoadingBtn: false,
    };
  },
  watch: {},
};
</script>
