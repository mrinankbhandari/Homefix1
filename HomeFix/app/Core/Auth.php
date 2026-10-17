<?php

namespace App\Core;

class Auth {
    public static function check(): bool {
        return isset($_SESSION['user_id']);
    }

    public static function user(): ?array {
        if (!self::check()) {
            return null;
        }
        return [
            'id' => $_SESSION['user_id'],
            'name' => $_SESSION['user_name'] ?? 'User',
            'email' => $_SESSION['user_email'] ?? '',
            'role' => $_SESSION['user_role'] ?? 'customer',
            'provider_id' => $_SESSION['provider_id'] ?? null,
            'profile_image' => $_SESSION['profile_image'] ?? null,
            'phone' => $_SESSION['user_phone'] ?? null
        ];
    }

    public static function id(): ?int {
        return $_SESSION['user_id'] ?? null;
    }

    public static function role(): ?string {
        return $_SESSION['user_role'] ?? null;
    }

    public static function isCustomer(): bool {
        return self::role() === 'customer';
    }

    public static function isProvider(): bool {
        return self::role() === 'provider';
    }

    public static function isAdmin(): bool {
        return self::role() === 'admin';
    }

    public static function providerId(): ?int {
        return $_SESSION['provider_id'] ?? null;
    }

    public static function login(array $user, ?int $providerId = null): void {
        $_SESSION['user_id'] = (int)$user['user_id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_phone'] = $user['phone'] ?? '';
        $_SESSION['profile_image'] = $user['profile_image'] ?? '';
        if ($providerId) {
            $_SESSION['provider_id'] = $providerId;
        }
    }

    public static function logout(): void {
        unset(
            $_SESSION['user_id'],
            $_SESSION['user_name'],
            $_SESSION['user_email'],
            $_SESSION['user_role'],
            $_SESSION['user_phone'],
            $_SESSION['profile_image'],
            $_SESSION['provider_id']
        );
        session_regenerate_id(true);
    }

    public static function requireLogin(?string $role = null): void {
        if (!self::check()) {
            $_SESSION['flash_error'] = "Please log in to continue.";
            $target = ($role === 'provider') ? 'provider-login' : 'login';
            header("Location: index.php?page={$target}");
            exit;
        }

        if ($role !== null && self::role() !== $role && self::role() !== 'admin') {
            $_SESSION['flash_error'] = "Access denied: unauthorized account type.";
            header("Location: index.php?page=home");
            exit;
        }
    }
}
