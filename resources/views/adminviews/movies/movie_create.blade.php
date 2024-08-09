<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <form action="{{ route('movie.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div>
            <label for="movieName" class="form-label"> Movie Name </label></br>
            <input type="text" class="form-control" name="movie_name">
        </div>
        <div>
            <label for="movieBanner" class="form-label"> Movie Banner Upload </label></br>
            <input type="file" class="form-control" name="movie_banner">
        </div>
        <div>
            <label for="moviePoster" class="form-label"> Movie Poster Upload </label></br>
            <input type="file" class="form-control" name="movie_poster">
        </div>
        <div>
            <label for="movieTrailer" class="form-label"> Movie Trailer (Link) </label></br>
            <input type="text" class="form-control" name="movie_trailer">
        </div>
        <div>
            <label for="movieReleasedate" class="form-label"> Movie Release Date </label></br>
            <input type="date" class="form-control" name="release_date">
        </div>
        <div>
            <label for="movieGenre" class="form-label"> Genre </label></br>
            <input type="text" class="form-control" name="genre">
        </div>
        <div>
            <label for="movieSynopsis" class="form-label"> Movie Synopsis </label></br>
            <input type="text" class="form-control" name="movie_synopsis">
        </div>
        <div>
            <label for="Thisweek" class="form-label"> Hot movie this week? </label></br>
            <input type="hidden" name="movie_thisweeks" value="0">
            <input type="checkbox" class="form-control" name="movie_thisweeks" value="1">
        </div>
        <button class="bg-green-500 text-black py-1 px-6 text-xs rounded-md" type="submit"> Store Movie </button>
    </form>
</x-layout>