<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Settings;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MaintainUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:maintain';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes unactive users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->composeEmail();
    }



    // ========== [ Compose Email ] ================
    public function composeEmail() {
        require base_path("vendor/autoload.php");
        $mail = new PHPMailer(true);     // Passing `true` enables exceptions

        try {

            // Email server settings
            $mail->SMTPDebug = 2;
            $mail->isSMTP();
            $mail->Host = 'ssl://smtp.mail.com';             //  smtp host
            $mail->SMTPAuth = true;
            $mail->Username = 'soptunnan@email.com';   //  sender username
            $mail->Password = 'vykfof-debhy6-Jijqav';       // sender password
            $mail->SMTPSecure = 'ssl';                  // encryption - ssl/tls
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            $mail->Port = 587;                          // port - 587/465

            $mail->setFrom('soptunnan@email.com', 'Kenneth');
            $mail->addAddress('andreas@amedia.nu');

            //$mail->addReplyTo('sender-reply-email', 'sender-reply-name');


            $mail->isHTML(false);                // Set email content format to HTML

            $mail->Subject = 'test';
            $mail->Body    = 'hej';

            $mail->AltBody = 'plain text version of email body';

            if( !$mail->send() ) {
                echo "Email not sent. " . $mail->ErrorInfo;
            }
            
            else {
                echo "Email has been sent.";
            }

        } catch (Exception $e) {
             echo $e;
        }
    }
}
