<template>
    <div>
        <div class="level">
            <img :src="avatar" width="50" height="50" class="mr-1">

            <h1 v-text="user.name"> </h1>
        </div>

        <image-upload v-if="canUpdate" name="avatar" @loaded="onLoad"></image-upload>
    </div>
</template>

<script>
    import ImageUpload from './ImageUpload.vue'
    export default {
        components: {ImageUpload},

        props: ['user', 'endpoint'],

        data () {
            return {
                avatar: this.user.avatar
            }
        },

        computed: {
            canUpdate () {
                return this.authorize(user => user.id === this.user.id)
            }
        },

        methods: {
            onLoad (avatar) {
                this.avatar = avatar.src

                this.persist(avatar.file)
            },

            persist (file) {
                let data = new FormData;

                data.append('avatar', file)

                axios.post(this.endpoint, data)
                    .then(() => flash('Avatar uploaded'))
            }
        }
    }
</script>
