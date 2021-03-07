<template>
    
    <button @click="sendRequestFriendship()" dusk="request-friendship">
        {{ textBtn }}
    </button>
    
</template>

<script>
export default {
    props: {
        recipient: {
            type: Object,
            required: true,
        },
    },
    methods: {
        sendRequestFriendship() {
            axios.post(`friendships/${this.recipient.name}`)
                .then(response => {
                    this.textBtn = 'Solicitud Enviada'
                })
                .catch(err => { 
                    console.log(err.response.data)
                })
        }
    },
    data() {
        return {
            textBtn: 'Solicitar Amistad'
        }
    }
}
</script>

<style scoped>
    
</style>