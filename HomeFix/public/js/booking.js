/**
 * HomeFix - Booking & Interactive Action Logic
 * Handles booking form calculations, status updates via AJAX, and star review modal
 */

(function () {
    'use strict';

    // 1. Booking Form Logic (Price recalculation & Date min constraints)
    const serviceSelect = document.getElementById('bookingServiceSelect');
    const displayPrice = document.getElementById('bookingDisplayPrice');
    const dateInput = document.getElementById('bookingDateInput');

    if (dateInput) {
        // Enforce minimum date as today
        const today = new Date().toISOString().split('T')[0];
        dateInput.setAttribute('min', today);
    }

    if (serviceSelect && displayPrice) {
        serviceSelect.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const price = selectedOption.getAttribute('data-price') || '0';
            displayPrice.textContent = '₹' + parseFloat(price).toFixed(2);
        });
    }

    // 2. AJAX Booking Status Actions (Provider & Customer Dashboards)
    document.querySelectorAll('.btn-update-booking-status').forEach(btn => {
        btn.addEventListener('click', function () {
            const bookingId = this.getAttribute('data-booking-id');
            const targetStatus = this.getAttribute('data-status');
            const confirmMsg = this.getAttribute('data-confirm') || `Are you sure you want to mark this booking as ${targetStatus}?`;

            if (!confirm(confirmMsg)) {
                return;
            }

            const formData = new FormData();
            formData.append('booking_id', bookingId);
            formData.append('status', targetStatus);

            this.disabled = true;
            this.textContent = 'Updating...';

            fetch('index.php?page=api-update-booking', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    window.HomeFixToast(data.message, 'success');
                    // Reload after brief moment or update badge
                    setTimeout(() => window.location.reload(), 600);
                } else {
                    window.HomeFixToast(data.message || 'Status update failed.', 'error');
                    this.disabled = false;
                }
            })
            .catch(err => {
                console.error(err);
                window.HomeFixToast('Network error while updating booking.', 'error');
                this.disabled = false;
            });
        });
    });

    // 3. Interactive Star Rating & Review Modal
    let selectedStarRating = 5;

    window.openReviewModal = function (bookingId, providerName, serviceName) {
        const modal = document.getElementById('reviewModal');
        const hiddenBookingId = document.getElementById('reviewBookingId');
        const modalSubtitle = document.getElementById('reviewModalSubtitle');

        if (hiddenBookingId) hiddenBookingId.value = bookingId;
        if (modalSubtitle) modalSubtitle.textContent = `Reviewing ${providerName} for ${serviceName}`;

        resetStars(5);
        window.openModal('reviewModal');
    };

    function resetStars(rating) {
        selectedStarRating = rating;
        const hiddenRating = document.getElementById('reviewRatingInput');
        if (hiddenRating) hiddenRating.value = rating;

        document.querySelectorAll('.star-rating-selector .star-btn').forEach(btn => {
            const val = parseInt(btn.getAttribute('data-val'), 10);
            if (val <= rating) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });
    }

    // Star hover and click interactions
    document.querySelectorAll('.star-rating-selector .star-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const val = parseInt(this.getAttribute('data-val'), 10);
            resetStars(val);
        });

        btn.addEventListener('mouseenter', function () {
            const val = parseInt(this.getAttribute('data-val'), 10);
            document.querySelectorAll('.star-rating-selector .star-btn').forEach(b => {
                const bVal = parseInt(b.getAttribute('data-val'), 10);
                if (bVal <= val) b.style.color = '#F59E0B';
                else b.style.color = '#CBD5E1';
            });
        });
    });

    const starContainer = document.querySelector('.star-rating-selector');
    if (starContainer) {
        starContainer.addEventListener('mouseleave', function () {
            resetStars(selectedStarRating);
        });
    }

    // Submit Review via AJAX
    const reviewForm = document.getElementById('ajaxReviewForm');
    if (reviewForm) {
        reviewForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Submitting Review...';
            }

            const formData = new FormData(this);

            fetch('index.php?page=api-add-review', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    window.HomeFixToast(data.message, 'success');
                    window.closeModal('reviewModal');
                    setTimeout(() => window.location.reload(), 700);
                } else {
                    window.HomeFixToast(data.message || 'Failed to submit review.', 'error');
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Post Verified Review ★';
                    }
                }
            })
            .catch(err => {
                console.error(err);
                window.HomeFixToast('Connection issue while submitting review.', 'error');
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Post Verified Review ★';
                }
            });
        });
    }

})();
