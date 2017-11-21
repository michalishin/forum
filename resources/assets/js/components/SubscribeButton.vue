<template>
    <button class="btn"
            :class="active ? 'btn-primary' : 'btn-default'"
            @click="toggle">
        Subscribe
    </button>
</template>

<script>
    export default {
        props: ['id', 'active'],

        data () {
          return {
              url: `/threads/${this.id}/subscriptions`
          }
        },

        methods: {
            toggle () {
                !this.active ? this.subscribe() : this.unsubscribe()
            },

            subscribe () {
                axios.post(this.url)

                this.active = true

                flash('Subscribed')
            },

            unsubscribe () {
                axios.delete(this.url)

                this.active = false

                flash('Unsubscribed')
            }
        }
    }
</script>