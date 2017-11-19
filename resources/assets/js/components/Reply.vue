<script>
    import Favorite from './Favorite.vue'
    export default {
        components: {Favorite},
        props: ['attributes'],
        data () {
            return {
                body: this.attributes.body,
                editing: false
            }
        },
        methods: {
            update () {
                axios.put('/replies/' + this.attributes.id, {
                    body: this.body
                })
                flash('Updated!')
                this.editing = false
            },
            destroy () {
                axios.delete('/replies/' + this.attributes.id)
                $(this.$el).fadeOut(300, () => {
                    flash('Your reply has been deleted.')
                })
            }
        }
    }
</script>