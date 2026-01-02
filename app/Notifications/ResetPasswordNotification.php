<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * Create a new notification instance.
     *
     * @param  string  $token
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        $isArabic = app()->getLocale() === 'ar';

        return (new MailMessage)
            ->subject($isArabic ? 'إعادة تعيين كلمة المرور - Glowmira Beauty' : 'Reset Password - Glowmira Beauty')
            ->greeting($isArabic ? 'مرحباً!' : 'Hello!')
            ->line($isArabic 
                ? 'لقد تلقيت هذا البريد الإلكتروني لأننا تلقينا طلب إعادة تعيين كلمة المرور لحسابك.'
                : 'You are receiving this email because we received a password reset request for your account.')
            ->action($isArabic ? 'إعادة تعيين كلمة المرور' : 'Reset Password', $url)
            ->line($isArabic 
                ? 'ستنتهي صلاحية رابط إعادة تعيين كلمة المرور هذا خلال ' . config('auth.passwords.users.expire') . ' دقيقة.'
                : 'This password reset link will expire in ' . config('auth.passwords.users.expire') . ' minutes.')
            ->line($isArabic 
                ? 'إذا لم تطلب إعادة تعيين كلمة المرور، فلا يلزم اتخاذ أي إجراء آخر.'
                : 'If you did not request a password reset, no further action is required.')
            ->salutation($isArabic ? 'مع أطيب التحيات،' : 'Regards,');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
