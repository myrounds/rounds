<template>
    <div>

        <sidedrawer></sidedrawer>

        <header id="header">
            <div class="mui-appbar mui--appbar-line-height">
                <div class="mui-container-fluid">
                    <a class="sidedrawer-toggle mui--visible-xs-inline-block mui--visible-sm-inline-block js-show-sidedrawer">☰</a>
                    <a class="sidedrawer-toggle mui--hidden-xs mui--hidden-sm js-hide-sidedrawer">☰</a>
                    <span class="mui--text-title mui--visible-xs-inline-block">
                        Rounds
                    </span>
                    <a class="day">Monday</a>
                </div>
            </div>
        </header>

        <div id="content-wrapper">
            <div class="container">
                <router-view></router-view>
            </div>
        </div>

        <!--<footer id="footer">-->
            <!--<div class="mui-container-fluid">-->
                <!--<br>-->
                <!--Footer Content-->
            <!--</div>-->
        <!--</footer>-->

    </div>
</template>
<script>
    import Sidedrawer from './Sidedrawer.vue';

    export default {
        components: {Sidedrawer},
    }
</script>