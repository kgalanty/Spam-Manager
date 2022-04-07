<template>
  <form action="">
    <div class="modal-card" style="">
      <header class="modal-card-head">
        <p class="modal-card-title">Delete Template</p>
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
       Are you sure you wish to delete this template?
      </section>
      <footer class="modal-card-foot">
        <b-button label="Close" @click="$emit('close')" />
        <b-button
          label="Yes, Delete"
          @click="DeleteTemplateConfirm"
          icon-left="delete"
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
import { mapActions } from 'vuex';
// @ is an alias to /src
//import HelloWorld from '@/components/HelloWorld.vue'
import requestHelper from "../../mixins/requestHelper";
export default {
  name: "DelTplModal",
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
      getTemplates: "TemplatesStore/getTemplates",
    }),
    DeleteTemplateConfirm() {
      if (this.group == "") return;
      const params = requestHelper.generateParamsForRequest("Templates", [
        "a=DeleteTemplate",
      ]);
      this.error = "";
      this.$api
        .post("addonmodules.php?" + params, {
          group: this.group,
        })
        .then((response) => {
          if (response.data.response === "success") {
           
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
  computed: {},
  data() {
    return {
      templatename: "",
      error: "",
    };
  },
  watch: {},
};
</script>
