<?php

use tests\codeception\frontend\AcceptanceTester;

$I = new AcceptanceTester($scenario);
$I->amOnPage('/');

// Проверка наличия разметки с изображением логотипа
$I->seeElement('.logo img');

// Проверка доступности изображения логотипа
$img = $I->grabAttributeFrom('.logo img', 'src');

$I->amOnPage($img);
$I->seeResponseCodeIs(200);

$I->wantTo('Check exist logo');
