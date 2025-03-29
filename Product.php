<?php

class Product {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function getAllProducts() {
        $query = "SELECT * FROM products ORDER BY id";
        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getProductsByCategory($category) {
        $categories = [
            'watch' => [1, 4],
            'shirt' => [5, 8],
            'shoes' => [9, 12],
            'headphones' => [13, 16]
        ];
        
        if (!isset($categories[$category])) {
            return [];
        }
        
        $range = $categories[$category];
        $query = "SELECT * FROM products WHERE id BETWEEN ? AND ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $range[0], $range[1]);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getProductById($id) {
        $query = "SELECT * FROM products WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    public function getImagePath($product_id) {
        $category = '';
        if ($product_id <= 4) $category = 'watch';
        else if ($product_id <= 8) $category = 'shirt';
        else if ($product_id <= 12) $category = 'shoe';
        else $category = 'sp';
        
        $image_num = $product_id % 4 == 0 ? 4 : $product_id % 4;
        return "public/images/{$category}{$image_num}.jpg";
    }
} 