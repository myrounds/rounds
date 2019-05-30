import Login from "./views/Login";
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