<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Return Policy | Comparan</title>

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <style>
        body{
            background: var(--hover-soft);
        }

        .returnCard{
            background: white;
            border-radius: 28px;
            padding: 50px;
            max-width: 900px;
            margin: auto;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }

        .returnTitle{
            font-family: 'Alphazet', sans-serif;
            color: var(--primary-main);
            font-size: 48px;
        }

        .returnTitle span{
            color: var(--yellow);
        }

        .returnSection{
            margin-top: 35px;
        }

        .returnSection h3{
            color: var(--primary-dark);
            margin-bottom: 15px;
        }

        .returnSection p,
        .returnSection li{
            line-height: 1.9;
        }

        .btnBack{
            background: var(--primary-main);
            color: white;
            border-radius: 999px;
            padding: 12px 24px;
            text-decoration: none;
            display: inline-block;
            margin-top: 30px;
        }

        .btnBack:hover{
            background: var(--primary-dark);
        }
    </style>
</head>
<body>

<div class="container py-5">

    <div class="returnCard">

        <h1 class="returnTitle">
            Return <span>Policy</span>
        </h1>

        <p class="text-secondary mt-3">
            We care about customer satisfaction and product quality.
        </p>

        <div class="returnSection">
            <h3><i class="bi bi-arrow-repeat"></i> Return Conditions</h3>

            <ul>
                <li>Returns must be requested within 3 days after receiving the product.</li>
                <li>Products must remain in proper condition.</li>
                <li>Photo proof is required for damaged items.</li>
            </ul>
        </div>

        <div class="returnSection">
            <h3><i class="bi bi-x-circle"></i> Non-returnable Products</h3>

            <p>
                Products damaged due to customer negligence or improper care cannot be returned.
            </p>
        </div>

        <div class="returnSection">
            <h3><i class="bi bi-cash-stack"></i> Refund Process</h3>

            <p>
                Approved refunds will be processed within 3-5 business days depending on the payment method used.
            </p>
        </div>

        <a href="home.php" class="btnBack">
            <i class="bi bi-arrow-left"></i> Back to Home
        </a>

    </div>

</div>

</body>
</html>