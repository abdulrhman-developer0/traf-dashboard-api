import { usePage } from '@inertiajs/vue3'


import dashboard from './partials/dashboard';
import roles from './partials/roles';
import systemLogs from './partials/systemLogs';


const page = usePage()

const user = page.props.user

let nav = []

    nav = [...dashboard, 
            ...roles,
            ...systemLogs
        ];


export default nav
