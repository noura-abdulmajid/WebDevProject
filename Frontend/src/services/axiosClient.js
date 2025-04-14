import axios from 'axios';

const instance = axios.create({
    baseURL: import.meta.env.VITE_API_BASE_URL || "http://localhost:9000/api",
    headers: {
        "Content-Type": "application/json",
    },
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
            const message = error.response.data?.message || 'An error occurred';
            
            switch (status) {
                case 401:
                    localStorage.removeItem("jwt");
                    window.location.href = "/login";
                    break;
                case 404:
                    console.error('Resource not found:', message);
                    break;
                case 422:
                    console.error('Validation error:', message);
                    break;
                default:
                    console.error('Server error:', message);
            }
            
            // Throw the error with the message
            return Promise.reject(new Error(message));
        } else {
            const message = 'Network error or server is unreachable!';
            console.error(message);
            return Promise.reject(new Error(message));
        }
    }
);

export default instance;