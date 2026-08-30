<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    protected Model $model;

    public function __construct()
    {
        $this->model = app($this->model());
    }

    abstract public function model(): string;

    public function find(int $id, array $columns = ['*']): ?Model
    {
        return $this->model->find($id, $columns);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Model
    {
        $record = $this->model->findOrFail($id);
        $record->update($data);
        return $record->fresh();
    }

    public function delete(int $id): bool
    {
        return (bool) $this->model->findOrFail($id)->delete();
    }
}