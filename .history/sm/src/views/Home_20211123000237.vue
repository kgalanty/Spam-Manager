<template>
  <div class="panel-block loading-container">
    <article
      class="panel column is-full"
      style="width: 100% !important"
    >
          <p
        class="panel-heading"
      >
        Columns Visibility
      </p>
      <ColumnsFilter />
      <p class="panel-heading">Filters</p>
      <ChatFilters />
      <p
        class="panel-heading chatlistheader"
      >
        Chats list
      </p>
      
     <PendingChatListTable />
     <ChatListTable />
    </article>
  </div>
</template>
<style scoped>



</style>
<script>
// @ is an alias to /src
//import HelloWorld from '@/components/HelloWorld.vue'
//import { mapActions, mapState } from "vuex";
import ChatListTable from '../components/ChatListTable.vue'
import PendingChatListTable from '../components/PendingChatListTable.vue'
import ChatFilters from '../components/ChatFilters.vue'
import ColumnsFilter from '../components/ColumnsFilter.vue'
import "buefy/dist/buefy.css";
export default {
  name: "Home",
  components: {
    ChatListTable, ChatFilters, PendingChatListTable,ColumnsFilter
  },
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
  mounted() {
  },
  computed: {
  },
  data() {
    return {
      perPage: 25,
      datetimeFromFilter: null,
      datetimeToFilter: null,
      total: 5,
     
    };
  },
};
</script>
