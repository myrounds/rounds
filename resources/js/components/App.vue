<template>
    <div>

        <sidedrawer></sidedrawer>

        <header id="header">
            <div class="mui-appbar mui--appbar-line-height">
                    <span class="mui--text-title mui--visible-xs-inline-block">
                        <img src='img/rounds.svg' class='rounds_logo'>
                    </span>
                    <a class="sidedrawer-toggle mui--visible-xs-inline-block mui--visible-sm-inline-block js-show-sidedrawer"><img src='img/human.svg'></a>
                    <a class="sidedrawer-toggle mui--hidden-xs mui--hidden-sm js-hide-sidedrawer"><img src='img/human.svg'></a>
            </div>
            <div id='dateBar'>
                <span class='displayDay'>Monday</span>
                <span class='displayDate'>
                    26/05/2019 
                    <a class='openWeek'></a>
                    <ul class='day_selector'>
                        <li class='activeDay'>M</li>
                        <li>T</li>
                        <li>W</li>
                        <li>T</li>
                        <li>F</li>
                        <li>S</li> 
                        <li>S</li>                                                
                    </ul>
                </span>
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

    $(window).scroll(function() {    
        var scroll = $(window).scrollTop();

        if (scroll >= 50) {
            $(".mui-appbar").addClass('slideUpHd');
        } else {
            $(".mui-appbar").removeClass('slideUpHd');
        }
    });


$(document).ready(function() { 
$('.openWeek').click(function(){
    $(".day_selector").toggleClass('opened');
});
});





</script>