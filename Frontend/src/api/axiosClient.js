import axios from 'axios';

const instance = axios.create({
    baseURL: import.meta.env.VITE_API_BASE_URL || "http://localhost:8000/api",
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
            switch (status) {
                case 401:
                    alert("Unauthorized! Please log in again.");
                    localStorage.removeItem("jwt"); // Clear token
                    window.location.href = "/Homepage";
                    break;
                case 404:
                    alert("Requested resource not found.");
                    break;
                case 422:
                    alert("Validation error occurred.");
                    break;
                default:
                    alert("An unexpected error occurred!");
            }
        } else {
            alert("Network error or server is unreachable!");
        }
        return Promise.reject(error);
    }
);

export default instance;