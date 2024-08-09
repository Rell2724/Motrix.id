    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="initial-scale=1, width=device-width" />
        <link rel="stylesheet" href="{{ asset('css/shows.css') }}" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,300;0,400;0,500;0,600;1,400;1,700&display=swap" />
        <title>Motrix : Current Shows</title>
        <link rel="icon" href="{{ asset('img/icons/motrix-logo.png') }}">
    </head>

    <body>
        <div class="home">
            <div class="top-bar-container">
                <div class="frame-parent">
                    <div id="iconpage" class="asset-2-1-wrapper">
                        <img class="asset-2-1" loading="lazy" alt="" src="{{ asset('img/icons/motrix-logo.png') }}" />
                    </div>
                    <div class="search-wrapper">
                        <div class="search">
                            <div class="search-child"></div>
                            <div class="search-1-wrapper">
                                <img class="search-1-icon" alt="" src="{{ asset('img/icons/search-1.png') }}" />
                            </div>
                            <input class="type-to-search" placeholder="Type to Search......" type="text" />
                        </div>
                    </div>
                    <div class="frame-wrapper">
                        <div class="frame-group">
                            <div class="frame-container">
                                <div id="history-button" class="rectangle-parent scanblock">
                                    <img class="scan-3-icon" alt="" src="{{ asset('img/icons/clockwise.png') }}" />
                                    <div class="scan-wrapper">
                                        <div class="scan">History</div>
                                    </div>
                                </div>
                            </div>
                            <div class="maps-and-flags-1-wrapper">
                            </div>
                            <div class="bell-1-wrapper">
                                <img class="bell-1-icon" loading="lazy" alt="" src="{{ asset('img/icons/bell-1.png') }}" id="bell1Icon" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <main class="frame-main">
                <section class="frame-section">
                    <div class="profile-parent">
                        <div class="profile">
                            <div class="profile-child"></div>
                            <div class="profile-user-1-parent">
                                <img class="profile-user-1-icon" loading="lazy" alt="" src="{{ asset('img/icons/profile-user-1.png') }}" />
                                <div class="user-wrapper">
                                    <div class="user">{{ $userdata['name'] }}</div>
                                </div>
                            </div>
                            <div class="balance-parent">
                                <div class="balance">Balance</div>
                                <div class="wallet-1-parent">
                                    <img class="wallet-1-icon" loading="lazy" alt="" src="{{ asset('img/icons/wallet-1.png') }}" />
                                    <div class="balance-value-wrapper">
                                        <div class="balance-value">{{ $userbalance->amount }}</div>
                                    </div>
                                    <img id="add-balance" class="more-1-icon" loading="lazy" alt="" src="{{ asset('img/icons/more-1.png') }}" />
                                </div>
                            </div>
                        </div>
                        <div class="sidebar">
                            <div id="home-button" class="home1">
                                <img class="home-2-icon" alt="" src="{{ asset('img/icons/home-1.png') }}" />
                                <div href="#" class="home-wrapper">
                                    <div class="home2">Home</div>
                                </div>
                            </div>
                            <div class="favorites" id="FavoritesContainer">
                                <img class="favorite-1-icon" loading="lazy" alt="" src="{{ asset('img/icons/favorite-1.png') }}" />
                                <div href="#" class="favorites-wrapper">
                                    <div class="favorites1">Favorites</div>
                                </div>
                            </div>
                            <div class="snacks" id="SnacksContainer">
                                <img class="fast-food-1-icon" loading="lazy" alt="" src="{{ asset('img/icons/clapperboard-white.png') }}" />
                                <div href="#" class="food-beverage-wrapper">
                                    <div class="food-beverage">Shows</div>
                                </div>
                            </div>
                        </div>
                        <div class="order">
                            <div class="order-child"></div>
                            <div class="fast-food-2-parent">
                                <img class="fast-food-2-icon" loading="lazy" alt="" src="{{ asset('img/icons/fast-food-2.png') }}" />
                                <div class="coca-cola-burger-parent">
                                    <div class="coca-cola-container">
                                        <p class="coca-cola">Coca Cola</p>
                                        <p class="burger">& Burger</p>
                                    </div>
                                    <div class="order-price-wrapper">
                                        <div class="order-price">$10</div>
                                    </div>
                                </div>
                            </div>
                            <div id="order-button" class="order-inner">
                                <div href="/f&b" class="rectangle-group">
                                    <div class="frame-item"></div>
                                    <div class="order1">Order</div>
                                </div>
                            </div>
                        </div>
                        <div class="sidebar">
                            <div class="setting" id="SettingContainer">
                                <img class="setting-1-icon" loading="lazy" alt="" src="{{ asset('img/icons/setting-1.png') }}" />
                                <div href="/setting" class="setting-wrapper">
                                    <div class="setting1">Setting</div>
                                </div>
                            </div>
                            <div class="logout" id="LogoutContainer">
                                <img class="logout-1-icon" loading="lazy" alt="" src="{{ asset('img/icons/power-off-1.png') }}" />
                                <div href="/logout" class="logout-wrapper">
                                    <div class="logout1">Logout</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="frame-div">
                        <div class="frame-parent1">
                            @foreach($movies as $movie)
                            <div class="movie-1-wrapper">
                                <a href="{{ url('findshows?val=' . $movie->movie_id) }}" class="movie-1" id="MovieContainer">
                                    <img class="rating-1-icon" alt="" src="{{ asset('img/icons/star.png') }}" />
                                    <div class="rectangle-parent1">
                                        <img class="rectangle-icon" loading="lazy" alt="" src="{{ $movie->movie_poster }}" />
                                        <div class="frame-wrapper1">
                                            <div class="pasar-setan-horror-parent">
                                                <div class="pasar-setan-horror-container">
                                                    <p class="pasar-setan">{{ $movie->movie_name }}</p>
                                                    <p class="horror"><i>{{ $movie->genre }}</i></p>
                                                </div>
                                                <div class="favorite-1-wrapper">
                                                    <img class="favorite-1-icon1" loading="lazy" alt="" src="{{ asset('img/icons/favorite-1.png') }}" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>

                </section>
            </main>
        </div>

        <script>
            document.getElementById("iconpage")?.addEventListener("click", function() {
                window.location.href = "/index";
            });

            document.getElementById("history-button")?.addEventListener("click", function() {
                window.location.href = "/history";
            });

            document.getElementById("home-button")?.addEventListener("click", function() {
                window.location.href = "/index";
            });

            document.getElementById("add-balance")?.addEventListener("click", function() {
                window.location.href = "/setting/balance";
            });

            document.getElementById("SettingContainer")?.addEventListener("click", function() {
                window.location.href = "/setting/account";
            });

            document.getElementById("LogoutContainer")?.addEventListener("click", function() {
                window.location.href = "/logout";
            });

            document.getElementById("FavoritesContainer")?.addEventListener("click", function() {
                window.location.href = "/favorites";
            });

            document.getElementById("show-more")?.addEventListener("click", function() {
                window.location.href = "/shows";
            });

            document.getElementById("SnacksContainer")?.addEventListener("click", function() {
                window.location.href = "/shows";
            });

            document.getElementById("bell1Icon")?.addEventListener("click", function() {
                window.location.href = "/notification";
            });

            document.getElementById("order-button")?.addEventListener("click", function() {
                window.location.href = "/snacks";
            });
        </script>
    </body>

    </html>