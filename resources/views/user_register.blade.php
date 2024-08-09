    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="initial-scale=1, width=device-width" />

        <link rel="stylesheet" href="{{ asset('css/user_register.css') }}" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,700&display=swap" />
        <title>Motrix : Register</title>
        <link rel="icon" href="{{ asset('img/icons/motrix-logo.png') }}">
    </head>

    <body>
        <div class="register">
            <img class="denise-jans-ki-bqnrhew8-unspla-icon1" alt="" src="{{ asset('img/icons/denise-jans-Ki_BqNrHeW8-unsplash.png') }}" />

            <form class="frame-form" method="POST" action="{{ route('register.post') }}">
                @csrf
                <div class="input-labels">
                    <div class="username">Username</div>
                </div>
                <div class="input-labels1">
                    <input type="text" name="username" class="input-labels-child"></input>
                </div>
                <div class="input-labels2">
                    <div class="email">Email</div>
                </div>
                <div class="input-labels3">
                    <input type="text" name="email" class="input-labels-child"></input>
                </div>
                <div class="name-input">
                    <div class="nama">Nama</div>
                </div>
                <div class="input-labels4">
                    <input type="text" name="name" class="input-labels-child"></input>
                </div>
                <div class="password-input">
                    <div class="password1">Password</div>
                </div>
                <div class="input-labels5">
                    <input type="password" name="password" class="input-labels-child"></input>
                </div>
                <div class="input-labels6">
                    <div class="confirm-password">Confirm password</div>
                </div>
                <div class="input-labels7">
                    <input type="password" name="password_confirm" class="input-labels-child"></input>
                </div>
                <div class="register-button">
                    <button type="submit" class="button-container">
                        <div class="button-background" id="buttonBackground"></div>
                        <div class="create-account">Create Account</div>
                    </button>
                </div>
            </form>
        </div>
    </body>

    </html>