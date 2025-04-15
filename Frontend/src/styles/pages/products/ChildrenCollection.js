import axios from "axios";
import Cookies from "js-cookie";

export default {
    name: "ChildrensCollection",
    data() {
        return {
            products: [
                {
                    image: "https://via.placeholder.com/200",
                    name: "White Loafers",
                    price: "£40",
                },
                {
                    image: "https://via.placeholder.com/200",
                    name: "White Laced Trainers",
                    price: "£82",
                },
                {
                    image: "https://via.placeholder.com/200",
                    name: "Cream Heels",
                    price: "£60",
                },
                {
                    image: "https://via.placeholder.com/200",
                    name: "Flats",
                    price: "£25",
                },
                {
                    image: "https://via.placeholder.com/200",
                    name: "Black Shoes",
                    price: "£55",
                },
                {
                    image: "https://via.placeholder.com/200",
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
        async logChildrenVisit() {
            try {
                await axios.post(
                    "https://cs4team1.cs2410-web01pvm.aston.ac.uk/api/DashShoe/log-visit",
                    {
                        page: "ChildrenCollection-page",
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
        this.logChildrenVisit();
        this.updateCartCountFromCookie();
    },
};