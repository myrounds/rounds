<template>
    <header id="header">
        <div class="mui-appbar mui--appbar-line-height">
            <span class="mui--text-title mui--visible-xs-inline-block">
                <img src='../../images/rounds.svg' class='rounds-logo'>
            </span>
            <a class="sidedrawer-toggle mui--visible-xs-inline-block mui--visible-sm-inline-block js-show-sidedrawer">
                <img src='../../images/human.svg'>
            </a>
            <a class="sidedrawer-toggle mui--hidden-xs mui--hidden-sm js-hide-sidedrawer">
                <img src='../../images/human.svg'>
            </a>
        </div>
        <div id='date-bar'>
            <span class='display-day'>{{day}}</span>
            <span class='display-date'>
                {{date}}
                <a class='open-week' @click="openWeek"></a>
                <ul class='day-selector'>
                    <li v-bind:class="{ 'active-day': day === 'Monday' }">M</li>
                    <li v-bind:class="{ 'active-day': day === 'Tuesday' }">T</li>
                    <li v-bind:class="{ 'active-day': day === 'Wednesday' }">W</li>
                    <li v-bind:class="{ 'active-day': day === 'Thursday' }">T</li>
                    <li v-bind:class="{ 'active-day': day === 'Friday' }">F</li>
                    <li v-bind:class="{ 'active-day': day === 'Saturday' }">S</li>
                    <li v-bind:class="{ 'active-day': day === 'Sunday' }">S</li>
                </ul>
            </span>
        </div>
    </header>
</template>

<script>
    import DateTime from '../helpers/datetime';
    import Strings from '../helpers/strings';
    export default {
        data() {
            return {
                day: null
            };
        },
        created() {
            this.date = DateTime.getCurrentDate();
            this.day = Strings.upperCaseFirstLetter(DateTime.getCurrentDay());

            $(window).scroll(() => {
                const scroll = $(window).scrollTop();

                if (scroll >= 50) {
                    $(".mui-appbar").addClass('slide-up-hd');
                } else {
                    $(".mui-appbar").removeClass('slide-up-hd');
                }
            });
        },
        methods: {
            openWeek() {
                $(".day-selector").toggleClass('opened');
            }
        }
    }
</script>