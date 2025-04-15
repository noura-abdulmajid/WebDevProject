import axios from "axios";

export default {
    data() {
        return {
            carouselIndex: 0,
            images: [
                { src: "/image/beige converse.jpeg", alt: "Shoe 1" },
                { src: "/image/pink hearts converse.jpeg", alt: "Shoe 2" },
                { src: "/image/PINK TRAINER.jpg.webp", alt: "Shoe 3" },
            ],
            reviews: [],
        };
    },
    methods: {
        showImage(index) {
            this.carouselIndex = index;
        },
        nextImage() {
            this.carouselIndex = (this.carouselIndex + 1) % this.images.length;
        },
        prevImage() {
            this.carouselIndex =
                (this.carouselIndex - 1 + this.images.length) % this.images.length;
        },

        async getReviews() {
            try {
                const response = await axios.get(
                    "https://cs4team1.cs2410-web01pvm.aston.ac.uk/api/DashShoe/reviews"
                );
                const allReviews = response.data.reviews;

                // จำกัดแค่ 3 รีวิวแรก
                this.reviews = allReviews
                    .slice(0, 3)
                    .map((r) => ({
                        text: r.review,
                        author: r.reviewer_name || r.review_email || "Anonymous"
                    }));
            } catch (error) {
                console.error("Failed to fetch reviews:", error);
            }
        },


        async logHomeVisit() {
            try {
                await axios.post(
                    "https://cs4team1.cs2410-web01pvm.aston.ac.uk/api/DashShoe/log-visit",
                    {
                        page: "Homepage-page",
                        timestamp: new Date().toISOString(),
                    },
                    {
                        headers: {
                            "Content-Type": "application/json",
                        },
                    }
                );
            } catch (error) {
                console.error("Failed to log user visit:", error);
            }
        },
    },

    mounted() {
        setInterval(this.nextImage, 3000);
        this.logHomeVisit();
        this.getReviews();
    },
};
