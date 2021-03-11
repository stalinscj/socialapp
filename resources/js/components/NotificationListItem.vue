<template>
    <div class="dropdown-item d-flex align-items-center" :class="isRead ? '' : 'bg-light'" >

        <a class="dropdown-item" 
            :href="notification.data.link" v-text="notification.data.message"
            :dusk="notification.id"
        ></a>
        <button v-if="isRead" class="btn btn-link mr-2" :dusk="`mark-as-unread-${notification.id}`" @click.stop="markAsUnread()">
            <i class="far fa-circle"></i>
            <span class="position-absolute bg-dark text-white ml-2 py-1 px-2 rounded">
                Marcar como NO leída
            </span>
        </button>
        <button v-else class="btn btn-link mr-2" :dusk="`mark-as-read-${notification.id}`" @click.stop="markAsRead()">
            <i class="fas fa-circle"></i>
            <span class="position-absolute bg-dark text-white ml-2 py-1 px-2 rounded">
                Marcar como leída
            </span>
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
                    EventBus.$emit('notification-read')
                })
                .catch(err => { 
                    console.log(err.response.data)
                })
        },
        markAsUnread() {
            axios.delete(`/read-notifications/${this.notification.id}`)
                .then(response => {
                    this.isRead = false
                    EventBus.$emit('notification-unread')
                })
                .catch(err => { 
                    console.log(err.response.data)
                })
        },
    },
}
</script>

<style lang="scss" scoped>
    button > span {
        display: none;
    }
    button i {
        &:hover {
            & + span {
                display: inline;
            }
        }
    }
</style>