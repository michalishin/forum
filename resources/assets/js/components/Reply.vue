<template>
    <div :id="'reply-' + id"
         class="panel"
         :class="this.data.is_best ? 'panel-success' : 'panel-default'">
        <div class="panel-heading">
            <div class="level">
                <h5 class="flex">
                    <a :href="'profiles /'+ data.owner.name" v-text="data.owner.name"></a>
                    said
                    <span v-text="ago"></span>...
                </h5>

                <div v-if="signedIn">
                    <favorite :reply="data"></favorite>
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
        <div class="panel-footer level" v-if="canUpdate || canMarkReplyAsBest">
            <div class="btn btn-xs mr-1"
                 v-if="canUpdate"
                 @click="editing = true">Edit</div>
            <div class="btn btn-xs btn-danger mr-1"
                 v-if="canUpdate"
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

        props: ['data'],

        data () {
            return {
                body: this.data.body,
                editing: false,
                id: this.data.id
            }
        },

        methods: {
            update () {
                axios.put('/replies/' + this.data.id, {
                    body: this.body
                }).then(response => {
                    flash('Updated!')
                    this.editing = false
                }).catch(error => {
                    flash(error.response.data.message, 'danger')
                })

            },

            destroy () {
                axios.delete('/replies/' + this.data.id)
                flash('Reply was deleted!')
                this.$emit('deleted', this.data.id)
            },

            markBestReply () {
                axios.post('/replies/' + this.data.id + '/best').then(response => {
                    this.$emit('best')
                })
            }
        },
        computed: {
            canUpdate () {
                return this.autorize(user => this.data.user_id === user.id)
            },

            canMarkReplyAsBest () {
                return this.autorize(user => this.data.thread.user_id === user.id)
                    && !this.data.is_best
            },

            signedIn () {
                return window.App.signedIn
            },

            ago () {
                return moment(this.data.created_at).fromNow()
            }
        }
    }
</script>