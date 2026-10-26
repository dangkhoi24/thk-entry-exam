<?php

// Texts for the admin screens

return [

    'menu' => [
        'title' => 'サイト管理',
        'hotel_search' => 'ホテル検索',
        'hotel_create' => 'ホテル追加',
        'booking_search' => '予約情報検索',
    ],

    'common' => [
        'search' => '検索',
        'select_placeholder' => '選択してください',
        'date_placeholder' => '日付を選択',
        'no_data' => 'データがありません',
        'no_result' => '検索結果がありません',
        'count' => ':count件',
        'changed' => '変更あり',
        'before_change' => '変更前：:value',
    ],

    'top' => [
        'title' => 'THK Holdings Vietnam Hanoi\'s entrance examination',
        'description' => 'This is a management screen for THK Holdings Vietnam Hanoi\'s entry examination.<br>Although it will be a management screen, a login feature has not been implemented this time to simplify testing. note that.',
    ],

    'hotel' => [
        'search_title' => 'ホテル検索',
        'search_hint' => 'ホテル名または都道府県を入力して検索してください',
        'create_title' => 'ホテル追加',
        'edit_title' => 'ホテル編集',

        'field' => [
            'hotel_name' => 'ホテル名',
            'prefecture' => '都道府県',
            'hotel_image' => 'ホテル画像',
            'created_at' => '登録日',
            'updated_at' => '更新日',
        ],

        'placeholder' => [
            'hotel_name' => '例）ビジネスホテル 東京 A',
            'hotel_name_search' => 'ホテル名（部分一致）',
        ],

        'image_hint' => 'jpeg・png・webp形式、2MB以下',
        'image_keep_hint' => 'jpeg・png・webp形式、2MB以下。選択しない場合は現在の画像のままになります',
        'current_image' => '現在の画像',

        'action' => [
            'edit' => '編集',
            'delete' => '削除',
            'register' => '登録する',
            'save' => '保存する',
            'cancel' => 'キャンセル',
            'update' => '更新する',
            'view_detail' => 'ホテル詳細を見る',
            'back_to_search' => 'ホテル検索に戻る',
        ],

        'confirm_title' => '変更内容の確認',
        'confirm_message' => '以下の内容で更新します。よろしいですか？',
        'no_changes' => '変更された項目はありません',
        'delete_title' => 'ホテルの削除',
        'delete_confirm' => 'ホテル「:name」を削除します。本当によろしいですか？',

        'message' => [
            'created' => 'ホテル「:name」を登録しました',
            'updated' => 'ホテル「:name」の情報を更新しました',
            'deleted' => 'ホテル「:name」を削除しました',
            'not_found' => '指定されたホテルが見つかりません',
            'has_bookings' => 'ホテル「:name」は予約情報があるため削除できません',
            'edit_expired' => '編集内容が確認できませんでした。もう一度やり直してください',
        ],
    ],

    'booking' => [
        'search_title' => '予約情報検索',
        'search_hint' => '検索条件を入力して検索してください',

        'field' => [
            'customer_name' => '顧客名',
            'customer_contact' => '顧客連絡先',
            'checkin_date' => 'チェックイン日',
            'checkout_date' => 'チェックアウト日',
            'checkin_time' => 'チェックイン日時',
            'checkout_time' => 'チェックアウト日時',
            'created_at' => '予約日時',
            'updated_at' => '情報更新日時',
        ],

        'placeholder' => [
            'customer_name' => '顧客名（部分一致）',
            'customer_contact' => '電話番号・メール（部分一致）',
        ],
    ],

    'pagination' => [
        'summary' => '全:total件中 :from〜:to件',
        'previous' => '前へ',
        'next' => '次へ',
    ],

    'locale' => [
        'ja' => '日本語',
        'en' => 'English',
    ],

];
