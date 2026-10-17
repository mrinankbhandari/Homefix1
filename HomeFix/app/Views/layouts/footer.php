<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <h3>Home<span style="color:#FEF08A;">Fix</span>.</h3>
                <p>A maximalist local services marketplace connecting Mumbai residents with verified neighborhood fixers.</p>
                <div style="margin-top:16px; display:flex; gap:8px; flex-wrap:wrap;">
                    <span class="badge badge-amber">⚡ Instant Booking</span>
                    <span class="badge badge-emerald">🛡️ Verified Pros</span>
                    <span class="badge badge-violet">🗺️ MapTiler Enabled</span>
                </div>
            </div>

            <div class="footer-col">
                <h4>Quick Navigation</h4>
                <ul>
                    <li><a href="index.php?page=home#explore-services">Browse Fixers</a></li>
                    <li><a href="index.php?page=home#how-it-works">How It Works</a></li>
                    <li><a href="index.php?page=xml-catalog">XML &amp; XSL Catalog</a></li>
                    <li><a href="public/xml/services.xml" target="_blank">Raw XML (services.xml)</a></li>
                    <li><a href="public/xml/services.dtd" target="_blank">DTD Schema (services.dtd)</a></li>
                    <li><a href="public/xml/services.xsl" target="_blank">XSLT Stylesheet (services.xsl)</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Access Portals</h4>
                <ul>
                    <li><a href="index.php?page=login">Homeowner Login</a></li>
                    <li><a href="index.php?page=register">Homeowner Sign Up</a></li>
                    <li><a href="index.php?page=provider-login">Service Pro Login</a></li>
                    <li><a href="index.php?page=provider-register">Become a Service Partner</a></li>
                    <li><a href="index.php?page=admin-login">Admin Management Console</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Mumbai Neighborhoods</h4>
                <ul>
                    <li><a href="index.php?page=home" onclick="window.HomeFixSearch && window.HomeFixSearch.setCategory('All');">Andheri West &amp; East</a></li>
                    <li><a href="index.php?page=home">Bandra &amp; Khar</a></li>
                    <li><a href="index.php?page=home">Dadar &amp; Matunga</a></li>
                    <li><a href="index.php?page=home">Powai &amp; Hiranandani</a></li>
                    <li><a href="index.php?page=home">Chembur &amp; Ghatkopar</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <span>© 2026 HomeFix — Web Design &amp; Development Mini Project. Built with PHP (MVC), MySQL, MapTiler &amp; AJAX.</span>
            <span>Technology Stack: HTML5 · CSS3 · JS · PHP · MySQL · XML/DTD/XSL · MapTiler API</span>
        </div>
    </div>
</footer>

<!-- Interactive Review Modal (Customer Star Rating & Feedback) -->
<div class="modal-overlay" id="reviewModal">
    <div class="modal-window">
        <button class="modal-close-btn" data-close-modal="reviewModal">&times;</button>
        <div class="badge badge-amber" style="margin-bottom:12px;">Verified Customer Review</div>
        <h3>Rate Your <span class="text-gradient">Service</span></h3>
        <p id="reviewModalSubtitle" style="font-size:0.9rem; color:#64748B; margin-top:4px; margin-bottom:16px;">Share your experience with the service provider.</p>

        <form id="ajaxReviewForm">
            <input type="hidden" name="booking_id" id="reviewBookingId" value="">
            <input type="hidden" name="rating" id="reviewRatingInput" value="5">

            <label class="form-label">Your Rating (1 to 5 Stars)</label>
            <div class="star-rating-selector">
                <span class="star-btn active" data-val="1">★</span>
                <span class="star-btn active" data-val="2">★</span>
                <span class="star-btn active" data-val="3">★</span>
                <span class="star-btn active" data-val="4">★</span>
                <span class="star-btn active" data-val="5">★</span>
            </div>

            <div class="form-group">
                <label class="form-label" for="reviewComment">Your Written Feedback</label>
                <textarea class="form-textarea" id="reviewComment" name="comment" rows="4" required placeholder="How was their punctuality, quality of work, and professionalism?"></textarea>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Post Verified Review ★</button>
        </form>
    </div>
</div>

<!-- Leaflet JS (For MapTiler API mapping) -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<!-- Project JavaScript Modules -->
<script src="<?= BASE_URL ?>/public/js/main.js"></script>
<script src="<?= BASE_URL ?>/public/js/search-ajax.js"></script>
<script src="<?= BASE_URL ?>/public/js/map-maptiler.js"></script>
<script src="<?= BASE_URL ?>/public/js/booking.js"></script>

</body>
</html>
