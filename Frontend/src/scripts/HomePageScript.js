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
    },
    mounted() {
        setInterval(this.nextImage, 3000);
    },
};