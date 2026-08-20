<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class ProductController extends Controller
{
    private array $products = [
        'Signature 3Q Milk Tea' => [
            'name' => 'Signature 3Q Milk Tea',
            'description' => "Milksha's bestselling signature milk tea layered with a trio of honey pearls, crystals, and pudding.",
            'image' => '/products/milktea.jpg',
            'prices' => ['M' => 140, 'L' => 160],
        ],
        'Strawberry Milk Tea' => [
            'name' => 'Strawberry Milk Tea',
            'description' => 'Creamy milk swirled with a rich strawberry coulis for an indulgent treat.',
            'image' => '/products/milktea red velvet.jpg',
            'prices' => ['M' => 120, 'L' => 140],
        ],
        'Jasmine Milktea' => [
            'name' => 'Jasmine Milktea',
            'description' => 'Light and fragrant Jasmine milktea topped with our lush Cloudy Cream.',
            'image' => '/products/milktea3.jpg',
            'prices' => ['M' => 120, 'L' => 140],
        ],
        'Classic Milk Tea' => [
            'name' => 'Classic Milk Tea',
            'description' => 'Our bestselling signature milk tea with honey pearls.',
            'image' => '/products/Bubble-Milk-Tea.webp',
            'prices' => ['M' => 120, 'L' => 140],
        ],
        'Brown Sugar Boba' => [
            'name' => 'Brown Sugar Boba',
            'description' => 'Creamy milk sweetened with rich, caramelized brown sugar, topped with honey pearls.',
            'image' => '/products/brown-sugar-boba-milk-tea-with-cream-cheese-foam-close-up-bubble-tea-photo.jpg',
            'prices' => ['M' => 140, 'L' => 160],
        ],
        'Matcha Milktea' => [
            'name' => 'Matcha Milktea',
            'description' => 'Pure Japanese Matcha blended into a creamy milktea.',
            'image' => '/products/Matcha-milk-tea-post-6.jpg',
            'prices' => ['M' => 120, 'L' => 140],
        ],
        'Ube Milktea' => [
            'name' => 'Ube Milktea',
            'description' => 'Fresh crushed Ube blended with a creamy milk.',
            'image' => '/products/milktea ube.jpg',
            'prices' => ['M' => 160, 'L' => 180],
        ],
        'Okinawa Milktea' => [
            'name' => 'Okinawa Milktea',
            'description' => 'Rich and caramelized Okinawa brown sugar blended into a creamy milktea.',
            'image' => '/products/milktea4.jpg',
            'prices' => ['M' => 140, 'L' => 160],
        ],
    ];

    public function index()
    {
        return Inertia::render('AllProducts', [
            'products' => array_values($this->products),
        ]);
    }
}
