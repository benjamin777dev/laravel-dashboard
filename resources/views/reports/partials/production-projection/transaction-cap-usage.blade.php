<div class="bg-light p-2 mb-2">
    <h6 class="text-secondary"><strong>Transaction Cap Usage</strong></h6>
    <p><strong>Initial Cap Paid on THIS Transaction:</strong> ${{ number_format($deal['initialCHRSplit'], 2) }}</p>
    <p><strong>Residual Cap Paid on THIS Transaction:</strong> ${{ number_format($deal['residualCHRSplit'], 2) }}</p>
    <p><strong>Initial Cap Left AFTER THIS Transaction:</strong> ${{ number_format($deal['initialCapAfter'], 2) }}</p>
    <p><strong>Residual Cap Left AFTER THIS Transaction:</strong> ${{ number_format($deal['residualCapAfter'], 2) }}</p>
</div>
