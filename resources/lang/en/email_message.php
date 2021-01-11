<?php

return [
    // メールアドレスの確認
    "Verify" => [
        "Title" => "Verify Your Email Address",
        "NewUrl" => "A fresh verification link has been sent to your email address.",
        "SendActionUrl" => "Before proceeding, please check your email for a verification link.",
        "NotEmail" => "If you did not receive the email, <a href=':url'>click here</a> to request another"
    ],

    // メール
    "Mail" => [
        "Opning" => "Hello!",
        "greeting" => "Hello!",
        "Whoops" => "Whoops!",
        "Regards" => "Regards,",
        "NotClick" => "If you’re having trouble clicking the \":actionText\" button, copy and paste the URL below\ninto your web browser: [:actionURL](:actionURL)",

        // メールアドレスの確認
        "Verify" => [
            "Title" => "Verify Email Address",
            "Line" => "Please click the button below to verify your email address.",
            "Action" => "Verify Email Address",
            "OutLine" =>"If you did not create an account, no further action is required."
        ],
        // パスワードリセット
        "password_reset" => [
            "title" => "Reset Password Notification",
            "line" => "You are receiving this email because we received a password reset request for your account.",
            "action" => "Reset Password",
            "out_line1" => "This password reset link will expire in :count minutes.",
            "out_line2" => "If you did not request a password reset, no further action is required."
        ]
    ]
];