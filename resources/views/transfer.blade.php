<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Transferir Dinheiro') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p id="message" class="mt-2 text-center font-bold"></p>
                        <form id="transferForm" method="POST" action="{{ route('transfer') }}">
                            @csrf
                            <div class="mb-4">
                                <label for="receiver_account" class="block font-bold">Conta de destino</label>
                                <input type="number" name="receiver_account" id="receiver_account" required class="w-full p-2 border rounded">
                                <p id="receiverError" class="text-red-500 text-sm hidden">A conta deve ter exatamente 6 números.</p>
                            </div>

                            <div class="mb-4">
                                <label for="amount" class="block font-bold">Valor (R$)</label>
                                <input type="number" name="amount" id="amount" step="0.01" required class="w-full p-2 border rounded">
                                <p id="amountError" class="text-red-500 text-sm hidden">O valor deve ser maior que 0.</p>
                            </div>

                            <button type="submit" class="w-full bg-yellow-500 text-white p-2 rounded">Transferir</button>
                        </form>

                    <a href="{{ route('dashboard') }}" class="block text-center mt-4 text-blue-500">Voltar</a>
            </div>
        </div>
    </div>

        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const form = document.getElementById("transferForm");
                const receiverInput = document.getElementById("receiver_account");
                const amountInput = document.getElementById("amount");
                const responseMessage = document.getElementById("message");

                form.addEventListener("submit", function (event) {
                    event.preventDefault();

                    const receiverAccount = receiverInput.value.trim();
                    const amount = parseFloat(amountInput.value);

                    document.getElementById("receiverError").classList.add("hidden");
                    document.getElementById("amountError").classList.add("hidden");

                    let hasError = false;

                    if (!/^\d{6}$/.test(receiverAccount)) {
                        document.getElementById("receiverError").classList.remove("hidden");
                        hasError = true;
                    }

                    if (isNaN(amount) || amount <= 0) {
                        document.getElementById("amountError").classList.remove("hidden");
                        hasError = true;
                    }

                    if (hasError) return;

                    fetch("{{ route('transfer') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
                        },
                        body: JSON.stringify({
                            receiver_account: receiverAccount,
                            amount: amount
                        })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                responseMessage.textContent = data.message;
                                responseMessage.classList.add("text-green-500");
                                form.reset();
                            } else {
                                responseMessage.textContent = data.message || "Erro na transferência.";
                                responseMessage.classList.add("text-red-500");
                            }
                        })
                        .catch(error => {
                            if (error.errors) {
                                responseMessage.textContent = Object.values(error.errors).flat().join("\n");
                            } else {
                                responseMessage.textContent = "Erro ao processar a transferencia!";
                            }
                            responseMessage.classList.add("text-red-500");
                        });
                });

                receiverInput.addEventListener("input", function () {
                    this.value = this.value.replace(/\D/g, "").slice(0, 6);
                });
            });
        </script>

</x-app-layout>
