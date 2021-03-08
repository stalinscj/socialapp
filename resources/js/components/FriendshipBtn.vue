<template>
    
    <button @click="toggleFriendshipStatus()">
        {{ getText }}
    </button>
    
</template>

<script>
export default {
    props: {
        recipient: {
            type: Object,
            required: true,
        },
        friendshipStatus: {
            type: String,
            required: true,
        },
    },
    methods: {
        toggleFriendshipStatus() {
            let method = this.getMethod()

            axios[method](`/friendships/${this.recipient.name}`)
                .then(response => {
                    this.localFriendshipStatus = response.data.friendship_status
                })
                .catch(err => { 
                    console.log(err.response.data)
                })
        },
        getMethod() {
            return this.localFriendshipStatus == 'PENDING'
                ? 'delete'
                : 'post'

        }
    },
    data() {
        return {
            localFriendshipStatus: this.friendshipStatus
        }
    },
    computed: {
        getText() {
            const textSwitch = (status) => ({
                'PENDING' : 'Cancelar Solicitud',
                'ACCEPTED': 'Son Amigos',
                'DENIED'  : 'Solicitud Rechazada'
            })[status] || 'Solicitar Amistad'

            return textSwitch(this.localFriendshipStatus)
        },
    },
}
</script>

<style scoped>
    
</style>