<template>
    <ul class="pagination" v-if="shouldPaginate">
        <li v-show="prevUrl">
            <a href="#" aria-label="Previous" rel="prev" @click.prevent="page--">
                <span aria-hidden="true">&laquo; Previous</span>
            </a>
        </li>

        <li v-show="nextUrl">
            <a href="#" aria-label="Next" rel="Next" @click.prevent="page++">
                <span aria-hidden="true">Next &raquo;</span>
            </a>
        </li>
    </ul>
</template>

<script>
    export default {
        props: ['dataSet'],

        data () {
            return {
                page: 1,
                prevUrl: false,
                nextUrl: false
            }
        },

        watch: {
            dataSet (data) {
                this.page = data.current_page
                this.prevUrl = data.prev_page_url
                this.nextUrl = data.next_page_url
            },

            page () {
                this.broadcast()

                this.updateUrl()
            }
        },

        methods: {
            broadcast () {
                this.$emit('change-page', this.page)
            },

            updateUrl () {
                history.pushState(null, null, `?page=${this.page}`)
            }
        },

        computed: {
            shouldPaginate () {
                return !! this.prevUrl || !! this.nextUrl
            }
        }
    }
</script>