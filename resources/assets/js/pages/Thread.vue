<script>
    import Replies from '../components/Replies.vue'
    import SubscribeButton from '../components/SubscribeButton.vue'
    import LockButton from '../components/LockButton.vue'
    export default {
        props: ['initialRepliesCount', 'lock'],

        components: {Replies, SubscribeButton, LockButton},

        data () {
            return {
                locked: this.lock,
                repliesCount: this.initialRepliesCount
            }
        }
    }
</script>