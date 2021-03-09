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

        <div v-if="isAuthenticated || status.comments.length" class="card-footer pb-0">

            <comment-list :statusId="status.id" :comments="status.comments"></comment-list>
            <comment-form :statusId="status.id"></comment-form>

        </div>

    </div>
</template>

<script>

import LikeBtn from "./LikeBtn";
import CommentList from "./CommentList";
import CommentForm from "./CommentForm";


export default {
    components: { LikeBtn, CommentList, CommentForm },
    props: {
        status: {
            type: Object,
            required: true,
        }
    },
}
</script>

<style scoped>

</style>