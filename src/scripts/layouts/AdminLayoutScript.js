import { computed, ref, onMounted } from "vue";
import { useRoute } from "vue-router";

export default {
    setup() {
        const isSidebarCollapsed = ref(localStorage.getItem("sidebarCollapsed") === "true");
        const route = useRoute();

        // ✅ Sidebar Toggle Function (Saves State)
        const toggleSidebar = () => {
            isSidebarCollapsed.value = !isSidebarCollapsed.value;
            localStorage.setItem("sidebarCollapsed", isSidebarCollapsed.value);
        };

        // ✅ Dynamic Breadcrumbs
        const breadcrumbs = computed(() => {
            const breadcrumbMappings = {
                "dashboard": "Dashboard",
                "customers": "Customers",
                "orders": "Orders",
                "payments": "Payments",
                "shipping": "Shipping",
                "products": "Products",
                "settings": "Settings"
            };

            const paths = route.path.split("/").filter((p) => p);
            let fullPath = "";
            return paths.map((segment) => {
                fullPath += `/${segment}`;
                return { name: breadcrumbMappings[segment] || segment.charAt(0).toUpperCase() + segment.slice(1), path: fullPath };
            });
        });



        return { isSidebarCollapsed, toggleSidebar, breadcrumbs };
    },
};
