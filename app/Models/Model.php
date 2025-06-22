<?php

namespace CountFit\Models;

interface  Model
{
    public function create(): bool;
    public function save(): bool;
    public function getAll();
    public function connect(): bool;
    public static function getById(int $id);
    public static function getByName(string $name);
    public static function getAllRelevant();
}
