<?php

namespace App\Models;

use App\Core\Model;

class Booking extends Model {
    public function createBooking(array $data): int {
        return $this->insert('bookings', [
            'user_id' => (int)$data['user_id'],
            'provider_id' => (int)$data['provider_id'],
            'service_id' => (int)$data['service_id'],
            'date' => $data['date'],
            'time' => $data['time'],
            'address' => trim($data['address']),
            'notes' => trim($data['notes'] ?? ''),
            'status' => 'Pending'
        ]);
    }

    public function getById(int $bookingId): ?array {
        $sql = "SELECT b.*, 
                       u.name as customer_name, u.email as customer_email, u.phone as customer_phone,
                       p.service_type, p.area, p.city, p.address as provider_address,
                       pu.name as provider_name, pu.email as provider_email, pu.phone as provider_phone,
                       s.service_name, s.category, s.price
                FROM bookings b
                JOIN users u ON b.user_id = u.user_id
                JOIN providers p ON b.provider_id = p.provider_id
                JOIN users pu ON p.user_id = pu.user_id
                JOIN services s ON b.service_id = s.service_id
                WHERE b.booking_id = ?";
        return $this->fetchOne($sql, [$bookingId]);
    }

    public function getByCustomerId(int $userId): array {
        $sql = "SELECT b.*, 
                       p.provider_id, p.service_type, p.area, p.city, p.rating,
                       pu.name as provider_name, pu.phone as provider_phone, pu.profile_image as provider_image,
                       s.service_name, s.price,
                       r.rating as review_rating, r.comment as review_comment
                FROM bookings b
                JOIN providers p ON b.provider_id = p.provider_id
                JOIN users pu ON p.user_id = pu.user_id
                JOIN services s ON b.service_id = s.service_id
                LEFT JOIN reviews r ON b.booking_id = r.booking_id
                WHERE b.user_id = ?
                ORDER BY b.created_at DESC";
        return $this->fetchAll($sql, [$userId]);
    }

    public function getByProviderId(int $providerId): array {
        $sql = "SELECT b.*, 
                       u.name as customer_name, u.phone as customer_phone, u.email as customer_email, u.profile_image as customer_image,
                       s.service_name, s.price,
                       r.rating as review_rating, r.comment as review_comment
                FROM bookings b
                JOIN users u ON b.user_id = u.user_id
                JOIN services s ON b.service_id = s.service_id
                LEFT JOIN reviews r ON b.booking_id = r.booking_id
                WHERE b.provider_id = ?
                ORDER BY b.date DESC, b.time DESC";
        return $this->fetchAll($sql, [$providerId]);
    }

    public function updateStatus(int $bookingId, string $status): bool {
        $valid = ['Pending', 'Accepted', 'Rejected', 'Completed', 'Cancelled'];
        if (!in_array($status, $valid, true)) {
            return false;
        }
        return $this->update('bookings', ['status' => $status], 'booking_id = ?', [$bookingId]) > 0;
    }

    public function getAllWithDetails(int $limit = 50): array {
        $sql = "SELECT b.*, 
                       u.name as customer_name, u.phone as customer_phone,
                       pu.name as provider_name, p.service_type,
                       s.service_name, s.price
                FROM bookings b
                JOIN users u ON b.user_id = u.user_id
                JOIN providers p ON b.provider_id = p.provider_id
                JOIN users pu ON p.user_id = pu.user_id
                JOIN services s ON b.service_id = s.service_id
                ORDER BY b.created_at DESC
                LIMIT {$limit}";
        return $this->fetchAll($sql);
    }

    public function getStats(): array {
        $total = (int)$this->fetchOne("SELECT COUNT(*) as c FROM bookings")['c'];
        $pending = (int)$this->fetchOne("SELECT COUNT(*) as c FROM bookings WHERE status = 'Pending'")['c'];
        $accepted = (int)$this->fetchOne("SELECT COUNT(*) as c FROM bookings WHERE status = 'Accepted'")['c'];
        $completed = (int)$this->fetchOne("SELECT COUNT(*) as c FROM bookings WHERE status = 'Completed'")['c'];
        $cancelled = (int)$this->fetchOne("SELECT COUNT(*) as c FROM bookings WHERE status IN ('Cancelled', 'Rejected')")['c'];

        $revenue = (float)($this->fetchOne("SELECT SUM(s.price) as rev FROM bookings b JOIN services s ON b.service_id = s.service_id WHERE b.status = 'Completed'")['rev'] ?? 0);

        return [
            'total' => $total,
            'pending' => $pending,
            'accepted' => $accepted,
            'completed' => $completed,
            'cancelled' => $cancelled,
            'revenue' => $revenue
        ];
    }
}
