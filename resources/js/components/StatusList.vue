<template>
    <div @click="redirectIfGuest()">
        <div v-if="statuses.length">
            <transition-group name="status-list-transition">
                <status-list-item v-for="status in statuses" :key="status.id" :status="status"></status-list-item>
            </transition-group>
        </div>
        <div v-else>
            <p class="card-text text-secondary">No hay estados para mostrar</p>
        </div>
    </div>
</template>

<script>

export default {
    props: {
        url: {
            type: String,
            required: true
        }
    },
    data() {
        return {
            statuses: [],
        }
    },
    mounted() {
        axios.get(this.url)
            .then(response => {
                this.statuses = response.data.data
            })
            .catch(err => {
                console.log(err.response.data)
            });

        EventBus.$on('status-created', status => {
            this.statuses.unshift( { ...status, ...{likes_count: 0} } )
        });

        Echo.channel('statuses').listen('StatusCreatedEvent', ({status}) => {
            this.statuses.unshift( { ...status, ...{likes_count: 0} } )
        })
    },
}
</script>

<style scoped>
    .status-list-transition-move {
        transition: all 1s;
    }
</style>