<template>
    <li class="nav-item dropdown">
        <a dusk="notifications" class="nav-link dropdown-toggle" :class="unreadCount ? 'text-primary font-weight-bold' : ''" 
            href="#" id="dropdownNotifications" data-toggle="dropdown"  role="button" aria-haspopup="true" aria-expanded="false" >
            <slot></slot> {{ unreadCount ? unreadCount : '' }}
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownNotifications">
            <div class="dropdown-header text-center">Notificaciones</div>
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
            unreadCount: 0
        }
    },
    created() {
        axios.get('/notifications')
            .then(response => {
                this.notifications = response.data
                this.updateUnreadCount()
            })
            .catch(err => {
                console.log(err.response.data)
            });

        EventBus.$on('notification-read', () => {
            this.unreadCount--
        });

        EventBus.$on('notification-unread', () => {
            this.unreadCount++
        });
    },
    methods: {
        updateUnreadCount() {
            this.unreadCount = this.notifications
                .filter(notification => !notification.read_at)
                .length
        }
    }
}
</script>

<style scoped>
    
</style>