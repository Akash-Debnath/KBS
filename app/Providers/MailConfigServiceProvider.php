<?php

namespace App\Providers;

use Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class MailConfigServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // if (Schema::hasTable('email_settings')) {

        //     $mail = DB::table('email_settings')->first();
        //     if ($mail) //checking if table is not empty
        //     {
        //         $config = array(
        //             'driver'     => $mail->type,
        //             'host'       => $mail->host,
        //             'port'       => $mail->port,
        //             'from'       => array('address' => $mail->primary_email, 'name' =>config('app.name')),
        //             'encryption' => $mail->encryption,
        //             'username'   => $mail->username,
        //             'password'   => $mail->password,
        //         );
        //         Config::set('mail', $config);
        //     }
        // }
    }
}