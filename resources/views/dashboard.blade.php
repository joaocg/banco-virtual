<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="container mx-auto p-6">
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Saldo -->
                            <div class="bg-white p-6 rounded shadow">
                                <h3 class="text-xl font-bold mb-2">Saldo Atual</h3>
                                <p id="balance" class="text-3xl font-semibold text-green-600">Carregando...</p>
                            </div>

                            <!-- Extrato -->
                            <div class="bg-white p-6 rounded shadow">
                                <h3 class="text-xl font-bold mb-2">Extrato de Transações</h3>
                                <table class="w-full border-collapse border border-gray-300">
                                    <thead>
                                    <tr class="bg-gray-200">
                                        <th class="border p-2">Data</th>
                                        <th class="border p-2">Tipo</th>
                                        <th class="border p-2">Valor</th>
                                        <th class="border p-2">Destinatário</th>
                                    </tr>
                                    </thead>
                                    <tbody id="transactions">
                                    <tr>
                                        <td colspan="4" class="p-2 text-center">Carregando...</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function fetchAccountData() {
            fetch("{{ route('account.data') }}")
                .then(response => response.json())
                .then(data => {

                    document.getElementById('balance').textContent = `R$ ${data.balance}`;

                    let transactionsHtml = '';
                    data.transactions.forEach(transaction => {
                        transactionsHtml += `
                            <tr class="border">
                                <td class="p-2">${transaction.date}</td>
                                <td class="p-2">
                                    <span class="${transaction.type === 'Entrada' ? 'text-green-500' : 'text-red-500'}">
                                        ${transaction.type}
                                    </span>
                                </td>
                                <td class="p-2">R$ ${transaction.amount}</td>
                                <td class="p-2">${transaction.recipient}</td>
                            </tr>
                        `;
                    });

                    document.getElementById('transactions').innerHTML = transactionsHtml;
                })
                .catch(error => {
                    console.error("Erro ao buscar dados da conta:", error);
                    document.getElementById('balance').textContent = "Erro ao carregar";
                    document.getElementById('transactions').innerHTML = "<tr><td colspan='4' class='p-2 text-center text-red-500'>Erro ao carregar dados.</td></tr>";
                });
        }

        fetchAccountData();

        setInterval(fetchAccountData, 3600000);
    </script>
</x-app-layout>
