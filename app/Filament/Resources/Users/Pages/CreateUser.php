<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getCreateAnotherButton(): bool
    {
        return false;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    // Tangani roles setelah user dibuat
    protected function handleRecordCreation(array $data): Model
    {
        $roles = $data['roles'] ?? [];
        unset($data['roles']);
        
        $user = static::getModel()::create($data);
        
        // Assign roles yang dipilih
        if (!empty($roles)) {
            $user->syncRoles($roles);
            
            // Berikan permission berdasarkan role
            foreach ($roles as $role) {
                $roleObj = \Spatie\Permission\Models\Role::findByName($role);
                if ($roleObj) {
                    // Berikan semua permission dari role
                    $permissions = $roleObj->permissions->pluck('name')->toArray();
                    if (!empty($permissions)) {
                        $user->givePermissionTo($permissions);
                    }
                }
            }
        }
        
        return $user;
    }
}
