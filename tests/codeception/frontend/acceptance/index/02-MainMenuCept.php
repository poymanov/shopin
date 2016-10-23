<?php

use tests\codeception\frontend\AcceptanceTester;
use common\models\Category;

// Проверяем, что главное меню содержит все необходимые пункты меню
// страницы Home, Contact и страницы с родительскими категориями
$I = new AcceptanceTester($scenario);

$I->amOnPage('/');

// Проверка наличия разметки меню
$I->seeElement('.nav.navbar-nav.nav_1');

// Проверка наличия пунктов меню

// Основные страницы
$I->see('Home', '.nav.navbar-nav.nav_1 li a');
$I->see('Contact', '.nav.navbar-nav.nav_1 li a');

// Получаем список родительских категорий проекта
// и проверяем их наличие в меню
$categories = Category::getParentsCategory();

foreach ($categories as $category) {
    $I->see($category->name, '.nav.navbar-nav.nav_1 li a');
}

$I->wantTo('Check main menu contain all items');
