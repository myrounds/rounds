<template>
    <transition name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
                <div class="modal-container">

                    <div class="modal-header">
                        <slot name="header">

                        </slot>
                        <button class="modal-default-button mui-btn mui-btn--small mui-btn--primary mui-btn--fab" @click="$emit('close')">
                            x
                        </button>
                    </div>

                    <div class="modal-body">
                        <slot name="body">

                        </slot>
                    </div>

                    <div class="modal-footer">
                        <slot name="footer">

                        </slot>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
    export default {
        data() {
            return {
                day: null
            };
        },
        created() {
        },
        methods: {
        }
    }
</script>