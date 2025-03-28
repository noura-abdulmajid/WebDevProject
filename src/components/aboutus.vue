<template>
<div>
    <header>
    <div class="logo-container">
        <img src="/logo.png" class="logo" alt="DashShoe logo" />
    </div>
    </header>
    <div class="main-container">
    <div class="about-container">
        <div class="wrapper">
        <h1>About DashShoe</h1>
        
        <div class="about-content">
            <section class="about-section">
            <p>At DashShoe, we are dedicated to redefining the online footwear retail experience. Our e-commerce platform offers a diverse and carefully curated selection of high-quality shoes for men, women, and children. From formal and casual wear to performance-driven sports footwear and seasonal collections, our range is designed to meet the needs of every customer.</p>
            
            <p>We are committed not only to style and comfort but also to sustainability. Our products incorporate eco-friendly materials, and we adhere to ethical production practices that prioritize environmental responsibility. By integrating sustainability into our business model, we aim to provide customers with footwear options that align with their values.</p>
            
            <p>At DashShoe, we strive to combine innovation, quality, and sustainability to offer a seamless and responsible shopping experience. Step into the future of footwear with us.</p>
            </section>
        </div>
        </div>
    </div>
    </div>
</div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'

export default defineComponent({
name: 'AboutUs',
setup() {
    return {}
}
})
</script>

<style>
body {
  background-color: #d3c6b2;
  box-shadow: none;
  margin: 0%;
  font-family: 'Roboto', sans-serif;
}
</style>

<style scoped>
header {
padding: 20px;
background-color: #d3c6b2;
}

.logo-container {
width: 200px;
padding: 20px;
}

.logo {
margin-left: -700px;
max-width: 200px;
height: auto;
}

.main-container {
width: 1000px;
margin: 0 auto;
padding: 20px;
display: flex;
justify-content: center;
}

.about-container {
flex: 1;
}

.wrapper {
background-color: #d3c6b2;
border-radius: 12px;
padding: 40px;
}


h1, p {
color: #000000; 
}

h1 {
font-size: 1.99rem;
margin-bottom: 30px;
text-align: center;
}

.about-content {
line-height: 1.6;
}

.about-section {
margin-bottom: 30px;
}

.about-section p {
margin-bottom: 20px;
font-size: 1.1rem;
color: #000;
}

@media (max-width: 768px) {
.main-container {
    width: 100%;
    padding: 10px;
}

.wrapper {
    padding: 20px;
}

h1 {
    font-size: 1.5rem;
}

}
</style>