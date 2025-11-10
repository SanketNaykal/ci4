<?php 
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Login</title>
        <style>
            /* Copy the same CSS from SignupPage.php but remove .field margin-bottom */
            /* ...existing CSS... */
        </style>
    </head>
    <body>
        <div class="wrap">
            <div class="card">
                <div class="left">
                    <h1>Login</h1>
                    <?= session()->getFlashdata('error') ?>
                    <form action="<?= base_url('login') ?>" method="post">
                        <div class="field">
                            <label for="name">Username</label>
                            <input type="text" name="name" id="name" required>
                        </div>
                        <div class="field">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" required>
                        </div>
                        <div class="field">
                            <button type="submit" class="btn">Login</button>
                        </div>
                        <div class="bottom">
                            Need an account? <a href="<?= base_url('signup') ?>">Sign up</a>
                        </div>
                    </form>
                </div>
                <div class="right">
                    <img src="<?= base_url('assets/img/login-otp-banner.png') ?>" alt="banner" class="img-sample">
                </div>
            </div>
        </div>
    </body>
</html>