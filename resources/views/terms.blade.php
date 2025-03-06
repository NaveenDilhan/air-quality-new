<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms and Conditions | Colombo Air Quality Monitoring</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .hover-zoom:hover {
            transform: scale(1.05);
            transition: transform 0.3s ease-in-out;
        }
        .custom-container {
            background: rgba(0, 0, 0, 0.7);
            border-radius: 10px;
            padding: 3rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
        }
    </style>
</head>
<body class="bg-gray-900 text-white">

    <div class="container mx-auto px-6 py-12">
        <h1 class="text-5xl font-extrabold text-green-400 mb-8 text-center">Terms and Conditions</h1>

        <div class="custom-container">
            <p class="text-xl mb-8">
                Welcome to the Colombo Air Quality Monitoring website. By accessing or using our website, you agree to comply with and be bound by the following terms and conditions.
            </p>

            <div class="space-y-6">
                <div>
                    <h2 class="text-3xl font-semibold text-yellow-400 mb-4">1. Use of Service</h2>
                    <p class="text-lg mb-4">
                        The Colombo Air Quality Monitoring website provides real-time data about air quality in Colombo. You agree to use the website for lawful purposes only and not to engage in any activity that could harm the service or its users.
                    </p>
                </div>

                <div>
                    <h2 class="text-3xl font-semibold text-yellow-400 mb-4">2. Data Accuracy</h2>
                    <p class="text-lg mb-4">
                        While we strive to provide accurate and up-to-date air quality information, we cannot guarantee the accuracy, completeness, or reliability of the data. Users are encouraged to verify air quality information from other trusted sources.
                    </p>
                </div>

                <div>
                    <h2 class="text-3xl font-semibold text-yellow-400 mb-4">3. Subscription to Alerts</h2>
                    <p class="text-lg mb-4">
                        By subscribing to our alert service, you agree to receive notifications about air quality levels and other related updates. You can unsubscribe at any time through the settings on your account or by following the unsubscribe link in any notification email.
                    </p>
                </div>

                <div>
                    <h2 class="text-3xl font-semibold text-yellow-400 mb-4">4. Privacy</h2>
                    <p class="text-lg mb-4">
                        We value your privacy and are committed to protecting your personal information. Please refer to our Privacy Policy for detailed information about how we collect, use, and protect your data.
                    </p>
                </div>

                <div>
                    <h2 class="text-3xl font-semibold text-yellow-400 mb-4">5. Limitation of Liability</h2>
                    <p class="text-lg mb-4">
                        We are not liable for any direct, indirect, incidental, or consequential damages arising from the use of our website, including but not limited to the loss of data, business interruption, or any other financial loss.
                    </p>
                </div>

                <div>
                    <h2 class="text-3xl font-semibold text-yellow-400 mb-4">6. Changes to Terms</h2>
                    <p class="text-lg mb-4">
                        We reserve the right to modify or update these terms at any time without prior notice. The changes will be effective once posted on this page. It is your responsibility to review the terms periodically.
                    </p>
                </div>

                <div>
                    <h2 class="text-3xl font-semibold text-yellow-400 mb-4">7. Contact Us</h2>
                    <p class="text-lg mb-4">
                        If you have any questions about these terms, please contact us through our website's contact page.
                    </p>
                </div>
            </div>

            <div class="mt-8 text-center">
                <a href="{{ url('/') }}" class="px-8 py-3 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg hover-zoom">
                    Go Back to Homepage
                </a>
            </div>
        </div>
    </div>

</body>
</html>
