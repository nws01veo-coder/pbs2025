<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Tambahkan data roles untuk diisi pada form
        $data['roles'] = $this->record->roles->pluck('name')->toArray();
        
        return $data;
    }
    
    // Tangani roles saat update user
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $roles = $data['roles'] ?? [];
        unset($data['roles']);
        
        // Update user data
        $record->update($data);
        
        // Sync roles yang dipilih
        if (!empty($roles)) {
            $record->syncRoles($roles);
            
            // Reset dan berikan permission berdasarkan role
            $permissions = [];
            foreach ($roles as $role) {
                $roleObj = \Spatie\Permission\Models\Role::findByName($role);
                if ($roleObj) {
                    // Kumpulkan semua permission dari role
                    $rolePermissions = $roleObj->permissions->pluck('name')->toArray();
                    $permissions = array_merge($permissions, $rolePermissions);
                }
            }
            
            // Sync semua permission yang diperlukan
            if (!empty($permissions)) {
                $record->syncPermissions(array_unique($permissions));
            }
        } else {
            // Hapus semua role dan permission jika tidak ada yang dipilih
            $record->syncRoles([]);
            $record->syncPermissions([]);
        }
        
        return $record;
    }
}
