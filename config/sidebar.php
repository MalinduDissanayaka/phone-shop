<?php

// Central registry of every sidebar page. Each leaf item's array key is its
// permission key (checked via User::hasPageAccess()). Roles store a flat
// list of these keys in `roles.permissions`.
return [

    'main' => [
        'label' => 'Main',
        'icon' => 'home',
        'items' => [
            'dashboard' => ['label' => 'Dashboard', 'route' => 'dashboard'],
            'pos_terminal' => ['label' => 'POS Terminal', 'route' => 'pos.terminal'],
        ],
    ],

    'inventory' => [
        'label' => 'Inventory',
        'icon' => 'cube',
        'items' => [
            'inventory.category' => ['label' => 'Product Category', 'route' => 'inventory.category'],
            'inventory.add_product' => ['label' => 'Add Product', 'route' => 'inventory.add_product'],
            'inventory.stock' => ['label' => 'Product Stock', 'route' => 'inventory.stock'],
        ],
    ],

    'customer' => [
        'label' => 'Customer',
        'icon' => 'users',
        'items' => [
            'customer.create' => ['label' => 'Customer Creation', 'route' => 'customer.create'],
            'customer.list' => ['label' => 'Customer List', 'route' => 'customer.list'],
        ],
    ],

    'report' => [
        'label' => 'Report',
        'icon' => 'chart-bar',
        'items' => [
            'report.sales' => ['label' => 'Sales Report', 'route' => 'report.sales'],
        ],
    ],

    'finance' => [
        'label' => 'Finance',
        'icon' => 'banknotes',
        'items' => [
            'finance.expenses' => ['label' => 'Expenses', 'route' => 'finance.expenses'],
        ],
    ],

    'notification' => [
        'label' => 'Notification',
        'icon' => 'bell',
        'items' => [
            'notification' => ['label' => 'Notifications', 'route' => 'notifications.index'],
        ],
    ],

    'settings' => [
        'label' => 'Settings',
        'icon' => 'cog',
        'items' => [
            'settings.invoice' => ['label' => 'Invoice Setting', 'route' => 'settings.invoice.edit'],
            'settings.branch' => ['label' => 'Branch Setup', 'route' => 'settings.branches.index'],
            'settings.roles' => ['label' => 'User Role Creation', 'route' => 'settings.roles.index'],
            'settings.users' => ['label' => 'User Creation', 'route' => 'settings.users.index'],
        ],
    ],

];
