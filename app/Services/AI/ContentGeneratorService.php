<?php

namespace App\Services\AI;

class ContentGeneratorService extends BaseAiService
{
    /**
     * Generate content based on a prompt and type.
     * Respects tenant context and uses appropriate model.
     */
    public function generate(string $prompt, string $type, bool $premium = false): string
    {
        if (!$this->ensureClient()) {
            return $this->getFallbackContent($prompt, $type);
        }

        try {
            $model = $premium ? $this->settings->premium_model : $this->settings->chat_model;

            $systemPrompt = $this->getSystemPrompt($type);

            $response = $this->client->chat()->create([
                'model' => $model,
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.7,
                'max_tokens' => 500,
            ]);

            return $response->choices[0]->message->content ?? $this->getFallbackContent($prompt, $type);

        } catch (\Exception $e) {
            \Log::error('AI Content Generation failed: ' . $e->getMessage());
            return $this->getFallbackContent($prompt, $type);
        }
    }

    /**
     * Get tenant-scoped system prompt
     */
    protected function getSystemPrompt(string $type): string
    {
        $tenantName = tenant() ? tenant()->name : 'the restaurant';

        $basePrompt = "You are a professional marketing assistant for {$tenantName}. ";
        $basePrompt .= "You must ONLY use information about this specific restaurant. ";
        $basePrompt .= "Never reference other restaurants or make assumptions about data you don't have. ";

        return $basePrompt . match ($type) {
            'email' => "Generate engaging email marketing content with a clear subject line and compelling body. Be professional yet friendly.",
            'sms' => "Generate concise SMS marketing messages (max 160 characters). Be direct and include a clear call-to-action.",
            'social' => "Generate engaging social media posts. Use emojis appropriately and include relevant hashtags.",
            default => "Generate professional marketing content.",
        };
    }

    /**
     * Fallback content when AI is unavailable
     */
    protected function getFallbackContent(string $prompt, string $type): string
    {
        $prefix = match ($type) {
            'email' => "Subject: Exciting News!\n\nDear Valued Customer,\n\n",
            'sms' => "[Promo] ",
            'social' => "Hey Foodies! 🍔 ",
            default => "",
        };

        return $prefix . "This is AI-generated content based on your prompt: '{$prompt}'. \n\nWe are excited to announce special offers just for you. Visit us today!\n\n(Note: AI service is currently unavailable. Please configure API keys in Super Admin panel.)";
    }

    /**
     * Generate an image using DALL-E based on a text prompt
     */
    public function generateImage(string $prompt): ?string
    {
        if (!$this->client || !$this->settings) {
            return null;
        }

        try {
            $response = $this->client->images()->create([
                'model' => $this->settings->image_model ?? 'dall-e-3',
                'prompt' => $prompt,
                'n' => 1,
                'size' => '1024x1024',
                'response_format' => 'url',
            ]);

            // Return the URL of the generated image
            return $response->data[0]->url ?? null;

        } catch (\Exception $e) {
            \Log::error('AI Image Generation failed: ' . $e->getMessage());
            return null;
        }
    }
    /**
     * Refine a user's prompt to be optimized for DALL-E image generation.
     */
    public function refineImagePrompt(string $prompt, string $style = 'Photographic'): string
    {
        if (!$this->client) {
            return $prompt;
        }

        try {
            $systemPrompt = "You are an expert prompt engineer for DALL-E 3. ";
            $systemPrompt .= "Your task is to take a user's simple idea and rewrite it into a highly detailed, descriptive prompt that will generate a perfect image. ";
            $systemPrompt .= "Focus on lighting, composition, texture, and mood. ";
            $systemPrompt .= "Ensure the style matches: '{$style}'. ";
            $systemPrompt .= "Return ONLY the refined prompt text, nothing else.";

            $response = $this->client->chat()->create([
                'model' => $this->settings->chat_model ?? 'gpt-4',
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.7,
            ]);

            return $response->choices[0]->message->content ?? $prompt;

        } catch (\Exception $e) {
            \Log::error('AI Prompt Refinement failed: ' . $e->getMessage());
            return $prompt;
        }
    }
}
