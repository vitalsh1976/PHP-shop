<?php

// include_once ROOT. '/models/category.php';
// include_once ROOT. '/models/product.php';

class ProductController {
    
    public function actionView ($productId) {
        
        $categories = array();
        $categories = Category::getCategoriesList();
        
        $product = Product::getProductById($productId);
        
        
        
        require_once(ROOT. '/views/product/view.php');
        return true;
        
    }
    
    
    
    
    
    
}