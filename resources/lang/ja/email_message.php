<?php

return [
    // メールアドレスの確認
    "Verify" => [
        "Title" => "メールアドレスの確認",
        "NewUrl" => "新しく確認用のURLを送信しました。",
        "SendActionUrl" => "送られたメールを確認してURLをクリックして下さい。",
        "NotEmail" => "メールが届いていない場合は、<a href=':url'>こちら</a>をクリックして下さい。"
    ],

    // メール
    "Mail" => [
        "Opning" => "ご利用ありがとうございます。",
        "greeting" => "ご登録ありがとうございます。",
        "Whoops" => "ご迷惑をおかけいたします。",
        "Regards" => "引き続きのご利用をよろしくお願いいたします。",
        "NotClick" =>"「:actionText」がクリックできない場合は以下のURLをブラウザにコピーして下さい。\n[:actionURL](:actionURL)",

        // メールアドレスの確認
        "Verify" => [
            "Title" => "メールアドレスの確認",
            "Line" => "登録を完了するには、下のボタンをクリックしてください。",
            "Action" => "メールアドレスを確認",
            "OutLine" =>"このメールに覚えが無い場合は破棄してください。"
        ],
        // パスワードリセット
        "password_reset" => [
            "title" => "パスワードリセットのお知らせ",
            "line" => "パスワードリセットリクエストを受け付けました。",
            "action" => "リセット開始",
            "out_line1" => "こちらのパスワードリセットの有効期限は :count 分です。",
            "out_line2" => "このメールに覚えが無い場合は破棄してください。"
        ]
    ]
];