export default [
    {
      title: 'Roles & Permissions',
      icon: { icon: 'tabler-settings' },
      children: [
        {
            title: 'Roles',
            to: 'roles',
            path: '/roles',
            permission: 'roles-and-permissions-roles-view'
        },
        {
            title: 'Permissions',
            to: 'permissions',
            path: '/permissions',
            permission: 'roles-and-permissions-permissions-view'
        }
      ]
    },
]
  