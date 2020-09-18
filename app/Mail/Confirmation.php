<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

use Ical\Ical;
use App\Booking;
use Hashids\Hashids;

class Confirmation extends Mailable
{
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Booking $booking, $eventCreator=true)
    {
        $this->booking = $booking;
        $this->eventCreator = $eventCreator;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->generateIcs();

        // https://github.com/laravel/lumen/blob/60776a0d763ac8a255ac14008e4edda25d2224b1/.env.example
        // https://laracasts.com/discuss/channels/lumen/lumen-52-mail-not-working
        $hostname = env('SILID_HOSTNAME');
        $hashids = new Hashids(env('APP_KEY'), config('booking.hashes.VIEW_HASH_LENGTH'));

        $booking_view_link = "$hostname/booking/view/" . $hashids->encode($this->booking->id);
        $this->view('emails.cerberus-responsive')
             ->subject( env('COMPANY_NAME') . ' Room Booking Reference Code: ' . str_pad($this->booking->id, 10, "0", STR_PAD_LEFT))
             ->with([
                'booking_view_link' => $booking_view_link,
                'purpose' => $this->booking->purpose,
                'participants' => $this->booking->participants,
                'booking_reserved_by' => $this->booking->reserved_by,
                'booking_room_id' => $this->booking->room->id,
                'booking_room_name' => $this->booking->room->name,
                'booking_room_description' => $this->booking->room->description,
                'booking_start' => date('F d, Y @H:i A', strtotime($this->booking->start)),
                'booking_end' => date('F d, Y @H:i A', strtotime($this->booking->end)),
                'eventCreator' => $this->eventCreator
              ])
             ->attach("calendar-files/{$this->booking->id}.ics");
        \Storage::delete("calendar-files/{$this->booking->id}.ics");
    }

    private function generateIcs() {
      try {
        $ical = (new Ical())->setAddress($this->booking->room->name)
                ->setDateStart(new \DateTime($this->booking->start))
                ->setDateEnd(new \DateTime($this->booking->end))
                ->setDescription($this->booking->room->description)
                ->setSummary($this->booking->purpose)
                ->setAlarm(900);
                // Add organizer
                // ORGANIZER;CN="Sally Example":mailto:sally@example.com

        \Storage::disk('calendar-files')->put("{$this->booking->id}.ics", $ical->getICAL());
      } catch (\Exception $exc) {
        \Log::error($exc->getMessage());
      }
    }
}
