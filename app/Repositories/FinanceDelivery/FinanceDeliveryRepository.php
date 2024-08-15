<?php

namespace Vanguard\Repositories\FinanceDelivery;

use Illuminate\Database\Eloquent\Collection;

interface FinanceDeliveryRepository
{
    public function all(): Collection;

    public function lists(string $column = 'name', string $key = 'id'): \Illuminate\Support\Collection;

    public function find(int $id): ?FinanceDelivery;

    public function findByName(string $name): ?FinanceDelivery;

    public function create(array $data): FinanceDelivery;

    public function update(int $id, array $data): FinanceDelivery;

    public function delete(int $id): void;
}
