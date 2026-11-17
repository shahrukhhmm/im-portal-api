<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use SendGrid\Mail\Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $userName;
    private $userEmail;
    private $token;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userName, $userEmail, $token)
    {
        $this->userName = $userName;
        $this->userEmail = $userEmail;
        $this->token = $token;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $apiKey = getenv('SENDGRID_API_KEY');
        $sendgrid = new \SendGrid($apiKey);
        $linkUrl = getenv('FRONT_APP_URL')."/auth/forget-password/".$this->token;

        $email = new Mail();
        $email->setFrom(getenv('MAIL_FROM_ADDRESS'), 'HISENSE Portal');
        $email->setSubject("HISENSE Password Reset Request");
        $email->addTo($this->userEmail);
        $email->addContent(
            "text/html", "<b>Hello ".$this->userName.", </b><br/><br/>
            Please click the link below to confirm and set a new password.<br/><br/>
            Set a new password <a href='".$linkUrl."'>Please click here</a><br/>"
        );

        try {
            $response = $sendgrid->send($email);
//            print $response->statusCode() . "\n";
//            print_r($response->headers());
//            print $response->body() . "\n";
        } catch (\Exception $e) {
            echo 'Caught exception: '.  $e->getMessage(). "\n";
        }
    }
}
