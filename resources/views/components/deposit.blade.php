<div class="max-w-md mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-center text-2xl font-bold mb-4">Depósito</h2>
    <form method="POST" action="{{ route('deposit') }}">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Valor</label>
            <input type="number" name="amount" class="mt-1 p-2 w-full border rounded" required>
        </div>
        <button type="submit" class="w-full bg-green-500 text-white p-2 rounded">Depositar</button>
    </form>
</div>
