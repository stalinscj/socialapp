<template>
    <div>
        <form @submit.prevent="submit">
            <div class="card-body">
                <textarea v-model="body" name="body" class="form-control border-0 bg-light" placeholder="¿Qué estás pensando Stalin?"></textarea>
            </div>
            <div class="card-footer">
                <button class="btn btn-primary" id="create-status">Publicar</button>
            </div>
        </form>
        <div v-for="status in statuses" :key="status.body" v-text="status.body"></div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            body: '',
            statuses: []
        }
    },
    methods: {
        submit() {
        axios.post('/statuses', {body: this.body})
            .then(response => {
                this.statuses.push(response.data)
                this.body = ''
            })
            .catch(err => { 
                console.log(err.response.data)
            })
        }
    }
}
</script>

<style scoped>

</style>