<template>
  <div class="panel-block loading-container" style="border: 0">
    <article class="panel column is-full" style="width: 100% !important">
      <p class="panel-heading" style="width: auto">Queues List</p>
      <b-message type="is-danger" has-icon v-if="error">
        {{ error }}
      </b-message>
      <div class="columns is-desktop columnsvisibility">
        <div class="column tplmaintable">
          <b-table
            :data="list"
            :mobile-cards="false"
            :loading="loading"
            :current-page.sync="page"
            :perPage="perPage"
            :total="total"
            :page="page"
            @onPageChange="changePage"
            paginated
            backend-pagination
          >
            <b-table-column
              centered
              label="Template"
              field="tpl"
              v-slot="props"
              width="15%"
            >
              {{ extractGroupTplName(props.row.template) }}
            </b-table-column>
            <b-table-column
              centered
              label="Emails count"
              field="emailcount"
              v-slot="props"
            >
              {{ props.row.emails_count }}
            </b-table-column>
            <b-table-column
              centered
              label="Sender"
              field="admin"
              v-slot="props"
            >
              {{ props.row.admin.username }}
            </b-table-column>
            <b-table-column centered label="Date" field="date" v-slot="props">
              {{ props.row.date }}
            </b-table-column>
            <b-table-column centered label="Open" field="" v-slot="props">
              <b-button @click="OpenQueue(props.row.id)">Open</b-button>
            </b-table-column>
          </b-table>
        </div>
      </div>
    </article>
  </div>
</template>
<style scoped>
a {
  color: white;
}
a:hover {
  color: rgb(183, 172, 255);
}
</style>
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
import templatesNameHelper from "../mixins/templatesNameHelper";
export default {
  name: "List",
  components: {},
  methods: {
    ...mapActions({
      getQueueList: "QueueListStore/getQueue",
    }),
    OpenQueue(id) {
      this.$router.push({ path: `/queue/${id}` });
    },
    extractGroupTplName(template) {
      return (
        templatesNameHelper.extractGroupTplName(template?.name) ??
        "Template doesn't exist"
      );
    },
    changePage(val) {
      this.page = val;
      this.getCompleted();
    },
    fetchData() {
      this.getQueueList()
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
  },
  mounted() {
    this.error = "";
    this.fetchData();
  },
  computed: {
    ...mapState(["baseurl"]),
    ...mapState("QueueListStore", ["list"]),
    ...mapState("QueueListStore", ["loading"]),
    page: {
      get() {
        return this.$store.state.QueueListStore.page;
      },
      set(val) {
        this.$store.commit("QueueListStore/setPage", val);
        this.fetchData();
      },
    },
    perPage: {
      get() {
        return this.$store.state.QueueListStore.perPage;
      },
      set(val) {
        this.$store.commit("QueueListStore/setperPage", val);
      },
    },
    total() {
      return this.$store.state.QueueListStore.total;
    },
  },
  data() {
    return {
      error: "",
    };
  },
};
</script>
