<?php

namespace WHMCS\Module\Addon\ChatManager;

abstract class VueController
{
    const COMPONENTSDIR = __DIR__ . '/app/Components/';
    public $components;
    public function __construct()
    {
        foreach (glob(self::COMPONENTSDIR . "*.vue") as $vc) {
            $component .= file_exists($vc) ? file_get_contents($vc) : '';
        }
        $this->components = $component;
    }
    public function returnComponents()
    {

        return $this->components;
    }
}
