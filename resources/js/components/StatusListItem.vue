<template>
    <div class="card border-0 mb-3 shadow-sm">
        <div class="card-body d-flex flex-column">
            <div class="d-flex align-items-center mb-3">
                <img class="rounded mr-3 shadow-sm" width="40px" src="img/default-avatar.jpg" alt="User Image">
                <div>
                    <h5 class="mb-1" v-text="status.user_name"></h5>
                    <div class="small text-muted" v-text="status.ago"></div>
                </div>
            </div>
            <p class="card-text text-secondary" v-text="status.body"></p>
        </div>
        <div class="card-footer p-2 d-flex justify-content-between align-items-center">
            
            <like-btn :status="status"></like-btn>

            <div class="mr-2 text-secondary">
                <i class="far fa-thumbs-up"></i>
                <span dusk="likes-count" v-text="status.likes_count"></span>
            </div>

            <form @submit.prevent="addComment">
                <textarea name="comment" v-model="newComment" rows="1"></textarea>
                <button dusk="comment-btn">Enviar</button>
            </form>

            <div v-for="comment in comments" :key="comment.body" v-text="comment.body"></div>

        </div>
    </div>
</template>

<script>

import LikeBtn from "./LikeBtn";

export default {
    components: { LikeBtn },
    props: {
        status: {
            type: Object,
            required: true,
        }
    },
    data() {
        return {
            newComment: '',
            comments: this.status.comments,
        }
    },
    methods: {
        addComment() {
            axios.post(`/statuses/${this.status.id}/comments`, {body: this.newComment})
                .then(response => {
                    this.newComment = ''
                    this.comments.push(response.data.data)
                })
                .catch(err => { 
                    console.log(err.response.data)
                })
        },
    }
}
</script>

<style scoped>

</style>