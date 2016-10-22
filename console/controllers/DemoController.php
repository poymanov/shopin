<?php

namespace console\controllers;

use common\models\ProductBrand;
use common\models\ProductDiscount;
use common\models\ProductImage;
use common\models\ProductsCategories;
use common\models\ProductType;
use common\models\User;
use Yii;
use yii\console\Controller;
use common\models\Brand;
use common\models\Category;
use common\models\Product;

class DemoController extends Controller
{
    public function actionIndex($message = 'hello world')
    {
        echo "Load demo data? All current data will be delete! [yes|no] ";
        $answer = trim(fgets(STDIN));

        // Если no или другой неопреденный ответ, то завершаем процедуру загрузки

        if ($answer != 'yes') {
            echo "\n  Quit demo data loader.\n";
            exit(0);
        }

        // Загрузка брендов
        $this->loadBrands();

        // Загрузка категорий
        $this->loadCategories();

        // Загрузка типов скидок товаров
        $this->loadProductsDiscounts();

        // Загрузка типов товаров
        $this->loadProductsTypes();

        // Загрузка брендов товаров
        $this->loadProductsBrands();

        // Загрузка товаров
        $this->loadProducts();

        // Загрузка пользователей
        $this->loadUsers();
    }

    protected function loadBrands() 
    {
        echo "\n Load brands data.\n";

        $paths = $this->initPath('brands');

        // Получение CSV файла с данными
        $path = $paths['path'];

        // Папка с изображениями для брендов
        $imagesPath = $paths['imagesPath'];

        // Папка куда будут скопированы изображения с брендами
        $storagePath = $paths['storagePath'];

        // Путь к изображениям для записи в БД
        $savePath = $paths['savePath'];

        // Проверка файла с демо данными
        if (!$this->checkDemoDataFile($path)) {
            exit(0);
        }

        // Получение содержимого xml-файла
        $xml = simplexml_load_file($path);

        // Если в файле нет данных
        // прерываем выполнение загрузки
        if (count($xml) == 0) {
            echo "Can't load brands data from file. Empty." . PHP_EOL;
            exit(0);
        }

        // Удаление всех записей о брендах из таблицы
        Brand::deleteAll();

        // Очистка кэша брендов
        Yii::$app->cacheFrontend->delete('brands');

        // Удаление папки брендов и всего её содержимого
        $this->deleteDirectory($storagePath);

        // Создание папки для хранения изображений брендов
        $this->createDirectory($storagePath);

        // Запись данных о брендах в БД
        foreach ($xml->brand as $brand) {
            print_r($brand);

            // Получение данных из xml
            $name = (string) $brand->name;
            $href = (string) $brand->href;
            $file = (string) $brand->file;
            $status = (string) $brand->status;
            $alt = (string) $brand->alt;
            $title = (string) $brand->title;
            $sort = (string) $brand->sort;

            // Проверки перед записью

            // Наименование бренда должно быть заполнено
            if (!$this->validateData($name, 'Name', 'empty')) {
                continue;
            }

            // Поле изображения должно содержать в себе значение
            if (!$this->validateData($file, 'File', 'empty')) {
                continue;
            }
            
            // Изображение должно физически существовать в структуре проекта
            if (!$this->validateData($imagesPath . $file, 'Image', 'fileExists')) {
                continue;
            }
           
            // Поле активности должно быть цифровым значением
            if (!$this->validateData($status, 'Status', 'isDigit')) {
                continue;
            }

            // Поле активности должно содержать цифру 1 или 0
            if ($status != 0 && $status != 1) {
                echo 'Active field value must be 1 or 0 digit! Skip data.' . PHP_EOL;
                continue;
            }

            // Поле сортировки должно быть цифровым значением
            // если оно заполнено
            if (!empty($sort) &&
                !$this->validateData($sort, 'Sort', 'isDigit')) {
                continue;
            }

            // Запись значений в БД
            $newBrand = new Brand();
            $newBrand->name = $name;
            $newBrand->href = $href;
            $newBrand->image = $savePath . $file;
            $newBrand->status = $status;
            $newBrand->alt = $alt;
            $newBrand->title = $title;
            $newBrand->sort = $sort;

            if ($newBrand->save()) {
                echo "Success!" . PHP_EOL;

                // При успешном сохранении модели переносим файлы в папку storage
                copy($imagesPath . $file, $storagePath . $file);
            } else {
                print_r($newBrand->errors);
            }
        }
    }

