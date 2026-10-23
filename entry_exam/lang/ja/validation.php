<?php

// Validation messages (only the rules used in this application;
// others fall back to the framework's English messages)

return [

    'after_or_equal' => ':attributeは:date以降の日付を入力してください',
    'date_format' => ':attributeを正しく入力してください',
    'exists' => '選択された:attributeは正しくありません',
    'image' => ':attributeは画像ファイルを選択してください',
    'integer' => ':attributeを正しく選択してください',
    'max' => [
        'file' => ':attributeは:maxKB以下のファイルを選択してください',
        'string' => ':attributeは:max文字以内で入力してください',
    ],
    'mimes' => ':attributeは:values形式のファイルを選択してください',
    'required' => ':attributeを入力してください',
    'required_without' => ':attributeを入力してください',
    'required_without_all' => ':attributeを入力してください',
    'string' => ':attributeは文字列で入力してください',

    /*
    | Custom messages for "attribute.rule"
    */
    'custom' => [
        // search forms: nothing entered
        'hotel_name' => [
            'required_without' => '何も入力されていません',
        ],
        'customer_name' => [
            'required_without_all' => '何も入力されていません',
        ],
        'prefecture_id' => [
            'required' => ':attributeを選択してください',
        ],
        'hotel_image' => [
            'required' => ':attributeを選択してください',
            'max' => ':attributeは2MB以下のファイルを選択してください',
        ],
    ],

    'attributes' => [
        'hotel_name' => 'ホテル名',
        'prefecture_id' => '都道府県',
        'hotel_image' => 'ホテル画像',
        'customer_name' => '顧客名',
        'customer_contact' => '顧客連絡先',
        'checkin_date' => 'チェックイン日',
        'checkout_date' => 'チェックアウト日',
    ],

];
