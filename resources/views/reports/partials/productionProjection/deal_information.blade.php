<div class="bg-secondary text-white p-2 mb-2">
    <p><strong>Closing Date:</strong> {{ $deal['closing_date'] }}</p>
    <p><strong>Sale Price:</strong> ${{ number_format($deal['sale_price'], 2) }} at {{ number_format($deal['commission_percent'], 2) }}% commission</p>
    <p><strong>Total Possible Commission:</strong> ${{ number_format($deal['fullAgentEarnings'], 2) }}</p>
</div>
