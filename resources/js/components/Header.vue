<template>
    <header id="header">
        <div class="mui-appbar mui--appbar-line-height" :class="{ 'slide-up-hd': slideUpHeader }">
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
        <div id='date-bar' @click="showDaySelector" :class="{ 'hidden':dateBarHidden }">
            <span class='display-day'>{{selectedDay || 'Whole Week'}}</span>
            <span class='display-date'>
                {{date}}
                <a class='calander-icon'></a>
                <ul class='day-selector' @click="setDay" :class="{ 'opened': daySelectorOpened }">
                    <li v-bind:class="{ 'active-day': selectedDay === null || '' }">/</li>
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
            this.selectedDay = this.currentDay;

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