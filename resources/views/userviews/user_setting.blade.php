<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1, width=device-width" />

    <link rel="stylesheet" href="{{asset('css/usersetting.css')}}" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,700&display=swap" />
</head>

<body>
    <div class="setting1">
        <div class="navbar">
            <div class="navbar-inner">
                <div class="settings-1-group">
                    <img class="settings-1-icon1" loading="lazy" alt="" src="{{ asset('img/icons/setting-1.png') }}" />
                    <div class="setting-container">
                        <div class="setting2">Setting</div>
                    </div>
                </div>
            </div>
            <div class="frame-parent14">
                <button class="rectangle-parent13">
                    <img class="profile-user-3-icon" alt="" src="{{ asset('img/icons/profile-user-3.png') }}" />
                    <div class="account">Account</div>
                </button>
                <div id="balance" class="rectangle-parent132">
                    <img class="bell-1-icon1" loading="lazy" alt="" src="{{ asset('img/icons/wallet-1.png') }}" />
                    <div class="notification-wrapper">
                        <div class="notification">
                            Balance
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="setting-inner">
            <div class="frame-parent15">
                <div class="profile-user-2-parent">
                    <img class="profile-user-2-icon" loading="lazy" alt="" src="{{ asset('img/icons/profile-user-2.png') }}" />
                </div>
                <div class="frame-wrapper11">
                    <div class="frame-parent16">
                        <div class="form-container">
                            <form action="{{ route('setting.account_post') }}" method="post">
                                @csrf
                                <div class="input-group">
                                    <label for="name">Name</label>
                                    <input type="text" id="name" name="name">
                                </div>
                                <div class="input-group">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" name="email">
                                </div>
                                <div class="input-group">
                                    <label for="password">Password</label>
                                    <input type="password" id="password" name="password">
                                </div>
                            </form>
                            <div class="cancel-parent">
                                <button class="cancel" id="cancel">
                                    <div class="cancel-child"></div>
                                    <div class="cancel1">Cancel</div>
                                </button>
                                <button class="save" id="save">
                                    <div class="save-child"></div>
                                    <div class="save1">Save</div>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var balance = document.getElementById("balance");
        if (balance) {
            balance.addEventListener("click", function(e) {
                window.location.href = "/setting/balance";
            });
        }

        var cancel = document.getElementById("cancel");
        if (cancel) {
            cancel.addEventListener("click", function(e) {
                window.location.href = "/index";
            });
        }

        var save = document.getElementById("save");
        if (save) {
            save.addEventListener("click", function(e) {
                e.preventDefault();
                var form = document.querySelector('.form-container form');
                form.submit();
            });
        }
    </script>
</body>

</html>