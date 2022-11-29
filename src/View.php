<?php

namespace App;

class View
{
    public function render(string $page, array $params = []): void
    {
        $params = $this->escape($params);
        include_once("../templates/layout/layout.php");
    }

    private function escape(array $params): array
    {
        $clearParams = [];
        foreach ($params as $key => $param) {
            $clearParams[$key] = match (true) {
                is_array($param) => $this->escape($param),
                is_int($param) => $param,
                $param => htmlentities($param),
                default => $param,
            };
        }
        return $clearParams;
    }
}