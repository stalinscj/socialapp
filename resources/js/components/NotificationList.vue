<template>
    <li class="nav-item dropdown">
        <a dusk="notifications" class="nav-link dropdown-toggle" :class="unreadCount ? 'text-primary font-weight-bold' : ''" 
            href="#" id="dropdownNotifications" data-toggle="dropdown"  role="button" aria-haspopup="true" aria-expanded="false" >
            <slot></slot> <span dusk="notifications-count" v-text="unreadCount ? unreadCount : ''"></span>
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

        if (this.isAuthenticated) {
            
            Echo.private(`App.Models.User.${this.currentUser.id}`)
                .notification(notification => {
                    this.unreadCount++
                    this.notifications.unshift({
                        id: notification.id,
                        data: {
                            link: notification.link,
                            message: notification.message,
                        }
                    })
                })
        }

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