    protected function loadCategories() 
    {
        echo "\n Load categories data.\n";

        $paths = $this->initPath('categories');

        // Получение CSV файла с данными
        $path = $paths['path'];

        // Проверка файла с демо данными
        if (!$this->checkDemoDataFile($path)) {
            exit(0);
        }

        // Получение содержимого xml-файла
        $xml = simplexml_load_file($path);

        // Если в файле нет данных
        // прерываем выполнение загрузки
        if (count($xml) == 0) {
            echo "Can't load categories data from file. Empty." . PHP_EOL;
            exit(0);
        }

        // Очищаем таблицу категорий в БД
        Category::deleteAll();

        // Очистка кэша категорий
        Yii::$app->cacheFrontend->delete('categories');

        foreach ($xml->category as $category) {
            print_r($category);

            // Получение данных из xml
            $name = (string) $category->name;

            // Проверки перед записью

            // Наименование категории должно быть заполнено
            if (!$this->validateData($name, 'Category', 'empty')) {
                continue;
            }

            //Запись в БД родительской категории
            $newCategory = new Category();
            $newCategory->name = $name;

            if ($newCategory->save()) {
                echo "Success!" . PHP_EOL;

                // При успешном сохранении родительской категории
                // находим и записываем все дочерние категории

                foreach ($category->subcategories as $subcategory) {

                    // Получение данных из xml
                    $nameSubcategory = (string) $subcategory->category;

                    // Проверки перед записью в БД
                    // Наименование категории должно быть заполнено
                    if (!$this->validateData($nameSubcategory, 'Category', 'empty')) {
                        continue;
                    }

                    // Запись дочерней категории
                    $newChildCategory = new Category();
                    $newChildCategory->name = $nameSubcategory;
                    $newChildCategory->parent_id = $newCategory->id;

                    if ($newChildCategory->save()) {
                        echo "Success - Child category!" . PHP_EOL;
                    } else {
                        print_r($newChildCategory->errors);
                    }
                }

            } else {
                print_r($newCategory->errors);
            }
        }
    }

