<template>
  <div id="app">
        <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@mdi/font@5.8.55/css/materialdesignicons.min.css"
    />
     <div id="nav" class="hover">
      <router-link to="/">Templates</router-link>
       <router-link to="/">Queue</router-link>
    </div> 
    <router-view />
    <Footer />
  </div>
</template>
<style>
body {
  height: calc(100vh - 60px);
  margin: 0;
  padding: 0px;

  width: 100%;
}
#app {
  background: rgb(36, 36, 36);
}
</style>
<script>
import Footer from "./components/Main/Footer";
import "@/assets/scss/dark.scss";
import "@/assets/scss/global.scss";
//import { mapActions } from "vuex";

export default {
  name: "App",
  mixins: [],
  components: {
    Footer,
  },
  data() {
    return {};
  },
  methods: {
    //...mapActions(["switchStyle"]),
  },
  mounted() {
    // if (window.localStorage.getItem("darktheme") == 'true') {
    //    this.darktheme = true
    //    this.switchStyle(this.darktheme)
    // }
    //this.points = this.calculatePointsFromTags(this.tags, this.invoiceStatus)
  },
};
</script>
