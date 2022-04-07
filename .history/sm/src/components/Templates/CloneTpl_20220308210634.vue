<template>
  <form action="">
    <div class="modal-card" style="">
      <header class="modal-card-head">
        <p class="modal-card-title">Clone Template</p>
        <button type="button" class="delete" @click="$emit('close')" />
      </header>
      <section class="modal-card-body">
        <b-notification
          v-if="error"
          type="is-danger"
          has-icon
        >
          {{ error }}
        </b-notification>
        <b-field label="New Template Name">
          <b-input
            v-model="templatename"
            placeholder="Template name"
            required
          ></b-input>
        </b-field>
        <b-field label="">
          <b-checkbox v-model="openeditcheckbox"
            >Open Edition of New Template</b-checkbox
          >
        </b-field>
      </section>
      <footer class="modal-card-foot">
        <b-button label="Close" @click="$emit('close')" />
        <b-button
          label="Clone it"
          @click="CloneTemplateConfirm"
          :loading="saveLoadingBtn"
          icon-left="content-copy"
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
  name: "CloneTpl",
  mixins: [],
  components: {},
  props: {
    tplid: {
      type: Number,
      required: true,
    },
  },
  methods: {
    ...mapActions({
      getTemplates: "TemplatesStore/getTemplates",
    }),
    CloneTemplateConfirm() {
      if (this.templatename == "")
      {
        this.error = "Template name cannot be empty."
        return;
      } 
      this.saveLoadingBtn = true;
      const params = requestHelper.generateParamsForRequest("Templates", [
        "a=CloneTemplate",
      ]);
      this.error = "";
      this.$api
        .post("addonmodules.php?" + params, {
          tplid: this.tplid,
          name: this.templatename.trim(),
        })
        .then((response) => {
          this.saveLoadingBtn = false;
          if (response.data.response === "success") {
            this.getTemplates();
            if (this.openeditcheckbox) {
              window.open(
                this.baseurl +
                  "configemailtemplates.php?action=edit&id" +
                  response.data.templateid +
                  "&new=true",
                "_blank"
              );
            }
            this.$emit("close");
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
      openeditcheckbox: false,
      templatename: "",
      error: "",
      saveLoadingBtn: false,
    };
  },
  watch: {},
};
</script>
