<x-layout>
    <x-slot:title>{{$title}}</x-slot:title>

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
                        <p class="block antialiased text-sm text-blue-gray-900 font-normal leading-none opacity-70"> Username
                    </th>
                    <th class="border-y border-blue-gray-100 bg-blue-gray-50/50 p-4">
                        <p class="block antialiased text-sm text-blue-gray-900 font-normal leading-none opacity-70"> Status
                    </th>
                    <th class="border-y border-blue-gray-100 bg-blue-gray-50/50 p-4">
                        <p class="block antialiased text-sm text-blue-gray-900 font-normal leading-none opacity-70"> Action
                    </th>
                </tr>
            </thead>
            <tbody>
                @php $i = 1; @endphp
                @foreach ($users as $user)
                <tr>
                    <td class="p-4 border-b border-blue-gray-50">
                        <p class="block antialiased text-sm leading-normal text-blue-gray-900 font-normal">{{ $i++ }}</p>
                    </td>
                    <td class="p-4 border-b border-blue-gray-50">
                        <p class="block antialiased text-sm leading-normal text-blue-gray-900 font-bold">{{ $user['name'] }}</p>
                    </td>
                    <td class="p-4 border-b border-blue-gray-50">
                        <p class="block antialiased text-sm leading-normal text-blue-gray-900 font-normal">{{ $user['username'] }}</p>
                    </td>
                    <td class=" p-4 border-b border-blue-gray-50">
                        <p class="block antialiased text-sm leading-normal text-blue-gray-900 font-normal">{{ $user['status'] }}</p>
                    </td>
                    <td class="p-4 border-b border-blue-gray-50">
                        <div class="flex items-center gap-3">
                            <div class="w-max">
                                <a href="{{ route('users.edit', $user) }}" class="bg-yellow-200 text-black py-1 px-2 text-xs rounded-md">
                                    Edit
                                </a>
                            </div>
                            <div class="w-max">
                                <form action="{{ route('users.destroy', $user) }}" method="POST">
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
        <a href="{{ route('users.create') }}" class="bg-green-500 text-black py-1 px-6 text-xs rounded-md">
            Add User
        </a>
    </div>
    
</x-layout>