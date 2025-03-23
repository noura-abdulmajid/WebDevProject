export default {
    name: "NavbarComponent",
    data() {
        return {
            searchQuery: "",
            isHidden: false,
            lastScrollY: window.scrollY,
            userFirstName: "",
            showDropdown: false,
        };
    },
    computed: {
        // Check Login Status
        isLoggedIn() {
            return !!localStorage.getItem("jwt");
        },
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

        toggleDropdown() {
            this.showDropdown = !this.showDropdown;
        },
        logout() {
            // Clear session
            localStorage.clear();
            this.$router.push("/Homepage");
            window.location.reload(); // optional: refresh to reset layout
        },
    },
    mounted() {
        window.addEventListener("scroll", this.handleScroll);
        this.userFirstName = localStorage.getItem("user_first_name") || "";
    },
    beforeUnmount() {
        window.removeEventListener("scroll", this.handleScroll);
    },
};
