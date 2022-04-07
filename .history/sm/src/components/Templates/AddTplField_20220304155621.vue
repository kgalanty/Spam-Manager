<template>
  <div class="columns">
    <div class="column is-four-fifths">Add New Template</div>
    <div class="column">
      <b-input v-model="templatename" placeholder="Template name" expanded></b-input>
    </div>
    <div class="column">
      <b-button
        @click="AddTemplate"
        type="is-danger"
        icon-right="plus"
        size="is-small"
        >Add</b-button
      >
    </div>
  </div>
</template>
<style>
.tplmaintable .table-footer th {
  color: white;
  vertical-align: middle;
  border: 0;
}
</style>
<style scoped>
.columns div 
{
    padding:10px 5px;
}
</style>
<script>
// @ is an alias to /src
//import HelloWorld from '@/components/HelloWorld.vue'
export default {
  name: "AddTplField",
  mixins: [],
  components: {},
  props: ["groupid"],
  methods: {
    AddGroup() {},
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
    };
  },
  watch: {},
};
</script>
