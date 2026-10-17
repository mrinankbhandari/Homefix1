<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\Booking;

class CustomerController extends Controller {
    private Booking $bookingModel;

    public function __construct() {
        $this->bookingModel = new Booking();
    }

    public function bookings(): void {
        Auth::requireLogin('customer');

        $userId = Auth::id();
        $bookings = $this->bookingModel->getByCustomerId($userId);

        $this->render('booking/customer_bookings', [
            'pageTitle' => 'My Fix Bookings — HomeFix',
            'bookings' => $bookings,
            'user' => Auth::user()
        ]);
    }
}
