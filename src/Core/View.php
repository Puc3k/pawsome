<?php

namespace App\Core;

use JetBrains\PhpStorm\NoReturn;

class View
{
    //#[NoReturn] PHPstorm IDE attribute
    //Main render function with optional paremeters
    #[NoReturn] public function render(string $page, array $params = []): void
    {
        //Escape paremeters
        $params = $this->escape($params);
        include_once("../templates/layout/layout.php");
        exit();
    }

    private function escape(array $params): array
    {
        $clearParams = [];
        foreach ($params as $key => $param) {
            $clearParams[$key] = match (true) {
                is_array($param) => $this->escape($param),
                //Convert all html special entities like <,>
                $param => htmlentities($param),
                default => $param,
            };
        }
        //Return clear params
        return $clearParams;
    }
}