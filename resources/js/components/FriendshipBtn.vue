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
            return ['PENDING', 'ACCEPTED'].includes(this.localFriendshipStatus)
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
                'PENDING' : 'Cancelar solicitud',
                'ACCEPTED': 'Eliminar de mis amigos',
                'DENIED'  : 'Solicitud denegada'
            })[status] || 'Solicitar amistad'

            return textSwitch(this.localFriendshipStatus)
        },
    },
}
</script>

<style scoped>
    
</style>