<template>
    <div>

        <a class="dropdown-item" 
            :href="notification.data.link" v-text="notification.data.message"
            :dusk="notification.id"
        ></a>
        
        <button v-if="isRead" :dusk="`mark-as-unread-${notification.id}`" @click.stop="markAsUnread()">
            Marcar como No leída
        </button>
        <button v-else :dusk="`mark-as-read-${notification.id}`" @click.stop="markAsRead()">
            Marcar como leída
        </button>

    </div>
</template>

<script>

export default {
    props: {
        notification: {
            type: Object,
            required: true
        },
    },
    data() {
        return {
            isRead: !! this.notification.read_at
        }
    },
    methods: {
        markAsRead() {
            axios.post(`/read-notifications/${this.notification.id}`)
                .then(response => {
                    this.isRead = true
                })
                .catch(err => { 
                    console.log(err.response.data)
                })
        },
        markAsUnread() {
            axios.delete(`/read-notifications/${this.notification.id}`)
                .then(response => {
                    this.isRead = false
                })
                .catch(err => { 
                    console.log(err.response.data)
                })
        },
    },
}
</script>

<style scoped>
    
</style>