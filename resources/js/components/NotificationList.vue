<template>
    <li class="nav-item dropdown">
        <a dusk="notifications" class="nav-link dropdown-toggle" role="button" data-toggle="dropdown" 
            href="#" id="dropdownNotifications" aria-haspopup="true" aria-expanded="false" >
            <slot></slot>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownNotifications">

            <notification-list-item v-for="notification in notifications" 
                :key="notification.id" 
                :notification="notification"
            ></notification-list-item>
            
        </div>
    </li>
</template>

<script>

import NotificationListItem from "./NotificationListItem";

export default {
    components: { NotificationListItem },
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