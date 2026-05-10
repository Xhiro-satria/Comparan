<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Shipping & Delivery | Comparan</title>

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <style>
        body{
            background: var(--hover-soft);
        }

        .shippingCard{
            background: white;
            border-radius: 28px;
            padding: 50px;
            max-width: 900px;
            margin: auto;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }

        .shippingTitle{
            font-family: 'Alphazet', sans-serif;
            color: var(--primary-main);
            font-size: 48px;
        }

        .shippingTitle span{
            color: var(--yellow);
        }

        .shippingSection{
            margin-top: 35px;
        }

        .shippingSection h3{
            color: var(--primary-dark);
            margin-bottom: 15px;
        }

        .shippingSection p,
        .shippingSection li{
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

    <div class="shippingCard">

        <h1 class="shippingTitle">
            Shipping <span>&</span> Delivery
        </h1>

        <p class="text-secondary mt-3">
            Every plant deserves safe delivery and proper care.
        </p>

        <div class="shippingSection">
            <h3><i class="bi bi-truck"></i> Delivery Information</h3>

            <p>
                Comparan collaborates with trusted delivery services to ensure products arrive safely and in the best condition.
            </p>
        </div>

        <div class="shippingSection">
            <h3><i class="bi bi-clock"></i> Estimated Delivery Time</h3>

            <ul>
                <li>Within city : 1 - 2 days</li>
                <li>Outside city : 3 - 7 days</li>
                <li>Remote areas : depends on courier service</li>
            </ul>
        </div>

        <div class="shippingSection">
            <h3><i class="bi bi-box-seam"></i> Eco Packaging</h3>

            <p>
                Products are packaged carefully using eco-friendly packaging whenever possible to reduce environmental impact.
            </p>
        </div>

        <a href="home.php" class="btnBack">
            <i class="bi bi-arrow-left"></i> Back to Home
        </a>

    </div>

</div>

</body>
</html>