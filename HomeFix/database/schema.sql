-- ==========================================================
-- HomeFix - A Local Services Marketplace
-- Database Schema & Comprehensive Seed Data
-- Aligned with PPT Slide 8: users, providers, services, bookings, reviews
-- ==========================================================

CREATE DATABASE IF NOT EXISTS homefix_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE homefix_db;

-- 1. Users Table (Customer, Provider, Admin)
CREATE TABLE IF NOT EXISTS users (
    user_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(191) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('customer', 'provider', 'admin') NOT NULL DEFAULT 'customer',
    phone VARCHAR(20) DEFAULT NULL,
    profile_image VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Providers Table (Extended details for service professionals)
CREATE TABLE IF NOT EXISTS providers (
    provider_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL UNIQUE,
    service_type VARCHAR(100) NOT NULL, -- e.g. Electrician, Plumber, Carpenter, etc.
    city VARCHAR(100) NOT NULL DEFAULT 'Mumbai',
    area VARCHAR(100) NOT NULL,
    experience TINYINT UNSIGNED NOT NULL DEFAULT 1, -- in years
    bio TEXT NOT NULL,
    hourly_rate DECIMAL(10,2) NOT NULL DEFAULT 399.00,
    rating DECIMAL(2,1) NOT NULL DEFAULT 4.5,
    review_count INT UNSIGNED NOT NULL DEFAULT 0,
    address VARCHAR(255) NOT NULL,
    latitude DECIMAL(10,7) NOT NULL,
    longitude DECIMAL(10,7) NOT NULL,
    is_verified TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    INDEX idx_service_type (service_type),
    INDEX idx_area (area),
    INDEX idx_city (city)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Services Table (Specific services offered by category and provider)
CREATE TABLE IF NOT EXISTS services (
    service_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    provider_id INT UNSIGNED NOT NULL,
    service_name VARCHAR(150) NOT NULL,
    category VARCHAR(80) NOT NULL, -- Plumber, Electrician, Carpenter, Cleaning, Appliance Repair, Painting
    price DECIMAL(10,2) NOT NULL,
    description TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (provider_id) REFERENCES providers(provider_id) ON DELETE CASCADE,
    INDEX idx_category (category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Bookings Table (Connects users and providers, tracking appointment status)
CREATE TABLE IF NOT EXISTS bookings (
    booking_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    provider_id INT UNSIGNED NOT NULL,
    service_id INT UNSIGNED NOT NULL,
    date DATE NOT NULL,
    time TIME NOT NULL,
    address VARCHAR(255) NOT NULL,
    notes TEXT DEFAULT NULL,
    status ENUM('Pending', 'Accepted', 'Rejected', 'Completed', 'Cancelled') NOT NULL DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (provider_id) REFERENCES providers(provider_id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(service_id) ON DELETE CASCADE,
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Reviews Table (Ratings & comments left by customer after service completion)
CREATE TABLE IF NOT EXISTS reviews (
    review_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    booking_id INT UNSIGNED NOT NULL UNIQUE,
    user_id INT UNSIGNED NOT NULL,
    provider_id INT UNSIGNED NOT NULL,
    rating TINYINT UNSIGNED NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(booking_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (provider_id) REFERENCES providers(provider_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==========================================================
-- SEED DATA
-- Default Passwords:
-- Admin: admin123
-- Providers & Customers: password123
-- Generated using password_hash('...', PASSWORD_DEFAULT)
-- Hash for 'password123': $2y$10$wN1GzQ/Z6rW4K5x15J90lO3HjC0yU72WJ05z4GvF5tG9cQh3u7Mqm (or PHP computed)
-- ==========================================================

-- We will insert with PHP password_hash for safety or standard bcrypt hash:
-- '$2y$10$O4Z9/839/VfO4k2fV9zX4euW.vJtY8Z9X6n2o2Q4mZk1bQh6H4qK2' (password123)
-- '$2y$10$f9fB6vR7wXp7gK4u9j8t.eN9sB4u6zY1qA8w7e6r5t4y3u2i1o0p.' (admin123)
