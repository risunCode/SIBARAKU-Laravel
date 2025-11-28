<?php

namespace App\Enums;

enum Role: string
{
    case ADMIN = 'admin';
    case STAFF = 'staff';
    case USER = 'user';

    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'Administrator',
            self::STAFF => 'Staff',
            self::USER => 'User',
        };
    }

    public static function options(): array
    {
        return [
            self::ADMIN->value => self::ADMIN->label(),
            self::STAFF->value => self::STAFF->label(),
            self::USER->value => self::USER->label(),
        ];
    }

    public function permissions(): array
    {
        return match($this) {
            self::ADMIN => ['*'], // All permissions
            self::STAFF => [
                'commodities.view', 'commodities.create', 'commodities.edit',
                'categories.view', 'locations.view',
                'transfers.view', 'transfers.create',
                'maintenance.view', 'maintenance.create',
                'reports.view'
            ],
            self::USER => [
                'commodities.view',
                'categories.view',
                'locations.view',
                'reports.view'
            ],
        };
    }
}
