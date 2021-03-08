<template>
    
    <div>

        <div v-if="localFriendshipStatus == 'PENDING'">

            <span v-text="sender.name"></span> te ha enviado una solicitus de amistad

            <button dusk="accept-friendship" @click="acceptFriendshipRequest()">
                Aceptar Solicitud
            </button>

            <button dusk="deny-friendship" @click="denyFriendshipRequest()">
                Denegar Solicitud
            </button>

        </div>
        <div v-else-if="localFriendshipStatus == 'ACCEPTED'">
            Tú y <span v-text="sender.name"></span> son amigos
        </div>
        <div v-else-if="localFriendshipStatus == 'DENIED'">
            Solicitud denegada de <span v-text="sender.name"></span>
        </div>

        <div v-if="localFriendshipStatus == 'DELETED'">Solicitud eliminada</div>
        <button v-else dusk="delete-friendship" @click="deleteFriendship()">
            Eliminar
        </button>

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
                    this.localFriendshipStatus = response.data.friendship_status
                })
                .catch(err => {
                    console.log(err.response.data)
                })
        },
        denyFriendshipRequest() {

            axios.delete(`/accept-friendships/${this.sender.name}`)
                .then(response => {
                    this.localFriendshipStatus = response.data.friendship_status
                })
                .catch(err => {
                    console.log(err.response.data)
                })
        },
        deleteFriendship() {

            axios.delete(`/friendships/${this.sender.name}`)
                .then(response => {
                    this.localFriendshipStatus = response.data.friendship_status
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