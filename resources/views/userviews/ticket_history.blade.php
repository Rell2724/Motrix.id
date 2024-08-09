<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1, width=device-width" />

    <link rel="stylesheet" href="{{ asset('css/history.css') }}" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600;1,700&display=swap" />
    <title>Motrix : History</title>
    <link rel="icon" href="{{ asset('img/icons/motrix-logo.png') }}">
</head>

<body>
    <div class="history1">
        <div class="container1">
            <div class="content">
                <img class="back-2-icon" loading="lazy" alt="" src="{{ asset('img/icons/back_black.png') }}" id="back2Icon" />
                <div class="history-container">
                    <img class="history-1-icon1" loading="lazy" alt="" src="{{ asset('img/icons/history.png') }}" />
                </div>
            </div>
        </div>
        @foreach($usertickets as $userticket)
        <div class="ticket">
            <div class="status">
                <div class="result">
                    <img class="mask-group-icon6" loading="lazy" alt="" src="{{ $userticket->movie_poster }}" />
                    <div class="info">
                        <p class="pasar-setan-plaza-container">
                            <span class="pasar-setan3">{{ $userticket->movie_name }}</span><br>
                            <span class="plaza-araya-xxi1">{{ $userticket->theater_name }}</span><br>
                            <span class="tiket-2">{{ $userticket->total_tickets }} Ticket(s)</span><br>
                            <span class="jumat-23-februari1">{{ $userticket->booked_time }}</span>
                        </p>
                    </div>
                </div>
                <div class="confirmation">
                    @if($userticket->status == 'Pending')
                    <a href="{{ route('user.payment', ['transaction_id' => $userticket->transaction_id]) }}" class="berhasil">{{ $userticket->status }}</a>
                    @else
                    <i class="berhasil">{{ $userticket->status }}</i>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <script>
        var back2Icon = document.getElementById("back2Icon");
        if (back2Icon) {
            back2Icon.addEventListener("click", function(e) {
                window.history.back();
            });
        }
    </script>
</body>

</html>