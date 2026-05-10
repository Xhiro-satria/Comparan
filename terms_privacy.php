<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms & Privacy | Comparan</title>

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <style>
        body{
            background: var(--hover-soft);
        }

        .policyContainer{
            max-width: 900px;
            margin: auto;
        }

        .policyCard{
            background: var(--white);
            border-radius: 28px;
            padding: 50px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }

        .policyTitle{
            font-family: 'Alphazet', sans-serif;
            color: var(--primary-main);
            font-size: 48px;
        }

        .policyTitle span{
            color: var(--yellow);
        }

        .policySub{
            color: var(--gray);
            margin-bottom: 40px;
        }

        .policySection{
            margin-bottom: 35px;
        }

        .policySection h3{
            color: var(--primary-dark);
            font-size: 24px;
            margin-bottom: 15px;
        }

        .policySection p{
            color: var(--soft-black);
            line-height: 1.9;
        }

        .btnBack{
            background: var(--primary-main);
            color: white;
            border-radius: 999px;
            padding: 12px 24px;
            text-decoration: none;
            transition: .3s;
        }

        .btnBack:hover{
            background: var(--primary-dark);
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="policyContainer">
        <div class="policyCard">

            <h1 class="policyTitle">
                Terms <span>&</span> Privacy
            </h1>

            <p class="policySub">
                Comparan is committed to creating a safe, sustainable, and comfortable platform for every nature lover.
            </p>

            <div class="policySection">
                <h3><i class="bi bi-shield-check"></i> User Agreement</h3>

                <p>
                    By using Comparan, users agree to use the platform responsibly and respectfully.
                    Every transaction, upload, and interaction must support our mission of sustainable living and environmental awareness.
                </p>
            </div>

            <div class="policySection">
                <h3><i class="bi bi-lock"></i> Privacy Protection</h3>

                <p>
                    Your personal information such as email, username, and transaction data is securely protected.
                    Comparan will never distribute personal information to third parties without user permission.
                </p>
            </div>

            <div class="policySection">
                <h3><i class="bi bi-person-check"></i> Account Responsibility</h3>

                <p>
                    Users are responsible for maintaining account security and protecting their passwords.
                    Any activities performed through your account remain your responsibility.
                </p>
            </div>

            <a href="home.php" class="btnBack">
                <i class="bi bi-arrow-left"></i> Back to Home
            </a>

        </div>
    </div>
</div>
</body>
</html>