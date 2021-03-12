<template>
    
    <button :class="getBtnClasses" @click="toggle()">
        <i :class="getIconClasses"></i>
        {{ getText }}
    </button>
    
</template>

<script>
export default {
    props: {
        model: {
            type: Object,
            required: true,
        },
        url: {
            type: String,
            required: true
        }
    },
    methods: {
        toggle() {
            let method = this.model.is_liked ? 'delete' : 'post'

            axios[method](this.url)
                .then(response => {
                    this.model.is_liked = !this.model.is_liked
                    this.model.likes_count = response.data.likes_count
                })
                .catch(err => { 
                    console.log(err.response.data)
                })
        }
    },
    computed: {
        getText() {
            return this.model.is_liked ? 'TE GUSTA' : 'ME GUSTA'
        },
        getBtnClasses() {
            return [
                'btn',
                'btn-link',
                'btn-sm',
                this.model.is_liked ? 'font-weight-bold' : '',
            ]
        },
        getIconClasses() {
            return [
                'fa-thumbs-up',
                'text-primary',
                'mr-1',
                this.model.is_liked ? 'fa' : 'far',
            ]
        },
    },
}
</script>

<style lang="scss" scoped>
    .comments-like-btn {
        font-size: 0.8em;
        padding-left: 0;
        i { display: none; }
    }
</style>