    protected function loadProducts()
    {
        echo "\n Load products data.\n";

        $paths = $this->initPath('products');

        // Получение CSV файла с данными
        $path = $paths['path'];

        // Директория, в которой будут храниться изображения товаров
        $storagePath = $paths['storagePath'];

        // Проверка файла с демо данными
        if (!$this->checkDemoDataFile($path)) {
            exit(0);
        }

        // Получение содержимого xml-файла
        $xml = simplexml_load_file($path);

        // Если в файле нет данных
        // прерываем выполнение загрузки
        if (count($xml) == 0) {
            echo "Can't load brands data from file. Empty." . PHP_EOL;
            exit(0);
        }

        // Путь к директории с изображениями
        $imagesDirectory = Yii::getAlias('@root') . '/demo/products/images/';

        // Удаление всех записей о продуктах из таблиц
        Product::deleteAll();
        ProductImage::deleteAll();
        ProductsCategories::deleteAll();

        // Очистка кэша популярных позиций
        Yii::$app->cacheFrontend->delete('trendingItems');

        // Удаление директории с изображениями товаров
        $this->deleteDirectory($storagePath);

        // Запись информации в БД
        foreach ($xml->product as $product) {
            print_r($product);

            // Получение данных из xml
            $name = (string) $product->name;
            $price = (string) $product->price;
            $preview_text = (string) $product->preview_text;
            $full_description = (string) $product->full_description;
            $status = (string) $product->status;
            $discount = (string) $product->discount;
            $type = (string) $product->type;
            $brand = (string) $product->brand;

            // Проверки перед записью

            // Наименование должно быть заполнено
            if (!$this->validateData($name, 'Name', 'empty')) {
                continue;
            }

            // Поле активности должно содержать цифру 1 или 0
            if ($status != 0 && $status != 1) {
                echo 'Active field value must be 1 or 0 digit! Skip data.' . PHP_EOL;
                continue;
            }

            $newProduct = new Product();
            $newProduct->name = $name;
            $newProduct->price = $price;
            $newProduct->preview_text = $preview_text;
            $newProduct->full_description = $full_description;
            $newProduct->status = $status;

            // Поиск бренда товара и запись при его наличии
            $brandProduct = ProductBrand::findOne(['name' => $brand]);

            if ($brandProduct) {
                $newProduct->brand_id = $brandProduct->id;
            }

            // Поиск типа товара и запись при его наличии
            $typeProduct = ProductType::findOne(['name' => $type]);

            if ($typeProduct) {
                $newProduct->type_id = $typeProduct->id;
            }

            // Поиск типа скидки и запись при её наличии
            $discountProduct = ProductDiscount::findOne(['name' => $discount]);

            if ($discountProduct) {
                $newProduct->discount_id = $discountProduct->id;
            }

            if ($newProduct->save()) {
                echo "Success!" . PHP_EOL;

                // При успешной записи товара записываем его изображения

                // Поиск изображений по товару
                foreach ($product->images->image as $image) {

                    // Получение данных из xml
                    $name = (string) $image->name;
                    $pathImage = (string) $image->path;
                    $title = (string) $image->title;
                    $alt = (string) $image->alt;
                    $main = (string) $image->main;

                    // Проверка файла на физическое существование
                    if (!$this->validateData($imagesDirectory . $pathImage, 'Image', 'fileExists')) {
                        continue;
                    }

                    // Проверка значения поля main
                    // Если заполнено, то должно быть равно 1
                    if (!empty($main) && $main != '1') {
                        echo "Main value must be eq 1!" . PHP_EOL;
                        continue;
                    }

                    // Получаем расширение файла
                    $fileExtension = pathinfo($imagesDirectory . $pathImage, PATHINFO_EXTENSION);

                    // Создание директории, где будут храниться изображения
                    $path = $storagePath . $newProduct->id . '/';
                    $this->createDirectory($path);

                    // Путь к изображениям для записи в БД
                    $savePath = '/storage/products/';

                    // Получение префикса для уникализации файла
                    $prefix = Yii::$app->getSecurity()->generateRandomString(5);

                    // Запись уникального имени для нового файла
                    $fileName = $prefix . '_product_' . $newProduct->id . '.' . $fileExtension;

                    // Копирование файла изображения
                    copy($imagesDirectory . $pathImage, $path . $fileName);

                    // Запись информации об изображении в БД
                    $newProductImage = new ProductImage();
                    $newProductImage->product_id = $newProduct->id;
                    $newProductImage->name = $name;
                    $newProductImage->path = $savePath . $newProduct->id . '/' . $fileName;
                    $newProductImage->title = $title;
                    $newProductImage->alt = $alt;
                    $newProductImage->main = $main;

                    if ($newProductImage->save()) {
                        echo "Success - Image!" . PHP_EOL;
                    } else {
                        print_r($newProductImage->errors);
                    }
                }
                
                // Поиск категорий товара
                foreach ($product->categories->category as $category) {

                    // Получение значений из xml
                    $name = (string) $category->name;

                    // Предварительные проверки

                    // Наименование должно быть заполнено
                    if (!$this->validateData($name, 'Name', 'empty')) {
                        continue;
                    }

                    // Поиск категории в списке категорий и запись при её наличии
                    $dbCategory = Category::findOne(['name' => $name]);

                    if ($dbCategory) {
                        $newProductCategory = new ProductsCategories();
                        $newProductCategory->product_id = $newProduct->id;
                        $newProductCategory->category_id = $dbCategory->id;

                        if ($newProductCategory->save()) {
                            echo "Success Category!" . PHP_EOL;
                        } else {
                            print_r($newProductCategory->errors);
                        }


                    }
                }

            } else {
                print_r($newProduct->errors);
            }
        }
//
//        // Запись данных о брендах в БД
//        foreach ($arrProducts as $product) {
//            print_r($product);
//            // Проверки перед записью
//
//            // Наименование продуткра должно быть заполнено
//            if (!$this->validateData($product['name'], 'Name', 'empty')) {
//                continue;
//            }
//
//            // Поле активности должно содержать цифру 1 или 0
//            if ($product['status'] != 0 && $product['status'] != 1) {
//                echo 'Active field value must be 1 or 0 digit! Skip data.' . PHP_EOL;
//                continue;
//            }
//
//            $newProduct = new Product();
//            $newProduct->name = $product['name'];
//            $newProduct->price = $product['price'];
//            $newProduct->preview_text = $product['preview_text'];
//            $newProduct->full_description = $product['full_description'];
//            $newProduct->status = $product['status'];
//            // discount
//            // type
//            // brand
//
//            if ($newProduct->save()) {
//                echo "Success!" . PHP_EOL;
//
//                // При успешной записи товара записываем его изображения
//
//                // Поиск изображений по товару
//                foreach ($arrImages as $image) {
//                    if ($image['product'] != $product['name']) {
//                        continue;
//                    }
//
//                    // Проверка файла на физическое существование
//                    if (!$this->validateData($imagesDirectory . $image['path'], 'Image', 'fileExists')) {
//                        continue;
//                    }
//
//                    // Проверка значения поля main
//                    // Если заполнено, то должно быть равно 1
//                    if (!empty($image['main']) && $image['main'] != '1') {
//                        echo "Main value must be eq 1!" . PHP_EOL;
//                        continue;
//                    }
//
//                    // Получаем расширение файла
//                    $fileExtension = pathinfo($imagesDirectory . $image['path'], PATHINFO_EXTENSION);
//
//                    // Создание директории, где будут храниться изображения
//                    $path = $storagePath . $newProduct->id . '/';
//                    $this->createDirectory($path);
//
//                    // Путь к изображениям для записи в БД
//                    $savePath = '/storage/products/';
//
//                    // Получение префикса для уникализации файла
//                    $prefix = Yii::$app->getSecurity()->generateRandomString(5);
//
//                    // Запись уникального имени для нового файла
//                    $fileName = $prefix . '_product_' . $newProduct->id . '.' . $fileExtension;
//
//                    // Копирование файла изображения
//                    copy($imagesDirectory . $image['path'], $path . $fileName);
//
//                    // Запись информации об изображении в БД
//                    $newProductImage = new ProductImage();
//                    $newProductImage->product_id = $newProduct->id;
//                    $newProductImage->name = $image['name'];
//                    $newProductImage->path = $savePath . $newProduct->id . '/' . $fileName;
//                    $newProductImage->title = $image['title'];
//                    $newProductImage->alt = $image['alt'];
//                    $newProductImage->main = $image['main'];
//
//                    if ($newProductImage->save()) {
//                        echo "Success - Image!" . PHP_EOL;
//                    } else {
//                        print_r($newProductImage->errors);
//                    }
//                }
//
//                // При успешной записи товара записываем его категории
//
//            } else {
//                print_r($newProduct->errors);
//            }
//        }
    }

