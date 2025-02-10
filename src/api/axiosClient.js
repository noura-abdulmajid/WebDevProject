import axios from "axios";

const instance = axios.create({
    baseURL: import.meta.env.VITE_API_BASE_URL || "http://localhost:8000", // Uses .env or default localhost
    headers: {
        "Content-Type": "application/json",
    },
    timeout: 5000, // Request timeout set to 5 seconds
});

// 🔹 Request Interceptor: Attach JWT Token to Headers
instance.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem("jwt"); // Get token
        if (token) {
            config.headers["Authorization"] = `Bearer ${token}`;
        }
        return config;
    },
    (error) => Promise.reject(error)
);

// 🔹 Response Interceptor: Handle API Errors Gracefully
instance.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response) {
            const status = error.response.status;
            if (status === 401) {
                console.warn("Unauthorized! Redirecting to login.");
                alert("Session expired! Please log in again.");
                localStorage.removeItem("jwt"); // Clear token
                window.location.href = "/login"; // Redirect to login
            } else if (status === 404) {
                console.warn("Resource not found!");
                alert("Requested resource not found.");
            } else if (status === 422) {
                console.warn("Validation error!");
                alert("Validation error occurred.");
            } else {
                console.error("Unexpected error:", error.response);
                alert("An unexpected error occurred!");
            }
        } else {
            console.error("Network error:", error);
            alert("Network error or server is unreachable!");
        }
        return Promise.reject(error);
    }
);

export default instance;
