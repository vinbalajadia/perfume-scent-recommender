<?php
require_once 'models/scent.php';

class ScentController {
    private $scentRecommender;
    
    public function __construct() {
        $this->scentRecommender = new ScentRecommender();
    }
    
    public function handleRequest() {
        $action = $_GET['action'] ?? 'home';
        
        switch ($action) {
            case 'search':
                $this->handleSearch();
                break;
            case 'results':
                $this->showResults();
                break;
            default:
                $this->showHome();
                break;
        }
    }
    
    private function showHome() {
        include 'views/home.php';
    }
    
    private function handleSearch() {
        if ($_POST && isset($_POST['scent_description'])) {
            $query = trim($_POST['scent_description']);
            
            if (!empty($query)) {
                $recommendations = $this->scentRecommender->perfumeSearch($query);
                
                $_SESSION['search_query'] = $query;
                $_SESSION['recommendations'] = $recommendations;
                
                // Redirect to results
                header('Location: index.php?action=results');
                exit;
            } else {
                $_SESSION['error'] = 'Please enter a scent description.';
                header('Location: index.php');
                exit;
            }
        }
    }
    
    private function showResults() {
        $query = $_SESSION['search_query'] ?? '';
        $recommendations = $_SESSION['recommendations'] ?? [];
        
        include 'views/results.php';
    }
    
    public function getFragranticaLink($perfume) {
        return $this->scentRecommender->getFragranticaUrl($perfume->getName(), $perfume->getBrand());
    }
}

// Initialize controller if this file is accessed directly
if (!defined('CONTROLLER_INCLUDED')) {
    define('CONTROLLER_INCLUDED', true);
    $controller = new ScentController();
}
?>