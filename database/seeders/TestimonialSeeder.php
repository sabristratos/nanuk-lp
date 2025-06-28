<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testimonials = [
            [
                'quote' => 'Nanuk Web a transformé notre présence en ligne. Nos campagnes Google Ads génèrent maintenant 3x plus de leads qualifiés qu\'avant.',
                'author_name' => 'Marie Dubois',
                'company_name' => null,
                'position' => 'Propriétaire',
                'rating' => 5,
                'order' => 1,
                'language' => 'fr',
                'is_active' => true,
            ],
            [
                'quote' => 'En seulement 2 mois, notre ROI publicitaire est passé de 150% à 320%. L\'équipe de Nanuk Web connaît vraiment son métier.',
                'author_name' => 'Jean Tremblay',
                'company_name' => null,
                'position' => 'Directeur',
                'rating' => 5,
                'order' => 2,
                'language' => 'fr',
                'is_active' => true,
            ],
            [
                'quote' => 'Approche professionnelle et résultats concrets. Nous avons doublé nos ventes en ligne grâce à leurs stratégies Meta Ads.',
                'author_name' => 'Sophie Martin',
                'company_name' => null,
                'position' => 'Gérante',
                'rating' => 5,
                'order' => 3,
                'language' => 'fr',
                'is_active' => true,
            ],
            [
                'quote' => 'Service client exceptionnel et expertise technique remarquable. Nos campagnes n\'ont jamais été aussi performantes.',
                'author_name' => 'Pierre Lavoie',
                'company_name' => null,
                'position' => 'Propriétaire',
                'rating' => 5,
                'order' => 4,
                'language' => 'fr',
                'is_active' => true,
            ],
            [
                'quote' => 'ROI exceptionnel de 450% ! Nanuk Web a optimisé nos campagnes pour maximiser nos profits tout en réduisant nos coûts.',
                'author_name' => 'Isabelle Bouchard',
                'company_name' => null,
                'position' => 'Associée',
                'rating' => 5,
                'order' => 5,
                'language' => 'fr',
                'is_active' => true,
            ],
            [
                'quote' => 'Croissance de 200% en trafic qualifié. L\'équipe de Nanuk Web comprend parfaitement les besoins des PME québécoises.',
                'author_name' => 'Marc Deschamps',
                'company_name' => null,
                'position' => 'Directeur général',
                'rating' => 5,
                'order' => 6,
                'language' => 'fr',
                'is_active' => true,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }
    }
}
