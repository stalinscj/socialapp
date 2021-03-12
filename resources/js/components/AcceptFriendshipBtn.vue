<template>
    
    <div class="d-flex justify-content-between bg-light p-3 rounded mb-3 shadow-sm">

        <div>
            <div v-if="localFriendshipStatus == 'PENDING'">
                <span v-text="sender.name"></span> te ha enviado una solicitus de amistad
            </div>
            <div v-else-if="localFriendshipStatus == 'ACCEPTED'">
                Tú y <span v-text="sender.name"></span> son amigos
            </div>
            <div v-else-if="localFriendshipStatus == 'DENIED'">
                Solicitud denegada de <span v-text="sender.name"></span>
            </div>
            <div v-if="localFriendshipStatus == 'DELETED'">
                Solicitud eliminada de <span v-text="sender.name"></span>
            </div>

        </div>
        
        <div>
            <button v-if="localFriendshipStatus == 'PENDING'" class="btn btn-sm btn-primary" dusk="accept-friendship" @click="acceptFriendshipRequest()">
                Aceptar solicitud
            </button>

            <button v-if="localFriendshipStatus == 'PENDING'" class="btn btn-sm btn-warning" dusk="deny-friendship" @click="denyFriendshipRequest()">
                Denegar solicitud
            </button>
            <button v-if="localFriendshipStatus != 'DELETED'" class="btn btn-sm btn-danger" dusk="delete-friendship" @click="deleteFriendship()">
                Eliminar
            </button>
        </div>

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