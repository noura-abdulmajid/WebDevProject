const instance = axios.create({
    headers: {
        "Content-Type": "application/json",
    },
});

instance.interceptors.request.use(
    (config) => {
        if (token) {
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
                    alert("Requested resource not found.");
                    alert("Validation error occurred.");
                    alert("An unexpected error occurred!");
            }
        } else {
            alert("Network error or server is unreachable!");
        }
        return Promise.reject(error);
    }
);

export default instance;