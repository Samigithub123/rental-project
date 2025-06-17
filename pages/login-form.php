<?php require "includes/header.php" ?>
<main>
    <form action="/actions/login-handler.php" class="account-form" method="post">
        <h2>Log in</h2>
        <?php if (isset($_SESSION['success'])) { ?>
            <div class="success-message"><?= $_SESSION['success'] ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php } ?>
        <?php if (isset($_SESSION['error'])) { ?>
            <div class="error-message"><?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php } ?>
        <label for="email">Uw e-mail</label>
        <input type="email" name="email" id="email" placeholder="johndoe@gmail.com" value="<?= isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : '' ?>" required autofocus>
        <label for="password">Uw wachtwoord</label>
        <input type="password" name="password" id="password" placeholder="Uw wachtwoord" required>
        <input type="submit" value="Log in" class="button-primary">
    </form>
    <p style="text-align: center; margin-top: 1rem;">Nog geen account? <a href="/register-form">Registreer hier</a>.</p>
</main>

<style>
.error-message {
    background: #FFF5F5;
    color: #E53E3E;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    text-align: center;
    font-weight: 500;
}

.success-message {
    background: #F0FFF4;
    color: #2F855A;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    text-align: center;
    font-weight: 500;
}
</style>

<?php require "includes/footer.php" ?>








