import axios from "axios";

export default {
    data() {
        return {
            carouselIndex: 0,
            images: [
                { src: "/src/image/beige converse.jpeg", alt: "Shoe 1" },
                { src: "/src/image/pink hearts converse.jpeg", alt: "Shoe 2" },
                { src: "/src/image/PINK TRAINER.jpg.webp", alt: "Shoe 3" },
            ],
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
        async logHomeVisit() {
            try {
                await axios.post(
                    "http://127.0.0.1:8000/api/DashShoe/log-visit",
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
    },
};