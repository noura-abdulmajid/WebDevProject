<head>

    <meta charset="UTF-8">

    <title>Review Us</title>

    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('/css/style.css') }}">

</head>

<body>

<header>

    <div class="logo">

        <img src="{{ asset('images/logo.png') }}">

    </div>

</header>

<div class="container">

    <div class="wrapper">

        <h2>Review Form</h2>

        <form method="POST" action="/site_review">
            @csrf

            <div class="form-review">

                <label for="review_email">Email Address<span class="required">*</span></label>

                <input type="email" id="review_email" name="review_email" required>

            </div>
            <div class="form-review">
                <label for="review">Review<span class="required">*</span></label>
                <textarea id="review" name="review" rows="5" required></textarea>
            </div>


            <div class="form-split">

                <label>Rating<span class="required">*</span></label>

                <div class="star-rating">

                    <span class="star" data-value="1">&#9733;</span>

                    <span class="star" data-value="2">&#9733;</span>

                    <span class="star" data-value="3">&#9733;</span>

                    <span class="star" data-value="4">&#9733;</span>

                    <span class="star" data-value="5">&#9733;</span>

                </div>

                <input type="hidden" id="ratingValue" name="rating" required>

            </div>

            <button type="submit">Submit</button>

        </form>

    </div>

</div>

<script>

    document.addEventListener("DOMContentLoaded", () => {

        const stars = document.querySelectorAll(".star");

        const ratingInput = document.getElementById("ratingValue");



        if (!stars.length || !ratingInput) return;

        let currentRating = 0;

        for (let star of stars) {

            star.addEventListener("click", () => {

                const rating = star.dataset.value;

                if (currentRating === rating) {

                    ratingInput.value = "";

                    currentRating = 0;

                    for (let s of stars) {

                        s.style.color = "gray";

                    }

                } else {

                    ratingInput.value = rating;

                    currentRating = rating;

                    for (let s of stars) {

                        s.style.color = s.dataset.value <= rating ? "gold" : "gray";

                    }

                }

            });

        }

    });



</script>

</body>
