<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\models\Brand;
use common\models\Category;

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

        // Получение файла с демо данными
        $brands = $this->getDemoDataFile('brands', $path);

        if ($brands == false) {
            exit(0);
        }
        
        // Номер строки, с которой надо начинать обход
        $row = 1;

        // Схема для получения массива данных
        $dataScheme = [
            'name', 'href', 'file', 'status', 'alt', 'title', 'sort'
        ];

        // Массив для временной записи брендов
        $arrBrands = $this->getDemoDataArray($brands, $dataScheme, $row);
    
        // Если в массиве есть данные, то очищаем текущую таблицу брендов и записываем новые
        if (count($arrBrands) == 0) {
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
        foreach ($arrBrands as $brand) {
            print_r($brand);
            // Проверки перед записью

            // Наименование бренда должно быть заполнено
            if (!$this->validateData($brand['name'], 'Name', 'empty')) {
                continue;
            }

            // Поле изображения должно содержать в себе значение
            if (!$this->validateData($brand['file'], 'File', 'empty')) {
                continue;
            }
            
            // Изображение должно физически существовать в структуре проекта
            if (!$this->validateData($imagesPath . $brand['file'], 'Image', 'fileExists')) {
                continue;
            }
           
            // Поле активности должно быть цифровым значением
            if (!$this->validateData($brand['status'], 'Active', 'isDigit')) {
                continue;
            }

            // Поле активности должно содержать цифру 1 или 0
            if ($brand['status'] != 0 && $brand['status'] != 1) {
                echo 'Active field value must be 1 or 0 digit! Skip data.' . PHP_EOL;
                continue;
            }

            // Поле сортировки должно быть цифровым значением
            // если оно заполнено
            if (!empty($brand['sort']) && 
                !$this->validateData($brand['sort'], 'Sort', 'isDigit')) {
                continue;
            }

            // Запись значений в БД
            $newBrand = new Brand();
            $newBrand->name = $brand['name'];
            $newBrand->href = $brand['href'];
            $newBrand->image = $savePath . $brand['file'];
            $newBrand->status = $brand['status'];
            $newBrand->alt = $brand['alt'];
            $newBrand->title = $brand['title'];
            $newBrand->sort = $brand['sort'];

            if ($newBrand->save()) {
                echo "Success!" . PHP_EOL;

                // При успешном сохранении модели переносим файлы в папку storage
                copy($imagesPath . $brand['file'], $storagePath . $brand['file']);
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
        $categories = $this->getDemoDataFile('categories', $path);

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
            'path' => Yii::getAlias('@root') . '/demo/' . $data . '/' . $data . '.csv', // CSV файл с данными            
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
    * Функция получает файл с демо данными
    * или возвращает ошибку, если такой файл не найден
    */
    protected function getDemoDataFile($data, $path)
    {
        if (!file_exists($path)) {
            echo "\n Can't find brand demo file. Check app/demo/" . $data . "/" . $data . ".csv.\n";
            return false;
        }

        if (($brands = fopen($path, "r")) === false) {
            echo "\n  Can't load " . $data . " demo data.\n";
            return false;
        }

        return $brands;
    }

    /**
    * Функция обходит файл с демо данными
    * и формирует массив согласно схеме
    */
    protected function getDemoDataArray($dataDemo, $scheme, $row)
    {
        $arrData = [];

        // Счетчик строк перебора файла
        $i = 0;

        // Получение массива демо данных согласно схеме
        while (($data = fgetcsv($dataDemo, ";")) !== false) {

            if ($i < $row) {
                $i++;
                continue;
            }

            $i++;

            // Получение массива из строки файла
            $arr = explode(';', $data[0]);

            // Запись строки в массив брендов
            foreach ($scheme as $key => $value) {
                $arrData[$i][$value] = $arr[$key];
            }
        }

        return $arrData;
    }
}