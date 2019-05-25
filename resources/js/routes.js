import Login from "./views/Login";
import AssigneeRounds from "./views/assignee/Rounds";
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
        path: '/assignee',
        name: 'assignee.rounds',
        component: AssigneeRounds
    },
    {
        path: '/account',
        name: 'account.rounds',
        component: AccountRounds,
    },
]