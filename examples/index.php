<?php
include_once __DIR__ . "/vendor/autoload.php";

use Xu\Facebook\Facebook;
use Xu\Facebook\User;

// create 30 users
$users = [];
for ($i = 0; $i < 30; $i++) {
    $users[] = User::fromArray([
        'image'      => __DIR__ . "/blue.png",
        'name'       => "User $i",
        'name_font'  => [
            'family' => 'helvetica',
            'style'  => 'BI',
            'size'   => '14',
        ],

        'desc'       => "Hi, \nI am user $i.",
        'desc_color' => [255, 0, 0],
    ]);
}

// render
$pdf = new Facebook();
$pdf->SetFont('Times', '', 12);
$pdf->addUsers($users);
$pdf->render();
$pdf->Output();
