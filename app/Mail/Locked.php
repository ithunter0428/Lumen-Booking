<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

use App\Booking;
use Hashids\Hashids;

class Locked extends Mailable
{
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // https://github.com/laravel/lumen/blob/60776a0d763ac8a255ac14008e4edda25d2224b1/.env.example
        // https://laracasts.com/discuss/channels/lumen/lumen-52-mail-not-working
        $hostname = env('SILID_HOSTNAME');
        $hashids = new Hashids(env('APP_KEY'), config('booking.hashes.CONFIRMATION_HASH_LENGTH'));

        $confirmation_id = $hashids->encode($this->booking->id);

        $confirmation_link = "$hostname/booking/confirmation/$confirmation_id";
        return $this->view('emails.confirmation')
                    ->subject('Silid Room Booking - Timing locked and reserved!')
                    ->with([
                        'confirmation_link' => $confirmation_link,
                        'booking_room_name' => $this->booking->room->name,
                        'booking_room_description' => $this->booking->room->description,
                        'booking_start' => date('F d, Y @H:i A', strtotime($this->booking->start)),
                        'booking_end' => date('F d, Y @H:i A', strtotime($this->booking->end)),
                    ]);
    }
}
