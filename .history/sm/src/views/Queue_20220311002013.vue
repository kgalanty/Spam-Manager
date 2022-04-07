<template>
  <div class="panel-block loading-container" style="border: 0">
    <article class="panel column is-full" style="width: 100% !important">
      <p class="panel-heading" style="width: auto">Queued Email (Recent 100 entries)</p>
      <b-message type="is-danger" has-icon v-if="error">
        {{ error }}
      </b-message>
      <div class="columns is-desktop columnsvisibility">
        <div class="column tplmaintable">
          <b-table :data="queue" :mobile-cards="false" :loading="loading">
            <b-table-column
              centered
              label="Template"
              field="tpl"
              v-slot="props"
              width="15%"
            >
              {{ extractGroupTplName(props.row.tplname) }}
            </b-table-column>
             <b-table-column
              centered
              label="Service"
              field="name"
              v-slot="props"
            >
              <a :href="baseurl+'clientsservices.php?id='+props.row.service.id">#{{ props.row.service.id }} {{ props.row.service.domain }}</a>
            </b-table-column>
            <b-table-column
              centered
              label="Server"
              field="name"
              v-slot="props"
            >
              {{ props.row.service.server.ipaddress }} {{ props.row.service.server.name }}
            </b-table-column>
            <b-table-column
              centered
              label="Sending status"
              field="name"
              v-slot="props"
            >
              <b-icon v-if="props.row.sent == 0"
                icon="close-thick"
                size="is-small">
            </b-icon>
              <b-icon v-if="props.row.sent == 1"
                icon="check"
                size="is-small">
            </b-icon>
            </b-table-column>
            <b-table-column centered label="Date" field="date" v-slot="props">
              {{ props.row.date }}
            </b-table-column>
          </b-table>
        </div>
      </div>
    </article>
  </div>
</template>
<style >
#newgroup {
  background-color: rgb(49, 57, 85);
  margin: 0 auto;
  color: white;
}
.tplmaintable > .table td {
  vertical-align: middle !important;
}
</style>
<script>
// @ is an alias to /src
//import HelloWorld from '@/components/HelloWorld.vue'
import { mapActions, mapState } from "vuex";
import "buefy/dist/buefy.css";
export default {
  name: "Queue",
  components: {},
  methods: {
    ...mapActions({
      getQueue: "QueueStore/getQueue",
    }),
    extractGroupTplName(name)
    {
      const splitted = name.split('_', 3)
      return splitted[1] + ' -> ' + splitted[2]
    }
  },
  mounted() {
    this.error = "";
    this.getQueue()
      .then(() => {
      //  console.log(r);
      })
      .catch(
        (error) =>
          (this.error =
            error.message +
            ". Please check if you are logged in or have permissions.")
      );
  },
  computed: {
    ...mapState(["baseurl"]),
    ...mapState("QueueStore", ["queue"]),
    ...mapState("QueueStore", ["loading"]),
  },
  data() {
    return {
      error: "",
    };
  },
};
</script>
