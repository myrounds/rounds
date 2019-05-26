import Login from "./views/Login";
import MemberRounds from "./views/member/Rounds";
import AccountRounds from './views/account/Rounds';

export default [
    {
        path: '/',
        name: 'login',
        component: Login,
    },
    {
        path: '/login',
        name: 'login',
        component: Login,
    },
    {
        path: '/member',
        name: 'member.rounds',
        component: MemberRounds
    },
    {
        path: '/account',
        name: 'account.rounds',
        component: AccountRounds,
    },
]