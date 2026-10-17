<?php

namespace App\Models;

use App\Core\Model;

class Review extends Model {
    public function createReview(int $bookingId, int $userId, int $providerId, int $rating, string $comment): int {
        $rating = max(1, min(5, $rating));
        $reviewId = $this->insert('reviews', [
            'booking_id' => $bookingId,
            'user_id' => $userId,
            'provider_id' => $providerId,
            'rating' => $rating,
            'comment' => trim($comment)
        ]);

        if ($reviewId) {
            $providerModel = new Provider();
            $providerModel->recalculateRating($providerId);
        }

        return $reviewId;
    }

    public function getByProviderId(int $providerId): array {
        $sql = "SELECT r.*, u.name as reviewer_name, u.profile_image as reviewer_image 
                FROM reviews r 
                JOIN users u ON r.user_id = u.user_id 
                WHERE r.provider_id = ? 
                ORDER BY r.created_at DESC";
        return $this->fetchAll($sql, [$providerId]);
    }

    public function hasUserReviewed(int $bookingId): bool {
        $res = $this->fetchOne("SELECT review_id FROM reviews WHERE booking_id = ?", [$bookingId]);
        return $res !== null;
    }

    public function getRecentReviews(int $limit = 6): array {
        $sql = "SELECT r.*, u.name as reviewer_name, 
                       p.service_type, p.area,
                       pu.name as provider_name 
                FROM reviews r 
                JOIN users u ON r.user_id = u.user_id 
                JOIN providers p ON r.provider_id = p.provider_id 
                JOIN users pu ON p.user_id = pu.user_id 
                ORDER BY r.created_at DESC 
                LIMIT {$limit}";
        return $this->fetchAll($sql);
    }
}
