<template>
    
    <div v-if="localFriendshipStatus == 'PENDING'">
        
        <span v-text="sender.name"></span> te ha enviado una solicitus de amistad
        
        <button dusk="accept-friendship" @click="acceptFriendshipRequest()">
            Aceptar Solicitud
        </button>
        
    </div>
    <div v-else>
        Tú y <span v-text="sender.name"></span> son amigos
    </div>
    
</template>

<script>
export default {
    props: {
        sender: {
            type: Object,
            required: true,
        },
        friendshipStatus: {
            type: String,
            required: true,
        },
    },
    methods: {
        acceptFriendshipRequest() {

            axios.post(`/accept-friendships/${this.sender.name}`)
                .then(response => {
                    this.localFriendshipStatus = 'ACCEPTED'
                })
                .catch(err => {
                    console.log(err.response.data)
                })
        },
    },
    data() {
        return {
            localFriendshipStatus: this.friendshipStatus
        }
    },
}
</script>

<style scoped>
    
</style>