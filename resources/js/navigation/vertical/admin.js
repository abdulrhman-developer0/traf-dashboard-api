export default [
    {
        title: 'Dashboard',
        to: '/',
        icon: { icon: 'tabler-smart-home' },
        path: '/',
        permission: 'dashboard-dashboard-view'
    },
    {
        title: 'Join requests',
        to: '/requests',
        icon: { icon: 'tabler-users' },
        path: '/requests', 
        permission: 'dashboard-dashboard-view'
    },
    
    {
        title: 'Clients',
        to: '/clients',
        icon: { icon: 'tabler-cash-register' },
        path: '/clients',
        permission: 'dashboard-dashboard-view'
    },
    {
        title: 'Providers',
        to: { name: '/service-providers' },
        icon: { icon: 'tabler-home-question' },
        path: '/service-providers',
        permission: 'dashboard-dashboard-view'
    },
    {
        title: 'Pricing',
        to: { name: 'pricing' },
        icon: { icon: 'tabler-calendar-time' },
        path: '/pricing',
        permission: 'dashboard-dashboard-view'
    },
    {
        title: 'Services',
        to: { name: 'services' },
        icon: { icon: 'tabler-bell' },
        path: '/services',
        permission: 'dashboard-dashboard-view'
    },
    {
        title: 'Bookings',
        to: { name: 'bookings' },
        icon: { icon: 'tabler-a-b-2' },
        path: '/bookings',
        permission: 'dashboard-dashboard-view'
    },
    {
        title: 'Services types',
        to: { name: 'services-categories' },
        icon: { icon: 'tabler-settings' },
        path: '/services-categories',
        permission: 'dashboard-dashboard-view'
    },
    {
        title: 'Payments',
        to: { name: 'payments' },
        icon: { icon: 'tabler-settings' },
        path: '/payments',
        permission: 'dashboard-dashboard-view'
    },
    {
        title: 'Polices',
        to: { name: 'index' },
        icon: { icon: 'tabler-settings' },
        path: '/admin/settings',
        permission: 'dashboard-dashboard-view'
    },
    {
        title: 'Ads',
        to: { name: 'index' },
        icon: { icon: 'tabler-settings' },
        path: '/admin/settings',
        permission: 'dashboard-dashboard-view'
    },
    
  ]
  