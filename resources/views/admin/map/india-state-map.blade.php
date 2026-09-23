@php
    $stateStats = $stateStats ?? [];
    $stateNominations = $stateNominations ?? [];
    $totalNominations = $totalNominations ?? 0;
    $maxStateTotal = $maxStateTotal ?? 0;

    // code => name lookup for map labels, e.g. "IN-UP" => "Uttar Pradesh"
    $stateCodeToName = collect($stateStats)->pluck('name', 'code')->toArray();
@endphp

<div class="col-md-6">
    <div class="chart-card h-100">
        <div class="d-flex align-items-center mb-1">
            <i class="fa fa-map-marked-alt me-2 text-primary"></i>
            <h6 class="mb-0 fw-bold">Nominations by State</h6>
            <span class="ms-auto text-muted small">{{ count($stateStats) }} states</span>
        </div>
        <p class="text-muted small mb-3">
            Number of nominations received per state
        </p>

        @if (count($stateStats) > 0)
            {{-- Map --}}
            <div id="indiaStateMap" style="width:100%;height:320px;"></div>


            {{-- Quick summary strip --}}
            <div class="d-flex justify-content-between align-items-center mt-3 mb-2 px-2 py-2 rounded"
                 style="background:#f8fafc;border:1px solid #e5e7eb;">
                <div class="text-center flex-fill">
                    <div class="fw-bold text-primary">{{ count($stateStats) }}</div>
                    <div class="text-muted" style="font-size:11px;">States Covered</div>
                </div>
                <div class="text-center flex-fill border-start border-end">
                    <div class="fw-bold text-primary">{{ number_format($totalNominations) }}</div>
                    <div class="text-muted" style="font-size:11px;">Total Nominations</div>
                </div>
                <div class="text-center flex-fill">
                    <div class="fw-bold text-primary">{{ $stateStats[0]['name'] ?? '-' }}</div>
                    <div class="text-muted" style="font-size:11px;">Top State</div>
                </div>
            </div>

            {{-- Professional stats table --}}
            <div class="table-responsive" style="max-height:260px;overflow-y:auto;">
                <table class="table table-sm align-middle mb-0">
                    <thead style="position:sticky;top:0;background:#fff;z-index:1;">
                        <tr class="text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.03em;">
                            <th style="width:36px;">#</th>
                            <th>State</th>
                            <th style="width:45%;">Share</th>
                            <th class="text-end" style="width:60px;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stateStats as $index => $stat)
                            @php
                                $percent = $maxStateTotal > 0 ? round(($stat['total'] / $maxStateTotal) * 100) : 0;
                            @endphp
                            <tr>
                                <td class="text-muted small">{{ $index + 1 }}</td>
                                <td>
                                    <span class="fw-semibold" style="font-size:13px;">{{ $stat['name'] }}</span>
                                </td>
                                <td>
                                    <div class="progress" style="height:6px;background:#e5e7eb;">
                                        <div class="progress-bar"
                                             role="progressbar"
                                             style="width: {{ $percent }}%; background:linear-gradient(90deg,#93c5fd,#1d4ed8);"
                                             aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <span class="badge rounded-pill" style="background:#dbeafe;color:#1d4ed8;font-weight:600;">
                                        {{ $stat['total'] }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted small mb-0">No state-wise nomination data available yet.</p>
        @endif
    </div>
</div>

@push('scripts')
<script src="{{ asset('assets/js/amcharts/index.js') }}"></script>
<script src="{{ asset('assets/js/amcharts/map.js') }}"></script>
<script src="{{ asset('assets/js/amcharts/indiaLow.js') }}"></script>
<script src="{{ asset('assets/js/amcharts/Animated.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const el = document.getElementById('indiaStateMap');
    if (!el) return;

    // Format: { "IN-UP": 45, "IN-MH": 30, ... }
    const stateData = @json($stateNominations);

    // Format: { "IN-UP": "Uttar Pradesh", "IN-MH": "Maharashtra", ... }
    const stateNames = @json($stateCodeToName);

    // If there's no data at all, don't bother initializing the chart
    if (Object.keys(stateData).length === 0) return;

    am5.ready(function () {
        const root = am5.Root.new("indiaStateMap");
        root.setThemes([am5themes_Animated.new(root)]);

        const chart = root.container.children.push(
            am5map.MapChart.new(root, {
                panX: "translateX",
                panY: "translateY",
                projection: am5map.geoMercator()
            })
        );

        const polygonSeries = chart.series.push(
            am5map.MapPolygonSeries.new(root, {
                geoJSON: am5geodata_indiaLow,
                valueField: "value",
                calculateAggregates: true
            })
        );

        // Build data: every nominated state gets a value + name + a flag so we
        // can visually highlight only the states that DO have nominations.
        const data = Object.keys(stateData).map(id => ({
            id: id,
            value: stateData[id],
            name: stateNames[id] || id,
            hasData: true
        }));
        polygonSeries.data.setAll(data);

        // Colour scale for states WITH nominations (light -> dark blue by count)
        polygonSeries.set("heatRules", [{
            target: polygonSeries.mapPolygons.template,
            dataField: "value",
            min: am5.color(0xbfdbfe),
            max: am5.color(0x1d4ed8),
            key: "fill"
        }]);

        // Base style for ALL states (default: no nominations = light grey, thin border)
        polygonSeries.mapPolygons.template.setAll({
            tooltipText: "{name}: {valueLabel} nominations",
            interactive: true,
            fill: am5.color(0xe5e7eb),
            stroke: am5.color(0xffffff),
            strokeWidth: 0.75
        });

        // Hover state
        polygonSeries.mapPolygons.template.states.create("hover", {
            fill: am5.color(0x60a5fa)
        });

        // Highlight states that actually have nominations:
        // thicker dark-blue border so they visually "pop" against the grey states.
        polygonSeries.mapPolygons.template.adapters.add("stroke", function (stroke, target) {
            const dataItem = target.dataItem;
            if (dataItem && dataItem.dataContext && dataItem.dataContext.hasData) {
                return am5.color(0x1d4ed8);
            }
            return stroke;
        });

        polygonSeries.mapPolygons.template.adapters.add("strokeWidth", function (width, target) {
            const dataItem = target.dataItem;
            if (dataItem && dataItem.dataContext && dataItem.dataContext.hasData) {
                return 1.5;
            }
            return width;
        });

        // Custom tooltip label so states with 0 nominations don't say "undefined"
        polygonSeries.mapPolygons.template.adapters.add("tooltipText", function (text, target) {
            const dataItem = target.dataItem;
            const ctx = dataItem && dataItem.dataContext;
            const hasData = ctx && ctx.hasData;

            if (!hasData) {
                return "{name}: no nominations yet";
            }

            let count = dataItem.get("value");
            if (count === undefined || count === null) {
                count = ctx.value;
            }

            return "{name}: " + count + " nominations";
        });

        // Show state name + nomination count directly on the map — plain text
        // label, always visible (like Google Maps place labels), not just on hover
        polygonSeries.bullets.push(function (root, series, dataItem) {
            const ctx = dataItem.dataContext;

            // Only label states that actually have nominations
            if (!ctx || !ctx.hasData) {
                return;
            }

            const label = am5.Label.new(root, {
                text: "{name}\n{value} nominations",
                populateText: true,
                textAlign: "center",
                fontSize: 11,
                fontWeight: "600",
                fill: am5.color(0x1e3a8a),        // dark blue text
                stroke: am5.color(0xffffff),       // white outline for readability
                strokeWidth: 3,
                paddingTop: 2,
                paddingBottom: 2,
                centerX: am5.p50,
                centerY: am5.p50
            });

            return am5.Bullet.new(root, {
                sprite: label
            });
        });

        chart.appear(1000, 100);
    });
});
</script>
@endpush