<div class="progressCardsContainer">
    <p class="proCardsText">Sales Volume</p>
    {{ '$' . number_format($totalSalesVolume, 0, '.', ',') }}
</div>
<div class="progressCardsContainer">
    <p class="proCardsText">Avg Commission</p>
    {{ number_format($averageCommission, 2) . '%' }}
</div>
<div class="progressCardsContainer">
    <p class="proCardsText">Potential GCI</p>
    {{ '$' . number_format($totalPotentialGCI, 0, '.', ',') }}
</div>
<div class="progressCardsContainer">
    <p class="proCardsText">Avg Probability</p>
    {{ number_format($averageProbability, 2) . '%' }}
</div>
<div class="progressCardsContainer">
    <p class="proCardsText">Probable GCI</p>
    {{ '$' . number_format($totalProbableGCI, 0, '.', ',') }}
</div>