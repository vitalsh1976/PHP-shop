<?php

class AdminProductController extends AdminBase {

    public function actionIndex () {

        self::checkAdmin();

        $productsList = Product::getProductsList();

        require_once(ROOT. '/views/admin_product/index.php');
        return true;
    }

    public static function actionDelete($id)  {

            self::checkAdmin();

            if(isset($_POST['submit'])) {
                Product::deleteProductById($id);

                header('Location: /admin/product');
            }
            require_once(ROOT. '/views/admin_product/delete.php');
            return true;
    }
    public function actionCreate () {

        self::checkAdmin();

        $categoriesList = Category::getCategoriesListAdmin();

        if (isset($_POST['submit'])) {

            $options['name'] = $_POST['name'];
            $options['code'] = $_POST['code'];
            $options['price'] = $_POST['price'];
            $options['category_id'] = $_POST['category_id'];
            $options['brand'] = $_POST['brand'];
            $options['availability'] = $_POST['availability'];
            $options['description'] = $_POST['description'];
            $options['is_new'] = $_POST['is_new'];
            $options['is_recommended'] = $_POST['is_recommended'];
            $options['status'] = $_POST['status'];

            $errors = false;

            if (!isset($options['name']) || (empty($options['name']))) {

                $errors = 'Заполните поля';
            }
            if ($errors == false) {

                $id = Product::createProduct($options);

                if($id) {
                    if(is_uploaded_file($_FILES["image"]["tmp_name"])) {
                        move_uploaded_file($_FILES["image"]["tmp_name"], $_SERVER["DOCUMENT_ROOT"] . "/upload/images/products/{$id}.jpg");
                    }
                }
                header('Location: /admin/product');
            }
        };
        require_once(ROOT. '/views/admin_product/create.php');
        return true;
    }
    public function actionUpdate($id)
    {
        // Проверка доступа
        self::checkAdmin();
        // Получаем список категорий для выпадающего списка
        $categoriesList = Category::getCategoriesListAdmin();
        // Получаем данные о конкретном заказе
        $product = Product::getProductById($id);
        // Обработка формы
        if (isset($_POST['submit'])) {
            // Если форма отправлена
            // Получаем данные из формы редактирования. При необходимости можно валидировать значения
            $options['name'] = $_POST['name'];
            $options['code'] = $_POST['code'];
            $options['price'] = $_POST['price'];
            $options['category_id'] = $_POST['category_id'];
            $options['brand'] = $_POST['brand'];
            $options['availability'] = $_POST['availability'];
            $options['description'] = $_POST['description'];
            $options['is_new'] = $_POST['is_new'];
            $options['is_recommended'] = $_POST['is_recommended'];
            $options['status'] = $_POST['status'];
            // Сохраняем изменения
            if (Product::updateProductById($id, $options)) {
                // Если запись сохранена
                // Проверим, загружалось ли через форму изображение
                if (is_uploaded_file($_FILES["image"]["tmp_name"])) {
                    // Если загружалось, переместим его в нужную папке, дадим новое имя
                   move_uploaded_file($_FILES["image"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . "/upload/images/products/{$id}.jpg");
                }
            }
            // Перенаправляем пользователя на страницу управлениями товарами
            header("Location: /admin/product");
        }
        // Подключаем вид
        require_once(ROOT . '/views/admin_product/update.php');
        return true;
    }

}