<?php

namespace ReedTech\AzureDataExplorer\Interfaces;

interface IngestModelInterface
{
    public function getIngestDatabase(): string;

    public function getIngestMapping(): string;

    public function getIngestTable(): string;

    public function toIngest(): array;
}
