<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\models\Brand;

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
    }

    protected function loadBrands() {
        echo "\n Load brands data.\n";

        // Получение CSV файла с данными
        $path = Yii::getAlias('@root') . '/demo/brands/brands.csv';

        // Папка с изображениями для брендов
        $imagesPath = Yii::getAlias('@root/demo/brands/images/');

        // Папка куда будут скопированы изображения с брендами
        $storagePath = Yii::getAlias('@frontend/web/storage/brands/');

        // Путь к изображениям для записи в БД
        $savePath = '/storage/brands/';

        if (!file_exists($path)) {
            echo "\n Can't find brand demo file. Check app/demo/brands/brands.csv.\n";
            exit(0);
        }

        if (($brands = fopen($path, "r")) === FALSE) {
            echo "\n  Can't load brand demo data.\n";
            exit(0);
        }

        // Массив для временной записи брендов
        $arrBrands = [];

        // Номер строки, с которой надо начинать обход
        $row = 1;

        // Счетчик строк перебора файла
        $i = 0;

        // Обход всех строк файла
        while (($data = fgetcsv($brands, ";")) !== FALSE) {

            if ($i < $row) {
                $i++;
                continue;
            }

            $i++;

            // Получение массива из строки файла
            $arr = explode(';', $data[0]);

            // Запись строки в массив брендов
            $arrBrands[] = [
                'name'      => $arr[0],
                'href'      => $arr[1],
                'file'      => $arr[2],
                'status'    => $arr[3],
                'alt'       => $arr[4],
                'title'     => $arr[5],
                'sort'      => $arr[6],
            ];
        }

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
            if (empty($brand['name'])) {
                echo 'Name value must be filled! Skip data.' . PHP_EOL;
                continue;
            }

            // Поля изображения должно содержать в себе значение
            if (empty($brand['file'])) {
                echo 'File path value must be filled! Skip data.' . PHP_EOL;
                continue;
            }
            
            // Изображение должно физически существовать в структуре проекта
            if (!file_exists($imagesPath . $brand['file'])) {
                echo 'Image file must be exist! Skip data.' . PHP_EOL;
                continue;
            }
        
            // Поле активности должно быть цифровым значением
            if (!ctype_digit($brand['status'])) {
                echo 'Active field value must be a digit! Skip data.' . PHP_EOL;
                continue;
            }

            // Поле активности должно содержать цифру 1 или 0
            if ($brand['status'] != 0 && $brand['status'] != 1) {
                echo 'Active field value must be 1 or 0 digit! Skip data.' . PHP_EOL;
                continue;
            }

            // Поле сортировки должно быть цифровым значением
            // если оно заполнено
            if (!empty($brand['sort']) && !ctype_digit($brand['sort'])) {
                echo 'Sort value must be a digit! Skip data.' . PHP_EOL;
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

    protected function createDirectory($path) {
        if (!file_exists($path)) {
            mkdir($path, 0775, true);
        }
    }

    protected function deleteDirectory($dir) {
        if ($objects = glob($dir."/*")) {
            foreach($objects as $object) {
                is_dir($object) ? $this->deleteDirectory($object) : unlink($object);
            }
        }
        rmdir($dir);
    }
}