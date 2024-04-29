<template>
  <div class="panel-block loading-container" style="border: 0">
    <article class="panel column is-full" style="width: 100% !important">
      <p class="panel-heading" style="width: auto" v-if="templateName">
        Template:
        <b-tag type="is-info" size="is-medium">{{ templateName }}</b-tag>
      </p>
      <p class="panel-heading" style="width: auto" v-if="servers.length">
        Servers:
        <b-tag
          type="is-primary is-light"
          size="is-medium"
          v-for="(server, i) in servers"
          :key="i"
          >{{ server.name }}</b-tag
        >
      </p>
      <p class="panel-heading" style="width: auto" v-if="products.length">
        Products:
        <b-tag
          type="is-primary"
          size="is-medium"
          v-for="(product, i) in products"
          :key="i"
          >{{ product.name }}</b-tag
        >
      </p>
      <p class="panel-heading" style="width: auto" v-if="list.statuses">
        Statuses:
        <b-tag
          type="is-link is-light"
          size="is-medium"
          v-for="(status, i) in list.statuses_array"
          :key="i"
          >{{ status }}</b-tag
        >
      </p>
      <p class="panel-heading" style="width: auto" v-if="list.statuses">
        <b-tag
          type="is-primary is-light"
          size="is-medium"
          >Service IDs based queue</b-tag
        >
      </p>
      <b-message type="is-danger" has-icon v-if="error">
        {{ error }}
      </b-message>
      <div class="columns is-desktop columnsvisibility">
        <div class="column tplmaintable">
          <b-table
            :data="queue"
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
              label="Client"
              field="service.client"
              v-slot="props"
            >
              <a v-if="props.row.service.client"
                :href="
                  baseurl +
                  'clientssummary.php?userid=' +
                  props.row.service.client.id
                "
                target="_blank"
                >#{{ getObjKey(props.row.service.client, 'id') }}
                {{ getObjKey(props.row.service.client, 'firstname') }}
                {{ getObjKey(props.row.service.client, 'lastname') }}</a
              >
            </b-table-column>
            <b-table-column
              centered
              label="Product"
              field="service.product"
              v-slot="props"
            >
              <a
                :href="
                  baseurl + 'clientsservices.php?id=' + props.row.service.id
                "
                target="_blank"
                >#{{ props.row.service.id }}
                {{ props.row.service.product.name }}
                {{ props.row.service.domain }}</a
              >
            </b-table-column>
            <b-table-column
              centered
              label="Server"
              field="service.server"
              v-slot="props"
            >
              {{ getObjKey(props.row.service.server, 'name') }}
            </b-table-column>
            <b-table-column
              centered
              label="Send status"
              field="sent"
              v-slot="props"
            >
              <b-icon v-if="props.row.sent == 0" icon="close-thick"></b-icon>
              <b-icon v-if="props.row.sent == 1" icon="check"></b-icon>
            </b-table-column>
            <b-table-column
              centered
              label="Date Sent"
              field="date_sent"
              v-slot="props"
            >
              <span v-if="props.row.sent == 1">{{ props.row.date_sent }}</span>
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
.panel-heading > .tag {
  margin: auto 5px;
}
</style>
<script>
// @ is an alias to /src
//import HelloWorld from '@/components/HelloWorld.vue'
import { mapActions, mapState } from "vuex";
import "buefy/dist/buefy.css";
import requestHelper from "../mixins/requestHelper";
import templatesNameHelper from "../mixins/templatesNameHelper";
export default {
  name: "Queue",
  components: {},
  methods: {
    ...mapActions({
      getQueue: "QueueStore/getQueue",
    }),
    getObjKey(object, key)
    {
      if(!object)
      {
        return ''
      }
      if(key in object)
      {
        return object[key]
      }
      return ''
    },
    changePage(val) {
      this.page = val;
      this.fetchData();
    },
    fetchData() {
      this.getQueue(this.$route.params.id)
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
    fetchListData() {
      const params = requestHelper.generateParamsForRequest("QueuesList", [
        `list=${this.$route.params.id}`,
      ]);

      this.$api.get("addonmodules.php?" + params).then((resp) => {
        if (resp.data.list) {
          this.list = resp.data.list;
          this.servers = resp.data.servers;
          this.products = resp.data.products;
        }
      });
    },
  },
  mounted() {
    this.error = "";
    this.fetchData();
    this.fetchListData();
  },
  computed: {
    ...mapState(["baseurl"]),
    ...mapState("QueueStore", ["queue"]),
    ...mapState("QueueStore", ["loading"]),
    templateName() {
      return templatesNameHelper.extractGroupTplName(this.list?.template?.name);
    },
    page: {
      get() {
        return this.$store.state.QueueStore.page;
      },
      set(val) {
        this.$store.commit("QueueStore/setPage", val);
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
      return this.$store.state.QueueStore.total;
    },
  },
  data() {
    return {
      error: "",
      servers: [],
      list: {},
      products: [],
    };
  },
};
</script>
