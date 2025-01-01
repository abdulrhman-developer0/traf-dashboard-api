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
        icon: { icon: 'tabler-user-plus' },
        path: '/requests', 
        permission: 'dashboard-dashboard-view'
    },
    
    {
        title: 'Clients',
        to: '/clients',
        icon: { icon: 'tabler-users-group' },
        path: '/clients',
        permission: 'dashboard-dashboard-view'
    },
    {
        title: 'Providers',
        to: { name: '/service-providers' },
        icon: { icon: 'tabler-users' },
        path: '/service-providers',
        permission: 'dashboard-dashboard-view'
    },
    {
        title: 'Pricing',
        to: { name: 'pricing' },
        icon: { icon: 'tabler-packages' },
        path: '/pricing',
        permission: 'dashboard-dashboard-view'
    },
    {
        title: 'Services',
        to: { name: 'services' },
        icon: { icon: 'tabler-cut' },
        path: '/services',
        permission: 'dashboard-dashboard-view'
    },
    {
        title: 'Bookings',
        to: { name: 'bookings' },
        icon: { icon: 'tabler-calendar-week' },
        path: '/bookings',
        permission: 'dashboard-dashboard-view'
    },
    {
        title: 'Services types',
        to: { name: 'services-categories' },
        icon: { icon: 'tabler-category' },
        path: '/services-categories',
        permission: 'dashboard-dashboard-view'
    },
    {
        title: 'Payments',
        to: { name: 'payments' },
        icon: { icon: 'tabler-cash' },
        path: '/payments',
        permission: 'dashboard-dashboard-view'
    },
    {
        title: 'Ads',
        to: { name: 'ads' },
        icon: { icon: 'tabler-speakerphone' },
        path: '/ads',
        permission: 'dashboard-dashboard-view'
    },
    {
        title: 'Polices',
        to: { name: 'policies' },
        icon: { icon: 'tabler-license' },
        path: '/policies',
        permission: 'dashboard-dashboard-view'
    },
    
    
  ]
  