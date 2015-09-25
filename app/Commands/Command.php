<?php namespace Ankh\Commands;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class Command implements SelfHandling, ShouldQueue {
	use InteractsWithQueue, SerializesModels;

}
