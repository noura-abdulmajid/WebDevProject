export default {
    name: "NavbarComponent",
    data() {
        return {
            searchQuery: "",
            isHidden: false,
            lastScrollY: window.scrollY,
        };
    },
    methods: {
        searchProducts() {
            console.log("Searching for:", this.searchQuery);
        },
        handleScroll() {
            const currentScrollY = window.scrollY;
            this.isHidden = currentScrollY > this.lastScrollY && currentScrollY > 50;
            this.lastScrollY = currentScrollY;
        },
    },
    mounted() {
        window.addEventListener("scroll", this.handleScroll);
    },
    beforeUnmount() {
        window.removeEventListener("scroll", this.handleScroll);
    },
};
