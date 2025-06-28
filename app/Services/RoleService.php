<?php

declare(strict_types=1);

namespace App\Services;

use App\Facades\ActivityLogger;
use App\Models\Role;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RoleService
{
    public function createRole(array $data, array $selectedPermissions): Role
    {
        try {
            DB::beginTransaction();

            // Validate that the role name is unique
            if (Role::where('name', $data['name'])->exists()) {
                throw ValidationException::withMessages([
                    'name' => __('A role with this name already exists.')
                ]);
            }

            // Validate that the slug is unique if provided
            if (isset($data['slug']) && Role::where('slug', $data['slug'])->exists()) {
                throw ValidationException::withMessages([
                    'slug' => __('A role with this slug already exists.')
                ]);
            }

            $role = Role::create($data);

            if (!empty($selectedPermissions)) {
                $role->permissions()->attach($selectedPermissions);
            }

            ActivityLogger::logCreated(
                $role,
                auth()->user(),
                [
                    'name' => $role->name,
                    'permissions' => $selectedPermissions,
                ],
                'role'
            );

            DB::commit();
            return $role;

        } catch (ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (QueryException $e) {
            DB::rollBack();
            throw new \Exception(__('Failed to create role due to a database error. Please try again.'));
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception(__('An unexpected error occurred while creating the role. Please try again.'));
        }
    }

    public function updateRole(Role $role, array $data, array $selectedPermissions): Role
    {
        try {
            DB::beginTransaction();

            // Check if role is system-protected
            if ($role->is_system) {
                throw new \Exception(__('System roles cannot be modified.'));
            }

            // Validate that the role name is unique (excluding current role)
            if (isset($data['name']) && Role::where('name', $data['name'])->where('id', '!=', $role->id)->exists()) {
                throw ValidationException::withMessages([
                    'name' => __('A role with this name already exists.')
                ]);
            }

            // Validate that the slug is unique if provided (excluding current role)
            if (isset($data['slug']) && Role::where('slug', $data['slug'])->where('id', '!=', $role->id)->exists()) {
                throw ValidationException::withMessages([
                    'slug' => __('A role with this slug already exists.')
                ]);
            }

            $oldValues = [
                'name' => $role->name,
                'permissions' => $role->permissions->pluck('id')->map(fn ($id) => (string)$id)->toArray(),
            ];

            $role->update($data);
            $role->permissions()->sync($selectedPermissions);

            ActivityLogger::logUpdated(
                $role,
                auth()->user(),
                [
                    'old' => $oldValues,
                    'new' => [
                        'name' => $role->name,
                        'permissions' => $selectedPermissions,
                    ],
                ],
                'role'
            );

            DB::commit();
            return $role;

        } catch (ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (QueryException $e) {
            DB::rollBack();
            throw new \Exception(__('Failed to update role due to a database error. Please try again.'));
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteRole(Role $role): void
    {
        try {
            DB::beginTransaction();

            // Check if role is system-protected
            if ($role->is_system) {
                throw new \Exception(__('System roles cannot be deleted.'));
            }

            // Check if role has users assigned
            if ($role->users()->count() > 0) {
                throw new \Exception(__('Cannot delete role that has users assigned to it. Please reassign users first.'));
            }

            ActivityLogger::logDeleted(
                $role,
                auth()->user(),
                [
                    'name' => $role->name,
                ],
                'role'
            );

            $role->delete();

            DB::commit();

        } catch (QueryException $e) {
            DB::rollBack();
            throw new \Exception(__('Failed to delete role due to a database error. Please try again.'));
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
} 