<template>
    <div>
        <div v-if="signedIn">
            <div class="form-group">
                        <textarea name="body"
                                  id="reply-body"
                                  placeholder="Have something to say?"
                                  rows="5"
                                  class="form-control"
                                  required
                                  v-model="body"></textarea>
            </div>
            <button type="button"
                    class="btn btn-default" @click="addReply">Post
            </button>
        </div>
        <p class="text-center" v-else>
            Please <a href="/login">sign in</a>
            to participate in this discussion
        </p>
    </div>
</template>
<script>
    export default {
        props: ['endpoint'],
        data () {
            return {
                body: ''
            }
        },

        computed: {
            signedIn () {
                return window.App.signedIn
            }
        },

        methods: {
            addReply () {
                axios.post(this.endpoint, {body: this.body})
                    .catch(error => {
                        flash(error.response.data, 'danger')
                    })
                    .then(({data}) => {
                        this.body = ''

                        flash('Your reply has been posted.')

                        this.$emit('created', data)
                    })
            }
        }
    }
</script>