<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\User;
use App\Models\Provider;

class AuthController extends Controller {
    private User $userModel;
    private Provider $providerModel;

    public function __construct() {
        $this->userModel = new User();
        $this->providerModel = new Provider();
    }

    // -----------------------------------------------------------------
    // CUSTOMER AUTHENTICATION (User Portal)
    // -----------------------------------------------------------------

    public function userLogin(): void {
        if (Auth::check()) {
            $this->redirect('home');
        }
        $this->render('auth/user_login', [
            'pageTitle' => 'Homeowner Login — HomeFix',
            'portalType' => 'customer'
        ]);
    }

    public function handleUserLogin(): void {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $this->flash('error', 'Please enter both your email address and password.');
            $this->redirect('login');
        }

        $user = $this->userModel->findByEmail($email);
        if (!$user || !password_verify($password, $user['password'])) {
            $this->flash('error', 'Invalid email or password. Please try again.');
            $this->redirect('login');
        }

        if ($user['role'] !== 'customer' && $user['role'] !== 'admin') {
            $this->flash('error', 'This account is registered as a Service Provider. Please use the Service Provider Portal.');
            $this->redirect('provider-login');
        }

        Auth::login($user);
        $this->flash('success', "Welcome back, {$user['name']}! Ready to sort your home fixes?");
        $this->redirect('home');
    }

    public function userRegister(): void {
        if (Auth::check()) {
            $this->redirect('home');
        }
        $this->render('auth/user_register', [
            'pageTitle' => 'Homeowner Sign Up — HomeFix',
            'portalType' => 'customer'
        ]);
    }

    public function handleUserRegister(): void {
        $name = trim($_POST['name'] ?? '');
        $email = trim(strtolower($_POST['email'] ?? ''));
        $phone = trim($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (empty($name) || empty($email) || empty($password)) {
            $this->flash('error', 'Please fill in all required fields.');
            $this->redirect('register');
        }

        if ($password !== $confirmPassword) {
            $this->flash('error', 'Passwords do not match. Please re-enter.');
            $this->redirect('register');
        }

        if (strlen($password) < 6) {
            $this->flash('error', 'Password must be at least 6 characters long.');
            $this->redirect('register');
        }

        if ($this->userModel->findByEmail($email)) {
            $this->flash('error', 'An account with this email address already exists. Please log in.');
            $this->redirect('login');
        }

        $userId = $this->userModel->create([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'password' => $password,
            'role' => 'customer'
        ]);

        $user = $this->userModel->findById($userId);
        Auth::login($user);
        $this->flash('success', "Welcome to HomeFix, {$name}! Your homeowner account has been created.");
        $this->redirect('home');
    }

    // -----------------------------------------------------------------
    // SERVICE PROVIDER AUTHENTICATION (Pro Partner Portal)
    // -----------------------------------------------------------------

    public function providerLogin(): void {
        if (Auth::check()) {
            if (Auth::isProvider()) {
                $this->redirect('provider-dashboard');
            }
            $this->redirect('home');
        }
        $this->render('auth/provider_login', [
            'pageTitle' => 'Service Pro Partner Login — HomeFix',
            'portalType' => 'provider'
        ]);
    }

    public function handleProviderLogin(): void {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $this->flash('error', 'Please enter your professional registered email and password.');
            $this->redirect('provider-login');
        }

        $user = $this->userModel->findByEmail($email);
        if (!$user || !password_verify($password, $user['password'])) {
            $this->flash('error', 'Invalid provider credentials. Please check your email and password.');
            $this->redirect('provider-login');
        }

        if ($user['role'] !== 'provider') {
            $this->flash('error', 'This account is registered as a Customer. Please log in through the Homeowner Portal.');
            $this->redirect('login');
        }

        $provider = $this->providerModel->getByUserId((int)$user['user_id']);
        $providerId = $provider ? (int)$provider['provider_id'] : null;

        Auth::login($user, $providerId);
        $this->flash('success', "Welcome to your Partner Dashboard, {$user['name']}! Ready for new leads.");
        $this->redirect('provider-dashboard');
    }

    public function providerRegister(): void {
        if (Auth::check()) {
            $this->redirect('home');
        }
        $this->render('auth/provider_register', [
            'pageTitle' => 'Register as a Service Provider — HomeFix',
            'portalType' => 'provider'
        ]);
    }

    public function handleProviderRegister(): void {
        $name = trim($_POST['name'] ?? '');
        $email = trim(strtolower($_POST['email'] ?? ''));
        $phone = trim($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $serviceType = trim($_POST['service_type'] ?? '');
        $area = trim($_POST['area'] ?? '');
        $city = trim($_POST['city'] ?? 'Mumbai');
        $experience = (int)($_POST['experience'] ?? 1);
        $hourlyRate = (float)($_POST['hourly_rate'] ?? 399.00);
        $address = trim($_POST['address'] ?? '');
        $bio = trim($_POST['bio'] ?? '');
        $latitude = (float)($_POST['latitude'] ?? 19.0760);
        $longitude = (float)($_POST['longitude'] ?? 72.8777);

        if (empty($name) || empty($email) || empty($password) || empty($serviceType) || empty($area) || empty($address)) {
            $this->flash('error', 'Please fill in all required registration fields.');
            $this->redirect('provider-register');
        }

        if ($password !== $confirmPassword) {
            $this->flash('error', 'Passwords do not match.');
            $this->redirect('provider-register');
        }

        if ($this->userModel->findByEmail($email)) {
            $this->flash('error', 'An account with this email address already exists.');
            $this->redirect('provider-login');
        }

        // Create User record
        $userId = $this->userModel->create([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'password' => $password,
            'role' => 'provider'
        ]);

        // Create Provider profile
        $providerId = $this->providerModel->createProvider($userId, [
            'service_type' => $serviceType,
            'city' => $city,
            'area' => $area,
            'experience' => $experience,
            'bio' => $bio ?: "Certified {$serviceType} serving {$area} and nearby Mumbai areas.",
            'hourly_rate' => $hourlyRate,
            'address' => $address,
            'latitude' => $latitude,
            'longitude' => $longitude
        ]);

        // Add a default primary service
        $serviceModel = new \App\Models\Service();
        $serviceModel->createService([
            'provider_id' => $providerId,
            'service_name' => "Standard {$serviceType} Inspection & Repair",
            'category' => $serviceType,
            'price' => $hourlyRate,
            'description' => "Initial diagnosis, testing, and standard labor for {$serviceType} jobs in {$area}."
        ]);

        $user = $this->userModel->findById($userId);
        Auth::login($user, $providerId);

        $this->flash('success', "Congratulations {$name}! Your service listing is now live on HomeFix.");
        $this->redirect('provider-dashboard');
    }

    // -----------------------------------------------------------------
    // ADMIN AUTHENTICATION
    // -----------------------------------------------------------------

    public function adminLogin(): void {
        if (Auth::isAdmin()) {
            $this->redirect('admin-dashboard');
        }
        $this->render('auth/admin_login', [
            'pageTitle' => 'Administrator Access — HomeFix Console',
            'portalType' => 'admin'
        ]);
    }

    public function handleAdminLogin(): void {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $user = $this->userModel->findByEmail($email);
        if (!$user || !password_verify($password, $user['password']) || $user['role'] !== 'admin') {
            $this->flash('error', 'Unauthorized administrative credentials.');
            $this->redirect('admin-login');
        }

        Auth::login($user);
        $this->flash('success', 'Logged into Admin Management Console.');
        $this->redirect('admin-dashboard');
    }

    public function logout(): void {
        Auth::logout();
        $this->flash('info', 'You have been safely signed out. See you next time!');
        $this->redirect('home');
    }
}
