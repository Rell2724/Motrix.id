<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div>
        <table class="table">
            <thead>
                <tr>
                    <th class="border-y border-blue-gray-100 bg-blue-gray-50/50 p-4">No</th>
                    <th class="border-y border-blue-gray-100 bg-blue-gray-50/50 p-4">Transaction ID</th>
                    <th class="border-y border-blue-gray-100 bg-blue-gray-50/50 p-4">User ID</th>
                    <th class="border-y border-blue-gray-100 bg-blue-gray-50/50 p-4">Movie Name</th>
                    <th class="border-y border-blue-gray-100 bg-blue-gray-50/50 p-4">Theater Name</th>
                    <th class="border-y border-blue-gray-100 bg-blue-gray-50/50 p-4">Seat</th>
                    <th class="border-y border-blue-gray-100 bg-blue-gray-50/50 p-4">Booked-at</th>
                    <th class="border-y border-blue-gray-100 bg-blue-gray-50/50 p-4">Action</th>
                </tr>
            </thead>
            <tbody>
                @php $i = 1; @endphp
                @foreach ($fetchTickets as $ticket)
                <tr>
                    <td class="p-4 border-b border-blue-gray-50">{{ $i++ }}</td>
                    <td class="p-4 border-b border-blue-gray-50 text-center">{{ $ticket->transaction_id }}</td>
                    <td class="p-4 border-b border-blue-gray-50 text-center">{{ $ticket->user_id }}</td>
                    <td class="p-4 border-b border-blue-gray-50">{{ $ticket->movie_name }}</td>
                    <td class="p-4 border-b border-blue-gray-50">{{ $ticket->theater_name}}</td>
                    <td class="p-4 border-b border-blue-gray-50">{{ strtoupper($ticket->seatcol) }}{{ $ticket->seatrow }}</td>
                    <td class="p-4 border-b border-blue-gray-50">{{ $ticket->booked_time }}</td>
                    <td class="p-4 border-b border-blue-gray-50">
                        <div class="flex items-center gap-3">
                            <div class="w-max">
                                <a href="{{ route('ticket.edit', ['id' => $ticket->id]) }}" class="bg-yellow-200 text-black py-1 px-2 text-xs rounded-md"> Edit </a>
                                <a href="{{ route('ticket.destroy', ['id' => $ticket->id]) }}" class="bg-red-500 text-white py-1 px-2 text-xs rounded-md"> Delete </a>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layout>