<?php namespace Ankh\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;

abstract class Job implements SelfHandling, ShouldQueue {
    use InteractsWithQueue, SerializesModels;
    use Queueable;
}
