<?php

namespace App\Filament\Navigation;

enum NavigationGroup: string
{
    case USER_MANAGEMENT = 'User Management';
    case GENERAL = 'General';
    case SETTINGS = 'Settings';
    case CONTENT = 'Content';
}
