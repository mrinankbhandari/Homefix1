/**
 * HomeFix - MapTiler API & Leaflet Integration
 * Fully Interactive Navigation:
 * - Smooth scroll-wheel zoom enabled
 * - Double-click zoom & drag panning enabled
 * - Zoom controls (+/-) and Fullscreen expand/collapse
 * - Live zoom level indicator
 * - Auto-detects user location and live distance calculation
 * - Dynamic MapTiler tile layer with seamless backup layer
 */

window.HomeFixMap = (function () {
    'use strict';

    let map = null;
    let markersLayer = null;
    let userLocationLayer = null;
    let activeTileLayer = null;
    let fallbackTriggered = false;
    let userHasInteracted = false;
    let currentUserCoords = null; // { lat, lng }
    let lastLoadedProviders = [];
    let isFullscreen = false;

    const defaultCenter = [19.0760, 72.8777]; // Mumbai Center
    const defaultZoom = 12;

    function getApiKey() {
        const localKey = localStorage.getItem('HOMEFIX_MAPTILER_KEY');
        if (localKey && localKey.trim() !== '') {
            return localKey.trim();
        }
        const serverKey = (window.MAPTILER_API_KEY || '').trim();
        if (serverKey && serverKey !== 'YOUR_MAPTILER_KEY_HERE' && serverKey !== 'v4wI9Zl2vQ9fGv6XwQ3Z') {
            return serverKey;
        }
        return '';
    }

    function init() {
        const mapContainer = document.getElementById('maptiler-map');
        if (!mapContainer || typeof L === 'undefined') {
            return;
        }

        // Clean any existing Leaflet map instance
        if (map !== null) {
            map.remove();
            map = null;
        }

        fallbackTriggered = false;
        userHasInteracted = false;

        // Initialize Leaflet Map with FULL Interactive Navigation enabled
        map = L.map('maptiler-map', {
            center: defaultCenter,
            zoom: defaultZoom,
            minZoom: 9,
            maxZoom: 19,
            scrollWheelZoom: true,      // Enabled! Mouse wheel zooms freely
            doubleClickZoom: true,      // Enabled! Double click zooms in
            touchZoom: true,            // Enabled! Pinch to zoom on mobile/tablets
            dragging: true,             // Enabled! Free drag and pan
            boxZoom: true,              // Enabled! Shift-drag box zoom
            keyboard: true,             // Enabled! Arrow keys navigate
            zoomAnimation: true,
            fadeAnimation: true,
            markerZoomAnimation: true,
            zoomControl: false          // We create a custom styled navigation control
        });

        // Add custom styled zoom controls in top-left
        L.control.zoom({
            position: 'topleft'
        }).addTo(map);

        // Track user interaction so automatic updates don't fight user zooming/panning
        map.on('dragstart', function () { userHasInteracted = true; });
        map.on('zoomstart', function () { userHasInteracted = true; });

        // Update zoom indicator on zoom
        map.on('zoomend', function () {
            updateZoomDisplay();
        });

        // Load Tiles (MapTiler Streets or Fallback)
        const apiKey = getApiKey();

        if (apiKey) {
            // MapTiler 256px raster tiles
            const maptilerUrl = `https://api.maptiler.com/maps/streets-v2/256/{z}/{x}/{y}.png?key=${encodeURIComponent(apiKey)}`;
            const maptilerAttr = '&copy; <a href="https://www.maptiler.com/copyright/" target="_blank">MapTiler</a> &copy; <a href="https://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap</a>';

            activeTileLayer = L.tileLayer(maptilerUrl, {
                tileSize: 256,
                minZoom: 9,
                maxZoom: 19,
                crossOrigin: true,
                attribution: maptilerAttr
            });

            // Single failover if key is invalid, rate-limited, or blocked
            activeTileLayer.on('tileerror', function () {
                if (fallbackTriggered) return;
                fallbackTriggered = true;
                console.warn('MapTiler tile returned an error. Gracefully falling back to OpenStreetMap tiles.');

                if (map && activeTileLayer) {
                    map.removeLayer(activeTileLayer);
                }
                loadFallbackTiles();
            });

            activeTileLayer.addTo(map);

            const statusPill = document.getElementById('mapStatusPill');
            if (statusPill) {
                statusPill.textContent = 'MapTiler Streets v2';
                statusPill.style.background = '#EDE9FE';
                statusPill.style.color = '#5B21B6';
            }
        } else {
            // No custom key entered yet: load OpenStreetMap immediately so map is 100% visible
            loadFallbackTiles();
        }

        // Marker layer groups
        markersLayer = L.layerGroup().addTo(map);
        userLocationLayer = L.layerGroup().addTo(map);

        // Load initial providers
        if (window.INITIAL_PROVIDERS && Array.isArray(window.INITIAL_PROVIDERS)) {
            lastLoadedProviders = window.INITIAL_PROVIDERS;
            updateMarkers(window.INITIAL_PROVIDERS, true); // true = initial fit
        } else {
            fetchProviders();
        }

        // Setup "Locate Me" button listener
        const locateBtn = document.getElementById('mapLocateBtn');
        if (locateBtn) {
            locateBtn.addEventListener('click', function () {
                requestUserLocation(true);
            });
        }

        updateZoomDisplay();

        // Ensure Leaflet calculates viewport properly after layout renders
        setTimeout(function () {
            if (map) map.invalidateSize();
        }, 200);
        setTimeout(function () {
            if (map) map.invalidateSize();
        }, 800);
    }

    function loadFallbackTiles() {
        if (!map) return;
        const osmLayer = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            minZoom: 9,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap</a> contributors'
        });
        osmLayer.addTo(map);

        const statusPill = document.getElementById('mapStatusPill');
        if (statusPill) {
            statusPill.textContent = 'Active (OpenStreetMap Layer)';
            statusPill.style.background = '#FEF08A';
            statusPill.style.color = '#0F172A';
        }
    }

    function updateZoomDisplay() {
        const zoomEl = document.getElementById('mapZoomIndicator');
        if (zoomEl && map) {
            zoomEl.textContent = `Zoom: ${map.getZoom()}x`;
        }
    }

    function fetchProviders() {
        fetch('index.php?page=api-map-providers')
            .then(res => res.json())
            .then(data => {
                if (data.success && Array.isArray(data.providers)) {
                    lastLoadedProviders = data.providers;
                    updateMarkers(data.providers, true);
                }
            })
            .catch(err => console.error('Error fetching map providers:', err));
    }

    function createCustomPin(serviceType) {
        const icons = {
            'Electrician': '⚡',
            'Plumber': '🔧',
            'Carpenter': '🪚',
            'Cleaning': '🧹',
            'Appliance Repair': '❄️',
            'Painting': '🎨'
        };

        const iconSymbol = icons[serviceType] || '📍';

        return L.divIcon({
            className: 'custom-maptiler-marker',
            html: `
                <div style="
                    background: #6C5CE7;
                    border: 2px solid #0F172A;
                    box-shadow: 2px 2px 0px #0F172A;
                    border-radius: 50% 50% 50% 0;
                    transform: rotate(-45deg);
                    width: 38px;
                    height: 38px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    cursor: pointer;
                    transition: transform 0.15s ease;
                ">
                    <span style="
                        transform: rotate(45deg);
                        font-size: 17px;
                    ">${iconSymbol}</span>
                </div>
            `,
            iconSize: [38, 38],
            iconAnchor: [19, 38],
            popupAnchor: [0, -38]
        });
    }

    function calculateDistanceKm(lat1, lon1, lat2, lon2) {
        const R = 6371;
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                  Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                  Math.sin(dLon / 2) * Math.sin(dLon / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return (R * c).toFixed(1);
    }

    function updateMarkers(providers, forceBoundsFit = false) {
        if (!map || !markersLayer) return;

        lastLoadedProviders = providers || [];
        markersLayer.clearLayers();

        if (!providers || providers.length === 0) return;

        const bounds = [];

        if (currentUserCoords) {
            bounds.push([currentUserCoords.lat, currentUserCoords.lng]);
        }

        providers.forEach(p => {
            if (!p.lat || !p.lng) return;

            const latLng = [parseFloat(p.lat), parseFloat(p.lng)];
            bounds.push(latLng);

            const marker = L.marker(latLng, {
                icon: createCustomPin(p.service_type)
            });

            let distanceHtml = '';
            if (currentUserCoords) {
                const distKm = calculateDistanceKm(currentUserCoords.lat, currentUserCoords.lng, p.lat, p.lng);
                distanceHtml = `<span style="display:inline-block; background:#FEF08A; color:#0F172A; border:1px solid #0F172A; font-weight:800; font-size:0.75rem; padding:2px 6px; border-radius:4px; margin-bottom:6px;">📍 ${distKm} km from you</span>`;
                
                const card = document.getElementById(`provider-card-${p.id}`);
                if (card) {
                    let distTag = card.querySelector('.user-dist-tag');
                    if (!distTag) {
                        distTag = document.createElement('span');
                        distTag.className = 'badge badge-amber user-dist-tag';
                        distTag.style.marginLeft = '6px';
                        const metricRow = card.querySelector('.provider-metrics');
                        if (metricRow) metricRow.appendChild(distTag);
                    }
                    distTag.textContent = `📍 ${distKm} km away`;
                }
            }

            const popupContent = `
                <div class="map-popup-card" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                    <span class="popup-tag">${p.service_type}</span>
                    ${distanceHtml}
                    <h4 style="font-family: 'Outfit', sans-serif; margin: 4px 0;">${p.name}</h4>
                    <p style="font-size: 0.8rem; color: #64748B; margin: 2px 0 6px;">📍 ${p.area}, Mumbai</p>
                    <div style="font-size: 0.82rem; font-weight: 700; margin-bottom: 8px;">
                        <span style="color: #F59E0B;">★ ${p.rating}</span> · <strong>Starts ₹${p.hourly_rate}</strong>
                    </div>
                    <a href="index.php?page=book&provider_id=${p.id}" class="btn btn-primary btn-sm btn-block" style="display: block; text-align: center; text-decoration: none; padding: 6px 12px; font-size: 0.8rem;">
                        Book Pro ⚡
                    </a>
                </div>
            `;

            marker.bindPopup(popupContent);
            markersLayer.addLayer(marker);
        });

        // Only auto-fit bounds on initial load or explicit reset, NOT when user is freely zooming/panning!
        if ((forceBoundsFit || !userHasInteracted) && bounds.length > 0) {
            map.fitBounds(bounds, { padding: [40, 40], maxZoom: 14 });
        }
    }

    function resetView() {
        if (!map) return;
        userHasInteracted = false;
        if (currentUserCoords) {
            map.flyTo([currentUserCoords.lat, currentUserCoords.lng], 13, { duration: 1 });
        } else {
            map.flyTo(defaultCenter, defaultZoom, { duration: 1 });
        }
        if (typeof window.HomeFixToast === 'function') {
            window.HomeFixToast('Map re-centered on Mumbai.', 'info');
        }
    }

    function toggleFullscreen() {
        const mapCard = document.querySelector('.map-card');
        const expandBtn = document.getElementById('mapExpandBtn');
        if (!mapCard) return;

        isFullscreen = !isFullscreen;

        if (isFullscreen) {
            mapCard.classList.add('map-card-fullscreen');
            if (expandBtn) expandBtn.textContent = '✕ Close Fullscreen';
            document.body.style.overflow = 'hidden';
        } else {
            mapCard.classList.remove('map-card-fullscreen');
            if (expandBtn) expandBtn.textContent = '⛶ Expand';
            document.body.style.overflow = '';
        }

        setTimeout(function () {
            if (map) map.invalidateSize();
        }, 200);
    }

    function requestUserLocation(userInitiated = false) {
        if (!navigator.geolocation) {
            if (userInitiated && typeof window.HomeFixToast === 'function') {
                window.HomeFixToast('Geolocation is not supported by your browser.', 'error');
            }
            return;
        }

        const locateBtn = document.getElementById('mapLocateBtn');
        if (locateBtn) {
            locateBtn.textContent = '📍 Locating...';
            locateBtn.disabled = true;
        }

        navigator.geolocation.getCurrentPosition(
            function (position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                currentUserCoords = { lat: lat, lng: lng };
                userHasInteracted = true;

                if (userLocationLayer) {
                    userLocationLayer.clearLayers();

                    const accuracyRadius = Math.max(150, Math.min(position.coords.accuracy || 300, 1500));
                    const accuracyCircle = L.circle([lat, lng], {
                        radius: accuracyRadius,
                        color: '#6C5CE7',
                        fillColor: '#EDE9FE',
                        fillOpacity: 0.35,
                        weight: 2,
                        dashArray: '4, 4'
                    });
                    userLocationLayer.addLayer(accuracyCircle);

                    const userMarkerIcon = L.divIcon({
                        className: 'user-location-pin',
                        html: `
                            <div style="position:relative; width:44px; height:44px; display:flex; align-items:center; justify-content:center;">
                                <div style="position:absolute; width:100%; height:100%; border-radius:50%; background:rgba(16, 185, 129, 0.4); animation:pulsePin 1.8s infinite ease-out;"></div>
                                <div style="position:relative; width:34px; height:34px; background:#10B981; border:2.5px solid #0F172A; box-shadow:2px 2px 0px #0F172A; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:16px; z-index:3;">
                                    👤
                                </div>
                            </div>
                        `,
                        iconSize: [44, 44],
                        iconAnchor: [22, 22],
                        popupAnchor: [0, -24]
                    });

                    const userMarker = L.marker([lat, lng], { icon: userMarkerIcon, zIndexOffset: 1000 });
                    userMarker.bindPopup(`
                        <div style="font-family:'Plus Jakarta Sans',sans-serif; text-align:center; padding:4px;">
                            <span class="badge badge-emerald" style="margin-bottom:4px;">Your Location</span>
                            <h4 style="font-family:'Outfit',sans-serif; margin:4px 0;">You Are Here 📍</h4>
                            <p style="font-size:0.8rem; color:#64748B; margin:0;">Fixers around you are highlighted below.</p>
                        </div>
                    `);
                    userLocationLayer.addLayer(userMarker);
                }

                if (map) {
                    map.flyTo([lat, lng], 13, { duration: 1.2 });
                }

                updateMarkers(lastLoadedProviders, false);

                if (locateBtn) {
                    locateBtn.textContent = '✓ Located';
                    locateBtn.classList.remove('btn-lime');
                    locateBtn.classList.add('btn-emerald');
                    locateBtn.disabled = false;
                }

                if (typeof window.HomeFixToast === 'function') {
                    window.HomeFixToast('📍 Your location has been detected! Showing nearby fixers.', 'success');
                }
            },
            function (error) {
                console.warn('Geolocation error:', error.message);
                if (locateBtn) {
                    locateBtn.textContent = '📍 Locate Me';
                    locateBtn.disabled = false;
                }
                if (userInitiated && typeof window.HomeFixToast === 'function') {
                    window.HomeFixToast('Could not access your location (' + error.message + '). Showing all Mumbai pros.', 'info');
                }
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 60000
            }
        );
    }

    function focusProvider(lat, lng) {
        if (!map) return;
        userHasInteracted = true;
        map.setView([parseFloat(lat), parseFloat(lng)], 15, { animate: true });
    }

    function setKeyFromPrompt() {
        const current = getApiKey();
        const entered = prompt('Enter or paste your MapTiler API Key (from cloud.maptiler.com):', current);
        if (entered !== null) {
            localStorage.setItem('HOMEFIX_MAPTILER_KEY', entered.trim());
            if (typeof window.HomeFixToast === 'function') {
                window.HomeFixToast('MapTiler key saved! Reloading map...', 'success');
            }
            init();
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    window.addEventListener('load', function () {
        if (map) map.invalidateSize();
    });
    window.addEventListener('resize', function () {
        if (map) map.invalidateSize();
    });

    return {
        updateMarkers: updateMarkers,
        focusProvider: focusProvider,
        requestUserLocation: requestUserLocation,
        setKeyFromPrompt: setKeyFromPrompt,
        resetView: resetView,
        toggleFullscreen: toggleFullscreen,
        getApiKey: getApiKey
    };
})();
