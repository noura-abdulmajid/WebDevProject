export const registerUser = async (userData) => {
    return axios.post("/register", userData);
};

export const logoutUser = async () => {
    try {
        // Optional: Call backend API to invalidate session
        await axios.post("/logout");

        // Clear local storage
        localStorage.removeItem("token");
        localStorage.removeItem("role");

        return true; // Indicate success
    } catch (error) {
        console.error("Logout failed:", error);
        return false;
    }
};