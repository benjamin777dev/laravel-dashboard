@extends('layouts.master')

@section('content')
<div class="container-fluid p-0" style="height: 100vh;"> <!-- Make the container full-screen -->
    <h1>Deal Stage Journey Map</h1>

    <!-- Dropdown to select the year -->
    <div class="form-group">
        <label for="yearSelect">Select Year:</label>
        <select id="yearSelect" class="form-control">
            @foreach($years as $year)
                <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>{{ $year }}</option>
            @endforeach
        </select>
    </div>

    <!-- Dropdown to select the agent -->
    <div class="form-group">
        <label for="agentSelect">Select Agent:</label>
        <select id="agentSelect" class="form-control">
            <option value="">All Agents</option> <!-- Option to show all agents' data -->
            @foreach($agents as $agent)
                <option value="{{ $agent->zoho_contact_id }}" {{ $agent->zoho_contact_id == $selectedAgentId ? 'selected' : '' }}>
                    {{ $agent->full_name }}
                </option>
            @endforeach
        </select>
    </div>

    <div id="journeyMap" style="width: 90%; height: calc(100% - 80px);"></div> <!-- Responsive chart container -->
</div>
@endsection

@section('script')
<script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const stageOrder = ['START', 'Potential', 'Pre-Active', 'Active', 'Under Contract', 'Sold', 'Dead'];

    function updateSankeyDiagram(year, agentId) {
        $.ajax({
            url: '{{ route('dealstages.journeymap') }}',
            data: { year: year, agent_id: agentId }, // Send year and agent ID
            success: function (response) {
                Plotly.purge('journeyMap');
                const data = response.journeyData;

                // Ensure stages follow left-to-right order
                const stages = Array.from(new Set(data.flatMap(d => {
                    const currentStage = d.current_stage || 'Unknown Stage';
                    const nextStage = (d.next_stage === 'Sold' || d.next_stage === 'Dead')
                        ? null // No further transitions after Sold or Dead
                        : d.next_stage !== null && d.next_stage !== ''
                            ? d.next_stage 
                            : 'Unchanged';  // Stalled deals are considered 'Unchanged'
                    return [currentStage, nextStage].filter(Boolean); // Remove null nextStage
                }))).sort((a, b) => stageOrder.indexOf(a) - stageOrder.indexOf(b));

                if (!stages.includes('Unchanged')) stages.push('Unchanged');
                if (!stages.includes('Unknown Stage')) stages.push('Unknown Stage');

                // Add 'START' at the beginning
                stages.unshift('START');

                // Function to color the links based on stage flow
                function getLinkColor(sourceStage, targetStage) {
                    if (sourceStage === 'Unknown Stage' || targetStage === 'Unknown Stage') return 'rgba(128, 128, 128, 0.4)';
                    if (sourceStage === "START" && targetStage === "Potential") return 'rgba(149, 165, 166, 0.4)';
                    if (sourceStage === "START" && targetStage === "Pre-Active") return 'rgba(241, 196, 15, 0.4)';
                    if (sourceStage === "START" && targetStage === "Active") return 'rgba(243, 156, 18, 0.4)';
                    if (sourceStage === "START" && targetStage === "Under Contract") return 'rgba(46, 204, 113, 0.4)';
                    if (sourceStage === "START" && targetStage === "Sold") return 'rgba(0, 128, 0, 0.6)';
                    if (sourceStage === "START" && targetStage === "Dead") return 'rgba(192, 57, 43, 0.4)';
                    if (targetStage === 'Sold') return 'rgba(0, 128, 0, 0.6)';
                    if (targetStage === 'Dead') return 'rgba(255, 0, 0, 0.6)';
                    if (stageOrder.indexOf(sourceStage) < stageOrder.indexOf(targetStage)) return 'rgba(0, 0, 255, 0.4)';
                    return 'rgba(33, 33, 33, 0.6)';
                }

                // Define stage colors
                const stageColors = stages.map(stage => {
                    if (stage === 'START') return 'rgb(236, 240, 241)';
                    if (stage === 'Sold') return 'rgb(39, 174, 96)';
                    if (stage === 'Dead') return 'rgb(192, 57, 43)';
                    if (stage === 'Potential') return 'rgb(149, 165, 166)';
                    if (stage === 'Pre-Active') return 'rgb(241, 196, 15)';
                    if (stage === 'Active') return 'rgb(243, 156, 18)';
                    if (stage === 'Under Contract') return 'rgb(46, 204, 113)';
                    if (stage === 'Unknown Stage') return 'rgb(128, 128, 128)'; // Default gray for unknown stages
                    return 'blue'; // Default color for unmatched stages
                });

                // Start links from 'START' to each initial stage
                const startLinks = stages.map(stage => {
                    const startedDeals = data
                        .filter(d => d.current_stage === stage)
                        .reduce((acc, d) => acc + (d.started_at_stage || 0), 0); // Use started_at_stage

                    if (startedDeals > 0) {
                        const targetColor = stageColors[stages.indexOf(stage)];
                        const semiTransparentColor = targetColor.replace(')', ', 0.4)').replace('rgb', 'rgba');

                        return {
                            source: stages.indexOf('START'),
                            target: stages.indexOf(stage),
                            value: startedDeals, // Use the count of deals that started at this stage
                            label: `START → ${stage}: ${startedDeals} deals`,
                            color: semiTransparentColor
                        };
                    }
                }).filter(link => link);

                // Regular links between stages (forward or backward movement)
                const links = data.filter(d => d.current_stage !== d.next_stage && d.next_stage !== 'Unchanged')
                    .map(d => {
                        const currentStage = d.current_stage || 'Unknown Stage';
                        const nextStage = (d.next_stage === 'Sold' || d.next_stage === 'Dead') 
                            ? d.next_stage  // Terminal transitions
                            : d.next_stage !== null && d.next_stage !== '' 
                                ? d.next_stage 
                                : 'Unchanged'; // Handle stalled or unchanged stages

                        const sourceIndex = stages.indexOf(currentStage);
                        const targetIndex = stages.indexOf(nextStage);

                        return {
                            source: sourceIndex,
                            target: targetIndex,
                            value: d.transition_count,
                            label: `${currentStage} → ${nextStage}: ${d.transition_count} deals`,
                            color: getLinkColor(currentStage, nextStage)
                        };
                    }).filter(link => link); // Filter out null links

                // Deals that are "stalled" or "unchanged" in a particular stage
                const stalledLinks = data.filter(d => d.stalled_count)
                    .map(d => ({
                        source: stages.indexOf(d.current_stage),
                        target: stages.indexOf('Unchanged'), // Treat stalled as unchanged
                        value: d.stalled_count,
                        label: `${d.current_stage} stalled: ${d.stalled_count} deals`,
                        color: 'rgba(128, 128, 128, 0.6)' // Gray for stalled/unchanged deals
                    }));

                // Combine startLinks, regular links, and stalled links
                const allLinks = [...startLinks, ...links, ...stalledLinks];

                const sankeyData = {
                    type: "sankey",
                    orientation: "h",
                    node: {
                        pad: 15,
                        thickness: 30,
                        line: { color: "black", width: 0.5 },
                        label: stages,
                        color: stageColors,
                        hovertemplate: '%{label}: %{value} deals<extra></extra>'
                    },
                    link: {
                        source: allLinks.map(l => l.source),
                        target: allLinks.map(l => l.target),
                        value: allLinks.map(l => l.value),
                        hovertemplate: '%{label}<br>%{value} deals<extra></extra>',
                        color: allLinks.map(l => l.color),
                        opacity: 0.6
                    }
                };

                const layout = {
                    title: `Deal Stage Journey Map for ${year}`,
                    font: { size: 12 },
                    height: window.innerHeight - 80,
                    width: window.innerWidth * .85,
                    showlegend: false,
                    responsive: true,
                    dragmode: 'pan',
                    scrollZoom: true
                };

                Plotly.newPlot('journeyMap', [sankeyData], layout);

                window.addEventListener('resize', function () {
                    Plotly.Plots.resize(document.getElementById('journeyMap'));
                });
            }
        });
    }

    // Handle year and agent selection changes
    $('#yearSelect, #agentSelect').on('change', function () {
        const selectedYear = $('#yearSelect').val();
        const selectedAgentId = $('#agentSelect').val();
        updateSankeyDiagram(selectedYear, selectedAgentId);
    });

    // Initialize with default year and agent
    updateSankeyDiagram($('#yearSelect').val(), $('#agentSelect').val());
});



</script>
@endsection
