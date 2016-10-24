<?php

use tests\codeception\frontend\AcceptanceTester;
use common\models\Product;

// Проверяем корректную работу виджета TrendingItems

$I = new AcceptanceTester($scenario);
$I->amOnPage('/');

// Проверяем наличие заголовка виджета
$I->see('Trending Items', '#trending-items h3');

// Проверяем наличие элементов популярных товаров
$I->seeElement('#trending-items .trending-item-grid');

// Получаем количество элементов виджета
$products = Product::find()->where(['status' => 1])->orderBy(['id' => 'desc'])->limit(8)->all();
$count = count($products);

// Проверяем каждое из изображений на корректность отображения данных
for($i = 1; $i <= $count; $i++) {
    $I->amOnPage('/');

    // Проверяем наличие изображений в элементах товаров
    $I->seeElement('#trending-items .trending-item-grid:nth-child(' . $i .') .main-image');

    // Проверяем, что изображения загружаются
    $img = $I->grabAttributeFrom('#trending-items .trending-item-grid:nth-child(' . $i .') .main-image', 'src');

    $I->amOnPage($img);
    $I->seeResponseCodeIs(200);

    $I->amOnPage('/');

    // Проверяем наличие заголовка категории
    $categoryTitle = $I->grabTextFrom('#trending-items .trending-item-grid:nth-child(' . $i .') .women-top span');

    if (empty($categoryTitle)) {
        // Завершаем тест, если заголовок пуст
        throw new Exception('Empty category title');
    }

    // Проверяем наличие заголовка товара
    $productTitle = $I->grabTextFrom('#trending-items .trending-item-grid:nth-child(' . $i .') .women-top .item-header a');

    if (empty($productTitle)) {
        // Завершаем тест, если заголовок пуст
        throw new Exception('Empty product title');
    }

    // Проверяем наличие цены
    $price = $I->grabTextFrom('#trending-items .trending-item-grid:nth-child(' . $i .') .mid-2 .item_price');

    if (empty($price)) {
        // Завершаем тест, если цена не указана
        throw new Exception('Empty product price');
    }

    // Завершаем тест, если цена равна $0.00
    if ($price == '$0.00') {
        // Завершаем тест, если цена не указана
        throw new Exception('Null product price');
    }
}

$I->wantTo('Check correct TrendingItems');
