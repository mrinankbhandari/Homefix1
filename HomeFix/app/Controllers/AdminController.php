<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\User;
use App\Models\Provider;
use App\Models\Booking;

class AdminController extends Controller {
    private User $userModel;
    private Provider $providerModel;
    private Booking $bookingModel;

    public function __construct() {
        $this->userModel = new User();
        $this->providerModel = new Provider();
        $this->bookingModel = new Booking();
    }

    public function dashboard(): void {
        Auth::requireLogin('admin');

        $users = $this->userModel->getAllUsers();
        $providers = $this->providerModel->getAllWithDetails();
        $bookings = $this->bookingModel->getAllWithDetails(50);
        $stats = $this->bookingModel->getStats();

        $customerCount = 0;
        $providerCount = 0;
        foreach ($users as $u) {
            if ($u['role'] === 'customer') $customerCount++;
            if ($u['role'] === 'provider') $providerCount++;
        }

        $this->render('admin/dashboard', [
            'pageTitle' => 'Admin Management Console — HomeFix',
            'users' => $users,
            'providers' => $providers,
            'bookings' => $bookings,
            'stats' => $stats,
            'customerCount' => $customerCount,
            'providerCount' => $providerCount
        ]);
    }

    public function deleteProvider(): void {
        Auth::requireLogin('admin');

        $providerId = (int)($_POST['provider_id'] ?? 0);
        if ($providerId > 0) {
            $this->providerModel->deleteProvider($providerId);
            $this->flash('success', "Listing #{$providerId} has been successfully removed from HomeFix marketplace.");
        } else {
            $this->flash('error', 'Invalid provider ID specified.');
        }

        $this->redirect('admin-dashboard');
    }
}
