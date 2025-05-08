// Configuração do Axios
axios.defaults.baseURL = '/api';
axios.interceptors.request.use(config => {
    const token = localStorage.getItem('token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

// Funções de utilidade
const api = {
    async login(email, password) {
        const response = await axios.post('/login', { email, password });
        localStorage.setItem('token', response.data.token);
        localStorage.setItem('user', JSON.stringify(response.data.user));
        return response.data;
    },

    logout() {
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        window.location.href = '/login';
    },

    async getDashboard() {
        const response = await axios.get('/dashboard');
        return response.data;
    },

    async getClients() {
        const response = await axios.get('/clients');
        return response.data;
    },

    async getClient(id) {
        const response = await axios.get(`/clients/${id}`);
        return response.data;
    },

    async createClient(data) {
        const response = await axios.post('/clients', data);
        return response.data;
    },

    async updateClient(id, data) {
        const response = await axios.put(`/clients/${id}`, data);
        return response.data;
    },

    async deleteClient(id) {
        await axios.delete(`/clients/${id}`);
    },

    async getRequests() {
        const response = await axios.get('/requests');
        return response.data;
    },

    async getRequest(id) {
        const response = await axios.get(`/requests/${id}`);
        return response.data;
    },

    async createRequest(data) {
        const response = await axios.post('/requests', data);
        return response.data;
    },

    async updateRequest(id, data) {
        const response = await axios.put(`/requests/${id}`, data);
        return response.data;
    },

    async updateRequestStatus(id, status, comment = null) {
        const response = await axios.put(`/requests/${id}/status`, { status, comment });
        return response.data;
    },

    async deleteRequest(id) {
        await axios.delete(`/requests/${id}`);
    },

    async getCalendar(start, end) {
        const response = await axios.get('/calendar', {
            params: { start, end }
        });
        return response.data;
    },

    async createEvent(data) {
        const response = await axios.post('/calendar', data);
        return response.data;
    },

    async updateEvent(id, data) {
        const response = await axios.put(`/calendar/${id}`, data);
        return response.data;
    },

    async deleteEvent(id) {
        await axios.delete(`/calendar/${id}`);
    }
};

// Verifica autenticação
function checkAuth() {
    const token = localStorage.getItem('token');
    if (!token && window.location.pathname !== '/login') {
        window.location.href = '/login';
    }
}

// Inicialização
document.addEventListener('DOMContentLoaded', () => {
    checkAuth();
}); 