<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1, width=device-width" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <link rel="stylesheet" href="{{ asset('css/detailfilm.css') }}" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,700&display=swap" />
    <title>Motrix : {{ $moviedetails->movie_name }}</title>
    <link rel="icon" href="{{ asset('img/icons/motrix-logo.png') }}">
</head>

<body>
    <div class="detail-film">
        <section class="frame-parent7">
            <div class="frame-parent8">
                <div class="frame-wrapper6">
                    <div class="frame-parent9">
                        <div class="movie-wrapper">
                            <h2 class="movie-name">{{ $moviedetails->movie_name }}</h2>
                        </div>
                        <div class="frame-parent10">
                            <div class="text-container">
                                <div class="year">{{ \Carbon\Carbon::parse($moviedetails->release_date)->format('Y') }}</div>
                                <div class="duration">{{ $moviedetails->movie_duration }}</div>
                                <div class="genre">{{ $moviedetails->genre }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="description-container">
                    <div class="description">Description</div>
                    <p class="film-description">{{ $moviedetails->movie_synopsis }}</p>
                    <img id="fav-button" class="heart-button" data-favorited="{{ $data['is_favorited'] ? 'true' : 'false' }}" loading="lazy" alt="" src="/img/icons/{{ $data['is_favorited'] ? 'heart-red.png' : 'heart.png' }}" />
                </div>
            </div>
        </section>
        <section class="mask-group-group">
            <img class="mask-group-icon3" alt="" src="{{ $moviedetails->movie_banner }}" />

            <img class="mask-group-icon4" loading="lazy" alt="" src="{{ $moviedetails->movie_poster }}" />

            <img class="back-1-1" loading="lazy" alt="" src="{{ asset('img/icons/back_white.png') }}" id="back11" />
        </section>
    </div>
    <div id="theaters-container">
        @php
        $groupedShows = $shows->groupBy([
        function ($item, $key) {
        return $item->theater_name;
        },
        function ($item, $key) {
        return \Carbon\Carbon::parse($item->showtime)->format('Y-m-d');
        }
        ]);
        @endphp
        @foreach ($groupedShows as $theaterName => $showsByTheater)
        @foreach ($showsByTheater as $date => $showsOnDate)
        <div class="theater-box">
            <h3 class="theater-name">{{ $theaterName }}</h3>
            <p class="theater-address">{{ $showsOnDate[0]->theater_address }}</p>
            <div class="price-container">
                <span class="show-type">Reguler</span>
                <span class="show-price">Rp. {{ number_format($showsOnDate[0]->price, 0, ',', '.') }}</span>
            </div>
            <div class="showtime-buttons">
                @foreach ($showsOnDate as $show)
                <button class="showtime-button" data-showid="{{ $show->show_id }}">{{ \Carbon\Carbon::parse($show->showtime)->format('H:i') }}</button>
                @endforeach
            </div>
        </div>
        @endforeach
        @endforeach
    </div>
</body>

<script>
    var showtimeButtons = document.getElementsByClassName('showtime-button');
    for (var i = 0; i < showtimeButtons.length; i++) {
        showtimeButtons[i].addEventListener('click', function() {
            var showId = this.getAttribute('data-showid');
            var url = "{{ url('findseats?showid=') }}" + showId;
            window.location.href = url;
        });
    }
    var backbutton = document.getElementById("back11");
    if (backbutton) {
        backbutton.addEventListener("click", function(e) {
            window.history.back();
        });
    }

    window.addEventListener("DOMContentLoaded", function() {
        var favButton = document.getElementById("fav-button");
        if (favButton) {
            favButton.addEventListener("click", function(e) {
                var isFavorite = favButton.dataset.favorited === 'true';
                var action = isFavorite ? "removefavorite" : "addfavorite";
                var valueToSend = isFavorite ? "0" : "1";
                var user_id = "{{ session()->get('id') }}";
                var url = new URL(window.location.href);
                var movie_id = url.searchParams.get("val");

                $.ajax({
                    url: "/" + action,
                    type: "POST",
                    data: {
                        user_id: user_id,
                        movie_id: movie_id,
                        value: valueToSend,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        location.reload();
                    },
                    error: function(xhr) {
                        console.error("Failed to update favorite status", xhr.status, xhr.statusText);
                    }
                });
            });
        }
    });
</script>

</html>