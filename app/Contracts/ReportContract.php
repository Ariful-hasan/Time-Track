<?php

namespace App\Contracts;


interface ReportContract
{
    public function generateReport(array $data);
}