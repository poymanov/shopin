<?php

namespace console\controllers;

use common\models\ProductImage;
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
        //$this->loadCategories();

        // Загрузка товаров
        //$this->loadProducts();
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

        // Получение файла с демо данными
        $categories = $this->getDemoDataFile($path);

        if ($categories == false) {
            exit(0);
        }
        
        // Номер строки, с которой надо начинать обход
        $row = 1;

        // Схема для получения массива данных
        $dataScheme = [
            'name', 'parent_name'
        ];

        // Массив для временной записи брендов
        $arrCategories = $this->getDemoDataArray($categories, $dataScheme, $row);

        // Отдельно получаем родительские категории
        $parentCategories = [];

        foreach ($arrCategories as $category) {
            
            // Берем только категории, у которых 
            // не указано имя родительской категории
            if (!empty($category['parent_name'])) {
                continue;
            }

            $parentCategories[] = $category['name'];
        }

        // Если нет категорий, то прекращаем загрузку данных
        if (count($parentCategories) == 0) {
            echo "Can't load categories data from file. Empty." . PHP_EOL;
            exit(0);
        }

        // Очищаем таблицу категорий в БД
        Category::deleteAll();

        // Запись категорий в БД
        foreach ($parentCategories as $category) {
            echo $category . PHP_EOL;

            // Проверки перед записью

            // Наименование категории должно быть заполнено
            if (!$this->validateData($category, 'Category', 'empty')) {
                continue;
            }

            // Запись в БД родительской категории
            $newCategory = new Category();
            $newCategory->name = $category;

            if ($newCategory->save()) {
                echo "Success!" . PHP_EOL;

                // При успешном сохранении родительской категории
                // находим и записываем все дочерние категории

                foreach ($arrCategories as $child) {
                    
                    if ($child['parent_name'] != $category) {
                        continue; 
                    }

                    // Запись дочерней категории
                    $newChildCategory = new Category();
                    $newChildCategory->name = $child['name'];
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

        // Путь к изображениям для записи в БД
        //$savePath = $paths['savePath'];

        // Получение файла с демо данными
        $products = $this->getDemoDataFile($path);

        if ($products == false) {
            exit(0);
        }

        // Номер строки, с которой надо начинать обход
        $row = 1;

        // Схема для получения массива данных
        $dataScheme = [
            'name', 'price', 'preview_text', 'full_description', 'status', 'discount', 'type', 'brand'
        ];

        // Массив для временной записи продукторв
        $arrProducts = $this->getDemoDataArray($products, $dataScheme, $row);
    
        // Если в массиве есть данные, 
        // то очищаем текущую таблицу продуктов и записываем новые
        if (count($arrProducts) == 0) {
            echo "Can't load products data from file. Empty." . PHP_EOL;
            exit(0);
        }

        // Получение данных об изображениях товаров

        // Путь к файлу с данными об изображениях
        $imagesPath = Yii::getAlias('@root') . '/demo/products/images.csv';

        // Путь к директории с изображениями
        $imagesDirectory = Yii::getAlias('@root') . '/demo/products/images/';

        // Файл с изображениями
        $images = $this->getDemoDataFile($imagesPath);

        // Схема получения массива данных по изображениями
        $imagesDataScheme = ['product', 'name', 'path', 'title', 'alt', 'main'];

        // Массив с данными об изображениях
        $arrImages = $this->getDemoDataArray($images, $imagesDataScheme, $row);

        // Удаление всех записей о продуктах из таблиц
        Product::deleteAll();
        ProductImage::deleteAll();

        // Удаление директории с изображениями товаров
        $this->deleteDirectory($storagePath);

        // Запись данных о брендах в БД
        foreach ($arrProducts as $product) {
            print_r($product);
            // Проверки перед записью

            // Наименование продуткра должно быть заполнено
            if (!$this->validateData($product['name'], 'Name', 'empty')) {
                continue;
            }

            // Поле активности должно содержать цифру 1 или 0
            if ($product['status'] != 0 && $product['status'] != 1) {
                echo 'Active field value must be 1 or 0 digit! Skip data.' . PHP_EOL;
                continue;
            }

            $newProduct = new Product();
            $newProduct->name = $product['name'];
            $newProduct->price = $product['price'];
            $newProduct->preview_text = $product['preview_text'];
            $newProduct->full_description = $product['full_description'];
            $newProduct->status = $product['status'];
            // discount
            // type
            // brand

            if ($newProduct->save()) {
                echo "Success!" . PHP_EOL;

                // При успешной записи товара записываем его изображения

                // Поиск изображений по товару
                foreach ($arrImages as $image) {
                    if ($image['product'] != $product['name']) {
                        continue;
                    }

                    // Проверка файла на физическое существование
                    if (!$this->validateData($imagesDirectory . $image['path'], 'Image', 'fileExists')) {
                        continue;
                    }

                    // Проверка значения поля main
                    // Если заполнено, то должно быть равно 1
                    if (!empty($image['main']) && $image['main'] != '1') {
                        echo "Main value must be eq 1!" . PHP_EOL;
                        continue;
                    }

                    // Получаем расширение файла
                    $fileExtension = pathinfo($imagesDirectory . $image['path'], PATHINFO_EXTENSION);

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
                    copy($imagesDirectory . $image['path'], $path . $fileName);

                    // Запись информации об изображении в БД
                    $newProductImage = new ProductImage();
                    $newProductImage->product_id = $newProduct->id;
                    $newProductImage->name = $image['name'];
                    $newProductImage->path = $savePath . $newProduct->id . '/' . $fileName;
                    $newProductImage->title = $image['title'];
                    $newProductImage->alt = $image['alt'];
                    $newProductImage->main = $image['main'];

                    if ($newProductImage->save()) {
                        echo "Success - Image!" . PHP_EOL;
                    } else {
                        print_r($newProductImage->errors);
                    }
                }

                // При успешной записи товара записываем его категории

            } else {
                print_r($newProduct->errors);
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
                is_dir($object) ? $this->deleteDirectory($object) : unlink($object);
            }
        }
        rmdir($dir);
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