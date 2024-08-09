<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1, width=device-width" />

    <link rel="stylesheet" href="{{ asset('css/login_user.css') }}" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400;1,700&display=swap" />
    <title>Motrix : Login</title>
    <link rel="icon" href="{{ asset('img/icons/motrix-logo.png') }}">
</head>

<body>
    <div class="login">
        <img class="denise-jans-ki-bqnrhew8-unspla-icon" alt="" src="{{ asset('img/icons/denise-jans-Ki_BqNrHeW8-unsplash.png') }}" />
        <div class="asset-4-1-wrapper">
            <img class="asset-4-1" loading="lazy" alt="" src="{{ asset('img/icons/Asset-4-1.png') }}" />
        </div>
        <form class="login-form" method="POST" action="{{ route('userlogin.post') }}">
            @csrf
            <div class="login-form-child"></div>
            <div class="input-fields">
                <div class="username-email"> Username </div>
                <input class="input-fields-child" type="text" name="username"></input>
            </div>
            <div class="input-fields1">
                <div class="password"> Password </div>
                <input class="input-fields-child" type="password" name="password"></input>
            </div>
            <div class="input-fields2">
                <button type="submit" class="vector-parent">
                    <b class="log-in">LOG IN</b>
                </button>
                <div class="forget-your-password-wrapper">
                    <div class="forget-your-password">Forget your password?</div>
                </div>
            </div>
            <div class="divider">
                <div class="line-2-stroke-wrapper">
                    <img class="line-2-stroke" loading="lazy" alt="" src="./public/line-2-stroke.svg" />
                </div>
                <div class="or">or</div>
                <div class="line-3-stroke-wrapper">
                    <img class="line-3-stroke" loading="lazy" alt="" src="./public/line-2-stroke.svg" />
                </div>
            </div>
            <div class="input-fields3">
                <button class="rectangle-parent">
                    <div class="frame-item"></div>
                    <div class="log-in-with">Log in with google</div>
                </button>
                <div class="dont-have-an-account-yet-regi-wrapper">
                    <a href="{{ route('register.page') }}" class="dont-have-an" id="dontHaveAn">
                        <span> Dont have an account yet? </span> Register
                    </a>
                </div>
            </div>
        </form>
    </div>
</body>

</html>