<?php

namespace App\Filament\Dashboard\Pages;

use App\Services\AI\ContentGeneratorService;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Actions\Action;

class AiImageGenerator extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-photo';
    protected static string|\UnitEnum|null $navigationGroup = 'Marketing';
    protected static ?string $navigationLabel = 'AI Image Studio';
    protected static ?string $title = 'AI Image Studio';

    protected string $view = 'filament.dashboard.pages.ai-image-generator';

    public static function canViewAny(): bool
    {
        return has_feature('ai_generator');
    }

    public ?array $data = [];
    public ?string $generatedImageUrl = null;
    public bool $isGenerating = false;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Select::make('style')
                    ->label('Image Style')
                    ->options([
                        'Photographic' => 'Photorealistic',
                        'Cinematic' => 'Cinematic',
                        'Digital Art' => 'Digital Art',
                        '3D Render' => '3D Render',
                        'Minimalist' => 'Minimalist',
                        'Oil Painting' => 'Oil Painting',
                        'Cyberpunk' => 'Cyberpunk',
                        'Vintage' => 'Vintage / Retro',
                    ])
                    ->default('Photographic')
                    ->required()
                    ->columnSpan(1),

                Textarea::make('prompt')
                    ->label('Describe your image')
                    ->placeholder('e.g., A delicious gourmet burger on a rustic wooden table with soft lighting...')
                    ->rows(4)
                    ->required()
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    public function refinePrompt(ContentGeneratorService $ai): void
    {
        $prompt = $this->data['prompt'] ?? '';

        if (empty($prompt)) {
            Notification::make()->title('Please enter a prompt first')->warning()->send();
            return;
        }

        $style = $this->data['style'] ?? 'Photographic';

        try {
            $refined = $ai->refineImagePrompt($prompt, $style);

            $this->data['prompt'] = $refined;
            $this->form->fill($this->data);

            Notification::make()->title('Prompt optimized!')->success()->send();

        } catch (\Exception $e) {
            Notification::make()->title('Failed to refine prompt')->danger()->send();
        }
    }

    public function generateImage(ContentGeneratorService $ai): void
    {
        $this->validate();

        $this->isGenerating = true;

        try {
            $prompt = $this->data['prompt'];
            // Append style to ensuring it's enforced even if not in the text
            $fullPrompt = $prompt . ", Style: " . ($this->data['style'] ?? 'Photographic');

            $url = $ai->generateImage($fullPrompt);

            if ($url) {
                $this->generatedImageUrl = $url;
                Notification::make()->title('Image generated successfully!')->success()->send();
            } else {
                Notification::make()->title('Failed to generate image. Please try again.')->danger()->send();
            }

        } catch (\Exception $e) {
            Notification::make()->title('Error: ' . $e->getMessage())->danger()->send();
        } finally {
            $this->isGenerating = false;
        }
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('refine')
                ->label('Refine Prompt with AI')
                ->icon('heroicon-o-sparkles')
                ->color('info')
                ->action('refinePrompt'),

            Action::make('generate')
                ->label('Generate Image')
                ->icon('heroicon-o-cpu-chip')
                ->action('generateImage'),
        ];
    }
}
