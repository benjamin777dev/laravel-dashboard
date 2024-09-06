<div class="bg-light p-2 mb-2">
    <h6 class="text-secondary"><strong>Caps Information</strong></h6>
    <p><strong>Annual Initial Cap:</strong> ${{ number_format($settings['initial_cap'], 2) }}</p>
    <p><strong>Annual Residual Cap:</strong> ${{ number_format($settings['residual_cap'], 2) }}</p>
    <p><strong>Remaining Initial Cap:</strong> ${{ number_format($deal['initialCapAfter'], 2) }}</p>
    <p><strong>Remaining Residual Cap:</strong> ${{ number_format($deal['residualCapAfter'], 2) }}</p>
</div>
