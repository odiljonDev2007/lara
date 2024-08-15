<?php

namespace Vanguard\Repositories\GuideTransport;

use Illuminate\Database\Eloquent\Collection;
use Vanguard\GuideTransport;
use Vanguard\Events\User\LoggedUserActivity;

class EloquentGuideTransport implements GuideTransportRepository
{
    public function all(): Collection
    {
        return GuideTransport::all();
    }

    public function find($id): ?GuideTransport
    {
        return GuideTransport::find($id);
    }

    public function create(array $data): GuideTransport
    {
        //dd($data);
        if ($data['mercenaryName'] == null) {
            $data['mercenaryName'] = '';
        }
        $GuideTransport = GuideTransport::create($data);

        //event(new Created($GuideTransport));
        event(new LoggedUserActivity);

        return $GuideTransport;
    }

    public function update($id, array $data): GuideTransport
    {
        if ($data['mercenaryName'] == null) {
            $data['mercenaryName'] = '';
        }
        $GuideTransport = $this->find($id);

        $GuideTransport->update($data);

        //event(new Updated($GuideTransport));
        event(new LoggedUserActivity);

        return $GuideTransport;
    }

    public function delete($id): void
    {
        $GuideTransport = $this->find($id);
        
        //event(new Deleted($GuideTransport));
        event(new LoggedUserActivity);

        $GuideTransport->delete();
    }

    public function lists(string $column = 'name', string $key = 'id'): \Illuminate\Support\Collection
    {
        return GuideTransport::pluck($column, $key);
    }

    public function findByName($name): ?GuideTransport
    {
        return GuideTransport::where('name', $name)->first();
    }
}
