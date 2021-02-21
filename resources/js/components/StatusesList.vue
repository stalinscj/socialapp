<template>
  <div>
    <div v-for="status in statuses" :key="status.body" v-text="status.body"></div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      statuses: [],
    }
  },
  mounted() {
    axios.get('/statuses')
      .then(response => {
        this.statuses = response.data.data
      })
      .catch(err => {
        console.log(err.response.data)
      });
    
    EventBus.$on('status-created', status => {
      this.statuses.unshift(status)
    });
  },
  
}
</script>

<style scoped>

</style>