<?php

namespace App\Models;

use App\Core\Model;

class User extends Model {
    public function findByEmail(string $email): ?array {
        return $this->fetchOne("SELECT * FROM users WHERE email = ?", [trim(strtolower($email))]);
    }

    public function findById(int $userId): ?array {
        return $this->fetchOne("SELECT * FROM users WHERE user_id = ?", [$userId]);
    }

    public function create(array $data): int {
        return $this->insert('users', [
            'name' => trim($data['name']),
            'email' => trim(strtolower($data['email'])),
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'role' => $data['role'] ?? 'customer',
            'phone' => trim($data['phone'] ?? ''),
            'profile_image' => $data['profile_image'] ?? null
        ]);
    }

    public function updateProfileImage(int $userId, string $imagePath): bool {
        return $this->update('users', ['profile_image' => $imagePath], 'user_id = ?', [$userId]) > 0;
    }

    public function updateProfile(int $userId, array $data): bool {
        $allowed = ['name', 'phone'];
        $updateData = [];
        foreach ($allowed as $f) {
            if (isset($data[$f])) {
                $updateData[$f] = trim($data[$f]);
            }
        }
        if (empty($updateData)) {
            return false;
        }
        return $this->update('users', $updateData, 'user_id = ?', [$userId]) >= 0;
    }

    public function getAllUsers(): array {
        return $this->fetchAll("SELECT user_id, name, email, role, phone, profile_image, created_at FROM users ORDER BY created_at DESC");
    }

    public function getCustomerCount(): int {
        return (int)$this->fetchOne("SELECT COUNT(*) as count FROM users WHERE role = 'customer'")['count'];
    }
}
