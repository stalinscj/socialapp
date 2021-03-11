<template>
    <li class="nav-item dropdown">
        <a dusk="notifications" class="nav-link dropdown-toggle" role="button" data-toggle="dropdown" 
            href="#" id="dropdownNotifications" aria-haspopup="true" aria-expanded="false" >
            <slot></slot>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownNotifications">

            <a v-for="notification in notifications" :key="notification.id" class="dropdown-item" 
                :href="notification.data.link" v-text="notification.data.message"
                :dusk="notification.id"
            ></a>
            
        </div>
    </li>
</template>

<script>

export default {
    data() {
        return {
            notifications: [],
        }
    },
    mounted() {
        axios.get('/notifications')
            .then(response => {
                this.notifications = response.data
            })
            .catch(err => {
                console.log(err.response.data)
            });
    },
}
</script>

<style scoped>
    
</style>