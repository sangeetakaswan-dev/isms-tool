<?php

namespace App\DTOs;

readonly class ReportData
{
    public function __construct(
        public string $key,
        public string $title,
        public string $description,
        public array $columns,
        public array $rows,
        public array $summary,
        public array $meta = [],
    ) {
    }

    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'title' => $this->title,
            'description' => $this->description,
            'columns' => $this->columns,
            'rows' => $this->rows,
            'summary' => $this->summary,
            'meta' => $this->meta,
        ];
    }
}