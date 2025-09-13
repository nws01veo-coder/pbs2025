<?php

namespace App\Filament\Resources;

enum NavigationGroups: string
{
    case UserManagement = 'User Management';
    case AnggotaManagement = 'Anggota Management';
    case KasManagement = 'Kas Management';
    case JadwalManagement = 'Jadwal Management';
    case GalleryManagement = 'Gallery Management';
    case ArisanManagement = 'Arisan Management';
}
