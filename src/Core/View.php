<?php

namespace App\Core;

use JetBrains\PhpStorm\NoReturn;

class View
{
    #[NoReturn] public function render(string $page, array $params = []): void
    {
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
                $param => htmlentities($param),
                default => $param,
            };
        }
        return $clearParams;
    }
}