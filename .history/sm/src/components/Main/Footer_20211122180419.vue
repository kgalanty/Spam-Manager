<template>
  <div class="footerclass">
    Version: {{ packageversion }} :: Last Build: {{ build }}
     - <a @click="openLog">Recent Changes</a>

  </div>
</template>
<style>
</style>
<style scoped>
</style>
<script>
// @ is an alias to /src
//import HelloWorld from '@/components/HelloWorld.vue'
import versionMixin from "@/mixins/versionMixin";
export default {
  name: "Footer",
  mixins: [versionMixin],
  components: {},
  methods: {
    openLog()
    {
       this.$buefy.dialog.alert({
                    title: 'Recent Log',
                    message: this.logmessage,
                    confirmText: 'Cool!',
                    type: 'is-info'
                })
    }
  },
  mounted() {
    //this.points = this.calculatePointsFromTags(this.tags, this.invoiceStatus)
  },
  beforeUpdate() {
    // this.points = this.calculatePointsFromTags(this.tags, this.invoiceStatus)
  },
  computed: {},
  data() {
    return {
      //  points: 0,
    };
  },
  watch: {},
};
</script>
