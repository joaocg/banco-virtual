<div class="max-w-md mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-center text-2xl font-bold mb-4">Transferência</h2>
    <form method="POST" action="{{ route('transfer') }}">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Destinatário (ID)</label>
            <input type="number" name="receiver_id" class="mt-1 p-2 w-full border rounded" required>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Valor</label>
            <input type="number" name="amount" class="mt-1 p-2 w-full border rounded" required>
        </div>
        <button type="submit" class="w-full bg-yellow-500 text-white p-2 rounded">Transferir</button>
    </form>
</div>
