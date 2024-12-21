<?php

namespace IdentityAndAccessContracts\Enums;

enum Role: string
{
    case Admin = 'admin';
    case Manager = 'manager';
    case InventoryHead = 'inventory_head';
    case SalesRep ='sales_rep';
    case Cashier = 'cashier';
    case Delivery = 'delivery';

    public function displayName(): string
    {
        return match($this)
        {
            Role::Admin => 'Admin',
            Role::InventoryHead => 'Inventory Head',
            Role::SalesRep => 'Sales Representative',
            Role::Cashier => 'Cashier',
            Role::Delivery => 'Delivery',
            self::Manager => 'Manager',
        };
    }
}
