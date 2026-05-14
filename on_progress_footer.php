<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
    <title>On Progress | Comparan</title>
    <style>

        body{
            margin: 0;
            padding: 0;
            min-height: 100vh;

            display: flex;
            justify-content: center;
            align-items: center;

            background-color: var(--hover-soft);
            font-family: Arial, sans-serif;
        }

        .progressBox{
            width: 90%;
            max-width: 580px;

            padding: 50px 40px;

            text-align: center;

            background: var(--white);

            border: 3px solid var(--primary-main);
            border-radius: 32px;

            box-shadow: 0 10px 30px var(--overlay-dark);
        }

        .progressIcon{
            font-size: 82px;

            color: var(--primary-main);

            animation: floating 3s ease-in-out infinite;
        }

        .progressTitle{
            margin-top: 22px;

            font-size: 2.2rem;
            font-weight: 700;

            color: var(--primary-dark);
        }

        .progressText{
            margin-top: 16px;

            font-size: 15px;
            line-height: 1.8;

            color: var(--primary-dark2);
        }

        .backButton{
            margin-top: 32px;

            display: inline-block;

            padding: 12px 30px;

            border-radius: 999px;

            background-color: var(--primary-main);

            color: var(--white);

            font-weight: 600;
            text-decoration: none;

            transition: 0.3s ease;
        }

        .backButton:hover{
            background-color: var(--primary-dark);

            transform: translateY(-4px);

            color: var(--white);
        }

        @keyframes floating{

            0%{
                transform: translateY(0px);
            }

            50%{
                transform: translateY(-10px);
            }

            100%{
                transform: translateY(0px);
            }
        }

        @media (max-width: 576px){

            .progressBox{
                padding: 40px 24px;
                border-radius: 24px;
            }

            .progressIcon{
                font-size: 64px;
            }

            .progressTitle{
                font-size: 1.7rem;
            }

            .progressText{
                font-size: 14px;
            }

            .backButton{
                width: 100%;
            }
        }

    </style>
</head>
<body>

    <div class="progressBox">

        <i class="bi bi-tree-fill progressIcon"></i>

        <h1 class="progressTitle">
            Feature Under Development
        </h1>

        <p class="progressText">
            This page is currently being developed to provide a better and more sustainable experience for all Comparan users.
            Please check back again later 🌱
        </p>

        <a href="home.php" class="backButton">
            Back To Dashboard
        </a>

    </div>

</body>
</html>