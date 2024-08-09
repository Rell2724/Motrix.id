<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1, width=device-width" />

    <link rel="stylesheet" href="{{ asset('css/payment.css') }}" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,700&display=swap" />
</head>

<body>
    <div class="payment">
        <section class="payment-inner">
            <div class="group-div">
                <div class="frame-child28"></div>
                <div class="pasar-setan-parent">
                    <div class="pasar-setan2">{{ $paymentdetails->movie_name }}</div>
                    <div class="mask-group-container">
                        <img class="mask-group-icon5" loading="lazy" alt="" src="{{ $paymentdetails->movie_poster }}" />

                        <div class="jumat-23-februari-2024-jam-wrapper">
                            <div class="jumat-23-februari-container">
                                <p class="jumat-23-februari">{{ date('Y-m-d', strtotime($paymentdetails->showtime)) }}</p>
                                <p class="blank-line">&nbsp;</p>
                                <p class="jam-1420">{{ date('H:i', strtotime($paymentdetails->showtime)) }}</p>
                                <p class="blank-line1">&nbsp;</p>
                                <p class="tiket-b6">Ticket(s) {{ $paymentdetails->total_tickets }}</p>
                                <p class="blank-line3">&nbsp;</p>
                                <p class="jam-1420">Seat(s)</p>
                                <p class="blank-line3">&nbsp;</p>
                                <p class="jam-1420">{{ strtoupper($paymentdetails->seats) }}</p>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="plaza-araya-xxi-parent">
                    <div class="plaza-araya-xxi">{{ $paymentdetails->theater_name}}</div>
                    <div class="frame-parent11">
                        <div class="rectangle-parent10">
                            <div class="frame-child29"></div>
                            <div class="div3">{{ $paymentdetails->amount }}</div>
                        </div>
                        <div class="cancel-back-wrapper">
                            <b class="cancel-back" id="cancelBack">Cancel / Back</b>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div class="pilih-pembayaran-wrapper">
            <div class="pilih-pembayaran">Select Payment</div>
        </div>
        <div class="payment-child">
            <div class="frame-parent12">
                <form action="/paytickets" method="POST">
                    @csrf
                    <input type="hidden" name="balance" value="{{ $paymentdetails->amount }}">
                    <input type="hidden" name="transaction_id" value="{{ request('transaction_id') }}">
                    <div class="transfer-bank-button-parent">
                        <button type="submit" class="transfer-bank-button">
                            <div class="transfer-bank-button-child"></div>
                            <div class="transfer-bank" id="transferBankText">
                                Your Wallet
                            </div>
                        </button>
                    </div>
                </form>
            </div>
            <img class="back-1-icon" alt="" src="./public/back-3@2x.png" id="back1Icon" />
        </div>

        <script>
            var cancelBack = document.getElementById("cancelBack");
            if (cancelBack) {
                cancelBack.addEventListener("click", function(e) {
                    window.location.href = "/history";
                });
            }

            var transferBankText = document.getElementById("transferBankText");
            if (transferBankText) {
                transferBankText.addEventListener("click", function(e) {
                    // Please sync "HISTORY" to the project
                });
            }
        </script>
</body>

</html>