<?php

namespace App\Mail;

use App\Models\Template;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Blade;

class NotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $name;
    protected $title;
    protected $template;
    protected $dataOptions;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $name, string $title, Template $template, array $dataOptions = [])
    {
        $this->name = $name;
        $this->title = $title;
        $this->template = $template;
        $this->dataOptions = $dataOptions;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->title)
            ->from(config('mail.from.address'), config('app.name'))
            ->view('email.notification', [
                "title" => $this->title,
                "user" => $this->name,
                "content" => Blade::render($this->template->template, $this->dataOptions),
                "link" => $this->dataOptions["link"] ?? ''
            ]);
    }
}
