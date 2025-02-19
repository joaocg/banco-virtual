<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Depositar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p id="message" class="mt-2 text-center font-bold"></p>
                    <form id="deposit-form" method="POST" action="{{ route('deposit') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="amount" class="block font-bold">Valor (R$)</label>
                            <input type="number" name="amount" id="amount" step="0.01" required
                                   class="w-full p-2 border rounded">
                            <p id="amount-error" class="text-red-600 text-sm mt-1 hidden">Digite um valor válido maior
                                que zero.</p>
                        </div>

                        <button type="submit" class="w-full bg-green-500  text-white p-2 rounded">Depositar</button>
                    </form>

                    <a href="{{ route('dashboard') }}" class="block text-center mt-4 text-blue-500">Voltar</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('deposit-form').addEventListener('submit', function (event) {
            event.preventDefault();

            let form = this;
            let formData = new FormData(form);
            let messageElement = document.getElementById('message');
            let amountInput = document.getElementById('amount');
            let amountError = document.getElementById('amount-error');


            let amount = parseFloat(amountInput.value);

            if (isNaN(amount) || amount <= 0) {
                amountError.classList.remove('hidden');
                return;
            } else {
                amountError.classList.add('hidden');
            }

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value,
                    'Accept': 'application/json'
                },
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    messageElement.textContent = data.message;
                    messageElement.classList.add('text-green-600');
                    form.reset();
                })
                .catch(error => {
                    if (error.errors) {
                        responseMessage.textContent = Object.values(error.errors).flat().join("\n");
                    } else {
                        responseMessage.textContent = "Erro ao processar o depósito!";
                    }
                    responseMessage.classList.add("text-red-500");
                });
        });
    </script>

</x-app-layout>
