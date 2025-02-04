import axios from 'axios';

const DEFAULT_BASE_URL = 'http://localhost:8000';

const instance = axios.create({
    baseURL: DEFAULT_BASE_URL,
    timeout: 5000,
});

instance.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem('jwt');
        if (token) {
            config.headers['Authorization'] = `Bearer ${token}`;
        }
        return config;
    },
    (error) => Promise.reject(error)
);

instance.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response) {
            const status = error.response.status;

            if (![404, 422, 401].includes(status)) {
                alert("An unexpected error occurred!");
            }
        } else {
            alert("Network error or server is unreachable!");
        }
        return Promise.reject(error);
    }
);

export default instance;