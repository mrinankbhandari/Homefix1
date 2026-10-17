<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="hero-grid">
            <div>
                <div class="eyebrow-pill">
                    <span>📍 Mumbai Local Services Hub</span>
                    <span style="color:#6C5CE7;">● Active Now</span>
                </div>
                <h1 class="hero-title">
                    Life breaks down.<br>
                    <span class="text-gradient">We fix it right.</span>
                </h1>
                <p class="hero-subtitle">
                    Finding a good plumber, electrician, or carpenter is no longer a guessing game. Compare trusted neighbourhood pros, inspect transparent prices, and book instantly.
                </p>

                <!-- Search Input Bar -->
                <div class="hero-search-box">
                    <span style="font-size:1.3rem; margin-left:6px;">🔍</span>
                    <input type="text" id="heroSearchInput" class="hero-search-input" placeholder="Search by service or area (e.g. Ramesh, Electrician, Andheri, Tap leak)...">
                    <button class="btn btn-primary" onclick="window.HomeFixSearch && window.HomeFixSearch.executeSearch()">
                        Find Fixer ⚡
                    </button>
                </div>

                <!-- Quick Category Chips -->
                <div class="quick-chips">
                    <span class="chip-label">Popular:</span>
                    <button class="chip-btn active" data-cat="All">✦ All Pros</button>
                    <?php foreach ($categories as $catKey => $catInfo): ?>
                        <button class="chip-btn" data-cat="<?= htmlspecialchars($catKey) ?>">
                            <span><?= $catInfo['icon'] ?></span>
                            <span><?= htmlspecialchars($catInfo['label']) ?></span>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="hero-visual">
                <div class="hero-sticker sticker-top">
                    <span>⚡ 100% Upfront Pricing</span>
                </div>
                <div class="hero-feature-card">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                        <span class="badge badge-emerald">Verified Marketplace</span>
                        <span style="font-size:0.85rem; font-weight:800; color:#64748B;">MUMBAI METRO</span>
                    </div>
                    <h3 style="font-size:1.5rem; margin-bottom:6px;">The HomeFix Guarantee</h3>
                    <p style="font-size:0.92rem; color:#475569;">No endless calls. Transparent pricing and real reviews from your neighborhood.</p>

                    <div class="feature-pill-grid">
                        <div class="feature-pill-item">
                            <span class="feature-pill-icon">⚡</span>
                            <div>
                                <div class="feature-pill-title">Electricians</div>
                                <div class="feature-pill-sub">Wiring, MCB, Lighting</div>
                            </div>
                        </div>
                        <div class="feature-pill-item">
                            <span class="feature-pill-icon">🔧</span>
                            <div>
                                <div class="feature-pill-title">Plumbers</div>
                                <div class="feature-pill-sub">Leaks, Taps, Tanks</div>
                            </div>
                        </div>
                        <div class="feature-pill-item">
                            <span class="feature-pill-icon">🪚</span>
                            <div>
                                <div class="feature-pill-title">Carpenters</div>
                                <div class="feature-pill-sub">Hinges, Furniture, Locks</div>
                            </div>
                        </div>
                        <div class="feature-pill-item">
                            <span class="feature-pill-icon">❄️</span>
                            <div>
                                <div class="feature-pill-title">Appliances</div>
                                <div class="feature-pill-sub">AC Jet Wash, Fridge, WM</div>
                            </div>
                        </div>
                    </div>

                    <div style="display:flex; align-items:center; justify-content:space-between; margin-top:16px; border-top:var(--border-thin); padding-top:14px;">
                        <div style="display:flex; align-items:center; gap:8px;">
                            <span style="font-size:1.3rem;">★</span>
                            <span style="font-weight:800; font-size:0.95rem;">4.8 / 5.0 Average Rating</span>
                        </div>
                        <a href="#explore-services" class="btn btn-secondary btn-sm">Explore Map ↓</a>
                    </div>
                </div>
                <div class="hero-sticker sticker-bottom">
                    <span>🛡️ Verified Local Fixers</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Filter & Search Section (AJAX Enabled) -->
