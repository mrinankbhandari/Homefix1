/**
 * HomeFix - AJAX Live Search & Filtering Module
 * Aligns with PPT Slide 7: Live search AJAX results update without page reload
 */

window.HomeFixSearch = (function () {
    'use strict';

    let searchTimeout = null;
    let currentCategory = 'All';

    function init() {
        const searchInput = document.getElementById('mainSearchInput');
        const heroInput = document.getElementById('heroSearchInput');
        const ratingSelect = document.getElementById('filterRating');
        const areaSelect = document.getElementById('filterArea');
        const sortSelect = document.getElementById('filterSort');

        // Main Search Input (Debounced)
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    executeSearch();
                }, 300);
            });
        }

        // Hero Search Input (Syncs with main and focuses)
        if (heroInput) {
            heroInput.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    if (searchInput) {
                        searchInput.value = this.value;
                        const servicesSection = document.getElementById('explore-services');
                        if (servicesSection) {
                            servicesSection.scrollIntoView({ behavior: 'smooth' });
                        }
                        executeSearch();
                    }
                }
            });
        }

        // Filter Selects
        [ratingSelect, areaSelect, sortSelect].forEach(select => {
            if (select) {
                select.addEventListener('change', executeSearch);
            }
        });

        // Category Chips
        document.querySelectorAll('.chip-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                document.querySelectorAll('.chip-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                currentCategory = this.getAttribute('data-cat') || 'All';
                executeSearch();
            });
        });
    }

    function executeSearch() {
        const searchInput = document.getElementById('mainSearchInput');
        const ratingSelect = document.getElementById('filterRating');
        const areaSelect = document.getElementById('filterArea');
        const sortSelect = document.getElementById('filterSort');
        const proGrid = document.getElementById('proGrid');
        const proCount = document.getElementById('proCount');

        const params = new URLSearchParams({
            page: 'api-search',
            search: searchInput ? searchInput.value.trim() : '',
            category: currentCategory,
            min_rating: ratingSelect ? ratingSelect.value : '',
            area: areaSelect ? areaSelect.value : 'All',
            sort: sortSelect ? sortSelect.value : 'rating_desc'
        });

        if (proGrid) {
            proGrid.style.opacity = '0.5';
        }

        fetch(`index.php?${params.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (proGrid) {
                proGrid.innerHTML = data.html;
                proGrid.style.opacity = '1';
            }

            if (proCount) {
                proCount.textContent = `${data.count} Pro${data.count === 1 ? '' : 's'} Found`;
            }

            // Sync with MapTiler Map Markers
            if (window.HomeFixMap && typeof window.HomeFixMap.updateMarkers === 'function') {
                window.HomeFixMap.updateMarkers(data.providers);
            }
        })
        .catch(err => {
            console.error('AJAX Search error:', err);
            if (proGrid) proGrid.style.opacity = '1';
        });
    }

    function setCategory(categoryName) {
        currentCategory = categoryName;
        document.querySelectorAll('.chip-btn').forEach(b => {
            if (b.getAttribute('data-cat') === categoryName) {
                b.classList.add('active');
            } else {
                b.classList.remove('active');
            }
        });
        const section = document.getElementById('explore-services');
        if (section) {
            section.scrollIntoView({ behavior: 'smooth' });
        }
        executeSearch();
    }

    function resetFilters() {
        const searchInput = document.getElementById('mainSearchInput');
        const ratingSelect = document.getElementById('filterRating');
        const areaSelect = document.getElementById('filterArea');
        const sortSelect = document.getElementById('filterSort');

        if (searchInput) searchInput.value = '';
        if (ratingSelect) ratingSelect.value = '';
        if (areaSelect) areaSelect.value = 'All';
        if (sortSelect) sortSelect.value = 'rating_desc';

        currentCategory = 'All';
        document.querySelectorAll('.chip-btn').forEach(b => {
            if (b.getAttribute('data-cat') === 'All') b.classList.add('active');
            else b.classList.remove('active');
        });

        executeSearch();
    }

    // Auto-init on DOMContentLoaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    return {
        setCategory: setCategory,
        resetFilters: resetFilters,
        executeSearch: executeSearch
    };
})();
