<?php


namespace App\Helpers;

use Auth;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\PHPMailer;
use App\Notifications\NewFaqNotify;
use App\Notifications\DeleteFaqNotify;
use App\Notifications\UpdateFaqNotify;
use App\Notifications\NewArticleNotify;
use Illuminate\Notifications\Notifiable;
use App\Notifications\DeleteArticleNotify;
use App\Notifications\UpdateArticleNotify;
use Illuminate\Support\Facades\Notification;
use Intervention\Image\Facades\Image as Image;

class Helper
{

    public static function idGenarator()
    {
        return (time() + rand(1000, 9000)).(rand(1000,9000) + rand(1000,9000));
    }

    public static function slugify($value)
    {

        return strtolower(preg_replace("/[^a-zA-Z0-9]+/", "-", $value));

    }

    public static function slugifyUsername($first_name,$last_name)
    {

        return strtolower(preg_replace("/[^a-zA-Z0-9]+/", "-", $first_name.'_'.$last_name));

    }

    public static function slugifyFullName($first_name,$last_name)
    {

        return strtolower(preg_replace("/[^a-zA-Z0-9]+/", "-", $first_name.' '.$last_name));

    }

    /**
     * @param $url
     * @param $image
     * @return string
     */
    public static function base64ProfileImageThumbUpload($url, $image)
    {
        if (!file_exists(public_path($url))) {
            mkdir(public_path($url), 777, true);
        }
        $filename = date('Ymdhis')."-".strtolower(preg_replace("/[^a-zA-Z0-9.]+/", "-", $image->getClientOriginalName()));
        Image::make($image->getRealPath())->save($url.$filename);
        return url($url.$filename);
//        $fileName = time () .".jpg";
//        $dir = "media/".$url."/".Auth::user()->id."/thumbs/";
//
//        if (!file_exists(public_path ($dir))) {
//
//            mkdir(public_path ($dir), 0755, true);
//
//        }
//
//        $small_image = Image::make($image);
//        $thumbImage = $small_image->resize(null, 250, function ($e) {
//
//            $e->aspectRatio();
//
//        });
//
//        $thumbImage->save(public_path($dir. $fileName ));
//
//        return url('/').'/'.$dir.$fileName;
    }

    public static function videoUpload($url, $video)
    {
        $file = $video;
        $fileName = time().'.'.$file->getClientOriginalExtension();
        $dir = "media/".$url."/";

        if (!file_exists(public_path ($dir))) {

            mkdir(public_path ($dir), 0755, true);
        }

        $file->move(public_path($dir), $fileName );

        return url('/').'/'.$dir.$fileName;

    }


    /**
     * @param $url
     * @param $image
     * @return string
     */
    
     public static function base64ImageUpload($url, $image)
    {
        $fileName = uniqid().'.'.$image->getClientOriginalExtension();
        $dir = "media/".$url."/";

        if (!file_exists(public_path ($dir))) {

            mkdir(public_path ($dir), 0755, true);

        }

        $small_image = Image::make($image);
        $thumbImage = $small_image->resize(350, null, function ($e) {

            $e->aspectRatio();

        });

        $thumbImage->save(public_path($dir. $fileName ));

        return url('/').'/'.$dir.$fileName;
    }

    public static function base64ContentImageUpload($url, $image)
    {
        $fileName = uniqid().'.'.$image->getClientOriginalExtension();
        $dir = "media/".$url."/";

        if (!file_exists(public_path ($dir))) {

            mkdir(public_path ($dir), 0755, true);

        }

        $small_image = Image::make($image);
        $thumbImage = $small_image;

        $thumbImage->save(public_path($dir. $fileName ));

        return url('/').'/'.$dir.$fileName;
    }

    public static function fileUpload($url, $file)
    {
        //$fileName = $file->getClientOriginalName().'_'.time(). '.' . $file->getClientOriginalExtension();
        $fileName = time().'_'.$file->getClientOriginalName();

        $dir = "media/" . $url . "/";

        if (!file_exists(public_path($dir))) {

            mkdir(public_path($dir), 0755, true);

        }

        $file->move(public_path($dir), $fileName);

        return url('/') . '/' . $dir . $fileName;

    }

    public static function base64PageLogoUpload($url, $image)
    {
        $file = $image;
        $fileName = strtolower(uniqid().'.'.$image->getClientOriginalExtension());
        $dir = "media/".$url."/";

        if (!file_exists(public_path ($dir))) {

            mkdir(public_path ($dir), 0755, true);
        }

        $file->move(public_path($dir), $fileName );

        return url('/').'/'.$dir.$fileName;
    }

