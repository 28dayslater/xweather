<template>
<!-- <div class="backdrop" v-show="showModal">
    <div class="content">
        <h2>{{ message }}</h2>
        {{ point }}
    </div>
</div> -->
<h1>{{ message1 }}</h1>
</template>

<script>
export default {
    name: 'Kurwa',

    data() {
        return {
            point: {},
            message1: "YYYYYYYY,
            showModal: false
        }
    },

    methods: {
        show(point, message) {
            this.point = Object.assign({}, point)
            this.message
        }
    }
}

<style lang="scss" scoped>
.backdrop {
    position: static;
    left: 0;
    top: 0;
    width: 100vh;
    height: 100vh;
    z-index: 200;
    background-color: black;
    opacity: 0.5;
}

.content {
    background-color: #eee;
    opacity: 1.0;
}
</style>
