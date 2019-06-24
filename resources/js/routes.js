import Login from "./views/Login";
import Register from "./views/Register";
import Schedule from './views/Schedule';
import Members from "./views/Members";

import MemberRounds from "./views/member/Rounds";

export default [
    {
        path: '/',
        name: 'home',
        component: Login,
    },
    {
        path: '/login',
        name: 'login',
        component: Login,
    },
    {
        path: '/login/account',
        name: 'login.account',
        component: Login,
    },
    {
        path: '/login/member',
        name: 'login.member',
        component: Login,
    },
    {
        path: '/register',
        name: 'register',
        component: Register,
    },
    {
        path: '/schedule',
        name: 'schedule',
        component: Schedule,
    },
    {
        path: '/schedule/:memberId',
        name: 'schedule',
        component: Schedule,
    },
    {
        path: '/members',
        name: 'members',
        component: Members,
    },
    {
        path: '/member',
        name: 'member.rounds',
        component: MemberRounds
    },
]