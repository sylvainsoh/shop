<?php

namespace App\Services;

use App\Entity\EmailModel;
use App\Entity\User;
use Mailjet\Client;
use Mailjet\Resources;

class EmailSender
{

    public function sendEmailNotification(User $user, EmailModel $email)
    {
        $mj = new Client($_ENV['MJ_APIKEY_PUBLIC'], $_ENV['MJ_APIKEY_PRIVATE'], true, ['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "me@sohsylvain.dev",
                        'Name' => "Shop"
                    ],
                    'To' => [
                        [
                            'Email' => $user->getEmail(),
                            'Name' => $user->getFullName()
                        ]
                    ],
                    'TemplateID' => 4890659,
                    'TemplateLanguage' => true,
                    'Subject' => $email->getSubject(),
                    'Variables' => [
                        "title" => $email->getTitle(),
                        "content" => $email->getContent()
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        // $response->success() && dd($response->getData());
    }
    public function sendEmailByMailToAdminJet(User $user, EmailModel $email)
    {
        $mj = new Client($_ENV['MJ_APIKEY_PUBLIC'], $_ENV['MJ_APIKEY_PRIVATE'], true, ['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "me@sohsylvain.dev",
                        'Name' => "Shop"
                    ],
                    'To' => [
                        [
                            'Email' => "sohsyl20@gmail.com",
                            'Name' => $user->getFullName()
                        ]
                    ],
                    'TemplateID' => 4890659,
                    'TemplateLanguage' => true,
                    'Subject' => $email->getSubject(),
                    'Variables' => [
                        "title" => $email->getTitle(),
                        "content" => $email->getContent()
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        // $response->success() && dd($response->getData());
    }
}