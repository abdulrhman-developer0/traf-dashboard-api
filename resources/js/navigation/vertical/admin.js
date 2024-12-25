export default [
    {
        title: 'Dashboard',
        to: '/dashboard',
        icon: { icon: 'tabler-smart-home' },
        path: '/',
        permission: 'dashboard-dashboard-view'
    },
    {
        title: 'Join requests',
        to: '/admin/users-management',
        icon: { icon: 'tabler-users' },
        path: '/admin/users-management',
        permission: 'dashboard-dashboard-view'
    },
    
    {
        title: 'Clients',
        to: '/admin/transactions',
        icon: { icon: 'tabler-cash-register' },
        path: '/admin/transactions',
        permission: 'dashboard-dashboard-view'
    },
    {
        title: 'Providers',
        to: { name: 'index' },
        icon: { icon: 'tabler-home-question' },
        path: '/admin/questions-bank',
        permission: 'dashboard-dashboard-view'
    },
    {
        title: 'Pricing',
        to: { name: 'index' },
        icon: { icon: 'tabler-calendar-time' },
        path: '/admin/schedule',
        permission: 'dashboard-dashboard-view'
    },
    {
        title: 'Services',
        to: { name: 'index' },
        icon: { icon: 'tabler-bell' },
        path: '/admin/notifications',
        permission: 'dashboard-dashboard-view'
    },
    {
        title: 'Bookings',
        to: { name: 'index' },
        icon: { icon: 'tabler-a-b-2' },
        path: '/admin/languages-management',
        permission: 'dashboard-dashboard-view'
    },
    {
        title: 'Services types',
        to: { name: 'index' },
        icon: { icon: 'tabler-settings' },
        path: '/admin/settings',
        permission: 'dashboard-dashboard-view'
    },
    {
        title: 'Payments',
        to: { name: 'index' },
        icon: { icon: 'tabler-settings' },
        path: '/admin/settings',
        permission: 'dashboard-dashboard-view'
    },
    {
        title: 'Polices',
        to: { name: 'index' },
        icon: { icon: 'tabler-settings' },
        path: '/admin/settings',
        permission: 'dashboard-dashboard-view'
    },
    
  ]
  