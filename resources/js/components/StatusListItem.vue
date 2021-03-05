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
        </div>

        <div class="card-footer">
            <div v-for="comment in comments" :key="comment.body">
                <img class="rounded shadow-sm float-left mr-2" width="34px" :src="comment.user_avatar" :alt="comment.user_name">
                <div class="card mb-3 border-0 shadow-sm">
                    <div class="card-body p-2 text-secondary">
                        <a href="#"><b v-text="comment.user_name"></b></a>
                        {{ comment.body }}
                    </div>
                </div>
                <span dusk="comment-likes-count" v-text="comment.likes_count"></span>
                <button v-if="comment.is_liked" class="btn btn-link btn-sm" dusk="comment-unlike-btn" @click="unlikeComment(comment)">
                    <b><i class="fa fa-thumbs-up text-primary mr-1"></i>TE GUSTA</b>
                </button>

                <button v-else class="btn btn-link btn-sm" dusk="comment-like-btn" @click="likeComment(comment)">
                    <i class="far fa-thumbs-up text-primary mr-1"></i>ME GUSTA
                </button>
            </div>

            <form @submit.prevent="addComment" v-if="isAuthenticated">
                <div class="d-flex align-items-center">
                    <img class="rounded shadow-sm mr-2" width="34px" src="img/default-avatar.jpg" :alt="currentUser.name">

                    <div class="input-group">
                        <textarea v-model="newComment" class="form-control border-0 shadow-sm" required
                            name="comment" rows="1" placeholder="Escribe un comentario..."></textarea>
                        <div class="input-group-append">
                            <button class="btn btn-primary" dusk="comment-btn">Enviar</button>
                        </div>
                    </div>
                </div>
            </form>
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
        likeComment(comment) {
            axios.post(`/comments/${comment.id}/likes`)
                .then(response => {
                    comment.is_liked = true
                    comment.likes_count++
                })
                .catch(err => { 
                    console.log(err.response.data)
                })
        },
        unlikeComment(comment) {
            axios.delete(`/comments/${comment.id}/likes`)
                .then(response => {
                    comment.is_liked = false
                    comment.likes_count--
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