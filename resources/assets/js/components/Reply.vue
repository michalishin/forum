<template>
    <div :id="'reply-' + id"
         class="panel"
         :class="reply.is_best ? 'panel-success' : 'panel-default'">
        <div class="panel-heading">
            <div class="level">
                <h5 class="flex">
                    <a :href="'profiles /'+ reply.owner.name" v-text="reply.owner.name"></a>
                    said
                    <span v-text="ago"></span>...
                </h5>

                <div v-if="signedIn">
                    <favorite :reply="reply"></favorite>
                </div>
            </div>

        </div>
        <div class="panel-body">
            <div v-if="editing">
                <form @submit="update">
                    <div class="form-group">
                        <textarea class="form-control" v-model="body" required></textarea>
                    </div>
                    <button class="btn btn-xs btn-primary">Update</button>
                    <button class="btn btn-xs btn-link" type="button" @click="editing = false">Cancel</button>
                </form>
            </div>
            <div v-else v-html="body"></div>
        </div>
        <!--@can('delete', $reply)-->
        <div class="panel-footer level"
             v-if="authorize('updateReply', reply) || canMarkReplyAsBest">
            <div class="btn btn-xs mr-1"
                 v-if="authorize('updateReply', reply)"
                 @click="editing = true">Edit</div>
            <div class="btn btn-xs btn-danger mr-1"
                 v-if="authorize('updateReply', reply)"
                 @click="destroy">Delete</div>
            <div class="btn btn-xs btn-default ml-a"
                 v-if="canMarkReplyAsBest"
                 @click="markBestReply">Best reply?</div>
        </div>
        <!--@endcan-->
    </div>
</template>

<script>
    import Favorite from './Favorite.vue'
    import moment from 'moment'
    export default {
        components: {Favorite},

        props: ['reply'],

        data () {
            return {
                body: this.reply.body,
                editing: false,
                id: this.reply.id
            }
        },

        methods: {
            update () {
                axios.put('/replies/' + this.reply.id, {
                    body: this.body
                }).then(response => {
                    flash('Updated!')
                    this.editing = false
                }).catch(error => {
                    flash(error.response.reply.message, 'danger')
                })

            },

            destroy () {
                axios.delete('/replies/' + this.reply.id)
                flash('Reply was deleted!')
                this.$emit('deleted', this.reply.id)
            },

            markBestReply () {
                axios.post('/replies/' + this.reply.id + '/best').then(response => {
                    this.$emit('best')
                })
            }
        },
        computed: {
            canMarkReplyAsBest () {
                return this.authorize('updateThread', this.reply.thread)
                    && !this.reply.is_best
            },

            ago () {
                return moment(this.reply.created_at).fromNow()
            }
        }
    }
</script>