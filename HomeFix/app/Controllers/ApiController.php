<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\Provider;
use App\Models\Booking;
use App\Models\Review;

class ApiController extends Controller {
    private Provider $providerModel;
    private Booking $bookingModel;
    private Review $reviewModel;

    public function __construct() {
        $this->providerModel = new Provider();
        $this->bookingModel = new Booking();
        $this->reviewModel = new Review();
    }

    /**
     * AJAX Live Search & Category Filtering
     * Returns matching HTML cards and JSON provider data for MapTiler update
     */
    public function search(): void {
        $filters = [
            'search' => $_GET['search'] ?? '',
            'category' => $_GET['category'] ?? 'All',
            'min_rating' => !empty($_GET['min_rating']) ? (float)$_GET['min_rating'] : null,
            'area' => $_GET['area'] ?? 'All',
            'sort' => $_GET['sort'] ?? 'rating_desc'
        ];

        $providers = $this->providerModel->getAllWithDetails($filters);

        // Build HTML for provider cards
        ob_start();
        if (empty($providers)) {
            ?>
            <div class="empty-state">
                <div class="empty-icon">🔍</div>
                <h3>No Service Fixers Found</h3>
                <p>We couldn't find any professionals matching "<strong><?= htmlspecialchars($filters['search'] ?: $filters['category']) ?></strong>".</p>
                <button class="btn btn-secondary" onclick="window.HomeFixSearch.resetFilters()">Clear Filters & View All</button>
            </div>
            <?php
        } else {
            foreach ($providers as $p) {
                require dirname(__DIR__) . '/Views/home/_provider_card.php';
            }
        }
        $html = ob_get_clean();

        // Extract simplified map points
        $mapPoints = array_map(function($p) {
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
        }, $providers);

        $this->json([
            'success' => true,
            'count' => count($providers),
            'html' => $html,
            'providers' => $mapPoints
        ]);
    }

    /**
     * AJAX Booking Status Update (Accept, Reject, Complete, Cancel)
     */
    public function updateBookingStatus(): void {
        $bookingId = (int)($_POST['booking_id'] ?? 0);
        $newStatus = trim($_POST['status'] ?? '');

        if ($bookingId <= 0 || empty($newStatus)) {
            $this->json(['success' => false, 'message' => 'Invalid booking parameters.'], 400);
        }

        $booking = $this->bookingModel->getById($bookingId);
        if (!$booking) {
            $this->json(['success' => false, 'message' => 'Booking not found.'], 404);
        }

        $currentUserId = Auth::id();
        $isCustomer = ($currentUserId === (int)$booking['user_id']);
        $provider = $this->providerModel->getByUserId($currentUserId);
        $isProvider = ($provider && (int)$provider['provider_id'] === (int)$booking['provider_id']);
        $isAdmin = Auth::isAdmin();

        if (!$isCustomer && !$isProvider && !$isAdmin) {
            $this->json(['success' => false, 'message' => 'Unauthorized action on this booking.'], 403);
        }

        // Validate permitted transitions
        if ($isCustomer && $newStatus !== 'Cancelled') {
            $this->json(['success' => false, 'message' => 'Customers can only cancel bookings.'], 400);
        }

        $updated = $this->bookingModel->updateStatus($bookingId, $newStatus);

        if ($updated) {
            $this->json([
                'success' => true,
                'booking_id' => $bookingId,
                'status' => $newStatus,
                'message' => "Booking #{$bookingId} status updated to '{$newStatus}'."
            ]);
        } else {
            $this->json(['success' => false, 'message' => 'Failed to update booking status.'], 500);
        }
    }

    /**
     * AJAX Review Submission
     */
    public function addReview(): void {
        if (!Auth::check()) {
            $this->json(['success' => false, 'message' => 'Please log in to submit a review.'], 401);
        }

        $bookingId = (int)($_POST['booking_id'] ?? 0);
        $rating = (int)($_POST['rating'] ?? 5);
        $comment = trim($_POST['comment'] ?? '');

        if ($bookingId <= 0 || empty($comment)) {
            $this->json(['success' => false, 'message' => 'Please provide a star rating and written comment.'], 400);
        }

        $booking = $this->bookingModel->getById($bookingId);
        if (!$booking) {
            $this->json(['success' => false, 'message' => 'Booking not found.'], 404);
        }

        if ((int)$booking['user_id'] !== Auth::id()) {
            $this->json(['success' => false, 'message' => 'You can only review services you booked.'], 403);
        }

        if ($booking['status'] !== 'Completed') {
            $this->json(['success' => false, 'message' => 'Reviews can only be submitted for completed services.'], 400);
        }

        if ($this->reviewModel->hasUserReviewed($bookingId)) {
            $this->json(['success' => false, 'message' => 'You have already submitted a review for this booking.'], 400);
        }

        $reviewId = $this->reviewModel->createReview(
            $bookingId,
            Auth::id(),
            (int)$booking['provider_id'],
            $rating,
            $comment
        );

        $this->json([
            'success' => true,
            'review_id' => $reviewId,
            'message' => 'Thank you! Your verified review and rating have been published.'
        ]);
    }

    /**
     * Map Data Endpoint
     */
    public function mapProviders(): void {
        $providers = $this->providerModel->getAllWithDetails();
        $data = array_map(function($p) {
            return [
                'id' => (int)$p['provider_id'],
                'name' => $p['name'],
                'service_type' => $p['service_type'],
                'area' => $p['area'],
                'rating' => (float)$p['rating'],
                'reviews' => (int)$p['review_count'],
                'hourly_rate' => (float)$p['hourly_rate'],
                'lat' => (float)$p['latitude'],
                'lng' => (float)$p['longitude'],
                'address' => $p['address']
            ];
        }, $providers);

        $this->json(['success' => true, 'providers' => $data]);
    }
}
