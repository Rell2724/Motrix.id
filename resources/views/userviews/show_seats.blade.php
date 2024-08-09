<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seat Booking</title>
    <link rel="stylesheet" href="{{ asset('css/seat_booking.css') }}">
    <title>Motrix : Book Seat(s)</title>
    <link rel="icon" href="{{ asset('img/icons/motrix-logo.png') }}">
</head>

<body>

    <div class="center">
        <div class="tickets">
            <form action="{{ route('user.bookseats') }}" method="POST">
                @csrf
                <input type="hidden" name="show_id" value="{{ request()->get('showid') }}">
                <input type="hidden" id="totalamount" name="totalamount" value="0.00">

                <div class="ticket-selector">
                    <div class="head">
                        <div class="title">Movie Name</div>
                    </div>
                    <div class="seats">
                        <div class="status">
                            <div class="item">Available</div>
                            <div class="item">Booked</div>
                            <div class="item">Selected</div>
                        </div>
                        <div class="all-seats">
                            @php
                            $seatRows = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
                            @endphp

                            @foreach($seatRows as $letter)
                            @foreach(range(14, 1) as $number)
                            @php
                            // Fetch the seat from seatstable
                            $seat = $seatstable->first(function($value) use($letter, $number) {
                            return strtolower($value->seatcol) == strtolower($letter) && $value->seatrow == $number;
                            });

                            $seatId = $seat ? (string) $seat->seat_id : null;

                            if ($seatId) {
                            // Fetch the status of the seat from showseatstable
                            $showSeat = $showseatstable->firstWhere('seat_id', $seatId);
                            $booked = ($showSeat && $showSeat->status == 1) ? 'booked' : '';
                            }
                            @endphp

                            @if($seatId)
                            <input type="checkbox" name="seat_id[]" value="{{ $seatId }}" id="{{ $seatId }}" {{ $booked ? 'disabled' : '' }} data-price="{{ $seatprice }}" />
                            <label for="{{ $seatId }}" class="seat {{ $booked }}">
                                {{ $letter . $number }}
                            </label>
                            @else
                            @php
                            // Log information for debugging
                            error_log("Seat not found for column: $letter and row: $number");
                            @endphp
                            @endif
                            @endforeach
                            @endforeach
                        </div>
                    </div>
                    <div class="price">
                        <div class="total">
                            <span><span class="count">0</span> Tickets</span>
                            <div class="amount">0.00</div>
                        </div>
                        <button type="submit">Book</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let seats = document.querySelectorAll("input[name='seat_id[]']");
            seats.forEach((seat) => {
                seat.addEventListener("change", () => {
                    let amount = parseFloat(document.querySelector(".amount").textContent);
                    let count = parseInt(document.querySelector(".count").textContent);

                    let price = parseFloat(seat.dataset.price);

                    if (seat.checked) {
                        count += 1;
                        amount += price;
                    } else {
                        count -= 1;
                        amount -= price;
                    }
                    
                    document.querySelector(".amount").textContent = amount.toFixed(2);
                    document.querySelector(".count").textContent = count;
                    document.querySelector("#totalamount").value = amount.toFixed(2);
                });
            });
        });
    </script>

</body>

</html>