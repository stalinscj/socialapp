<template>
    <div>
        <div v-for="comment in comments" :key="comment.body" class="mb-3">
            
            <div class="d-flex">
                <img class="rounded shadow-sm mr-2" height="34px" width="34px" :src="comment.user.avatar" :alt="comment.user.name">

                <div class="flex-grow-1">

                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-2 text-secondary">
                            <a :href="comment.user.link"><b v-text="comment.user.name"></b></a>
                            {{ comment.body }}
                        </div>
                    </div>

                    <like-btn class="comments-like-btn" dusk="comment-like-btn" :model="comment" :url="`/comments/${comment.id}/likes`"></like-btn>

                    <small class="float-right badge badge-pill badge-primary py-1 px-2 mt-1" dusk="comment-likes-count">
                        <i class="fa fa-thumbs-up"></i>
                        {{ comment.likes_count }}
                    </small>

                </div>

            </div>

        </div>
    </div>
</template>

<script>

import LikeBtn from "./LikeBtn";

export default {
    components: { LikeBtn },
    props: {
        statusId: {
            type: Number,
            required: true
        },
        comments: {
            type: Array,
            required: true
        },
    },
    data() {
        return {
            
        }
    },
    mounted() {
        EventBus.$on(`statuses.${this.statusId}.comments`, comment => {
            this.comments.push(comment)
        });

        Echo.channel(`statuses.${this.statusId}.comments`).listen('CommentCreatedEvent', ({comment}) => {
            this.comments.push(comment)
        })
    },
}
</script>

<style scoped>
    
</style>