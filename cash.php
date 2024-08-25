<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Interface</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 10px;
        }
        .product-item {
            padding: 10px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            text-align: center;
            cursor: pointer;
        }
        .ticket {
            border: 1px solid #dee2e6;
            padding: 10px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9">
                <h5 class="bg-success text-white p-2">Round Neck Softex/Whistler</h5>
                <div class="product-grid">
                    <button class="product-item btn">SOFTEX/WHISTLER-BLACK(XL)</button>
                    <button class="product-item btn">SOFTEX/WHISTLER-BLACK(L)</button>
                    <button class="product-item btn">SOFTEX/WHISTLER-BLACK(M)</button>
                    <button class="product-item btn">SOFTEX/WHISTLER-BLACK(S)</button>
                    <button class="product-item btn">SOFTEX/WHISTLER-CH(L)</button>
                    <button class="product-item btn">SOFTEX/WHISTLER-CH(M)</button>
                    <button class="product-item btn">SOFTEX/WHISTLER-CH(S)</button>
                    <button class="product-item btn">SOFTEX/WHISTLER-M(L)</button>
                    <button class="product-item btn">SOFTEX/WHISTLER-M(M)</button>
                    <button class="product-item btn">SOFTEX/WHISTLER-M(S)</button>
                </div>
            </div>
            <div class="col-md-3">
                <div class="ticket bg-light p-2">
                    <h5>Ticket</h5>
                    <ul class="list-unstyled">
                        <li>SOFTEX/WHISTLER-BLACK(XL) x 1 <span class="float-right">₱121.00</span></li>
                    </ul>
                    <hr>
                    <div class="text-right">
                        <strong>Total: ₱121.00</strong>
                    </div>
                </div>
                <button class="btn btn-success btn-block mt-2">CHARGE</button>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
