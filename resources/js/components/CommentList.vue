<template>
    <div>
        <comment-list-item v-for="comment in comments" :key="comment.id" :comment="comment" class="mb-3"></comment-list-item>
    </div>
</template>

<script>

import CommentListItem from "./CommentListItem";

export default {
    components: { CommentListItem },
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
            this.comments.push( { ...comment, ...{likes_count: 0} } )
        });

        Echo.channel(`statuses.${this.statusId}.comments`).listen('CommentCreatedEvent', ({comment}) => {
            this.comments.push( { ...comment, ...{likes_count: 0} } )
        })
    },
}
</script>

<style scoped>
    
</style>