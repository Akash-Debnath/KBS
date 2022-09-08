<?php

namespace App\Jobs;

use App\Helpers\Helper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $content ;
    private $users ;
    private $type ;
    private $contentType ;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($content, $users, $type, $contentType)
    {
        //
        $this->content = $content ;
        $this->users = $users ;
        $this->type =  $type ;
        $this->contentType =  $contentType ;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        switch($this->contentType) {
            case 'article':
                Helper::sendArticleNotification($this->content, $this->users, $this->type);
                break;
            case 'faq':
                Helper::sendFaqNotification($this->content, $this->users, $this->type);
                break;
        }
    }
}
