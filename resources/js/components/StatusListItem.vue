<template>
    <div class="card border-0 mb-3 shadow-sm">
        <div class="card-body d-flex flex-column">
            <div class="d-flex align-items-center mb-3">
                <img class="rounded mr-3 shadow-sm" width="40px" :src="status.user.avatar" :alt="status.user.name">
                <div>
                    <h5 class="mb-1"><a :href="status.user.link" v-text="status.user.name"></a></h5>
                    <div class="small text-muted" v-text="status.ago"></div>
                </div>
            </div>
            <p class="card-text text-secondary" v-text="status.body"></p>
        </div>
        <div class="card-footer p-2 d-flex justify-content-between align-items-center">
            
            <like-btn dusk="like-btn" :model="status" :url="`/statuses/${status.id}/likes`"></like-btn>

            <div class="mr-2 text-secondary">
                <i class="far fa-thumbs-up"></i>
                <span dusk="likes-count" v-text="status.likes_count"></span>
            </div>
        </div>

        <div class="card-footer">
            <comment-list :statusId="status.id" :comments="status.comments"></comment-list>

            <form @submit.prevent="addComment" v-if="isAuthenticated">
                <div class="d-flex align-items-center">
                    <img class="rounded shadow-sm mr-2" width="34px" :src="currentUser.avatar" :alt="currentUser.name">

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
import CommentList from "./CommentList";


export default {
    components: { LikeBtn, CommentList },
    props: {
        status: {
            type: Object,
            required: true,
        }
    },
    data() {
        return {
            newComment: '',
        }
    },
    methods: {
        addComment() {
            axios.post(`/statuses/${this.status.id}/comments`, {body: this.newComment})
                .then(response => {
                    EventBus.$emit('comment-created', response.data.data)
                    this.newComment = ''
                })
                .catch(err => { 
                    console.log(err.response.data)
                })
        }
    }
}
</script>

<style scoped>

</style>