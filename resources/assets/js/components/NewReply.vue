<template>
    <div>
        <div v-if="signedIn">
            <div class="form-group">
                        <textarea name="body"
                                  ref="body"
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
    import 'jquery.caret'
    import 'at.js'

    export default {
        props: ['endpoint'],
        data () {
            return {
                body: ''
            }
        },

        mounted () {
            $(this.$refs.body).atwho({
                at: '@',
                delay: 750,
                callbacks: {
                    remoteFilter (query, callback) {
                        axios.get("/api/users", {params: {name: query}})
                            .then(({data}) => {
                                callback(data)
                            })
                    }
                }
            })
        },

        methods: {
            addReply () {
                axios.post(this.endpoint, {body: this.body})
                    .catch(error => {
                        flash(error.response.data.message, 'danger')
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