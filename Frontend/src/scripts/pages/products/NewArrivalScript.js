import axios from "axios";
import Cookies from "js-cookie";

export default {
    name: 'MensWear',
    data() {
        return {
            cartCount: 0,
            showDropdown: false,
            selectedFilter: '',
            selectedCategory: '',
            selectedColor: '',
            selectedSort: 'recommended',
            searchTerm: '',
            selectedProduct: null,
            selectedSize: '',
            favourites: [],
            categories: ['Sneakers', 'Boots', 'Sandals'],
            colors: ['Red', 'Blue', 'Green', 'Black'],
            products: []
        };
    },
    computed: {
        filteredProducts() {
            return this.products.filter(prod => {
                if (this.selectedCategory && prod.category !== this.selectedCategory) return false;
                if (this.selectedColor && prod.color !== this.selectedColor) return false;
                if (this.searchTerm) {
                    const search = this.searchTerm.toLowerCase();
                    return (
                        prod.name.toLowerCase().includes(search) ||
                        prod.description.toLowerCase().includes(search) ||
                        prod.color.toLowerCase().includes(search)
                    );
                }
                return true;
            });
        },
        sortedProducts() {
            let sorted = [...this.filteredProducts];
            if (this.selectedSort === 'priceDesc') {
                sorted.sort((a, b) => b.price - a.price);
            } else if (this.selectedSort === 'priceAsc') {
                sorted.sort((a, b) => a.price - b.price);
            } else if (this.selectedSort === 'whatsNew') {
                sorted.sort((a, b) => new Date(b.dateAdded) - new Date(a.dateAdded));
            }
            return sorted;
        }
    },
    methods: {
        async logMenVisit() {
            try {
                await axios.post(
                    "https://cs4team1.cs2410-web01pvm.aston.ac.uk/api/DashShoe/log-visit",
                    {
                        page: "MenCollection-page",
                        timestamp: new Date().toISOString(),
                    },
                    {
                        headers: {
                            "Content-Type": "application/json",
                        }
                    }
                );
            } catch (error) {
                console.error(error);
            }
        },
        updateCartCountFromCookie() {
            const cart = JSON.parse(localStorage.getItem("cart") || "[]");
            this.cartCount = cart.reduce((total, item) => total + item.quantity, 0);
        },
        getImageURL(path) {
            return path;
        },
        openProduct(prod) {
            this.selectedProduct = prod;
        },
        closeModal() {
            this.selectedProduct = null;
        },
        modalAddToCart() {
            if (!this.selectedProduct.selectedSize || this.selectedProduct.selectedSize === '') {
                alert("Please select a size before adding to cart.");
                return;
            }
            this.addToCart(this.selectedProduct);
            this.selectedProduct.selectedSize = '';
            this.closeModal();
        },
        toggleFavourite(product) {
            product.isFavourite = !product.isFavourite;
            if (product.isFavourite) {
                this.favourites.push(product);
            } else {
                const index = this.favourites.findIndex(p => p.name === product.name);
                if (index !== -1) {
                    this.favourites.splice(index, 1);
                }
            }
        },
        async fetchProducts() {
            try {
                const response = await axios.get("https://cs4team1.cs2410-web01pvm.aston.ac.uk/api/DashShoe/products");
                this.products = response.data
                    .filter(product => product.gender_target === 'male' || product.gender_target === 'unisex')
                    .map(product => ({
                        name: product.p_name,
                        image: product.photo,
                        price: parseFloat(product.price),
                        description: product.description,
                        sizes: JSON.parse(product.sizes || '[]'),
                        color: JSON.parse(product.colours || '["Black"]')[0] || 'Black',
                        category: JSON.parse(product.categories || '["Sneakers"]')[0] || 'Sneakers',
                        isFavourite: false,
                        selectedSize: '',
                        dateAdded: product.created_at
                    }));
            } catch (error) {
                console.error("Failed to fetch products", error);
            }
        }
    },
    mounted() {
        this.fetchProducts();
        this.updateCartCountFromCookie();
        this.logMenVisit();
    }
};