    public static function sendArticleNotification($article, $users, $type) {


        $roles = [];
        $contents = $article->contents;
        if(!empty( $contents)) {
            foreach($contents  as $content) {
                $roleIds = explode(',',$content->role_id);
                 foreach($roleIds as $roleId) {
                     if(!in_array($roleId, $roles)) {
                         array_push($roles, $roleId);
                     }
                 }
             }
            
             foreach($users as $user) {
                if(in_array($user->userRole->role_id, $roles)) {

                    switch($type) {
                        case "add":
                            // $user->notify(new NewArticleNotify($article, $user));
                            $msg = "A new article has been added to your KBS portal. Click the bellow link to see more details. ";
                            self::sendEmail($user, $article, 'New Article Available', $msg);
                            break;
                        case "update": 
                            // $user->notify(new UpdateArticleNotify($article, $user));
                            $msg = "An article has been updated to your KBS portal. Click the bellow link to see more details. ";
                            self::sendEmail($user, $article, 'Article Updated', $msg);
                            break;
                        case "delete": 
                            // $user->notify(new DeleteArticleNotify($article, $user));
                            $msg = "An article has been deleted from your KBS portal. Click the bellow link to see more details. ";
                            self::sendEmail($user, $article, 'Article Deleted', $msg);  
                            break;         
                    }
                }
            }
        }
        return null; 
    }
    public static function sendFaqNotification($faq, $users, $type) {


        $roles = [];
        $contents = $faq->contents;
        if(!empty( $contents)) {
            foreach($contents  as $content) {
                $roleIds = explode(',',$content->role_id);
                 foreach($roleIds as $roleId) {
                     if(!in_array($roleId, $roles)) {
                         array_push($roles, $roleId);
                     }
                 }
             }
            
             foreach($users as $user) {
                if(in_array($user->userRole->role_id, $roles)) {

                    switch($type) {
                        case "add":
                            // $user->notify(new NewFaqNotify($faq, $user));
                            $msg = "A new faq has been added to your KBS portal. Click the bellow link to see more details. ";
                            self::sendEmail($user, $faq, 'New Faq Available', $msg);
                            break;
                        case "update": 
                            // $user->notify(new UpdateFaqNotify($faq, $user));
                            $msg = "A faq has been updated to your KBS portal. Click the bellow link to see more details. ";
                            self::sendEmail($user, $faq, 'Faq Updated', $msg);
                            break;
                        case "delete": 
                            // $user->notify(new DeleteFaqNotify($faq, $user));
                            $msg = "A faq has been deleted from your KBS portal. Click the bellow link to see more details. ";
                            self::sendEmail($user, $faq, 'Faq Deleted', $msg);
                            break;         
                    }
                }
            }
        }
        return null;  
    }

    public static function calculateResult($totalMarks, $numberOfQuestions, $resultData) {
        $score = 0;
        $numberOfCorrectAnswer = 0;
        $perQuestionmark = $totalMarks/$numberOfQuestions;
        foreach($resultData as $data) {
         if($data->question->f_default_value == $data->answer)   
            $numberOfCorrectAnswer++;
        }
        return  $score = $numberOfCorrectAnswer*$perQuestionmark;
    }

    public static function getClientIpAddress() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    // Composing and sending Email

