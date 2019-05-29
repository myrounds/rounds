import Login from "./views/Login";
import MemberRounds from "./views/member/Rounds";
import AccountRounds from './views/Schedule';

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
        component: AccountRounds,
    },
    {
        path: '/schedule/:memberId',
        name: 'schedule',
        component: AccountRounds,
    },
    {
        path: '/member',
        name: 'member.rounds',
        component: MemberRounds
    },
]