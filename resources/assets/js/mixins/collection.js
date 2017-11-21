export default {
    data () {
        return {
            items: []
        }
    },

    methods: {
        remove (index) {
            this.$delete(this.items, index)

            this.$emit('removed')
        },

        add (item) {
            this.items.push(item)

            this.$emit('added')
        }
    }
}