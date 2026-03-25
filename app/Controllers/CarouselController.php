<?php

namespace App\Controllers;

use App\Models\CarouselModel;

class CarouselController extends BaseController
{
    public function getSlides()
    {
        $carouselModel = new CarouselModel();
        $slides = $carouselModel->getSlides();

        // Configura o cabeçalho para JSON
        header('Content-Type: application/json');
        
        // Retorna os dados
        echo json_encode($slides);
        exit;
    }
}