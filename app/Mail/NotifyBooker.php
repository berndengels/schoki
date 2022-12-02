<?php

namespace App\Mail;

use App\Models\Message;
use App\Models\MusicStyle;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifyBooker extends Mailable
{
    use Queueable, SerializesModels;

	/**
	 * @var Message
	 */
	public $message;
	protected $musicStyle;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( Message $message )
    {
        $this->message = $message;
		$this->musicStyle = MusicStyle::where('id', $this->message->music_style_id)->pluck('name')->first();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
			->view('mail.notifyBooker')
			->with([
				'musicStyle'	=> $this->musicStyle,
				'name'			=> $this->message->name,
				'email'			=> $this->message->email,
				'text'			=> $this->message->message,
				'created_at'	=> $this->message->created_at,
			])
			->subject('Band Anfrage von: ' . $this->message->name);
    }
}
