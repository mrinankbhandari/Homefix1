<?php
/**
 * HomeFix - A Local Services Marketplace
 * Web Design & Development Mini Project
 * Front Controller & Routing Dispatcher
 */

require_once __DIR__ . '/config/config.php';

// PSR-4 Style Autoloader for App\ namespace
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/app/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

use App\Core\Router;

$router = new Router();

// Public & Home Routes
$router->get('home', 'HomeController@index');

// Authentication Routes (Distinct user & provider logins)
$router->get('login', 'AuthController@userLogin');
$router->post('login', 'AuthController@handleUserLogin');
$router->get('register', 'AuthController@userRegister');
$router->post('register', 'AuthController@handleUserRegister');

$router->get('provider-login', 'AuthController@providerLogin');
$router->post('provider-login', 'AuthController@handleProviderLogin');
$router->get('provider-register', 'AuthController@providerRegister');
$router->post('provider-register', 'AuthController@handleProviderRegister');

$router->get('admin-login', 'AuthController@adminLogin');
$router->post('admin-login', 'AuthController@handleAdminLogin');
$router->get('logout', 'AuthController@logout');

// Booking Flow (Matches PPT Slide 6 & 9)
$router->get('book', 'BookingController@showBookingForm');
$router->post('book', 'BookingController@createBooking');
$router->get('my-bookings', 'CustomerController@bookings');

// Provider Management Dashboard
$router->get('provider-dashboard', 'ProviderController@dashboard');
$router->get('provider-profile', 'ProviderController@profile');
$router->post('provider-profile', 'ProviderController@updateProfile');
$router->post('provider-add-service', 'ProviderController@addService');

// Admin Console
$router->get('admin-dashboard', 'AdminController@dashboard');
$router->post('admin-delete-provider', 'AdminController@deleteProvider');

// AJAX API Endpoints
$router->get('api-search', 'ApiController@search');
$router->post('api-update-booking', 'ApiController@updateBookingStatus');
$router->post('api-add-review', 'ApiController@addReview');
$router->get('api-map-providers', 'ApiController@mapProviders');

// XML, DTD & XSL Endpoints
$router->get('xml-catalog', 'XmlController@view');
$router->get('xml-raw', 'XmlController@raw');
$router->get('xml-validate', 'XmlController@validateDtd');

// Dispatch request
$router->dispatch();
