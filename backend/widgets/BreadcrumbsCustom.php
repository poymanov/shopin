<?php

namespace backend\widgets;

use yii\widgets\Breadcrumbs;
use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\base\InvalidConfigException;

class BreadcrumbsCustom extends Breadcrumbs
{

    public $iconHome = "<i class=\"fa fa-dashboard\"></i>";

    // Метод нужен для вывода корневый ссылки с нужным классом
    public function run()
    {
        if (empty($this->links)) {
            return;
        }
        $links = [];
        if ($this->homeLink === null) {
            $links[] = $this->renderItem([
                'label' => Yii::t('yii', 'Home'),
                'url' => Yii::$app->homeUrl,
                'icon' => $this->iconHome
            ], $this->itemTemplate);
        } elseif ($this->homeLink !== false) {
            $links[] = $this->renderItem($this->homeLink, $this->itemTemplate);
        }
        foreach ($this->links as $link) {
            if (!is_array($link)) {
                $link = ['label' => $link];
            }
            $links[] = $this->renderItem($link, isset($link['url']) ? $this->itemTemplate : $this->activeItemTemplate);
        }

        echo Html::tag($this->tag, implode('', $links), $this->options);
    }

    // Кастомизированный метод рендеринга
    protected function renderItem($link, $template)
    {
        $encodeLabel = ArrayHelper::remove($link, 'encode', $this->encodeLabels);
        if (array_key_exists('label', $link)) {
            $label = $encodeLabel ? Html::encode($link['label']) : $link['label'];
        } else {
            throw new InvalidConfigException('The "label" element is required for each link.');
        }
        if (isset($link['template'])) {
            $template = $link['template'];
        }
        if (isset($link['url'])) {
            $options = $link;
            unset($options['template'], $options['label'], $options['url']);
            // Выводим иконку для пункта крошек, если она есть
            if (isset($link['icon'])) {
                $link = Html::a($link['icon'] . $label, $link['url'], $options);
            } else {
                $link = Html::a($label, $link['url'], $options);
            }

        } else {
            $link = $label;
        }
        return strtr($template, ['{link}' => $link]);
    }

}