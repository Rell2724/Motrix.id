<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <h3>Transaction Lists</h3>

    <div>
        <table class="table">
            <thead>
                <tr>
                    <th class="border-y border-blue-gray-100 bg-blue-gray-50/50 p-4">No</th>
                    <th class="border-y border-blue-gray-100 bg-blue-gray-50/50 p-4">Transaction ID</th>
                    <th class="border-y border-blue-gray-100 bg-blue-gray-50/50 p-4">User ID</th>
                    <th class="border-y border-blue-gray-100 bg-blue-gray-50/50 p-4">Transaction Type</th>
                    <th class="border-y border-blue-gray-100 bg-blue-gray-50/50 p-4">Amount</th>
                    <th class="border-y border-blue-gray-100 bg-blue-gray-50/50 p-4">Status</th>
                    <th class="border-y border-blue-gray-100 bg-blue-gray-50/50 p-4">Created-at</th>
                    <th class="border-y border-blue-gray-100 bg-blue-gray-50/50 p-4">Action</th>
                </tr>
            </thead>
            <tbody>
                @php $i = 1; @endphp
                @foreach ($transactions as $transaction)
                <tr>
                    <td class="p-4 border-b border-blue-gray-50">{{ $i++ }}</td>
                    <td class="p-4 border-b border-blue-gray-50">{{ $transaction->transaction_id }}</td>
                    <td class="p-4 border-b border-blue-gray-50">{{ $transaction->user_id }}</td>
                    <td class="p-4 border-b border-blue-gray-50">{{ $transaction->transaction_type }}</td>
                    <td class="p-4 border-b border-blue-gray-50">{{ $transaction->amount }}</td>
                    <td class="p-4 border-b border-blue-gray-50">{{ $transaction->status }}</td>
                    <td class="p-4 border-b border-blue-gray-50">{{ $transaction->created_at }}</td>
                    <td class="p-4 border-b border-blue-gray-50">
                        <div class="flex items-center gap-3">
                            <div class="w-max">
                                <a href="{{ route('transaction.details', ['id' => $transaction->transaction_id]) }}" class="bg-yellow-200 text-xs text-small>text-yellow py-1 px-6 rounded-md"> See Details </a>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layout>