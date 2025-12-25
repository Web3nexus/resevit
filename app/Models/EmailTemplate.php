<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $connection = 'landlord';
    protected $fillable = [
        'key',
        'name',
        'subject',
        'body_html',
        'body_text',
        'variables',
        'is_active',
    ];

    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Render the template with provided data
     */
    public function render(array $data): array
    {
        $subject = $this->replaceVariables($this->subject, $data);
        $bodyHtml = $this->replaceVariables($this->body_html, $data);
        $bodyText = $this->body_text ? $this->replaceVariables($this->body_text, $data) : null;

        return [
            'subject' => $subject,
            'body_html' => $bodyHtml,
            'body_text' => $bodyText,
        ];
    }

    /**
     * Replace template variables with actual data
     */
    protected function replaceVariables(string $content, array $data): string
    {
        foreach ($data as $key => $value) {
            $content = str_replace('{{' . $key . '}}', $value, $content);
        }

        return $content;
    }
}
