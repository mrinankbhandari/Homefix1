<?php

namespace App\Models;

use App\Core\Model;

class Service extends Model {
    public function getByProviderId(int $providerId): array {
        return $this->fetchAll("SELECT * FROM services WHERE provider_id = ? ORDER BY price ASC", [$providerId]);
    }

    public function getById(int $serviceId): ?array {
        return $this->fetchOne("SELECT * FROM services WHERE service_id = ?", [$serviceId]);
    }

    public function createService(array $data): int {
        return $this->insert('services', [
            'provider_id' => (int)$data['provider_id'],
            'service_name' => trim($data['service_name']),
            'category' => trim($data['category']),
            'price' => (float)$data['price'],
            'description' => trim($data['description'] ?? '')
        ]);
    }

    public function updateService(int $serviceId, array $data): bool {
        $allowed = ['service_name', 'category', 'price', 'description'];
        $updateData = [];
        foreach ($allowed as $f) {
            if (isset($data[$f])) {
                $updateData[$f] = $data[$f];
            }
        }
        if (empty($updateData)) {
            return false;
        }
        return $this->update('services', $updateData, 'service_id = ?', [$serviceId]) >= 0;
    }

    public function deleteService(int $serviceId): bool {
        return $this->delete('services', 'service_id = ?', [$serviceId]) > 0;
    }

    public function getAllCategories(): array {
        return [
            'Electrician' => ['icon' => '⚡', 'label' => 'Electrician'],
            'Plumber' => ['icon' => '🔧', 'label' => 'Plumber'],
            'Carpenter' => ['icon' => '🪚', 'label' => 'Carpenter'],
            'Cleaning' => ['icon' => '🧹', 'label' => 'Cleaning'],
            'Appliance Repair' => ['icon' => '❄️', 'label' => 'Appliance Repair'],
            'Painting' => ['icon' => '🎨', 'label' => 'Painting']
        ];
    }
}
