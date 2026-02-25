<?php

namespace App\Services;

class DashboardService
{
    public function summaryByRole(string $role): array
    {
        return ['role' => $role];
    }
}