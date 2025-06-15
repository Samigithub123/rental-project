<?php session_start(); ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="ISO-8859-1">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rydr</title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="icon" type="image/png" href="/assets/images/favicon.ico" sizes="32x32">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<div class="topbar">
    <div class="logo">
        <a href="/">
            Rydr.
        </a>
    </div>
    <nav>
        <ul>
            <li><a href="/">Home</a></li>
            <li><a href="/ons-aanbod">Ons Aanbod</a></li>
            <li><a href="/hulp">Hulp nodig?</a></li>
        </ul>
    </nav>
    <div class="menu">
        <?php if(isset($_SESSION['id'])){ ?>
        <div class="account">
            <img src="<?php echo isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : '/assets/images/profil.png'; ?>" alt="Profile Picture">
            <div class="account-dropdown">
                <ul>
                    <li><img src="/assets/images/icons/setting.svg" alt=""><a href="/pages/account.php">Naar account</a></li>
                    <li><img src="/assets/images/icons/logout.svg" alt=""><a href="/actions/logout.php">Uitloggen</a></li>
                </ul>
            </div>
        </div>
        <?php }else{ ?>
            <a href="login-form" class="button-primary">Start met huren</a>
        <?php } ?>

    </div>
</div>
<div class="content">

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const account = document.querySelector('.account');
    const dropdown = document.querySelector('.account-dropdown');

    if (account && dropdown) {
        account.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        });

        document.addEventListener('click', function(e) {
            if (!account.contains(e.target)) {
                dropdown.style.display = 'none';
            }
        });
    }
});
</script>  