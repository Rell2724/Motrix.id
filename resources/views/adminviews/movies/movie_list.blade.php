<x-layout>
    <x-slot:title>{{$title}}</x-slot:title>

    <h3>All-Movie List</h3>

    <div>
        <table class="table">
            <thead>
                <tr>
                    <th class="border-y border-blue-gray-100 bg-blue-gray-50/50 p-4">
                        <p class="block antialiased text-sm text-blue-gray-900 font-normal leading-none opacity-70"> No
                    </th>
                    <th class="border-y border-blue-gray-100 bg-blue-gray-50/50 p-4">
                        <p class="block antialiased text-sm text-blue-gray-900 font-normal leading-none opacity-70"> Name
                    </th>
                    <th class="border-y border-blue-gray-100 bg-blue-gray-50/50 p-4">
                        <p class="block antialiased text-sm text-blue-gray-900 font-normal leading-none opacity-70"> Release Date
                    </th>
                    <th class="border-y border-blue-gray-100 bg-blue-gray-50/50 p-4">
                        <p class="block antialiased text-sm text-blue-gray-900 font-normal leading-none opacity-70"> Genre
                    </th>
                    <th class="border-y border-blue-gray-100 bg-blue-gray-50/50 p-4">
                        <p class="block antialiased text-sm text-blue-gray-900 font-normal leading-none opacity-70"> Currently Playing
                    </th>
                </tr>
            </thead>
            <tbody>
                @php $i = 1; @endphp
                @foreach ($movies as $movie)
                <tr>
                    <td class="p-4 border-b border-blue-gray-50">
                        <p class="block antialiased text-sm leading-normal text-blue-gray-900 font-normal">{{ $i++ }}</p>
                    </td>
                    <td class="p-4 border-b border-blue-gray-50">
                        <p class="block antialiased text-sm leading-normal text-blue-gray-900 font-bold">{{ $movie->movie_name }}</p>
                    </td>
                    <td class="p-4 border-b border-blue-gray-50">
                        <p class="block antialiased text-sm leading-normal text-blue-gray-900 font-normal">{{ $movie->release_date }}</p>
                    </td>
                    <td class=" p-4 border-b border-blue-gray-50">
                        <p class="block antialiased text-sm leading-normal text-blue-gray-900 font-normal">{{ $movie->genre }}</p>
                    </td>
                    <td class=" p-4 border-b border-blue-gray-50">
                        <p class="block antialiased text-sm leading-normal text-blue-gray-900 font-normal">{{ $movie->is_current }}</p>
                    </td>
                    <td class="p-4 border-b border-blue-gray-50">
                        <div class="flex items-center gap-3">
                            <div class="w-max">
                                <a href="{{ route('movie.edit', $movie->movie_id) }}" class="bg-yellow-200 text-black py-1 px-2 text-xs rounded-md">
                                    Edit
                                </a>
                            </div>
                            <div class="w-max">
                                <form action="{{ route('movie.destroy', $movie->movie_id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white py-1 px-2 text-xs rounded-md">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
        <a href="{{ route('movie.create') }}" class="bg-green-500 text-black py-1 px-6 text-xs rounded-md">
            Add Movies
        </a>
    </div>
</x-layout>