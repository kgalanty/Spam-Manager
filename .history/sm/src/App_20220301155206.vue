<template>
  <div
    id="app"
    v-cloak
  >
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@mdi/font@5.8.55/css/materialdesignicons.min.css"
    />
   
    <div id="nav" class="hover">
      <router-link to="/">Send E-mails</router-link>
    </div>
    <router-view />
    <Footer />
  </div>
</template>
<style>
body {
  height: 100vh;
  margin: 0;
  padding: 0px;

  width: 100%;
}
#app {
  display: flex;
  height: 100%;
}
</style>
<style scoped>
#app
{
  background: black;
}
</style>
<script>
import Footer from "./components/Main/Footer";
//import { mapActions } from "vuex";

export default {
  name: "App",
   mixins: [
  ],
  components: {
    Footer,
    
  },
  data() {
    return {
    };
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
