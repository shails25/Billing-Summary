<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <title>Success Message</title>
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card text-center">
        <div class="card-body">
            <img src="https://png.pngtree.com/png-clipart/20230105/original/pngtree-green-check-mark-png-image_8873320.png" alt="Checkmark Image" class="img-fluid mb-3" style="max-width: 100px;">
            <h5 class="card-title text-success">Success!</h5>
            <p class="card-text">Your request has been successfully processed.</p>
            <p class="card-text">ORDER ID: #{{$orderId}}</p>
            <a href="/" class="btn btn-primary">Back to Home</a>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

</body>
</html>