    protected function loadProductsDiscounts()
    {
        echo "\n Load products discounts data.\n";

        $paths = $this->initPath('products_discounts');

        // Получение CSV файла с данными
        $path = $paths['path'];

        // Проверка файла с демо данными
        if (!$this->checkDemoDataFile($path)) {
            exit(0);
        }

        // Получение содержимого xml-файла
        $xml = simplexml_load_file($path);

        // Если в файле нет данных
        // прерываем выполнение загрузки
        if (count($xml) == 0) {
            echo "Can't load products discounts data from file. Empty." . PHP_EOL;
            exit(0);
        }

        // Удаление всех записей о брендах из таблицы
        ProductDiscount::deleteAll();

        // Загрузка данных в БД
        foreach ($xml->discount as $discount) {
            print_r($discount);

            // Получение данных из xml
            $name = (string) $discount->name;

            // Проверки перед записью

            // Наименование должно быть заполнено
            if (!$this->validateData($name, 'Name', 'empty')) {
                continue;
            }

            $newProductDiscount = new ProductDiscount();
            $newProductDiscount->name = $name;

            if ($newProductDiscount->save()) {
                echo "Success!" . PHP_EOL;
            } else {
                print_r($newProductDiscount->errors);
            }
        }
    }

    protected function loadProductsTypes()
    {
        echo "\n Load products types data.\n";

        $paths = $this->initPath('products_types');

        // Получение CSV файла с данными
        $path = $paths['path'];

        // Проверка файла с демо данными
        if (!$this->checkDemoDataFile($path)) {
            exit(0);
        }

        // Получение содержимого xml-файла
        $xml = simplexml_load_file($path);

        // Если в файле нет данных
        // прерываем выполнение загрузки
        if (count($xml) == 0) {
            echo "Can't load products types data from file. Empty." . PHP_EOL;
            exit(0);
        }

        // Удаление всех записей о брендах из таблицы
        ProductType::deleteAll();

        // Загрузка данных в БД
        foreach ($xml->type as $type) {
            print_r($type);

            // Получение данных из xml
            $name = (string) $type->name;

            // Проверки перед записью

            // Наименование должно быть заполнено
            if (!$this->validateData($name, 'Name', 'empty')) {
                continue;
            }

            $newProductType = new ProductType();
            $newProductType->name = $name;

            if ($newProductType->save()) {
                echo "Success!" . PHP_EOL;
            } else {
                print_r($newProductType->errors);
            }
        }
    }

    protected function loadProductsBrands()
    {
        echo "\n Load products brands data.\n";

        $paths = $this->initPath('products_brands');

        // Получение CSV файла с данными
        $path = $paths['path'];

        // Проверка файла с демо данными
        if (!$this->checkDemoDataFile($path)) {
            exit(0);
        }

        // Получение содержимого xml-файла
        $xml = simplexml_load_file($path);

        // Если в файле нет данных
        // прерываем выполнение загрузки
        if (count($xml) == 0) {
            echo "Can't load products brands data from file. Empty." . PHP_EOL;
            exit(0);
        }

        // Удаление всех записей о брендах из таблицы
        ProductBrand::deleteAll();

        // Загрузка данных в БД
        foreach ($xml->brand as $brand) {
            print_r($brand);

            // Получение данных из xml
            $name = (string) $brand->name;

            // Проверки перед записью

            // Наименование должно быть заполнено
            if (!$this->validateData($name, 'Name', 'empty')) {
                continue;
            }

            $newProductBrand = new ProductBrand();
            $newProductBrand->name = $name;

            if ($newProductBrand->save()) {
                echo "Success!" . PHP_EOL;
            } else {
                print_r($newProductBrand->errors);
            }
        }
    }

