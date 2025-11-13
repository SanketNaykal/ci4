<!doctype html>
<html lang="en">
        <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
                <title>Signup — Para1 style</title>
                <style>
                        :root{ --card-bg: #fff; --accent: #2f6fbf; --muted:#666; }
                        *{box-sizing:border-box}
                        body{font-family:Segoe UI, Roboto, Arial, sans-serif;background:#eee;margin:0;padding:24px}
                        .wrap{max-width:1100px;margin:0 auto}
                        .card{background:var(--card-bg);border-radius:20px;display:flex;overflow:hidden;box-shadow:0 8px 30px rgba(0,0,0,0.08)}
                        .left, .right{padding:36px}
                        .left{flex:1 1 420px}
                        .right{flex:1 1 380px;background:linear-gradient(180deg,#fafafa,#f3f3f3);display:flex;align-items:center;justify-content:center}
                        h1{margin:0 0 18px;font-size:28px;text-align:left}
                        form{width:100%}
                        .field{margin-bottom:14px}
                        label{display:block;font-size:13px;color:var(--muted);margin-bottom:6px}
                        input[type="text"],input[type="email"],input[type="password"]{width:100%;padding:12px 14px;border-radius:8px;border:1px solid #d7d7d7;font-size:15px}
                        .btn{display:inline-block;padding:11px 22px;border-radius:10px;background:var(--accent);color:#fff;border:0;font-weight:600;cursor:pointer}
                        .bottom{margin-top:10px;font-size:14px;color:var(--muted)}
                        .img-sample{max-width:100%;height:auto;border-radius:8px}
                        @media (max-width:880px){.card{flex-direction:column}.right{order:2;padding:18px}.left{order:1;padding:22px}}
                </style>
        </head>
        <body>
                <div class="wrap">
                        <div class="card">
                                <div class="left">
                                        <h1>Sign up For ci4</h1>
                                        <form action="signup" method="post" autocomplete="off">
                                                <div class="field">
                                                        <label for="name">Unique User Name</label>
                                                        <input id="name" name="name" type="text" required>
                                                </div>
                                                <div class="field">
                                                        <label for="email">Your Email</label>
                                                        <input id="email" name="email" type="email" required>
                                                </div>
                                                <div class="field">
                                                        <label for="password">Password</label>
                                                        <input id="password" name="password" type="password" required>
                                                </div>
                                                <div class="field">
                                                        <label for="passwordcom">Repeat your password</label>
                                                        <input id="passwordcom" name="passwordcom" type="password" required>
                                                </div>
                                                <div class="field">
                                                        <button type="submit" class="btn">Signup</button>
                                                </div>
                                                <div class="bottom">Have an account? <a href="/login">Login</a></div>
                                        </form>
                                </div>
                                <div class="right">
                                        <?php $img = function_exists('base_url') ? base_url('para1/img/login-otp-banner.png') : '/para1/img/login-otp-banner.png'; ?>
                                        <img src="\public\assets\images\login-otp-banner.png" alt="banner" class="img-sample">
                                </div>
                        </div>
                </div>
        </body>
</html>