<template>
  <div class="panel-block loading-container">
    <article class="panel column is-full" style="width: 100% !important">
      <p class="panel-heading" style="width: auto">Sending E-mail</p>
      <div class="columns is-desktop columnsvisibility">
        <div class="column">
          <b-field label="E-mail Template" style="">
            <b-select placeholder="Select a character" icon="account">
              <optgroup label="Black Sails">
                <option value="flint">Flint</option>
                <option value="silver">Silver</option>
                <option value="vane">Vane</option>
                <option value="billy">Billy</option>
                <option value="jack">Jack</option>
              </optgroup>

              <optgroup label="Breaking Bad">
                <option value="heisenberg">Heisenberg</option>
                <option value="jesse">Jesse</option>
                <option value="saul">Saul</option>
                <option value="mike">Mike</option>
              </optgroup>

              <optgroup label="Game of Thrones">
                <option value="tyrion-lannister">Tyrion Lannister</option>
                <option value="jamie-lannister">Jamie Lannister</option>
                <option value="daenerys-targaryen">Daenerys Targaryen</option>
                <option value="jon-snow">Jon Snow</option>
              </optgroup>
            </b-select>
          </b-field>
        </div>
        <div class="column">
          <b-field label="Server" style="">
            <b-select placeholder="Select a character" icon="account">
              <optgroup label="Black Sails">
                <option value="flint">Flint</option>
                <option value="silver">Silver</option>
                <option value="vane">Vane</option>
                <option value="billy">Billy</option>
                <option value="jack">Jack</option>
              </optgroup>

              <optgroup label="Breaking Bad">
                <option value="heisenberg">Heisenberg</option>
                <option value="jesse">Jesse</option>
                <option value="saul">Saul</option>
                <option value="mike">Mike</option>
              </optgroup>

              <optgroup label="Game of Thrones">
                <option value="tyrion-lannister">Tyrion Lannister</option>
                <option value="jamie-lannister">Jamie Lannister</option>
                <option value="daenerys-targaryen">Daenerys Targaryen</option>
                <option value="jon-snow">Jon Snow</option>
              </optgroup>
            </b-select>
          </b-field>
        </div>
        <div class="column">
           <b-field label="Status" style="">

           </b-field>

        </div>
        <div class="column">
          <b-field label="Action" style="">
            <b-button type="is-link">Send</b-button>
          </b-field>
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
  name: "Home",
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
      datetimeFromFilter: null,
      datetimeToFilter: null,
      total: 5,
    };
  },
};
</script>
