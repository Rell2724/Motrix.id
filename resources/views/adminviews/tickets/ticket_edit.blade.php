<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <form action="{{ route('ticket.update', $ticketdetails->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-12">
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-4">
                        <label for="first-name" class="block text-sm font-medium leading-6 text-gray-900">Transaction ID</label>
                        <div class="mt-2">
                            <input disabled type="text" value="{{ $ticketdetails->transaction_id }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div class="sm:col-span-4">
                        <label for="last-name" class="block text-sm font-medium leading-6 text-gray-900">User ID</label>
                        <div class="mt-2">
                            <input disabled value="{{ $ticketdetails->user_id }}" class=" block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div class="sm:col-span-4">
                        <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Show ID</label>
                        <div class="mt-2">
                            <input disabled value="{{ $ticketdetails->show_id }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    <div class="sm:col-span-4">
                        <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Movie Name</label>
                        <div class="mt-2">
                            <input disabled value="{{ $ticketdetails->movie_name }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div class="col-span-4">
                        <label for="street-address" class="block text-sm font-medium leading-6 text-gray-900">Theater Name</label>
                        <div class="mt-2">
                            <input disabled value="{{ $ticketdetails->theater_name }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div class="col-span-4">
                        <label for="seat" class="block text-sm font-medium leading-6 text-gray-900">Seat | Current: {{ strtoupper($ticketdetails->seatcol) }}{{ $ticketdetails->seatrow }}</label>
                        <div class="mt-2">
                            <select name="seat_id" id="seat" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                @foreach ($seatstable as $seat)
                                @php
                                $isOccupied = false;
                                foreach ($showseatstable as $showseat) {
                                if ($showseat->seat_id === $seat->seat_id && $showseat->status == 1) {
                                $isOccupied = true;
                                break;
                                }
                                }
                                @endphp
                                @if ($isOccupied)
                                <option value="{{ $seat->seat_id }}" disabled>{{ strtoupper($seat->seatcol) }}{{ $seat->seatrow }} (Occupied)</option>
                                @else
                                <option value="{{ $seat->seat_id }}">{{ strtoupper($seat->seatcol) }}{{ $seat->seatrow }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-span-4">
                        <label for="street-address" class="block text-sm font-medium leading-6 text-gray-900">Booked-at</label>
                        <div class="mt-2">
                            <input disabled value="{{ $ticketdetails->booked_time }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="mt-6 flex items-center justify-end gap-x-6">
            <button type="button" class="text-sm font-semibold leading-6 text-gray-900">Cancel</button>
            <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Save</button>
        </div>
    </form>
</x-layout>