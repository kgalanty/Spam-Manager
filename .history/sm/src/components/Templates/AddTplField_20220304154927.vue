<template>
  <span>
     <th>Add New Template</th>
                  <th style="padding: 10px">
                    <b-input v-model="templatename" placeholder="Template name"></b-input>
                  </th>
                  <th>
                    <b-button
                      @click="AddTemplate"
                      type="is-danger"
                      icon-right="plus"
                      size="is-small"
                      >Add</b-button
                    >
                  </th>
  </span>
</template>
<style>
</style>
<style scoped>
</style>
<script>
// @ is an alias to /src
//import HelloWorld from '@/components/HelloWorld.vue'
export default {
  name: "AddTplField",
  mixins: [],
  components: {},
  props: ['groupid'],
  methods: {
    
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
      templatename: ''
    };
  },
  watch: {},
};
</script>
