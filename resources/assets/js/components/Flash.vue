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
                body: this.message,
                show: false,
                level: 'success'
            }
        },
        created () {
            if (this.message) {
              this.flash()
            }

            window.events.$on('flash', data => this.flash(data))
        },
        methods: {
            flash (data = null) {
                if (data) {
                    this.body = data.message;
                    this.level = data.level;
                }
                this.show = true;
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