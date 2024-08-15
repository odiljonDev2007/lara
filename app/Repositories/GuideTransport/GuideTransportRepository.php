<?php

namespace Vanguard\Repositories\GuideTransport;

use Illuminate\Database\Eloquent\Collection;
use Vanguard\GuideTransport;

interface GuideTransportRepository
{
    public function all(): Collection;

    public function lists(string $column = 'name', string $key = 'id'): \Illuminate\Support\Collection;

    public function find(int $id): ?GuideTransport;

    public function findByName(string $name): ?GuideTransport;

    public function create(array $data): GuideTransport;

    public function update(int $id, array $data): GuideTransport;

    public function delete(int $id): void;
}
