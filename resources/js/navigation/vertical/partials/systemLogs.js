export default [
    {
      title: 'System Logs & Trash',
      icon: { icon: 'tabler-file' },
      children: [
        {
            title: 'System Trash',
            to: 'system-trash',
            path: '/system-trash',
            permission: 'system-logs-and-trash-system-trash-view'
        },
        {
            title: 'Activity log',
            to: 'activity-log',
            path: '/activity-log',
            permission: 'system-logs-and-trash-activity-log-view'
        },
        {
            title: 'System log',
            to: 'system-log',
            path: '/system-log',
            permission: 'system-logs-and-trash-system-log-view'
        }
      ]
    },
]
  