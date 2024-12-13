<?php

namespace ExpenseManagementContracts\Enums;

enum Expense: int
{
    case TELEPHONE_LOAD = 0;
    case UTILITY_BILL = 1;
    case LIGHT_WATER = 2;
    case ADVERTISING = 3;
    case MERCHANDISE_ACCESS_REPAIRS = 4;
    case VEHICLE_MAINTENANCE_REPAIRS = 5;
    case REPAIRS_MAINTENANCE_OFFICE_EQUIPMENT = 6;
    case REPAIRS_MAINTENANCE_ITEMS_BLDG = 7;
    case OFFICE_SUPPLIES_EQUIPMENT = 8;
    case GASOLINE_TRANSPO = 9;
    case SALARIES_WAGES_ALLOWANCE = 10;
    case INCENTIVE_BENEFITS_POINTS = 11;
    case UNLOADING_CONTAINER = 12;
    case TAXES_LICENSES_PERMIT = 13;
    case TRAININGS = 14;
    case MISCELLANEOUS = 15;
    case DONATION_SOLICITATION = 16;
    case CASH_ADVANCES_LOANS = 17;
    case REFUND = 18;
    case DRAWEES = 19;

    public function displayName(): string
    {
        return match ($this) {
            self::TELEPHONE_LOAD => 'Telephone/Load',
            self::UTILITY_BILL => 'Utility Bill',
            self::LIGHT_WATER => 'Light & Water',
            self::ADVERTISING => 'Advertising',
            self::MERCHANDISE_ACCESS_REPAIRS => 'Merchandise Access/Repairs',
            self::VEHICLE_MAINTENANCE_REPAIRS => 'Vehicle Maintenance/Repairs',
            self::REPAIRS_MAINTENANCE_OFFICE_EQUIPMENT => 'Repairs & Maintenance - Office Equipment',
            self::REPAIRS_MAINTENANCE_ITEMS_BLDG => 'Repairs & Maintenance - Items/Bldg',
            self::OFFICE_SUPPLIES_EQUIPMENT => 'Office Supplies/Equipment',
            self::GASOLINE_TRANSPO => 'Gasoline/Transpo',
            self::SALARIES_WAGES_ALLOWANCE => 'Salaries, Wages, Allowance',
            self::INCENTIVE_BENEFITS_POINTS => 'Incentive/Benefits/Points',
            self::UNLOADING_CONTAINER => 'Unloading Container',
            self::TAXES_LICENSES_PERMIT => 'Taxes/Licenses/Permit',
            self::TRAININGS => 'Trainings',
            self::MISCELLANEOUS => 'Miscellaneous',
            self::DONATION_SOLICITATION => 'Donation/Solicitation',
            self::CASH_ADVANCES_LOANS => 'Cash Advances/Loans',
            self::REFUND => 'Refund',
            self::DRAWEES => 'Drawee',
        };
    }
}
