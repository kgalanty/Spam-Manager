<template>
  <div
    id="app"
    v-cloak
  >

   
    <div id="nav" class="hover">
      <router-link to="/">Send E-mails</router-link>
    </div>
    <router-view />
    <Footer />
  </div>
</template>

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
