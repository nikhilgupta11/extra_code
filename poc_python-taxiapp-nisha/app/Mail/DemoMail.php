<?php
  
namespace App\Mail;
  
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
  
class DemoMail extends Mailable
{
    use Queueable, SerializesModels;
  
    public $mail_data;
    
  
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mail_data)
    {
        $this->mail_data = $mail_data;
       
    }
  
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
       
        if(isset($this->mail_data['name'])&& $this->mail_data['name']== "reset_password"){
           

            return $this->subject($this->mail_data['subject'])
                    ->view('email.resetpassword');
        }else if(isset($this->mail_data['name'])&& $this->mail_data['name']== "verify_account"){
           

            return $this->subject($this->mail_data['subject'])
                    ->view('email.verifyaccount');
        }else if(isset($this->mail_data['name'])&& $this->mail_data['name']== "forget_password"){
            return $this->subject($this->mail_data['subject'])
                    ->view('email.forgetpassword');
        }
        else if(isset($this->mail_data['name'])&& $this->mail_data['name']== "front_contact_mail"){
            return $this->subject($this->mail_data['subject'])
                    ->view('email.contact_us');
        }
        else if(isset($this->mail_data['name'])&& $this->mail_data['name']== "admin_contact_mail"){
            return $this->subject($this->mail_data['subject'])
                    ->view('email.contact_us_admin');
        }
        else{
        return $this->subject($this->mail_data['subject'])
                    ->view('email.email');
        }
    }
}