<template>
    <div :class="classes"
         role="alert"
         v-show="show"
         v-text="body">
    </div>
</template>

<script>
    export default {
        props: ['message'],
        data () {
            return {
                body: '',
                show: false,
                level: 'success'
            }
        },
        created () {
            if (this.message) {
              this.flash(this.message)
            }

            window.events.$on('flash', data => this.flash(data))
        },
        methods: {
            flash ({message, level}) {
              this.show = true;
              this.body = message;
              this.level = level;
              this.hide();
            },
            hide () {
                setTimeout(() => {
                    this.show = false
                }, 3000)
            }
        },
        computed: {
            classes () {
                return `alert alert-flush alert-${this.level}`
            }
        }
    }
</script>

<style>
    .alert-flush {
        position: fixed;
        right: 25px;
        bottom: 25px;
    }
</style>