    protected function loadUsers()
    {
        echo "\n Load users data.\n";

        $paths = $this->initPath('users');

        // Получение CSV файла с данными
        $path = $paths['path'];

        // Проверка файла с демо данными
        if (!$this->checkDemoDataFile($path)) {
            exit(0);
        }

        // Получение содержимого xml-файла
        $xml = simplexml_load_file($path);

        // Если в файле нет данных
        // прерываем выполнение загрузки
        if (count($xml) == 0) {
            echo "Can't load users data from file. Empty." . PHP_EOL;
            exit(0);
        }

        // Удаление всех записей о брендах из таблицы
        User::deleteAll();

        // Запись данных о пользователях в БД
        foreach ($xml->user as $user) {
            print_r($user);

            // Получение данных из xml
            $username = (string) $user->username;
            $password = (string) $user->password;
            $email = (string) $user->email;

            // Проверки перед записью

            // Имя пользователя должно быть заполнено
            if (!$this->validateData($username, 'Username', 'empty')) {
                continue;
            }

            // Пароль пользователя должен быть заполнен
            if (!$this->validateData($password, 'Password', 'empty')) {
                continue;
            }

            // Почта пользователя должен быть заполнен
            if (!$this->validateData($email, 'Email', 'empty')) {
                continue;
            }

            // Запись значений в БД
            $newUser = new User();
            $newUser->username = $username;
            $newUser->password = $password;
            $newUser->email = $email;
            $newUser->generateAuthKey();

            if ($newUser->save()) {
                echo "Success!" . PHP_EOL;
            } else {
                print_r($newUser->errors);
            }
        }
    }

    protected function createDirectory($path) 
    {
        if (!file_exists($path)) {
            mkdir($path, 0775, true);
        }
    }

    /**
    * Функция определяет 
    * необходимые для загрузки тестовых данных пути
    */
    protected function initPath($data)
    {
        $paths = [
            'path' => Yii::getAlias('@root') . '/demo/' . $data . '/' . $data . '.xml', // CSV файл с данными
            'imagesPath' => Yii::getAlias('@root/demo/' . $data . '/images/'), // Папка с изображениями для данных
            'storagePath' => Yii::getAlias('@frontend/web/storage/' . $data . '/'), // Папка куда будут скопированы изображения
            'savePath' => '/storage/' . $data . '/' // Путь к изображениям для записи в БД
        ];
        
        return $paths;     
    }

    protected function deleteDirectory($dir) 
    {
        if ($objects = glob($dir."/*")) {
            foreach($objects as $object) {
                file_exists($object) && is_dir($object) ? $this->deleteDirectory($object) : unlink($object);
            }
        }

        if (file_exists($dir) && is_dir($dir)) {
            rmdir($dir);
        }
    }

    /**
    * Функция обеспечивает валидацию переданных значений
    */
    protected function validateData($data, $title, $mode) 
    {
        $result = true;

        // Проверка поля на ошибку
        if ($mode == 'empty') {
            if (empty($data)) {
                $result = false;
            }            
        } elseif ($mode == 'fileExists') {
            if (!file_exists($data)) {
                $result = false;
            }
        } elseif ($mode == 'isDigit') {
            if (!ctype_digit($data)) {
                $result = false;
            }
        }

        // Если ошибка найдена, вывод уведомления
        if (!$result) {
            echo $title . " " . $this->validateErrors()[$mode] . PHP_EOL;
        }

        return $result;
    }

    /**
    * Функция содержит массив с описанием ошибок валидации
    */
    protected function validateErrors()
    {
        return 
        [
            'empty' => 'value must be filled! Skip data.',
            'fileExists' => 'file must be exist! Skip data',
            'isDigit' => 'field value must be a digit! Skip data.'
        ];
    }

    /**
    * Функция проверяет наличие файла с демо-данными
    */
    protected function checkDemoDataFile($path)
    {
        if (!file_exists($path)) {
            echo "\n Can't find brand demo file: " . $path . ".\n";
            return false;
        }

        return true;
    }
}