<template>
    <div>
        <div :key="reply.id" v-for="(reply, index) in items">
            <reply :data="reply" @deleted="remove(index)"></reply>
        </div>

        <new-reply :endpoint="storeEndpoint" @created="add">

        </new-reply>
    </div>
</template>

<script>
    import Reply from './Reply.vue'
    import NewReply from './NewReply.vue'
    export default {
        props: ['data' , 'tread_id'],

        components: {Reply, NewReply},

        data () {
            return {
                items: this.data,
                storeEndpoint: '/threads/' + this.tread_id + '/replies'
            }
        },

        methods: {
            remove (index) {
                this.$delete(this.items, index)

                this.$emit('removed')

                flash('Reply was deleted!')
            },

            add (reply) {
                this.items.push(reply)

                this.$emit('added')
            }
        }
    }
</script>