    public static function sendEmail($user, $content, $subject, $message) {
        $emailData = DB::table('email_settings')->first();

        require base_path("vendor/autoload.php");
        $mail = new PHPMailer(true);     // Passing `true` enables exceptions

        if($emailData) {
            try {

                // Email server settings
                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );
                $mail->isSMTP();
                $mail->SMTPDebug = 0;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->AuthType = 'LOGIN';
              
                $mail->Host = $emailData->host; 
                $mail->SMTPAuth = true;
                $mail->Username =  $emailData->username;   //  sender username
                $mail->Password = $emailData->password;       // sender password
                $mail->Port = 25;                          // port - 587/465
    
                $mail->setFrom($emailData->primary_email, 'Knowledge Base Systems');
                $mail->addAddress($user->email);
                $mail->addReplyTo($emailData->primary_email, 'Knowledge Base Systems');
    
                $mail->isHTML(true);                // Set email content format to HTML
    
                $mail->Subject =  $subject;
                $mail->Body    =self::getEmailBody($user, $content, $message);
                
                // $mail->AltBody = plain text version of email body;
    
                if( !$mail->send() ) {
                    return $mail->ErrorInfo; 
                }
                
                else {
                    return "Email has been sent";
                }
    
            } catch (Exception $e) {
                return "Message could not be sent.";
            }
        }
    }

    public static function getEmailBody($user, $content, $message) {
        return  "<div
        style='box-sizing:border-box;background-color:#ffffff;color:#718096;height:100%;line-height:1.4;margin:0;padding:0;width:100%!important'>
        <table width='100%' cellpadding='0' cellspacing='0'
           style='box-sizing:border-box;background-color:#edf2f7;margin:0;padding:0;width:100%'>
           <tbody>
              <tr>
                 <td align='center'
                    style='box-sizing:border-box;'>
                    <table width='100%
                       ' cellpadding='0' cellspacing='0'
                       style='box-sizing:border-box;margin:0;padding:0;width:100%'>
                       <tbody>
                          <tr>
                             <td
                                style='box-sizing:border-box;padding:25px 0;text-align:center'>
                                <a 
                                   style='box-sizing:border-box;background-color:#1a621d;line-height:1;color:#ffffff;font-size:22px;font-weight:500;text-decoration:none;width:800px;margin:auto;padding:10px;border-radius:5px;display:inline-block'
                                   target='_blank'>
                                Knowledge Base Systems (KBS)
                                </a>
                             </td>
                          </tr>
                          <tr>
                             <td width='100%' cellpadding='0' cellspacing='0'
                                style='box-sizing:border-box;background-color:#edf2f7;border-bottom:1px solid #edf2f7;border-top:1px solid #edf2f7;margin:0;padding:0;width:100%'>
                                <table align='center' width='570' cellpadding='0' cellspacing='0'
                                   style='box-sizing:border-box;background-color:#ffffff;border:1px solid #ddd;border-radius:5px;margin:0 auto;padding:0;width:800px'>
                                   <tbody>
                                      <tr>
                                         <td
                                            style='box-sizing:border-box;max-width:100vw;padding:32px'>
                                            <span
                                               >
                                               <h1
                                                  style='box-sizing:border-box;font-size:18px;font-weight:bold;margin-top:0;text-align:left;color:green'>Hello {$user->first_name},</h1>
                                               
                                               <p
                                                  style='box-sizing:border-box;font-size:16px;line-height:1.5em;margin-top:0;text-align:left;color:#000000'> {$message}</p>
                                                   <p><strong>Title: </strong> {$content->en_title}</p>
                                            </span>
                                            <span>
                                               
                                               <table align='center' width='100%' cellpadding='0' cellspacing='0'
                                                  style='box-sizing:border-box;margin:30px auto;padding:0;text-align:center;width:100%'>
                                                  <tbody>
                                                     <tr>
                                                        <td align='center'
                                                           style='box-sizing:border-box;'>
                                                           <table width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation'
                                                              style='box-sizing:border-box;'>
                                                              <tbody>
                                                                 <tr>
                                                                    <td align='center'
                                                                       style='box-sizing:border-box;'>
                                                                       <table border='0' cellpadding='0' cellspacing='0' role='presentation'
                                                                          style='box-sizing:border-box;'>
                                                                          <tbody>
                                                                             <tr>
                                                                                <td
                                                                                   style='box-sizing:border-box;'>
                                                                                   <a href='https://pms.gplex.com/tasks' rel='noopener'
                                                                                      style='box-sizing:border-box;border-radius:4px;display:inline-block;overflow:hidden;text-decoration:none;background-color:#1a621d;color:#ffffff;padding:10px 20px;font-size:16px;line-height:1;font-weight:400'
                                                                                      target='_blank'>Click here</a>
                                                                                </td>
                                                                             </tr>
                                                                          </tbody>
                                                                       </table>
                                                                    </td>
                                                                 </tr>
                                                              </tbody>
                                                           </table>
                                                        </td>
                                                     </tr>
                                                  </tbody>
                                               </table>
                                               <p
                                                  style='box-sizing:border-box;font-size:16px;line-height:1.5em;margin-top:0;text-align:left;color:#000000'>Regards,<br>
                                                  KBS
                                               </p>
                                               <table width='100%' cellpadding='0' cellspacing='0'
                                                  style='box-sizing:border-box;border-top:1px solid #e8e5ef;margin-top:25px;padding-top:25px'>
                                                  <tbody>
                                                     <tr>
                                                        <td
                                                           style='box-sizing:border-box;'>
                                                           <p
                                                              style='box-sizing:border-box;line-height:1.5em;margin-top:0;text-align:left;color:#000000;font-size:14px'>If you’re having trouble clicking the 'Click here' button, copy and paste the URL below
                                                              into your web browser: <span
                                                                 style='box-sizing:border-box;word-break:break-all'><a
                                                                 href='https://kbs.gplex.com/'
                                                                 style='box-sizing:border-box;color:#3869d4'
                                                                 target='_blank'
                                                                 >https://kbs.gplex.com/</a></span>
                                                           </p>
                                                        </td>
                                                     </tr>
                                                  </tbody>
                                               </table>
                                            </span>
                                         </td>
                                      </tr>
                                   </tbody>
                                </table>
                             </td>
                          </tr>
                          <tr>
                             <td
                                style='box-sizing:border-box;'>
                                <table align='center' width='570' cellpadding='0' cellspacing='0'
                                   role='presentation'
                                   style='box-sizing:border-box;margin:0 auto;padding:0;text-align:center;width:570px'>
                                   <tbody>
                                      <tr>
                                         <td align='center'
                                            style='box-sizing:border-box;max-width:100vw;padding:32px'>
                                            <p
                                               style='box-sizing:border-box;line-height:1.5em;margin-top:0;color:#b0adc5;font-size:12px;text-align:center'>
                                               © 2022 KBS. All rights reserved.
                                            </p>
                                         </td>
                                      </tr>
                                   </tbody>
                                </table>
                             </td>
                          </tr>
                       </tbody>
                    </table>
                 </td>
              </tr>
           </tbody>
        </table>
     </div>";
    }
}
