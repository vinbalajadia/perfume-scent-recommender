<?php
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
 
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit;
    }

    $config_file = '../config/config.php';
    $model_file = '../models/scent.php';

    if (!file_exists($config_file)) {
        http_response_code(500);
        echo json_encode(['error' => 'Configuration file not found']);
        exit;
    }

    if (!file_exists($model_file)) {
        http_response_code(500);
        echo json_encode(['error' => 'Scent model file not found']);
        exit;
    }

    require_once $config_file;
    require_once $model_file;

    class ScentAPI {
        private $scentRecommender;
        
        public function __construct() {
            try {
                // Check if ScentRecommender class exists
                if (!class_exists('ScentRecommender')) {
                    throw new Exception('ScentRecommender class not found');
                }
                $this->scentRecommender = new ScentRecommender();
            } catch (Exception $e) {
                $this->sendError('Failed to initialize scent recommender: ' . $e->getMessage(), 500);
                exit;
            }
        }
        
        public function handleRequest() {
            $method = $_SERVER['REQUEST_METHOD'];
            $endpoint = $_GET['endpoint'] ?? '';
            
            try {
                switch ($method) {
                    case 'GET':
                        $this->handleGet($endpoint);
                        break;
                    case 'POST':
                        $this->handlePost($endpoint);
                        break;
                    default:
                        $this->sendError('Method not allowed', 405);
                }
            } catch (Exception $e) {
                error_log('ScentAPI Error: ' . $e->getMessage());
                $this->sendError('Internal server error', 500);
            }
        }
        
        private function handleGet($endpoint) {
            switch ($endpoint) {
                case 'search':
                    $this->handleSearch();
                    break;
                    
                case 'popular':
                    $this->handlePopular();
                    break;
                    
                default:
                    $this->sendError('Endpoint not found', 404);
            }
        }
        
        private function handleSearch() {
            $query = trim($_GET['q'] ?? '');
            
            if (empty($query)) {
                $this->sendError('Query parameter "q" is required', 400);
                return;
            }
            
            if (strlen($query) < 2) {
                $this->sendError('Query must be at least 2 characters long', 400);
                return;
            }
            
            try {
                $recommendations = $this->scentRecommender->perfumeSearch($query);
                
                if (!is_array($recommendations)) {
                    $recommendations = [];
                }
                
                $this->sendSuccess([
                    'query' => $query,
                    'count' => count($recommendations),
                    'results' => array_map(function($perfume) {
                        return $this->formatPerfumeResponse($perfume);
                    }, $recommendations)
                ]);
            } catch (Exception $e) {
                error_log('Search Error: ' . $e->getMessage());
                $this->sendError('Search failed', 500);
            }
        }
        
        private function handlePopular() {
            $this->sendSuccess([
                'categories' => [
                    'citrus' => ['lemon', 'bergamot', 'orange', 'grapefruit', 'lime', 'mandarin'],
                    'floral' => ['rose', 'jasmine', 'lily', 'peony', 'lavender', 'gardenia'],
                    'woody' => ['cedar', 'sandalwood', 'oak', 'pine', 'birch', 'rosewood'],
                    'spicy' => ['pepper', 'cinnamon', 'cardamom', 'clove', 'nutmeg', 'ginger'],
                    'fresh' => ['mint', 'eucalyptus', 'marine', 'ozone', 'green leaves', 'water lily'],
                    'sweet' => ['vanilla', 'caramel', 'honey', 'chocolate', 'sugar', 'marshmallow'],
                    'musky' => ['musk', 'amber', 'patchouli', 'oud', 'ambergris', 'civet']
                ],
                'message' => 'Popular scent categories and notes'
            ]);
        }
        
        private function handlePost($endpoint) {
            $json_input = file_get_contents('php://input');
            $input = json_decode($json_input, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->sendError('Invalid JSON input: ' . json_last_error_msg(), 400);
                return;
            }
            
            switch ($endpoint) {
                case 'recommend':
                    $this->handleRecommend($input);
                    break;
                    
                default:
                    $this->sendError('Endpoint not found', 404);
            }
        }
        
        private function handleRecommend($input) {
            $description = trim($input['description'] ?? '');
            $preferences = $input['preferences'] ?? [];
            
            if (empty($description)) {
                $this->sendError('Description is required', 400);
                return;
            }
            
            if (strlen($description) < 5) {
                $this->sendError('Description must be at least 5 characters long', 400);
                return;
            }
            
            try {
                $recommendations = $this->scentRecommender->perfumeSearch($description);
                
                if (!is_array($recommendations)) {
                    $recommendations = [];
                }

                if (!empty($preferences) && is_array($preferences)) {
                    $recommendations = $this->filterByPreferences($recommendations, $preferences);
                }
                
                $this->sendSuccess([
                    'description' => $description,
                    'preferences' => $preferences,
                    'count' => count($recommendations),
                    'recommendations' => array_map(function($perfume) {
                        return $this->formatPerfumeResponse($perfume);
                    }, $recommendations)
                ]);
            } catch (Exception $e) {
                error_log('Recommendation Error: ' . $e->getMessage());
                $this->sendError('Recommendation failed', 500);
            }
        }
        
        private function filterByPreferences($recommendations, $preferences) {
            return array_filter($recommendations, function($perfume) use ($preferences) {

                if (!is_object($perfume)) {
                    return false;
                }
                
                if (isset($preferences['max_price']) && is_numeric($preferences['max_price'])) {
                    if (method_exists($perfume, 'getPrice')) {
                        $price_string = $perfume->getPrice();
                        $price = (int) filter_var($price_string, FILTER_SANITIZE_NUMBER_INT);
                        if ($price > $preferences['max_price']) {
                            return false;
                        }
                    }
                }
                
                if (isset($preferences['min_price']) && is_numeric($preferences['min_price'])) {
                    if (method_exists($perfume, 'getPrice')) {
                        $price_string = $perfume->getPrice();
                        $price = (int) filter_var($price_string, FILTER_SANITIZE_NUMBER_INT);
                        if ($price < $preferences['min_price']) {
                            return false;
                        }
                    }
                }
                
                if (isset($preferences['gender']) && !empty($preferences['gender'])) {
                    if (method_exists($perfume, 'getTypes')) {
                        $types = $perfume->getTypes();
                        $gender = strtolower(trim($preferences['gender']));
                        
                        if (!is_array($types)) {
                            return true; 
                        }
                        
                        $types = array_map('strtolower', $types);
                        
                        switch ($gender) {
                            case 'masculine':
                            case 'male':
                            case 'men':
                                if (!in_array('masculine', $types) && !in_array('male', $types) && !in_array('men', $types)) {
                                    return false;
                                }
                                break;
                            case 'feminine':
                            case 'female':
                            case 'women':
                                if (!in_array('feminine', $types) && !in_array('female', $types) && !in_array('women', $types)) {
                                    return false;
                                }
                                break;
                            case 'unisex':
                                if (!in_array('unisex', $types)) {
                                    return false;
                                }
                                break;
                        }
                    }
                }
                
                // Filter by brand
                if (isset($preferences['brand']) && !empty($preferences['brand'])) {
                    if (method_exists($perfume, 'getBrand')) {
                        $brand = strtolower($perfume->getBrand());
                        $preferred_brand = strtolower(trim($preferences['brand']));
                        if (strpos($brand, $preferred_brand) === false) {
                            return false;
                        }
                    }
                }
                
                return true;
            });
        }
        
        private function formatPerfumeResponse($perfume) {
            if (!is_object($perfume)) {
                return null;
            }
            
            try {
                if (method_exists($perfume, 'toArray')) {
                    return $perfume->toArray();
                }
                
                $response = [];
                
                if (method_exists($perfume, 'getName')) {
                    $response['name'] = $perfume->getName();
                }
                if (method_exists($perfume, 'getBrand')) {
                    $response['brand'] = $perfume->getBrand();
                }
                if (method_exists($perfume, 'getPrice')) {
                    $response['price'] = $perfume->getPrice();
                }
                if (method_exists($perfume, 'getTypes')) {
                    $response['types'] = $perfume->getTypes();
                }
                if (method_exists($perfume, 'getDescription')) {
                    $response['description'] = $perfume->getDescription();
                }
                if (method_exists($perfume, 'getNotes')) {
                    $response['notes'] = $perfume->getNotes();
                }
                
                return $response;
            } catch (Exception $e) {
                error_log('Perfume formatting error: ' . $e->getMessage());
                return ['error' => 'Failed to format perfume data'];
            }
        }
        
        private function sendSuccess($data) {
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'data' => $data,
                'timestamp' => date('c')
            ], JSON_PRETTY_PRINT);
        }
        
        private function sendError($message, $code = 400) {
            http_response_code($code);
            echo json_encode([
                'success' => false,
                'error' => [
                    'message' => $message,
                    'code' => $code
                ],
                'timestamp' => date('c')
            ], JSON_PRETTY_PRINT);
        }
    }

    try {
        $api = new ScentAPI();
        $api->handleRequest();
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => [
                'message' => 'Failed to initialize API',
                'code' => 500
            ],
            'timestamp' => date('c')
        ]);
    }
?>