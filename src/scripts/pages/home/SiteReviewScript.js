import axios from "@/services/axiosClient.js";

export default {
    data() {
        return {
            reviewEmail: "",
            rating: 0,
            reviewText: "",
        };
    },
    methods: {
        setRating(star) {
            this.rating = this.rating === star ? 0 : star;
        },
        async submitForm() {
            if (!this.reviewEmail || !this.rating || !this.reviewText) {
                alert("All fields are required.");
                return;
            }
            const headers = {
                "Content-Type": "application/json",
            };

            const formData = {
                email: this.reviewEmail,
                rating: this.rating,
                review: this.reviewText,
            };

            try {
                const response = await axios.post("/DashShoe/site-review", formData,{headers});

                if (response && response.status === 200) {
                    alert(`Thank you for your review!`);
                } else {
                    alert("Failed to submit your review. Please try again.");
                }
            } catch (error) {
                console.error("Error submitting the form:", error);
                alert("An error occurred while submitting your review.");
            }

            this.reviewEmail = "";
            this.rating = 0;
            this.reviewText = "";
        },
    },
};