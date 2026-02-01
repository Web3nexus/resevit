<?php

namespace Database\Seeders;

use App\Models\WebsiteTemplate;
use Illuminate\Database\Seeder;

class WebsiteTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Home-1: Food Delivery',
                'slug' => 'home-1',
                'thumbnail_path' => 'design/Home-1.png',
                'structure_schema' => [],
                'default_content' => [
                    'business_name' => 'FastBites',
                    'hero_title' => 'Kings Burger',
                    'hero_tagline' => 'Satisfy your cravings.',
                    'hero_promo' => '20% OFF',
                    'categories' => [
                        ['name' => 'Combo Pack', 'image' => 'fast-food'],
                        ['name' => 'Huge Burger', 'image' => 'hamburger'],
                        ['name' => 'Hot Pizza', 'image' => 'pizza-slice'],
                        ['name' => 'The Fries', 'image' => 'french-fries'],
                    ],
                    'how_it_works' => [
                        ['title' => 'Select Food', 'desc' => 'Choose your favorite meal.', 'icon' => 'mouse'],
                        ['title' => 'Place Order', 'desc' => 'Confirm your selection.', 'icon' => 'shopping-cart'],
                        ['title' => 'Fast Delivery', 'desc' => 'Wait for the doorbell.', 'icon' => 'truck'],
                    ],
                    'menu_sections' => [
                        ['title' => 'Mexican Pizza Burger', 'price' => '$7.50', 'image' => ''],
                        ['title' => 'Soft Drink Coffee', 'price' => '$2.50', 'image' => ''],
                        ['title' => 'French Fry Burger', 'price' => '$5.30', 'image' => ''],
                    ],
                    'services' => [
                        ['title' => 'Afternoon Tea', 'desc' => 'Relax with our selection.', 'icon' => 'coffee'],
                        ['title' => 'Vegan Cuisine', 'desc' => 'Healthy and green.', 'icon' => 'leaf'],
                        ['title' => 'Online Order', 'desc' => 'Order from home.', 'icon' => 'mobile'],
                    ],
                    'best_sellers' => [
                        ['name' => 'Double Cheddar', 'price' => '$12.99', 'desc' => 'Extra cheese, extra beef.'],
                        ['name' => 'Crispy Chicken', 'price' => '$9.99', 'desc' => 'Fried to perfection.'],
                    ],
                    'footer_text' => 'We are a team of passionated people.',
                ],
                'is_active' => true,
                'is_premium' => false,
            ],
            // ... (keeping 2-12 same) ...
            [
                'name' => 'Home-13: Coffee House',
                'slug' => 'home-13',
                'thumbnail_path' => 'design/Home cofee.png',
                'structure_schema' => [],
                'default_content' => [
                    'business_name' => 'Coffee House',
                    'hero_title' => 'The Perfect Space to Enjoy Fantastic Food',
                    'history_title' => 'Explore The History of the Line',
                    'history_text' => 'Since 1982, we have been serving the best coffee in town. Our beans are sourced ethically and roasted with passion.',
                    'history_stat' => '95k',
                    'history_stat_label' => 'Visitors',
                    'special_menu' => [
                        ['name' => 'Cappuccino', 'desc' => 'Espresso with steamed milk', 'price' => '$4.00'],
                        ['name' => 'Americano', 'desc' => 'Espresso with hot water', 'price' => '$3.00'],
                        ['name' => 'Latte', 'desc' => 'Espresso with steamed milk', 'price' => '$4.50'],
                        ['name' => 'Espresso', 'desc' => 'Strong and bold', 'price' => '$2.50'],
                    ],
                    'products' => [
                        ['name' => 'Colombian Roast', 'title' => 'Costa Coffee Packet', 'desc' => 'Rich and full-bodied.'],
                        ['name' => 'Coffee Maker', 'title' => 'Moka Pot', 'desc' => 'Classic Italian design.'],
                    ],
                    'news' => [
                        ['title' => 'The art of brewing', 'date' => 'Jan 12', 'image' => ''],
                        ['title' => 'New beans arrival', 'date' => 'Jan 15', 'image' => ''],
                        ['title' => 'Barista championship', 'date' => 'Jan 20', 'image' => ''],
                    ],
                    'working_hours' => [
                        'Mon-Fri: 8am - 8pm',
                        'Sat-Sun: 9am - 10pm',
                    ],
                ],
                'is_active' => true,
                'is_premium' => false,
            ],
            [
                'name' => 'Home-2: Modern Food Startup',
                'slug' => 'home-2',
                'thumbnail_path' => 'design/Home-2.png',
                'structure_schema' => [],
                'default_content' => [
                    'business_name' => 'FreshStart',
                    'hero_title' => 'Healthy Meals for Busy Lives',
                    'hero_subtitle' => 'Chef-prepared, nutritionist-approved.',
                    'menu_highlights' => [
                        ['id' => 1, 'name' => 'Keto Bowl', 'description' => 'Low carb, high protein.', 'price' => '$14.99'],
                        ['id' => 2, 'name' => 'Vegan Salad', 'description' => 'Fresh seasonal veggies.', 'price' => '$12.99'],
                        ['id' => 3, 'name' => 'Grilled Salmon', 'description' => 'Rich in omega-3.', 'price' => '$18.99'],
                    ],
                ],
                'is_active' => true,
                'is_premium' => true,
            ],
            [
                'name' => 'Home-3: Burger Focused',
                'slug' => 'home-3',
                'thumbnail_path' => 'design/Home-3.png',
                'structure_schema' => [],
                'default_content' => [
                    'business_name' => 'BurgerKing Clone',
                    'hero_title' => 'Big. Juicy. Tasty.',
                ],
                'is_active' => true,
                'is_premium' => false,
            ],
            [
                'name' => 'Home-4: Promotional Heavy',
                'slug' => 'home-4',
                'thumbnail_path' => 'design/home-4.png',
                'structure_schema' => [],
                'default_content' => [
                    'business_name' => 'PromoFood',
                    'hero_title' => 'Mega Deal: 50% Off Everything',
                ],
                'is_active' => true,
                'is_premium' => false,
            ],
            [
                'name' => 'Home-5: Elegant Restaurant',
                'slug' => 'home-5',
                'thumbnail_path' => 'design/Home-5.png',
                'structure_schema' => [],
                'default_content' => [
                    'business_name' => 'L\'Elegance',
                    'hero_title' => 'Fine Dining Redefined',
                ],
                'is_active' => true,
                'is_premium' => true,
            ],
            [
                'name' => 'Home-6: Pizza Special',
                'slug' => 'home-6',
                'thumbnail_path' => 'design/Home-6.png',
                'structure_schema' => [],
                'default_content' => [
                    'business_name' => 'PizzaPalette',
                    'hero_title' => 'Slice of Heaven',
                ],
                'is_active' => true,
                'is_premium' => false,
            ],
            [
                'name' => 'Home-7: Light UI Food',
                'slug' => 'home-7',
                'thumbnail_path' => 'design/Home-7.png',
                'structure_schema' => [],
                'default_content' => [
                    'business_name' => 'LightBites',
                    'hero_title' => 'Simply Delicious',
                ],
                'is_active' => true,
                'is_premium' => false,
            ],
            [
                'name' => 'Home-8: Mobile-First App',
                'slug' => 'home-8',
                'thumbnail_path' => 'design/Home-8.png',
                'structure_schema' => [],
                'default_content' => [
                    'business_name' => 'AppEats',
                    'hero_title' => 'Your Favorite Food, Now in Your Pocket.',
                    'hero_subtitle' => 'The fastest way to order from top local restaurants. Real-time tracking, exclusive deals, and 24/7 support.',
                    'download_ios' => '#',
                    'download_android' => '#',
                    'app_icon' => 'https://cdn-icons-png.flaticon.com/512/732/732205.png',
                    'features' => [
                        ['title' => 'Live Tracking', 'desc' => 'Watch your food travel from the kitchen to your door in real-time.', 'icon' => 'fa-map-marker-alt'],
                        ['title' => 'No Minimum', 'desc' => 'Order as little as you want. We deliver anything, from a coffee to a banquet.', 'icon' => 'fa-shopping-basket'],
                        ['title' => 'Secure Pay', 'desc' => 'Pay with Apple Pay, Google Pay, or Credit Card instantly.', 'icon' => 'fa-credit-card'],
                    ],
                    'stats' => [
                        ['value' => '500+', 'label' => 'Restaurants'],
                        ['value' => '10k+', 'label' => 'Happy Eaters'],
                        ['value' => '15m', 'label' => 'Avg Delivery'],
                    ],
                    'screenshots' => [
                        'https://cdn.dribbble.com/users/914722/screenshots/15467331/media/0c822e1a384f55bb95925a2e4fe973c5.png',
                        'https://cdn.dribbble.com/users/914722/screenshots/16656247/media/1a90c0082725e4c60205562134db209e.png',
                        'https://cdn.dribbble.com/users/914722/screenshots/11559869/media/1e4839818816c56781254bf31bf94d23.png',
                    ],
                ],
                'is_active' => false,
                'is_premium' => false,
            ],
            [
                'name' => 'Home-9: Modern Marketplace',
                'slug' => 'home-9',
                'thumbnail_path' => 'design/Home-9.png',
                'structure_schema' => [],
                'default_content' => [
                    'business_name' => 'FoodMarket',
                    'hero_title' => 'Satisfy Your Cravings',
                    'hero_subtitle' => 'The best local food, delivered to your doorstep.',
                    'search_placeholder' => 'Enter your address or zip code',
                    'categories' => [
                        ['name' => 'Burgers', 'image' => 'https://cdn-icons-png.flaticon.com/512/3075/3075977.png'],
                        ['name' => 'Pizza', 'image' => 'https://cdn-icons-png.flaticon.com/512/1404/1404945.png'],
                        ['name' => 'Sushi', 'image' => 'https://cdn-icons-png.flaticon.com/512/2234/2234697.png'],
                        ['name' => 'Vegan', 'image' => 'https://cdn-icons-png.flaticon.com/512/2619/2619567.png'],
                        ['name' => 'Desserts', 'image' => 'https://cdn-icons-png.flaticon.com/512/3142/3142701.png'],
                        ['name' => 'Drinks', 'image' => 'https://cdn-icons-png.flaticon.com/512/3050/3050187.png'],
                    ],
                    'featured' => [
                        ['name' => 'Burger King', 'rating' => '4.8', 'time' => '15-25 min', 'image' => 'https://images.unsplash.com/photo-1571091718767-18b5b1457add?q=80&w=1000&auto=format&fit=crop'],
                        ['name' => 'Sushi Master', 'rating' => '4.9', 'time' => '30-45 min', 'image' => 'https://images.unsplash.com/photo-1579871494447-9811cf80d66c?q=80&w=1000&auto=format&fit=crop'],
                        ['name' => 'Pizza Hut', 'rating' => '4.5', 'time' => '20-30 min', 'image' => 'https://images.unsplash.com/photo-1604382354936-07c5d9983bd3?q=80&w=1000&auto=format&fit=crop'],
                    ],
                ],
                'is_active' => true,
                'is_premium' => true,
            ],
            [
                'name' => 'Home-10: Dark Luxury',
                'slug' => 'home-10',
                'thumbnail_path' => 'design/Home-10.png',
                'structure_schema' => [],
                'default_content' => [
                    'business_name' => 'Noir',
                    'hero_title' => 'Experience the Night',
                    'hero_subtitle' => 'A sensory journey through taste and shadow.',
                    'reservation_cta' => 'Secure Your Table',
                    'about_title' => 'The Art of Darkness',
                    'about_text' => 'Noir is not just a restaurant; it is a theatre of culinary excellence. Hidden from the bustle of the city, we invite you to disconnect and indulge.',
                    'featured_dishes' => [
                        ['name' => 'A5 Wagyu', 'desc' => 'Served with truffle reduction', 'price' => '$120', 'image' => 'https://images.unsplash.com/photo-1544025162-d7669d26560b?q=80&w=1000&auto=format&fit=crop'],
                        ['name' => 'Gold Leaf Sushi', 'desc' => 'Toro, Caviar, 24k Gold', 'price' => '$85', 'image' => 'https://images.unsplash.com/photo-1553621042-f6e147245754?q=80&w=1000&auto=format&fit=crop'],
                        ['name' => 'Midnight Truffle', 'desc' => 'House-made pasta, shaved black truffle', 'price' => '$65', 'image' => 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?q=80&w=1000&auto=format&fit=crop'],
                    ],
                ],
                'is_active' => true,
                'is_premium' => true,
            ],
            [
                'name' => 'Home-11: Shop Page',
                'slug' => 'home-11',
                'thumbnail_path' => 'design/shop.png',
                'structure_schema' => [],
                'default_content' => [
                    'business_name' => 'The Shop',
                    'hero_title' => 'Curated Essentials',
                    'hero_subtitle' => 'Hand-picked goods for the modern home.',
                    'categories' => ['All', 'Kitchen', 'Dining', 'Decor'],
                    'products' => [
                        ['name' => 'Ceramic Vase', 'price' => '$45.00', 'image' => 'https://images.unsplash.com/photo-1578749556935-ef887c471986?q=80&w=1000&auto=format&fit=crop'],
                        ['name' => 'Linen Napkins', 'price' => '$25.00', 'image' => 'https://images.unsplash.com/photo-1595160867490-333e70d4f40f?q=80&w=1000&auto=format&fit=crop'],
                        ['name' => 'Wooden Bowl', 'price' => '$35.00', 'image' => 'https://images.unsplash.com/photo-1603507119097-75e81d77a83d?q=80&w=1000&auto=format&fit=crop'],
                        ['name' => 'Cutlery Set', 'price' => '$80.00', 'image' => 'https://images.unsplash.com/photo-1584269614742-1e5f8f3c7e73?q=80&w=1000&auto=format&fit=crop'],
                    ],
                ],
                'is_active' => false,
                'is_premium' => false,
            ],
            [
                'name' => 'Home-12: Menu Page',
                'slug' => 'home-12',
                'thumbnail_path' => 'design/menu.png',
                'structure_schema' => [],
                'default_content' => [
                    'business_name' => 'Menu',
                    'hero_title' => 'Our Menu',
                    'hero_subtitle' => 'Fresh ingredients, cooked with passion.',
                    'sections' => [
                        [
                            'title' => 'Starters',
                            'items' => [
                                ['name' => 'Bruschetta', 'desc' => 'Toasted bread, tomato, basil', 'price' => '$12'],
                                ['name' => 'Calamari', 'desc' => 'Fried squid with lemon mayo', 'price' => '$16'],
                                ['name' => 'Soup of the Day', 'desc' => 'Ask your server', 'price' => '$9'],
                            ],
                        ],
                        [
                            'title' => 'Mains',
                            'items' => [
                                ['name' => 'Grilled Salmon', 'desc' => 'Asparagus, hollandaise sauce', 'price' => '$28'],
                                ['name' => 'Ribeye Steak', 'desc' => '12oz, served with fries', 'price' => '$34'],
                                ['name' => 'Wild Mushroom Risotto', 'desc' => 'Parmesan crisp, truffle oil', 'price' => '$24'],
                            ],
                        ],
                        [
                            'title' => 'Desserts',
                            'items' => [
                                ['name' => 'Tiramisu', 'desc' => 'Classic Italian recipe', 'price' => '$10'],
                                ['name' => 'Cheesecake', 'desc' => 'Berry compote', 'price' => '$11'],
                            ],
                        ],
                    ],
                ],
                'is_active' => true,
                'is_premium' => false,
            ],

        ];

        foreach ($templates as $template) {
            WebsiteTemplate::updateOrCreate(
                ['slug' => $template['slug']],
                $template
            );
        }
    }
}
