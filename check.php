<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recaptchaToken = $_POST['g-recaptcha-response'];
    $secretKey = '6LcQCa0pAAAAAF7jFzkb15b2_3a-jYZGLfzZ6H8M'; // Your reCAPTCHA secret key
    $url = 'https://www.google.com/recaptcha/api/siteverify';

    $data = [
        'secret' => $secretKey,
        'response' => $recaptchaToken,
        'remoteip' => $_SERVER['REMOTE_ADDR']
    ];

    $options = [
        'http' => [
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data)
        ]
    ];

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    $response = json_decode($result);
    var_dump($response);

    if ($response->success && $response->score >= 0.5) {
        // reCAPTCHA verification successful, process the form submission
        // Your logic here...
        echo 'reCAPTCHA verification successful. Form submitted.';
    } else {
        // reCAPTCHA verification failed
        echo 'reCAPTCHA verification failed.';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>reCAPTCHA v3 Implementation</title>
</head>
<body>
    <form id="demo-form" action="" method="post">
        <!-- Your form fields go here -->
        <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
        <button type="submit" id="submit-button">Submit</button>
    </form>
    <script src="https://www.google.com/recaptcha/api.js?render=6LcQCa0pAAAAAAjzIyzNhfuMRHxecavzGu2vxGL5"></script>
    <script>
        document.getElementById("submit-button").addEventListener("click", function(event) {
            event.preventDefault();
            grecaptcha.ready(function() {
                grecaptcha.execute('6LcQCa0pAAAAAAjzIyzNhfuMRHxecavzGu2vxGL5', { action: 'submit' }).then(function(token) {
                    document.getElementById('g-recaptcha-response').value = token;
                    document.getElementById('demo-form').submit();
                });
            });
        });
    </script>
</body>
</html>
