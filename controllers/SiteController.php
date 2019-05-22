<?php 
    

//    include_once ROOT. '/models/category.php';
//    include_once ROOT. '/models/product.php';

    class SiteController {

    public function actionIndex () {
        
        $categories = array();
        $categories = Category::getCategoriesList();
        
        $latestProducts = array();
        $latestProducts = Product::getLatestProducts(6);

        //Список товаров для слайда
        $sliderProducts = Product::getRecommendedProducts();
        
        require_once (ROOT . '/views/site/index.php');
        
        return true;
    }

    public function actionContact () {

        $userEmail = '';
        $userText = '';
        $result = false;

        if (isset($_POST['submit'])) {
            $userEmail = $_POST['userEmail'];
            $userText = $_POST['userText'];

            $errors = false;

            if (!User::checkEmail($userEmail)) {
                $errors = "Неправильный Email";
            }
            if ($errors == false) {
                $adminEmail = 'vitalsh189@gmail.com';
                $subject = 'Тема письма';
                $message = "Текст: {$userText} . от {$userEmail}";
                $result = mail($adminEmail, $subject, $message);
                $result = true;
            }
        }
            require_once ROOT. '/views/site/contact.php';
            return true;
        
    }
}
