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
    </div>
</template>

<script>
export default {
    data() {
        return {
            body: ''
        }
    },
    methods: {
        submit() {
        axios.post('/statuses', {body: this.body})
            .then(response => {
                EventBus.$emit('status-created', response.data.data)
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