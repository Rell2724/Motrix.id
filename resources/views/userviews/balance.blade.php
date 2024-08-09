<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1, width=device-width" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{asset('css/usersetting_balance.css')}}" />
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
                <div id="account" class="rectangle-parent13">
                    <img class="profile-user-3-icon" alt="" src="{{ asset('img/icons/profile-user-2.png') }}" />
                    <div class="account">Account</div>
                </div>
                <div id="balance" class="rectangle-parent132">
                    <img class="bell-1-icon1" loading="lazy" alt="" src="{{ asset('img/icons/wallet-white.png') }}" />
                    <div class="notification-wrapper">
                        <div class="notification" id="balance">
                            Balance
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="setting-inner">
            <div class="frame-parent15">
                <div class="profile-user-2-parent">
                    <img class="profile-user-2-icon" loading="lazy" alt="" src="{{ asset('img/icons/dollar.png') }}" />
                </div>
                <div class="frame-wrapper11">
                    <div class="money-group">
                        <button class="balance-button" data-value="10000">Rp.10.000</button>
                    </div>
                    <div class="money-group">
                        <button class="balance-button" data-value="20000">Rp.20.000</button>
                    </div>
                    <div class="money-group">
                        <button class="balance-button" data-value="50000">Rp.50.000</button>
                    </div>
                    <div class="money-group">
                        <button class="balance-button" data-value="100000">Rp.100.000</button>
                    </div>
                </div>
                <div class="frame-wrapper112">
                    <button id="addBalance">Add Balance</button>
                    <p>Current Balance: <span id="currentBalance">Rp. {{ number_format($currentBalance, 2, ',', '.') }}</span></p></br>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {

                var account = document.getElementById("account");
                if (account) {
                    account.addEventListener("click", function(e) {
                        window.location.href = "/setting/account";
                    });
                }

                var selectedValue = 0;
                var balanceButtons = document.querySelectorAll(".balance-button");
                var addBalanceButton = document.getElementById("addBalance");

                balanceButtons.forEach(function(button) {
                    button.addEventListener("click", function(e) {
                        selectedValue = parseInt(e.target.getAttribute("data-value"));
                        balanceButtons.forEach(function(btn) {
                            btn.classList.remove("selected");
                        });
                        e.target.classList.add("selected");
                    });
                });

                if (addBalanceButton) {
                    addBalanceButton.addEventListener("click", function(e) {
                        if (isNaN(selectedValue) || selectedValue === 0) {
                            alert("Please select a valid amount to add to balance.");
                            return;
                        }
                        console.log("Adding balance: " + selectedValue);
                        fetch('/adduserbalance', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify({
                                    balance: selectedValue
                                })
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error(`HTTP error! status: ${response.status}`);
                                }
                                return response.json();
                            })
                            .then(json => {
                                console.log(json);
                                var newBalance = 'Rp. ' + new Intl.NumberFormat('id-ID', {
                                    minimumFractionDigits: 2
                                }).format(json.newBalance);
                                document.getElementById('currentBalance').textContent = newBalance;
                            })
                            .catch(e => {
                                console.log('There was a problem with the fetch operation: ' + e.message);
                            });
                    });
                }
            });
        </script>
</body>

</html>