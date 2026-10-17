<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\Provider;
use App\Models\Service;
use App\Models\Booking;

class BookingController extends Controller {
    private Provider $providerModel;
    private Service $serviceModel;
    private Booking $bookingModel;

    public function __construct() {
        $this->providerModel = new Provider();
        $this->serviceModel = new Service();
        $this->bookingModel = new Booking();
    }

    public function showBookingForm(): void {
        $providerId = (int)($_GET['provider_id'] ?? 0);
        $serviceId = (int)($_GET['service_id'] ?? 0);

        if ($providerId <= 0) {
            $this->flash('error', 'Please select a valid service professional to book.');
            $this->redirect('home');
        }

        $provider = $this->providerModel->getById($providerId);
        if (!$provider) {
            $this->flash('error', 'Service professional not found.');
            $this->redirect('home');
        }

        $services = $provider['services'] ?? [];

        // If no services found for provider, provide default
        if (empty($services)) {
            $services[] = [
                'service_id' => 0,
                'service_name' => "Standard {$provider['service_type']} Visit",
                'price' => $provider['hourly_rate']
            ];
        }

        $this->render('booking/book', [
            'pageTitle' => "Book {$provider['name']} — {$provider['service_type']}",
            'provider' => $provider,
            'services' => $services,
            'selectedServiceId' => $serviceId,
            'isLoggedIn' => Auth::check(),
            'user' => Auth::user()
        ]);
    }

    public function createBooking(): void {
        // Require customer login
        Auth::requireLogin('customer');

        $providerId = (int)($_POST['provider_id'] ?? 0);
        $serviceId = (int)($_POST['service_id'] ?? 0);
        $date = trim($_POST['date'] ?? '');
        $time = trim($_POST['time'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $notes = trim($_POST['notes'] ?? '');

        if ($providerId <= 0 || empty($date) || empty($time) || empty($address)) {
            $this->flash('error', 'Please complete all required fields (Date, Time, Address).');
            $this->redirect('book', ['provider_id' => $providerId, 'service_id' => $serviceId]);
        }

        // Validate date is not in past
        if (strtotime($date) < strtotime(date('Y-m-d'))) {
            $this->flash('error', 'Booking date cannot be in the past.');
            $this->redirect('book', ['provider_id' => $providerId, 'service_id' => $serviceId]);
        }

        $provider = $this->providerModel->getById($providerId);
        if (!$provider) {
            $this->flash('error', 'Selected provider is no longer available.');
            $this->redirect('home');
        }

        // Fallback for service_id if zero
        if ($serviceId <= 0 && !empty($provider['services'])) {
            $serviceId = (int)$provider['services'][0]['service_id'];
        }

        $bookingId = $this->bookingModel->createBooking([
            'user_id' => Auth::id(),
            'provider_id' => $providerId,
            'service_id' => $serviceId,
            'date' => $date,
            'time' => $time,
            'address' => $address,
            'notes' => $notes
        ]);

        $this->flash('success', "Booking request #{$bookingId} submitted successfully! {$provider['name']} will review and confirm shortly.");
        $this->redirect('my-bookings');
    }
}
