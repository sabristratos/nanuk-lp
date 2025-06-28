<?php

declare(strict_types=1);

namespace App\Services;

use App\Facades\ActivityLogger;
use App\Models\Testimonial;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TestimonialService
{
    /**
     * Get all active testimonials for a specific language.
     */
    public function getActiveTestimonials(string $language = 'fr'): Collection
    {
        return Testimonial::active()
            ->forLanguage($language)
            ->ordered()
            ->get();
    }

    /**
     * Get all testimonials for admin management.
     */
    public function getAllTestimonials(): Collection
    {
        return Testimonial::ordered()->get();
    }

    /**
     * Create a new testimonial.
     */
    public function createTestimonial(array $data): Testimonial
    {
        try {
            DB::beginTransaction();

            // Validate required fields
            if (empty($data['quote'])) {
                throw ValidationException::withMessages([
                    'quote' => __('The testimonial quote is required.')
                ]);
            }

            // Validate language
            if (isset($data['language']) && !in_array($data['language'], ['fr', 'en'])) {
                throw ValidationException::withMessages([
                    'language' => __('Invalid language selected.')
                ]);
            }

            // Validate rating
            if (isset($data['rating']) && ($data['rating'] < 1 || $data['rating'] > 5)) {
                throw ValidationException::withMessages([
                    'rating' => __('Rating must be between 1 and 5.')
                ]);
            }

            $testimonial = Testimonial::create($data);

            ActivityLogger::logCreated(
                $testimonial,
                auth()->user(),
                [
                    'quote' => $testimonial->quote,
                    'author_name' => $testimonial->author_name,
                    'company_name' => $testimonial->company_name,
                    'language' => $testimonial->language,
                ],
                'testimonial'
            );

            DB::commit();
            return $testimonial;

        } catch (ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (QueryException $e) {
            DB::rollBack();
            throw new \Exception(__('Failed to create testimonial due to a database error. Please try again.'));
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception(__('An unexpected error occurred while creating the testimonial. Please try again.'));
        }
    }

    /**
     * Update an existing testimonial.
     */
    public function updateTestimonial(Testimonial $testimonial, array $data): Testimonial
    {
        try {
            DB::beginTransaction();

            // Validate required fields
            if (empty($data['quote'])) {
                throw ValidationException::withMessages([
                    'quote' => __('The testimonial quote is required.')
                ]);
            }

            // Validate language
            if (isset($data['language']) && !in_array($data['language'], ['fr', 'en'])) {
                throw ValidationException::withMessages([
                    'language' => __('Invalid language selected.')
                ]);
            }

            // Validate rating
            if (isset($data['rating']) && ($data['rating'] < 1 || $data['rating'] > 5)) {
                throw ValidationException::withMessages([
                    'rating' => __('Rating must be between 1 and 5.')
                ]);
            }

            $oldValues = [
                'quote' => $testimonial->quote,
                'author_name' => $testimonial->author_name,
                'company_name' => $testimonial->company_name,
                'language' => $testimonial->language,
                'is_active' => $testimonial->is_active,
            ];

            $testimonial->update($data);

            ActivityLogger::logUpdated(
                $testimonial,
                auth()->user(),
                [
                    'old' => $oldValues,
                    'new' => [
                        'quote' => $testimonial->quote,
                        'author_name' => $testimonial->author_name,
                        'company_name' => $testimonial->company_name,
                        'language' => $testimonial->language,
                        'is_active' => $testimonial->is_active,
                    ],
                ],
                'testimonial'
            );

            DB::commit();
            return $testimonial;

        } catch (ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (QueryException $e) {
            DB::rollBack();
            throw new \Exception(__('Failed to update testimonial due to a database error. Please try again.'));
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception(__('An unexpected error occurred while updating the testimonial. Please try again.'));
        }
    }

    /**
     * Delete a testimonial.
     */
    public function deleteTestimonial(Testimonial $testimonial): void
    {
        try {
            DB::beginTransaction();

            ActivityLogger::logDeleted(
                $testimonial,
                auth()->user(),
                [
                    'quote' => $testimonial->quote,
                    'author_name' => $testimonial->author_name,
                    'company_name' => $testimonial->company_name,
                ],
                'testimonial'
            );

            $testimonial->delete();

            DB::commit();

        } catch (QueryException $e) {
            DB::rollBack();
            throw new \Exception(__('Failed to delete testimonial due to a database error. Please try again.'));
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception(__('An unexpected error occurred while deleting the testimonial. Please try again.'));
        }
    }

    /**
     * Toggle the active status of a testimonial.
     */
    public function toggleActive(Testimonial $testimonial): Testimonial
    {
        try {
            DB::beginTransaction();

            $oldStatus = $testimonial->is_active;
            $testimonial->update(['is_active' => !$testimonial->is_active]);

            ActivityLogger::logUpdated(
                $testimonial,
                auth()->user(),
                [
                    'old' => ['is_active' => $oldStatus],
                    'new' => ['is_active' => $testimonial->is_active],
                ],
                'testimonial'
            );

            DB::commit();
            return $testimonial;

        } catch (QueryException $e) {
            DB::rollBack();
            throw new \Exception(__('Failed to toggle testimonial status due to a database error. Please try again.'));
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception(__('An unexpected error occurred while toggling the testimonial status. Please try again.'));
        }
    }

    /**
     * Update the order of testimonials.
     */
    public function updateOrder(array $orderData): void
    {
        try {
            DB::beginTransaction();

            foreach ($orderData as $id => $order) {
                Testimonial::where('id', $id)->update(['order' => $order]);
            }

            ActivityLogger::logUpdated(
                null,
                auth()->user(),
                [
                    'action' => 'reordered_testimonials',
                    'order_data' => $orderData,
                ],
                'testimonial'
            );

            DB::commit();

        } catch (QueryException $e) {
            DB::rollBack();
            throw new \Exception(__('Failed to update testimonial order due to a database error. Please try again.'));
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception(__('An unexpected error occurred while updating testimonial order. Please try again.'));
        }
    }
} 