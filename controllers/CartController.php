<?php

class CartController
{

    public function actionAdd($id)
    {
        // Добавляем товар в корзину
        Cart::addProduct($id);

        // Возвращаем пользователя на страницу
        $referrer = $_SERVER['HTTP_REFERER'];
        header("Location: $referrer");
    }

    public function actionAddAjax($id)
    {
        // Добавляем товар в корзину
        echo Cart::addProduct($id);
        return true;
    }

    public function actionIndex()
    {
        $categories = array();
        $categories = Category::getCategoriesList();

        $productsInCart = false;

        // Получим данные из корзины
        $productsInCart = Cart::getProducts();

        if ($productsInCart) {
            // Получаем полную информацию о товарах для списка
            $productsIds = array_keys($productsInCart);
            $products = Product::getProdustsByIds($productsIds);

            // Получаем общую стоимость товаров
            $totalPrice = Cart::getTotalPrice($products);
        }

        require_once(ROOT . '/views/cart/index.php');

        return true;
    }

    public function actionCheckOut () {

        $categories = array();
        $categories = Category::getCategoriesList();

        $result = false;

        //форма отправлена
        if (isset($_POST['submit'])) {
            $userName = $_POST['userName'];
            $userPhone = $_POST['userPhone'];
            $userComment = $_POST['userComment'];

        //Валидация полей
        $errors = false;
        if (!User::checkName($userName))            
            $errors[] = 'Неправильное имя';
        
        if (!User::checkPhone($userPhone))
            $errors[] = 'Неправильный телефон';

            //Форма заполнена корректно
        if ($errors == false) {

            $productsInCart = Cart::getProducts();

            if (User::isGuest()) {
                $userId = false;
            } else {
                $userId = User::checkLogged();
            }
            $result = Order::save($userName, $userPhone, $userComment, $userId, $productsInCart);

            if ($result) {
                $adminEmail = 'vitalsh189@gmail.com';
                $message = 'http://test.site/admin/orders';
                $subject = 'Новый заказ';
                mail($adminEmail, $subject, $message);

                Cart::clear();
            }
        } else {
            $productsInCart = Cart::getProducts();
            $productsIds = array_keys($productsInCart);
            $products = Product::getProdustsByIds($productsIds);
            $totalPrice = Cart::getTotalPrice($products);
            $totalQuantity = Cart::countItems();
        }
        } else {
            $productsInCart = Cart::getProducts();

            if ($productsInCart == false) {
                header('Location: /');
            } else {
                
                $productsIds = array_keys($productsInCart);
                $products = Product::getProdustsByIds($productsIds);
                $totalPrice = Cart::getTotalPrice($products);
                $totalQuantity = Cart::countItems();

                $userName = false;
                $userPhone = false;
                $userComment = false;

                if (User::isGuest()) {

                } else {
                    $userId = User::checkLogged();
                    $user = User::getUserById($userId);
                    $userName = $user['name'];
                }      
              }
        }
        require_once (ROOT. '/views/cart/checkout.php');
        return true;

    }
    public static function actionDelete ($id) {
        // Удалить товар из корзины
        // Возвращаем пользователя на страницу
        Cart::deleteProduct($id);
        header("Location: /cart/");
    }

}