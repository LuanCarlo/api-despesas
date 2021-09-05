<?php

namespace App\Listeners;

use App\Events\EventEmailDespesa;
use App\Mail\DespesaCadastrada;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class EmailEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  EventEmailDespesa  $event
     * @return void
     */
    public function handle(EventEmailDespesa $event)
    {
        Mail::to($event->user)->send(new DespesaCadastrada($event->user));
    }
}
