<template>
  <div class="panel-block loading-container">
    <article class="panel column is-full" style="width: 100% !important">
      <p class="panel-heading" style="width: auto">Templates</p>
      <div class="columns is-desktop columnsvisibility">
        <div class="column">
          <b-table :data="table" bordered>
            <b-table-column
              centered
              label="Group"
              field="name"
              v-slot="props"
              width="70%"
            >
            
                <b-table :data="props.row.tpls" striped  >
                  <b-table-column
                    centered
                    label="Template"
                    field="name"
                    v-slot="props2"
                  >
                    {{ props2.row.name }}
                  </b-table-column>
                   <b-table-column
                    centered
                    label="Actions"
                    field="name"
                    v-slot="props2"
                    width="20%"
                  >
                      <b-button @click="edit(props2.row.id)">Edit</b-button>
                       <b-button @click="edit(props2.row.id)">Delete</b-button>
                  </b-table-column>
                </b-table>

              </b-table-column>

            <b-table-column
              centered
              label="Actions"
              field="name"
              v-slot="props"
            >
              <b-button @click="edit(props.row.id)">Edit</b-button>
              <b-button @click="edit(props.row.id)">Delete</b-button>
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
        { id: 5, name: "group1", tpls: [{id: 5, name: 'tpl1'}] },
        { id: 5, name: "group2", tpls: [{id: 5, name: 'tpl1'}]  },
        { id: 5, name: "group3", tpls: [{id: 5, name: 'tpl1'}]  },
      ],
    };
  },
};
</script>
