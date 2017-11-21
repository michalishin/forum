<template>
    <button class="btn"
            :class="state ? 'btn-primary' : 'btn-default'"
            @click="toggle">
        Subscribe
    </button>
</template>

<script>
    export default {
        props: ['id', 'active'],

        data () {
          return {
              url: `/threads/${this.id}/subscriptions`,
              state: this.active
          }
        },

        methods: {
            toggle () {
                !this.state ? this.subscribe() : this.unsubscribe()
            },

            subscribe () {
                axios.post(this.url)

                this.state = true

                flash('Subscribed')
            },

            unsubscribe () {
                axios.delete(this.url)

                this.state = false

                flash('Unsubscribed')
            }
        }
    }
</script>