<template>
    <div @click="redirectIfGuest()">
        <div class="card border-0 mb-3 shadow-sm" v-for="status in statuses" :key="status.body">
            <div class="card-body d-flex flex-column">
                <div class="d-flex align-items-center mb-3">
                    <img class="rounded mr-3 shadow-sm" width="40px" src="img/default-avatar.jpg" alt="User Image">
                    <div>
                        <h5 class="mb-1" v-text="status.user_name"></h5>
                        <div class="small text-muted" v-text="status.ago"></div>
                    </div>
                </div>
                <p class="card-text text-secondary" v-text="status.body"></p>
            </div>
            <div class="card-footer p-2">
                <button v-if="status.is_liked" class="btn btn-link btn-sm" dusk="unlike-btn" @click="unlike(status)">
                    <b><i class="fa fa-thumbs-up text-primary mr-1"></i>TE GUSTA</b>
                </button>
                <button v-else class="btn btn-link btn-sm" dusk="like-btn" @click="like(status)">
                    <i class="far fa-thumbs-up text-primary mr-1"></i>ME GUSTA
                </button>
            </div>
        </div>
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
    methods: {
        like(status) {
            axios.post(`/statuses/${status.id}/likes`)
                .then(response => {
                    status.is_liked = true
                })
                .catch(err => { 
                    console.log(err.response.data)
                })
        },
        unlike(status) {
            axios.delete(`/statuses/${status.id}/likes`)
                .then(response => {
                    status.is_liked = false
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