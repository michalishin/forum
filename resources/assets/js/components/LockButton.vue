<template>
    <button class="btn"
            v-if="authorize('admin')"
            :class="state ? 'btn-danger' : 'btn-default'"
            @click="toggle">
        Lock
    </button>
</template>

<script>
    export default {
        props: ['thread'],

        data () {
          return {
              url: this.thread.path,
              state: this.thread.locked
          }
        },

        methods: {
            toggle () {
                !this.state ? this.lock() : this.unlock()
            },

            lock () {
                axios.patch(this.url, {
                    locked: true
                }).then(() => {
                    this.state = true
                    this.$emit('lock', true)
                    flash('Locked')
                })
            },

            unlock () {
                axios.patch(this.url, {
                    locked: false
                }).then(() => {
                    this.state = false
                    this.$emit('lock', false)
                    flash('Unlocked')
                })
            }
        }
    }
</script>