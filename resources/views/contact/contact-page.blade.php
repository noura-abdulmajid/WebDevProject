<head>

    <meta charset="UTF-8">

    <title>Contact Us</title>

    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('/css/style.css') }}">

</head>

<body>

<header>

    <div class="logo">

        <img src="{{ asset('images/logo.png') }}" alt="logo">

    </div>

</header>

<div class="container">

    <div class="wrapper">

        <h1>Contact Us</h1>

        <form method="POST" action="/contact">
            @csrf

            <div class="contact_section">

                <div class="split">

                    <div class="form-split">

                        <label for="first_name">First Name <span class="required">*</span></label>

                        <input type="text" id="first_name" name="first_name" required>

                    </div>

                    <div class="form-split">

                        <label for="last_name">Last Name <span class="required">*</span></label>

                        <input type="text" id="last_name" name="last_name" required>

                    </div>

                </div>

                <div class="contact_section">

                    <label for="email">Email Address<span class="required">*</span></label>

                    <input type="email" id="email" name="email" required>

                </div>

                <div class="contact_section">

                    <label for="message">Message<span class="required">*</span></label>

                    <textarea id="message" name="message" rows="10" required></textarea>

                </div>

                <button type="submit">Submit</button>

            </div>

        </form>

    </div>

</div>

</body>
