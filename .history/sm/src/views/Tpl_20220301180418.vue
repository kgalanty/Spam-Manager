<template>
  <div class="panel-block loading-container">
    <article class="panel column is-full" style="width: 100% !important">
      <p class="panel-heading" style="width: auto">Templates</p>
      <div class="columns is-desktop columnsvisibility">
        <div class="column">
          <b-table paginated :per-page="15" :data="table" narrowed bordered>
            <b-table-column centered label="Template"  field="name" v-slot="props">
              {{ props.row.name }}
            </b-table-column>
              <b-table-column centered label="Actions"  field="name" v-slot="props">
              <b-button @click="edit(props.row.id)">Edit</b-button>
            </b-table-column>
          </b-table>
        </div>
      </div>
    </article>
  </div>
</template>
<style scoped>
</style>
<script>
// @ is an alias to /src
//import HelloWorld from '@/components/HelloWorld.vue'
//import { mapActions, mapState } from "vuex";

import "buefy/dist/buefy.css";
export default {
  name: "Tpl",
  components: {},
  methods: {
    // ...mapActions([""]),
    clearDateTime() {
      this.datetimeFilter = null;
    },

    constructParams() {
      const params = {
        perPage: this.perPage,
        Datefrom: this.datetimeFromFilter,
        Dateto: this.datetimeToFilter,
      };
      return params;
    },
    parseDateTime(dateTime) {
      return this.moment(dateTime).format("YYYY-MM-DD HH:DD:SS");
    },
  },
  mounted() {},
  computed: {},
  data() {
    return {
      perPage: 25,
      table: [
        {'id': 5, 'name': 'tpl1'}, {'id': 5, 'name': 'tpl2'}, {'id': 5, 'name': 'tpl3'}
      ],
    };
  },
};
</script>
