<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Provider;
use App\Models\Service;
use App\Models\Review;

class HomeController extends Controller {
    public function index(): void {
        $providerModel = new Provider();
        $serviceModel = new Service();
        $reviewModel = new Review();

        $categories = $serviceModel->getAllCategories();
        $providers = $providerModel->getAllWithDetails();
        $areas = $providerModel->getAllAreas();
        $recentReviews = $reviewModel->getRecentReviews(4);

        $this->render('home/index', [
            'pageTitle' => 'HomeFix — Local Services Marketplace',
            'categories' => $categories,
            'providers' => $providers,
            'areas' => $areas,
            'recentReviews' => $recentReviews,
            'totalPros' => count($providers),
            'mapApiKey' => MAPTILER_API_KEY
        ]);
    }
}
