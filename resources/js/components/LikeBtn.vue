<template>
    
    <button v-if="status.is_liked" class="btn btn-link btn-sm" dusk="unlike-btn" @click="unlike(status)">
        <b><i class="fa fa-thumbs-up text-primary mr-1"></i>TE GUSTA</b>
    </button>
    
    <button v-else class="btn btn-link btn-sm" dusk="like-btn" @click="like(status)">
        <i class="far fa-thumbs-up text-primary mr-1"></i>ME GUSTA
    </button>
</template>

<script>
export default {
    props: {
        status: {
            type: Object,
            required: true,
        }
    },
    methods: {
        like(status) {
            axios.post(`/statuses/${status.id}/likes`)
                .then(response => {
                    status.is_liked = true
                    status.likes_count++
                })
                .catch(err => { 
                    console.log(err.response.data)
                })
        },
        unlike(status) {
            axios.delete(`/statuses/${status.id}/likes`)
                .then(response => {
                    status.is_liked = false
                    status.likes_count--
                })
                .catch(err => { 
                    console.log(err.response.data)
                })
        },
    }
}
</script>

<style scoped>

</style>