<template>
    <div @click="redirectIfGuest()">
        <transition-group name="status-list-transition">
            <status-list-item v-for="status in statuses" :key="status.id" :status="status"></status-list-item>
        </transition-group>
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