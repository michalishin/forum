<template>
    <li class="dropdown" v-if="notifications.length">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <span class="glyphicon glyphicon-bell"></span>
        </a>
        <ul class="dropdown-menu">
            <li v-for="notification in notifications">
                <!--<a href="#" v-text="notification.data.message"></a>-->
                <a @click.prevent="markAsRead(notification)"
                   :href="notification.data.link">
                    {{notification.data.message}}
                </a>
            </li>
        </ul>
    </li>
</template>

<script>
    export default {
        data () {
            return {
                notifications: false
            }
        },

        created () {
            axios.get(`/profiles/${window.App.user.name}/notifications`)
                .then(response => this.notifications = response.data)
        },

        methods: {
            markAsRead (notification) {
                axios.delete(`/profiles/${window.App.user.name}/notifications/${notification.id}`)
                this.$delete(this.notifications, this.notifications.indexOf(notification))
                location.href = notification.data.link
            }
        }
    }
</script>