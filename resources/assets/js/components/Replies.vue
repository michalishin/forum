<template>
    <div>
        <div v-for="(reply, index) in items"
             :key="reply.id">
            <reply :reply="reply"
                   @best="setBest(reply.id)"
                   @deleted="remove(index)"></reply>
        </div>

        <paginator :dataSet="dataSet" @change-page="fetch"></paginator>

        <new-reply :endpoint="endpoint" @created="add"></new-reply>
    </div>
</template>

<script>
    import Reply from './Reply.vue'
    import NewReply from './NewReply.vue'
    import collection from '../mixins/collection'
    export default {
        props: ['tread_slug'],

        mixins: [collection],

        components: {Reply, NewReply},

        data () {
            return {
                dataSet: false,
                endpoint: '/threads/' + this.tread_slug + '/replies'
            }
        },

        created () {
            this.fetch()
        },

        methods: {
            fetch (page) {
                if (!page) page = this.getDefaultPage()
                let params = {page}
                axios.get(this.endpoint, {params})
                    .then(this.refresh)
            },

            getDefaultPage () {
                let query = location.search.match(/page=(\d+)/)

                return query ? query[1] : 1
            },

            refresh ({data}) {
                this.dataSet = data
                this.items = data.data

                window.scrollTo(0, 0)
            },

            setBest (id) {
                this.items.forEach(item => item.is_best = item.id === id)
            }
        }
    }
</script>