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
}
</script>

<style scoped>

</style>