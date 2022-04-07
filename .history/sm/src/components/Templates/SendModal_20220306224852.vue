<template>
  <form action="">
    <div class="modal-card" style="">
      <header class="modal-card-head">
        <p class="modal-card-title">Send Mailing</p>
        <button type="button" class="delete" @click="$emit('close')" />
      </header>
      <section class="modal-card-body">
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
            placeholder="Type server name"
            @typing="getFilteredTags"
            maxtags="3"
            size="is-small"
          >
          </b-taginput>
        </b-field>
      </section>
      <footer class="modal-card-foot">
        <b-button label="Close" @click="$emit('close')" />
        <b-button
          label="Add"
          @click="AddTemplate"
          icon-left="plus"
          type="is-primary"
        />
      </footer>
    </div>
  </form>
</template>
<style>
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
      templatename: "",
      error: "",
      serversFiltered: this.servers,
      serversSelected: {},
    };
  },
  watch: {},
};
</script>
