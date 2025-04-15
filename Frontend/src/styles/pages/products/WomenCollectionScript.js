import axios from "axios";
import Cookies from "js-cookie";

export default {
    name: "App",
    data() {
        return {
            products: [
                {
                    image: "white loafers.avif",
                    name: "White Loafers",
                    price: "£40",
                },
                {
                    image: "white trainers.jpeg",
                    name: "White Laced Trainers",
                    price: "£82",
                },
                {
                    image: "cream heels.jpeg",
                    name: "Cream Heels",
                    price: "£60",
                },
                {
                    image: "flats.avif",
                    name: "Flats",
                    price: "£25",
                },
                {
                    image: "black shoes.jpeg",
                    name: "Black Shoes",
                    price: "£55",
                },
                {
                    image: "red heels.png",
                    name: "Red Heels",
                    price: "£65",
                },
                {
                    image: "https://via.placeholder.com/200",
                    name: "Sport Sneakers",
                    price: "£70",
                },
                {
                    image: "https://via.placeholder.com/200",
                    name: "Brown Loafers",
                    price: "£50",
                },
            ],
            cartCount: 0,
        };
    },
    methods: {
        async logWomenVisit() {
            try {
                await axios.post(
                    "https://cs4team1.cs2410-web01pvm.aston.ac.uk/api/DashShoe/log-visit",
                    {
                        page: "WomenCollection-page",
                        timestamp: new Date().toISOString(),
                    },
                    {
                        headers: {
                            "Content-Type": "application/json",
                        }
                    }
                );
            } catch (error) {
                console.error("Failed to log user visit:", error);
            }
        },
        getImageURL(image) {
            return new URL(`../image/${image}`, import.meta.url).href;
        },
        addToCart(product) {
            let cart = JSON.parse(Cookies.get("cart") || "[]");

            const productToAdd = {
                image: product.image.startsWith("http") ? product.image : this.getImageURL(product.image),
                name: product.name || "Unknown",
                description: product.description || "No description",
                color: product.color || "Default",
                size: product.size || "Standard",
                quantity: 1,
                price: parseFloat(product.price.replace(/[^0-9.]/g, "")) || 0,
            };

            const existingProduct = cart.find(item => item.name === productToAdd.name);
            if (existingProduct) {
                existingProduct.quantity++;
            } else {
                cart.push(productToAdd);
            }

            Cookies.set("cart", JSON.stringify(cart), { expires: 7 });
            this.cartCount = cart.reduce((total, item) => total + item.quantity, 0);
        },
        updateCartCountFromCookie() {
            const cart = JSON.parse(Cookies.get("cart") || "[]");
            this.cartCount = cart.reduce((total, item) => total + item.quantity, 0);
        },
    },
    mounted() {
        this.logWomenVisit();
        this.updateCartCountFromCookie();
    },
};