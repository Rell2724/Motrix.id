<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1, width=device-width" />
    <link rel="stylesheet" href="{{ asset('css/notification.css') }}" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600;1,700&display=swap" />
    <title>Motrix : Notification</title>
    <link rel="icon" href="{{ asset('img/icons/motrix-logo.png') }}">
</head>

<body>
    <div class="notification1">
        <div class="container2">
            <div class="notification2">
                <img class="back-1-icon1" loading="lazy" alt="" src="{{ asset('img/icons/back_black.png') }}" id="back1Icon" />
                <img class="bell-1-icon2" loading="lazy" alt="" src="{{ asset('img/icons/bell-1.png') }}" />
            </div>
        </div>
        <section class="promotion">
            <div class="voucher">
                <div class="voucher-child"></div>
                <div class="dapatkan-voucher-snack-10-parent">
                    <b class="dapatkan-voucher-snack">Hello, Hi There !</b>
                    <div class="setiap-pembelian-minimal">
                        Unfortunately, you have not received any notifications yet.
                    </div>
                </div>
                <div class="s-k-wrapper">
                    <div class="s-k1">.</div>
                </div>
            </div>
        </section>
    </div>

    <script>
        var back1Icon = document.getElementById("back1Icon");
        if (back1Icon) {
            back1Icon.addEventListener("click", function(e) {
                window.history.back();
            });
        }
    </script>
</body>

</html>