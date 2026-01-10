<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'use_layout',
        'email_title',
        'email_badge',
    ];

    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
        'use_layout' => 'boolean',
    ];

    public function smtpConfiguration()
    {
        return $this->belongsTo(SmtpConfiguration::class);
    }

    /**
     * Render the template with provided data
     */
    public function render(array $data): array
    {
        $subject = $this->replaceVariables($this->subject, $data);
        $bodyHtml = $this->replaceVariables($this->body_html, $data);
        $bodyText = $this->body_text ? $this->replaceVariables($this->body_text, $data) : null;

        // Wrap in branded layout if enabled
        if ($this->use_layout) {
            $bodyHtml = $this->wrapInLayout($bodyHtml, $data);
        }

        return [
            'subject' => $subject,
            'body_html' => $bodyHtml,
            'body_text' => $bodyText,
        ];
    }

    /**
     * Wrap content in the branded email layout
     */
    protected function wrapInLayout(string $content, array $data): string
    {
        $title = $this->email_title ? $this->replaceVariables($this->email_title, $data) : null;
        $badge = $this->email_badge ? $this->replaceVariables($this->email_badge, $data) : null;

        return view('emails.layout', [
            'body' => $content,
            'title' => $title,
            'badge' => $badge,
            'subject' => $this->replaceVariables($this->subject, $data),
        ])->render();
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
