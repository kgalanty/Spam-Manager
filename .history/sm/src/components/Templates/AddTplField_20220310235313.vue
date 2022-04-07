<template>
  <form action="">
    <div class="modal-card" style="">
      <header class="modal-card-head">
        <p class="modal-card-title">Add New Template</p>
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
        <b-field label="Template Name">
          <b-input
            v-model="templatename"
            placeholder="Template name"
            required
          ></b-input>
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
import { mapState } from 'vuex';
// @ is an alias to /src
//import HelloWorld from '@/components/HelloWorld.vue'
import requestHelper from "../../mixins/requestHelper";
export default {
  name: "AddTplField",
  mixins: [],
  components: {},
  props: {
    group: {
      type: String,
      required: true,
    },
  },
  methods: {
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
              this.baseurl +
                "configemailtemplates.php?action=edit&id=" +
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
    //this.points = this.calculatePointsFromTags(this.tags, this.invoiceStatus)
  },
  beforeUpdate() {
    // this.points = this.calculatePointsFromTags(this.tags, this.invoiceStatus)
  },
  computed: {
      ...mapState(["baseurl"]),
  },
  data() {
    return {
      templatename: "",
      error: "",
    };
  },
  watch: {},
};
</script>
