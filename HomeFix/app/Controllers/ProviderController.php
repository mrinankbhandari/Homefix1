<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\Provider;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Review;
use App\Models\User;

class ProviderController extends Controller {
    private Provider $providerModel;
    private Booking $bookingModel;
    private Service $serviceModel;
    private Review $reviewModel;
    private User $userModel;

    public function __construct() {
        $this->providerModel = new Provider();
        $this->bookingModel = new Booking();
        $this->serviceModel = new Service();
        $this->reviewModel = new Review();
        $this->userModel = new User();
    }

    public function dashboard(): void {
        Auth::requireLogin('provider');

        $userId = Auth::id();
        $provider = $this->providerModel->getByUserId($userId);

        if (!$provider) {
            $this->flash('error', 'Provider profile not found for this account.');
            $this->redirect('home');
        }

        $providerId = (int)$provider['provider_id'];
        $bookings = $this->bookingModel->getByProviderId($providerId);
        $reviews = $this->reviewModel->getByProviderId($providerId);
        $services = $this->serviceModel->getByProviderId($providerId);

        // Stats calculation
        $pendingCount = 0;
        $acceptedCount = 0;
        $completedCount = 0;
        $totalEarnings = 0.0;

        foreach ($bookings as $b) {
            if ($b['status'] === 'Pending') $pendingCount++;
            if ($b['status'] === 'Accepted') $acceptedCount++;
            if ($b['status'] === 'Completed') {
                $completedCount++;
                $totalEarnings += (float)$b['price'];
            }
        }

        $this->render('provider/dashboard', [
            'pageTitle' => 'Partner Pro Dashboard — HomeFix',
            'provider' => $provider,
            'bookings' => $bookings,
            'reviews' => $reviews,
            'services' => $services,
            'stats' => [
                'pending' => $pendingCount,
                'accepted' => $acceptedCount,
                'completed' => $completedCount,
                'earnings' => $totalEarnings
            ]
        ]);
    }

    public function profile(): void {
        Auth::requireLogin('provider');

        $userId = Auth::id();
        $provider = $this->providerModel->getByUserId($userId);

        if (!$provider) {
            $this->flash('error', 'Provider profile not found.');
            $this->redirect('home');
        }

        $this->render('provider/profile', [
            'pageTitle' => 'Edit Pro Profile & Avatar Gap — HomeFix',
            'provider' => $provider
        ]);
    }

    public function updateProfile(): void {
        Auth::requireLogin('provider');

        $userId = Auth::id();
        $provider = $this->providerModel->getByUserId($userId);
        if (!$provider) {
            $this->redirect('home');
        }

        $providerId = (int)$provider['provider_id'];

        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $area = trim($_POST['area'] ?? '');
        $experience = (int)($_POST['experience'] ?? 1);
        $hourlyRate = (float)($_POST['hourly_rate'] ?? 399.00);
        $address = trim($_POST['address'] ?? '');
        $bio = trim($_POST['bio'] ?? '');

        // Update User info
        $this->userModel->updateProfile($userId, [
            'name' => $name,
            'phone' => $phone
        ]);

        $latitude = !empty($_POST['latitude']) ? (float)$_POST['latitude'] : (float)($provider['latitude'] ?? 19.0760);
        $longitude = !empty($_POST['longitude']) ? (float)$_POST['longitude'] : (float)($provider['longitude'] ?? 72.8777);

        // Update Provider info
        $this->providerModel->updateProvider($providerId, [
            'area' => $area,
            'experience' => $experience,
            'hourly_rate' => $hourlyRate,
            'address' => $address,
            'bio' => $bio,
            'latitude' => $latitude,
            'longitude' => $longitude
        ]);

        // Handle profile image upload if provided (or user can drop manually)
        if (!empty($_FILES['profile_image']['name']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            $allowedMime = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
            $fileType = mime_content_type($_FILES['profile_image']['tmp_name']);

            if (in_array($fileType, $allowedMime, true)) {
                $ext = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
                $newFilename = 'provider_' . $providerId . '_' . time() . '.' . $ext;
                $targetPath = UPLOAD_DIR . $newFilename;

                if (!is_dir(UPLOAD_DIR)) {
                    mkdir(UPLOAD_DIR, 0777, true);
                }

                if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetPath)) {
                    $this->userModel->updateProfileImage($userId, $newFilename);
                    $_SESSION['profile_image'] = $newFilename;
                }
            }
        }

        $this->flash('success', 'Your Pro profile and photo slot have been updated!');
        $this->redirect('provider-dashboard');
    }

    public function addService(): void {
        Auth::requireLogin('provider');

        $userId = Auth::id();
        $provider = $this->providerModel->getByUserId($userId);
        if (!$provider) {
            $this->redirect('home');
        }

        $serviceName = trim($_POST['service_name'] ?? '');
        $category = trim($_POST['category'] ?? $provider['service_type']);
        $price = (float)($_POST['price'] ?? 0);
        $description = trim($_POST['description'] ?? '');

        if (!empty($serviceName) && $price > 0) {
            $this->serviceModel->createService([
                'provider_id' => $provider['provider_id'],
                'service_name' => $serviceName,
                'category' => $category,
                'price' => $price,
                'description' => $description
            ]);
            $this->flash('success', "Service '{$serviceName}' added to your catalog.");
        } else {
            $this->flash('error', 'Please enter a valid service title and price.');
        }

        $this->redirect('provider-dashboard');
    }
}