<section class="filter-section" id="explore-services">
    <div class="container">
        <div class="filter-bar">
            <div class="filter-group">
                <span style="font-family:var(--font-heading); font-weight:900; font-size:1.1rem;">Search Fixers:</span>
                <input type="text" id="mainSearchInput" class="form-input" style="width:280px; padding:8px 12px; font-size:0.9rem;" placeholder="Search pro, skill, or area...">
            </div>

            <div class="filter-group">
                <select id="filterRating" class="filter-select">
                    <option value="">★ Any Rating</option>
                    <option value="4.0">★ 4.0 &amp; Above</option>
                    <option value="4.5">★ 4.5 &amp; Above</option>
                    <option value="4.8">★ 4.8 &amp; Above (Top Rated)</option>
                </select>

                <select id="filterArea" class="filter-select">
                    <option value="All">📍 All Areas (Mumbai)</option>
                    <?php foreach ($areas as $a): ?>
                        <option value="<?= htmlspecialchars($a['area']) ?>"><?= htmlspecialchars($a['area']) ?></option>
                    <?php endforeach; ?>
                </select>

                <select id="filterSort" class="filter-select">
                    <option value="rating_desc">Sort: Highest Rated</option>
                    <option value="price_asc">Price: Low to High</option>
                    <option value="price_desc">Price: High to Low</option>
                    <option value="exp_desc">Most Experienced</option>
                </select>
            </div>
        </div>
    </div>
</section>

<!-- Main Exploration: Split Layout (Cards + MapTiler Map) -->
<section class="explore-section">
    <div class="container">
        <div class="explore-layout">
            <!-- Left Column: Provider List (AJAX Target) -->
            <div>
                <div class="results-meta">
                    <h2 style="font-size:1.7rem;">
                        Available <span class="text-gradient">Fixers</span>
                    </h2>
                    <span id="proCount" class="badge badge-amber" style="font-size:0.85rem;">
                        <?= count($providers) ?> Pros Found
                    </span>
                </div>

                <div class="provider-list-grid" id="proGrid">
                    <?php if (empty($providers)): ?>
                        <div class="empty-state">
                            <div class="empty-icon">🔍</div>
                            <h3>No Providers Found</h3>
                            <p>Try clearing your search filters to view all available service professionals.</p>
                            <button class="btn btn-secondary" onclick="window.HomeFixSearch.resetFilters()">Reset Filters</button>
                        </div>
                    <?php else: ?>
                        <?php foreach ($providers as $p): ?>
                            <?php require __DIR__ . '/_provider_card.php'; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right Column: MapTiler API Interactive Map -->
            <div class="map-sticky-wrapper">
                <div class="map-card">
                    <div class="map-card-header">
                        <div style="display:flex; align-items:center; gap:8px;">
                            <span style="font-size:1.2rem;">🗺️</span>
                            <h3>Local Pros Map</h3>
                        </div>
                        <div style="display:flex; gap:6px; flex-wrap:wrap;">
                            <button type="button" class="btn btn-secondary btn-sm" id="mapExpandBtn" onclick="window.HomeFixMap && window.HomeFixMap.toggleFullscreen()" title="Toggle full screen map view">
                                ⛶ Expand
                            </button>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="window.HomeFixMap && window.HomeFixMap.resetView()" title="Re-center map on Mumbai">
                                ⟲ Reset
                            </button>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="window.HomeFixMap && window.HomeFixMap.setKeyFromPrompt()" title="View or change MapTiler API Key">
                                🔑 Key
                            </button>
                            <button type="button" class="btn btn-lime btn-sm" id="mapLocateBtn" title="Detect your location and show pros near you">
                                📍 Locate Me
                            </button>
                        </div>
                    </div>
                    <div id="maptiler-map"></div>
                    <div class="map-card-footer">
                        <span id="mapZoomIndicator" style="font-family:var(--font-heading); font-weight:800; background:#F1F5F9; border:1px solid #CBD5E1; padding:2px 8px; border-radius:4px;">Zoom: 12x</span>
                        <span id="mapStatusPill">MapTiler · Streets v2</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How a Booking Works (Slide 9 from PPT) -->
