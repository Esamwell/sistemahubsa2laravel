const API_CONFIG = {
    baseURL: '/api',
    endpoints: {
        auth: {
            login: '/login',
            logout: '/logout',
            me: '/me'
        },
        users: {
            list: '/users',
            create: '/users',
            get: (id) => `/users/${id}`,
            update: (id) => `/users/${id}`,
            delete: (id) => `/users/${id}`
        },
        clients: {
            list: '/clients',
            create: '/clients',
            get: (id) => `/clients/${id}`,
            update: (id) => `/clients/${id}`,
            delete: (id) => `/clients/${id}`
        },
        requests: {
            list: '/requests',
            create: '/requests',
            get: (id) => `/requests/${id}`,
            update: (id) => `/requests/${id}`,
            delete: (id) => `/requests/${id}`,
            updateStatus: (id) => `/requests/${id}/status`
        },
        calendar: {
            list: '/calendar',
            create: '/calendar',
            get: (id) => `/calendar/${id}`,
            update: (id) => `/calendar/${id}`,
            delete: (id) => `/calendar/${id}`
        },
        dashboard: {
            index: '/dashboard'
        }
    }
}; 