<template>
    <header id="header">

        <div class="logged-out">
            <img src="../../images/login-icon.svg">
        </div>

        <div class="mui-appbar mui--appbar-line-height" :class="{ 'slide-up-hd': slideUpHeader }">
            <span class="sidedrawer-toggle js-show-sidedrawer"></span>
            <span class="sidedrawer-toggle js-hide-sidedrawer"></span>
            <img src='../../images/icon.svg' alt='' class='logo'>
            <span class="appbar-search">
                <i class='toggleSearch fas fa-search'></i>
                <input type='search' id='search_input' placeholder='Search Rounds'>
            </span>
        </div>

        <div id='date-bar' @click="showDaySelector" :class="{ 'hidden':dateBarHidden }">
            <!--<span class='display-day'>{{selectedDay || 'Whole Week'}}</span>-->
            <span class='display-day'>change day</span>
            <span class='display-date'>
                {{date}}
                <a class='calander-icon'></a>
                <ul class='day-selector' @click="setDay" :class="{ 'opened': daySelectorOpened }">
                    <li v-bind:class="{ 'active-day': selectedDay === null || '' }"><i class="fas fa-calendar-alt"></i></li>
                    <li v-for="btn in days"
                        :id="btn"
                        v-bind:class="{ 'active-day': selectedDay === btn, 'current-day': currentDay === btn }">
                        {{btn[0].toUpperCase()}}
                    </li>
                </ul>
            </span>
        </div>

    </header>
</template>

<script>
    import DateTime from '../helpers/datetime';
    import Events from '../helpers/events';
    import Storage from '../helpers/storage';
    export default {
        data() {
            return {
                days: ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'],
                selectedDay: null,
                currentDay: null,
                slideUpHeader: false,
                dateBarHidden: false,
                daySelectorOpened: false
            };
        },
        created() {
            this.date = DateTime.getCurrentDate();
            this.currentDay = DateTime.getCurrentDay();

            $(window).scroll(() => {
                const scroll = $(window).scrollTop();
                const user = Storage.get('user');

                if (scroll >= 50) {
                    this.slideUpHeader = true;
                    if (user && user.type === 'account') {
                        this.dateBarHidden = true;
                    }
                } else {
                    this.slideUpHeader = false;
                    if (user && user.type === 'account') {
                        this.dateBarHidden = false;
                    }
                }
            });
        },
        methods: {
            showDaySelector() {
                this.daySelectorOpened = !this.daySelectorOpened;
            },
            setDay(event) {
                const day = event.target.id;

                this.selectedDay = day === '' ? null : day;

                Events.dispatch('filters-changed', { day: this.selectedDay });
            }
        }
    }


</script>