<section style="background:#FFFFFF; border-top:var(--border-thick); border-bottom:var(--border-thick); padding:64px 0;" id="how-it-works">
    <div class="container">
        <div style="text-align:center; max-width:680px; margin:0 auto 48px;">
            <div class="badge badge-violet" style="margin-bottom:12px;">6-Step Seamless Process</div>
            <h2 style="font-size:2.3rem; margin-bottom:12px;">How a <span class="text-gradient">Booking Works</span></h2>
            <p>Direct from our platform workflow: simple, transparent, and built for both homeowners and workers.</p>
        </div>

        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(260px, 1fr)); gap:24px;">
            <div style="background:#F8FAFC; border:var(--border-thick); box-shadow:var(--shadow-main); border-radius:var(--radius-md); padding:24px;">
                <span class="badge badge-amber" style="font-size:0.85rem; margin-bottom:12px;">Step 01</span>
                <h4 style="margin-bottom:8px;">Search for a Service</h4>
                <p style="font-size:0.9rem;">Filter by electrician, plumber, carpenter, area, or price to find the perfect fit.</p>
            </div>

            <div style="background:#F8FAFC; border:var(--border-thick); box-shadow:var(--shadow-main); border-radius:var(--radius-md); padding:24px;">
                <span class="badge badge-amber" style="font-size:0.85rem; margin-bottom:12px;">Step 02</span>
                <h4 style="margin-bottom:8px;">Pick a Provider</h4>
                <p style="font-size:0.9rem;">Compare verified ratings, experience, past customer reviews, and starting rates.</p>
            </div>

            <div style="background:#F8FAFC; border:var(--border-thick); box-shadow:var(--shadow-main); border-radius:var(--radius-md); padding:24px;">
                <span class="badge badge-amber" style="font-size:0.85rem; margin-bottom:12px;">Step 03</span>
                <h4 style="margin-bottom:8px;">Choose Date &amp; Confirm</h4>
                <p style="font-size:0.9rem;">Pick an exact appointment date and time slot, enter your address, and submit.</p>
            </div>

            <div style="background:#F8FAFC; border:var(--border-thick); box-shadow:var(--shadow-main); border-radius:var(--radius-md); padding:24px;">
                <span class="badge badge-emerald" style="font-size:0.85rem; margin-bottom:12px;">Step 04</span>
                <h4 style="margin-bottom:8px;">Provider Accepts</h4>
                <p style="font-size:0.9rem;">The pro reviews your request and accepts the booking through their dedicated portal.</p>
            </div>

            <div style="background:#F8FAFC; border:var(--border-thick); box-shadow:var(--shadow-main); border-radius:var(--radius-md); padding:24px;">
                <span class="badge badge-emerald" style="font-size:0.85rem; margin-bottom:12px;">Step 05</span>
                <h4 style="margin-bottom:8px;">Service is Completed</h4>
                <p style="font-size:0.9rem;">The pro arrives on time, fixes the issue, and marks the job as completed.</p>
            </div>

            <div style="background:#F8FAFC; border:var(--border-thick); box-shadow:var(--shadow-main); border-radius:var(--radius-md); padding:24px;">
                <span class="badge badge-emerald" style="font-size:0.85rem; margin-bottom:12px;">Step 06</span>
                <h4 style="margin-bottom:8px;">Customer Leaves Review</h4>
                <p style="font-size:0.9rem;">Rate from 1 to 5 stars and leave authentic feedback to keep the community trusted.</p>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Banner -->
<section style="padding:60px 0;">
    <div class="container">
        <div style="background:#6C5CE7; color:#FFFFFF; border:var(--border-thick); box-shadow:var(--shadow-heavy); border-radius:var(--radius-lg); padding:48px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:24px;">
            <div style="max-width:600px;">
                <span class="badge badge-amber" style="margin-bottom:12px;">For Skilled Professionals</span>
                <h2 style="color:#FFFFFF; font-size:2.2rem; margin-bottom:12px;">Are you a plumber, electrician or carpenter?</h2>
                <p style="color:#E0E7FF; font-size:1.05rem;">Join the HomeFix partner network. Receive direct customer appointments with zero upfront fees.</p>
            </div>
            <div>
                <a href="index.php?page=provider-register" class="btn btn-lime" style="font-size:1.1rem; padding:14px 28px;">
                    Register as Pro Partner ↗
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Inject initial providers into JS for map markers -->
<script>
    window.INITIAL_PROVIDERS = <?= json_encode(array_map(function($p) {
        return [
            'id' => (int)$p['provider_id'],
            'name' => $p['name'],
            'service_type' => $p['service_type'],
            'area' => $p['area'],
            'rating' => (float)$p['rating'],
            'hourly_rate' => (float)$p['hourly_rate'],
            'lat' => (float)$p['latitude'],
            'lng' => (float)$p['longitude'],
            'address' => $p['address']
        ];
    }, $providers)) ?>;
</script>
