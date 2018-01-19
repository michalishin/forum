let user = window.App.user

export default {
    updateReply (reply) {
        return reply.user_id === user.id
    },

    updateThread (thread) {
        return thread.user_id === user.id
    },

    admin () {
        return user.is_admin
    }
}