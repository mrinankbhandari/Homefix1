<?php

namespace App\Models;

use App\Core\Model;

class Provider extends Model {
    public function getAllWithDetails(array $filters = []): array {
        $sql = "SELECT p.*, u.name, u.email, u.phone, u.profile_image 
                FROM providers p 
                JOIN users u ON p.user_id = u.user_id 
                WHERE 1=1 ";
        $params = [];

        if (!empty($filters['search'])) {
            $sql .= " AND (u.name LIKE ? OR p.service_type LIKE ? OR p.area LIKE ? OR p.bio LIKE ?) ";
            $term = '%' . trim($filters['search']) . '%';
            $params[] = $term;
            $params[] = $term;
            $params[] = $term;
            $params[] = $term;
        }

        if (!empty($filters['category']) && $filters['category'] !== 'All') {
            $sql .= " AND p.service_type = ? ";
            $params[] = trim($filters['category']);
        }

        if (!empty($filters['min_rating'])) {
            $sql .= " AND p.rating >= ? ";
            $params[] = (float)$filters['min_rating'];
        }

        if (!empty($filters['area']) && $filters['area'] !== 'All') {
            $sql .= " AND p.area = ? ";
            $params[] = trim($filters['area']);
        }

        $sort = $filters['sort'] ?? 'rating_desc';
        switch ($sort) {
            case 'price_asc':
                $sql .= " ORDER BY p.hourly_rate ASC";
                break;
            case 'price_desc':
                $sql .= " ORDER BY p.hourly_rate DESC";
                break;
            case 'exp_desc':
                $sql .= " ORDER BY p.experience DESC";
                break;
            case 'rating_desc':
            default:
                $sql .= " ORDER BY p.rating DESC, p.review_count DESC";
                break;
        }

        $providers = $this->fetchAll($sql, $params);

        // Fetch services for each provider
        $serviceModel = new Service();
        foreach ($providers as &$p) {
            $p['services'] = $serviceModel->getByProviderId((int)$p['provider_id']);
        }

        return $providers;
    }

    public function getById(int $providerId): ?array {
        $sql = "SELECT p.*, u.name, u.email, u.phone, u.profile_image 
                FROM providers p 
                JOIN users u ON p.user_id = u.user_id 
                WHERE p.provider_id = ?";
        $provider = $this->fetchOne($sql, [$providerId]);

        if ($provider) {
            $serviceModel = new Service();
            $provider['services'] = $serviceModel->getByProviderId($providerId);
        }

        return $provider;
    }

    public function getByUserId(int $userId): ?array {
        $sql = "SELECT p.*, u.name, u.email, u.phone, u.profile_image 
                FROM providers p 
                JOIN users u ON p.user_id = u.user_id 
                WHERE p.user_id = ?";
        $provider = $this->fetchOne($sql, [$userId]);

        if ($provider) {
            $serviceModel = new Service();
            $provider['services'] = $serviceModel->getByProviderId((int)$provider['provider_id']);
        }

        return $provider;
    }

    public function createProvider(int $userId, array $data): int {
        return $this->insert('providers', [
            'user_id' => $userId,
            'service_type' => trim($data['service_type']),
            'city' => trim($data['city'] ?? 'Mumbai'),
            'area' => trim($data['area']),
            'experience' => (int)($data['experience'] ?? 1),
            'bio' => trim($data['bio'] ?? ''),
            'hourly_rate' => (float)($data['hourly_rate'] ?? 399.00),
            'rating' => 5.0,
            'review_count' => 0,
            'address' => trim($data['address']),
            'latitude' => (float)($data['latitude'] ?? 19.0760),
            'longitude' => (float)($data['longitude'] ?? 72.8777),
            'is_verified' => 1
        ]);
    }

    public function updateProvider(int $providerId, array $data): bool {
        $allowed = ['service_type', 'city', 'area', 'experience', 'bio', 'hourly_rate', 'address', 'latitude', 'longitude'];
        $updateData = [];
        foreach ($allowed as $f) {
            if (isset($data[$f])) {
                $updateData[$f] = $data[$f];
            }
        }
        if (empty($updateData)) {
            return false;
        }
        return $this->update('providers', $updateData, 'provider_id = ?', [$providerId]) >= 0;
    }

    public function recalculateRating(int $providerId): void {
        $res = $this->fetchOne("SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews FROM reviews WHERE provider_id = ?", [$providerId]);
        $avg = $res['avg_rating'] ? round((float)$res['avg_rating'], 1) : 5.0;
        $count = (int)($res['total_reviews'] ?? 0);

        $this->update('providers', [
            'rating' => $avg,
            'review_count' => $count
        ], 'provider_id = ?', [$providerId]);
    }

    public function deleteProvider(int $providerId): bool {
        $p = $this->getById($providerId);
        if ($p) {
            // Delete user which cascades to provider, services, bookings, reviews
            return $this->delete('users', 'user_id = ?', [$p['user_id']]) > 0;
        }
        return false;
    }

    public function getAllAreas(): array {
        return $this->fetchAll("SELECT DISTINCT area FROM providers WHERE area IS NOT NULL AND area != '' ORDER BY area ASC");
    }

    public function getProviderCount(): int {
        return (int)$this->fetchOne("SELECT COUNT(*) as count FROM providers")['count'];
    }
}
