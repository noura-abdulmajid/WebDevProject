import axios from "@/api/axios.js";

export const login = async (email, password) => {
    try {
        const response = await axios.post("/api/DashShoe/login", {
            email: email,
            password: password,
        });

        const data = response.data;

        if (!data.access_token) {
            throw new Error("Access token is missing in login response!");
        }

        localStorage.setItem("jwt", data.access_token);
        localStorage.setItem("token_type", data.token_type);
        localStorage.setItem("user_id", data.user?.C_ID);
        localStorage.setItem("user_email", data.user?.email_address);

        return data;
    } catch (error) {
        const status = error.response?.status || "Unknown";

        if ([404, 422, 401].includes(status)) {
            alert("Invalid username or password. Please try again.");
        } else {
            alert("Unexpected login error occurred!");
        }

        throw error;
    }
};