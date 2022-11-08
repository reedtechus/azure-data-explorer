<?php

namespace ReedTech\AzureDataExplorer\Interfaces;

interface IngestModelInterface
{
    public function getDEMapping(): string;

    public function getDETable(): string;

    public function toDEIngest(): array;
}
