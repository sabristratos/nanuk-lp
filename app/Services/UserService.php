<?php

declare(strict_types=1);

namespace App\Services;

use App\Facades\ActivityLogger;
use App\Models\User;
use App\Notifications\Admin\UserCreatedNotification;
use App\Notifications\Admin\UserDeletedNotification;
use App\Notifications\User\NewUserWelcomeNotification;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;

class UserService
{
    public function createUser(array $data, array $selectedRoles): User
    {
        try {
            DB::beginTransaction();

            // Validate that the email is unique
            if (User::where('email', $data['email'])->exists()) {
                throw ValidationException::withMessages([
                    'email' => __('A user with this email already exists.')
                ]);
            }

            $plainPassword = $data['password'];
            $data['password'] = Hash::make($data['password']);

            $user = User::create($data);

            if (!empty($selectedRoles)) {
                $user->roles()->attach($selectedRoles);
            }

            ActivityLogger::logCreated(
                $user,
                auth()->user(),
                [
                    'name' => $user->name,
                    'email' => $user->email,
                    'roles' => $selectedRoles,
                    'status' => $user->status->value,
                ],
                'user'
            );

            DB::commit();

            // Send notifications outside of transaction
            try {
                $user->notify(new NewUserWelcomeNotification($user, $plainPassword));
            } catch (\Exception $e) {
                Log::error('Failed to send welcome email to user ' . $user->email . ': ' . $e->getMessage());
            }

            try {
                $adminUsers = User::whereHas('roles', fn($q) => $q->where('slug', 'admin'))->get();
                if ($adminUsers->isNotEmpty() && auth()->check()) {
                    Notification::send($adminUsers, new UserCreatedNotification($user, auth()->user()));
                }
            } catch (\Exception $e) {
                Log::error('Failed to send UserCreatedNotification to admins: ' . $e->getMessage());
            }

            return $user;

        } catch (ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (QueryException $e) {
            DB::rollBack();
            throw new \Exception(__('Failed to create user due to a database error. Please try again.'));
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception(__('An unexpected error occurred while creating the user. Please try again.'));
        }
    }

    public function updateUser(User $user, array $data, array $selectedRoles): User
    {
        try {
            DB::beginTransaction();

            // Validate that the email is unique (excluding current user)
            if (isset($data['email']) && User::where('email', $data['email'])->where('id', '!=', $user->id)->exists()) {
                throw ValidationException::withMessages([
                    'email' => __('A user with this email already exists.')
                ]);
            }

            $oldValues = [
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles->pluck('id')->map(fn ($id) => (string)$id)->toArray(),
                'status' => $user->status->value,
            ];

            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            $user->update($data);
            $user->roles()->sync($selectedRoles);

            ActivityLogger::logUpdated(
                $user,
                auth()->user(),
                [
                    'old' => $oldValues,
                    'new' => [
                        'name' => $user->name,
                        'email' => $user->email,
                        'roles' => $selectedRoles,
                        'status' => $user->status->value,
                    ],
                ],
                'user'
            );

            DB::commit();
            return $user;

        } catch (ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (QueryException $e) {
            DB::rollBack();
            throw new \Exception(__('Failed to update user due to a database error. Please try again.'));
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception(__('An unexpected error occurred while updating the user. Please try again.'));
        }
    }

    public function deleteUser(User $user): void
    {
        try {
            DB::beginTransaction();

            // Prevent self-deletion
            if (auth()->id() === $user->id) {
                throw new \Exception(__('You cannot delete your own account.'));
            }

            // Check if user is the last admin
            if ($user->roles()->where('slug', 'admin')->exists()) {
                $adminCount = User::whereHas('roles', fn($q) => $q->where('slug', 'admin'))->count();
                if ($adminCount <= 1) {
                    throw new \Exception(__('Cannot delete the last administrator account.'));
                }
            }

            ActivityLogger::logDeleted(
                $user,
                auth()->user(),
                [
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'user'
            );

            $user->delete();

            DB::commit();

            // Send notifications outside of transaction
            try {
                $adminUsers = User::whereHas('roles', fn ($q) => $q->where('slug', 'admin'))->get();
                if ($adminUsers->isNotEmpty() && auth()->check()) {
                    Notification::send($adminUsers, new UserDeletedNotification($user->toArray(), auth()->user()));
                }
            } catch (\Exception $e) {
                Log::error('Failed to send UserDeletedNotification to admins: ' . $e->getMessage());
            }

        } catch (QueryException $e) {
            DB::rollBack();
            throw new \Exception(__('Failed to delete user due to a database error. Please try again.'));
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
} 