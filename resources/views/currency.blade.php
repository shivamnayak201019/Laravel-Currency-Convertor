<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Currency Converter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
  <div class="container">
        <div class="row">
            <div class="col-md-12 mt-5 pt-5">
                <form action="{{ route('currency') }}" class="border rounded mt-5 pt-5" method="get">
                    <div class="row">
                        <div class="col-md-12 mb-4 text-center">
                            <h1>Currency Converter</h1>
                        </div>

                        <div class="col-md-5 mb-3">
                            <label for="currency_from" class="form-label">From Currency</label>
                            <select id="currency_from" class="form-select" name="currency_from" required>
                                <option value="" disabled selected>Loading currencies...</option>
                            </select>
                        </div>

                        <div class="col-md-2 text-center">
                            <h4 class="mt-4">To</h4>
                        </div>

                        <div class="col-md-5 mb-3">
                            <label for="currency_to" class="form-label">To Currency</label>
                            <select id="currency_to" class="form-select" name="currency_to" required>
                                <option value="" disabled selected>Loading currencies...</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="amount" class="form-label">Amount</label>
                            <input type="number" name="amount" class="form-control" value="{{ Request::get('amount') }}" placeholder="Enter amount" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="converted_amount" class="form-label">Converted Amount</label>
                            <input type="text" name="converted_amount" class="form-control" value="{{ $converted ?? '' }}" placeholder="Converted amount" disabled>
                        </div>

                        <div class="col-md-12 d-flex justify-content-center mt-4">
                            <button type="submit" class="btn btn-primary">Convert</button>
                        </div>

                        @if($errors->any())
                        <div class="col-md-12 mt-4">
                            <div class="alert alert-danger text-center">
                                {{ $errors->first() }}
                            </div>
                        </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            const currencyApiUrl = 'https://cdn.jsdelivr.net/npm/@fawazahmed0/currency-api@latest/v1/currencies.json';
            const currencyFromSelect = document.getElementById('currency_from');
            const currencyToSelect = document.getElementById('currency_to');

            const selectedFromCurrency = "{{ Request::get('currency_from') }}";
            const selectedToCurrency = "{{ Request::get('currency_to') }}";

            try {
                const response = await fetch(currencyApiUrl);
                const data = await response.json();

                // Clear existing options
                currencyFromSelect.innerHTML = '<option value="" disabled selected>Select Currency</option>';
                currencyToSelect.innerHTML = '<option value="" disabled selected>Select Currency</option>';

                // Populate the dropdowns with currencies
                for (const [code, name] of Object.entries(data)) {
                    const fromOption = `<option value="${code}" ${code === selectedFromCurrency ? 'selected' : ''}>${name} (${code})</option>`;
                    const toOption = `<option value="${code}" ${code === selectedToCurrency ? 'selected' : ''}>${name} (${code})</option>`;
                    currencyFromSelect.insertAdjacentHTML('beforeend', fromOption);
                    currencyToSelect.insertAdjacentHTML('beforeend', toOption);
                }
            } catch (error) {
                console.error('Error fetching currency data:', error);
                alert('Failed to load currencies. Please try again later.');
            }
        });
    </script>
  </body>
</html>