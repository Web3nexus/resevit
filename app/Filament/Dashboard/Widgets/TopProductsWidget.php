<?php

namespace App\Filament\Dashboard\Widgets;

use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class TopProductsWidget extends BaseWidget
{
    protected static ?string $heading = 'Most Selling Products';

    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 1;

    public function table(Table $table): Table
    {
        // Get all paid orders and extract items
        $orders = Order::query()
            ->where('payment_status', 'paid')
            ->whereNotNull('items')
            ->get();

        // Aggregate product data
        $products = collect();
        foreach ($orders as $order) {
            if (is_array($order->items)) {
                foreach ($order->items as $item) {
                    $menuItemId = $item['menu_item_id'] ?? null;
                    if (!$menuItemId) {
                        continue;
                    }

                    $existing = $products->firstWhere('id', $menuItemId);
                    if ($existing) {
                        $existing['units_sold'] += $item['quantity'];
                        $existing['revenue'] += $item['price'] * $item['quantity'];
                    } else {
                        $products->push([
                            'id' => $menuItemId,
                            'name' => $item['name'],
                            'image' => $item['image'] ?? null,
                            'units_sold' => $item['quantity'],
                            'revenue' => $item['price'] * $item['quantity'],
                        ]);
                    }
                }
            }
        }

        // Sort by revenue and take top 10
        $topProducts = $products->sortByDesc('revenue')->take(10)->values();

        return $table
            ->query(
                // Use a fake query builder with our aggregated data
                \App\Models\MenuItem::query()->whereIn('id', $topProducts->pluck('id'))
            )
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Image')
                    ->circular()
                    ->defaultImageUrl('https://via.placeholder.com/50'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Product')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('units_sold')
                    ->label('Units Sold')
                    ->badge()
                    ->color('success')
                    ->formatStateUsing(function ($record) use ($topProducts) {
                        $product = $topProducts->firstWhere('id', $record->id);

                        return $product['units_sold'] ?? 0;
                    }),
                Tables\Columns\TextColumn::make('revenue')
                    ->label('Revenue')
                    ->money('USD')
                    ->sortable()
                    ->formatStateUsing(function ($record) use ($topProducts) {
                        $product = $topProducts->firstWhere('id', $record->id);

                        return $product['revenue'] ?? 0;
                    }),
            ])
            ->paginated(false);
    }
}
