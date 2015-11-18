<?php

return [

/*
|--------------------------------------------------------------------------
| Validation Language Lines
|--------------------------------------------------------------------------
|
| The following language lines contain the default error messages used by
| the validator class. Some of these rules have multiple versions such
| as the size rules. Feel free to tweak each of these messages here.
|
*/

"accepted"             => ":attribute 에 동의해 주세요",
"active_url"           => ":attribute 은(는) 유효한 URL이 아닙니다.",
"after"                => ":attribute 은(는) :date 이후만 허용합니다.",
"alpha"                => ":attribute 은(는) 영문자만 허용합니다.",
"alpha_dash"           => ":attribute 은(는) 영문자, 숫자, 대시(-)만 허용합니다.",
"alpha_num"            => ":attribute 은(는) 영문자와 숫자만 허용합니다.",
"array"                => ":attribute 은(는) 배열 형태만 허용 합니다.",
"before"               => ":attribute 은(는) :date 이전만 허용합니다.",
"between"              => array(
"numeric" => ":attribute 은(는) :min 보다 크고 :max 보다 작아야 합니다.",
"file"    => ":attribute 은(는) :min kilobytes보다 크고 :max 보다 작아야 합니다.",
"string"  => ":attribute 은(는) :min 문자 보다 길고 :max 문자를 넘지 않아야 합니다. ",
"array"   => ":attribute 은(는) 최소 :min 개, 최대 :max 개의 배열 요소만 허용합니다.",
),
"boolean"              => ":attribute 은(는) true/false 만 허용됩니다.",
"confirmed"            => ":attribute 확인란이 일치하지 않습니다.",
"date"                 => ":attribute 은(는) 유효한 날짜 형식이 아닙니다.",
"date_format"          => ":attribute 은(는) 유효한 날짜 형식(:format)이 아닙니다.",
"different"            => ":attribute 은(는) :other 와 같을 수 없습니다.",
"digits"               => ":attribute 은(는) :digits 자리 숫자여야 합니다.",
"digits_between"       => ":attribute 은(는) 최소 :min 최대 :max 자리 숫자여야 합니다.",
"email"                => ":attribute 은(는) 유효한 이메일 주소가 아닙니다.",
"exists"               => ":attribute 은(는) 유효하지 않습니다.",
"image"                => ":attribute 은(는) 이미지 형식의 파일만 허용합니다.",
"in"                   => ":attribute 은(는) 유효하지 않습니다.",
"integer"              => ":attribute 은(는) 정수만 허용됩니다.",
"ip"                   => ":attribute 은(는) 유효한 IP 주소가 아닙니다.",
"max"                  => array(
"numeric" => ":attribute 은(는) 최대 :max 까지만 허용됩니다.",
"file"    => ":attribute 은(는) 최대 :max 킬로바이트 까지만 허용됩니다.",
"string"  => ":attribute 은(는) 최대 :max 글자까지만 허용됩니다.",
"array"   => ":attribute 은(는) 최대 :max 개의 배열요소까지만 허용됩니다. ",
),
"mimes"                => ":attribute 은(는) type: :values 형식만 허용됩니다.",
"min"                  => array(
"numeric" => ":attribute 은(는) 최소 :min 이상이어야 합니다.",
"file"    => ":attribute 은(는) 최소 :min kilobytes 이상이어야 합니다.",
"string"  => ":attribute 은(는) 최소 :min 글자 이상이어야 합니다.",
"array"   => ":attribute 은(는) 최소 :min 배열요소 이상이어야 합니다.",
),
"not_in"               => ":attribute 은(는) 유효하지 않습니다.",
"numeric"              => ":attribute 은(는) 숫자 형태만 허용합니다.",
"regex"                => ":attribute 은(는) 올바른 형식이 아닙니다.",
"required"             => ":attribute 은(는) 필수 입력 항목 입니다.",
"required_if"          => ":attribute 은(는) :other 값이 :value 일때 필수 입력 항목 입니다.",
"required_with"        => ":attribute 은(는) :values 값이 있을 때 필수 입력 항목 입니다.",
"required_with_all"    => ":attribute 은(는) :values 값이 있을 때 필수 입력 항목 입니다.",
"required_without"     => ":attribute 은(는) :values 값이 없을 때 필수 입력 항목 입니다.",
"required_without_all" => ":attribute 은(는) :values 값이 없을 때 필수 입력 항목 입니다.",
"same"                 => ":attribute 은(는) :other 와 입력값이 일치해야 합니다.",
"size"                 => array(
"numeric" => ":attribute 은(는) :size 여야 합니다.",
"file"    => ":attribute 은(는) :size kilobytes 여야 합니다.",
"string"  => ":attribute 은(는) :size 문자여야 합니다.",
"array"   => ":attribute 은(는) :size 배열 요소를 담고 있어야 합니다.",
),
"unique"               => ":attribute 은(는) 이미 사용 중입니다.",
"url"                  => ":attribute 은(는) 유효하지 않은 Url 입니다.",

/*
|--------------------------------------------------------------------------
| Custom Validation Language Lines
|--------------------------------------------------------------------------
|
| Here you may specify custom validation messages for attributes using the
| convention "attribute.rule" to name the lines. This makes it quick to
| specify a specific custom language line for a given attribute rule.
|
*/

'custom'               => array(
'attribute-name' => array(
'rule-name' => 'custom-message',
),
),

/*
|--------------------------------------------------------------------------
| Custom Validation Attributes
|--------------------------------------------------------------------------
|
| The following language lines are used to swap attribute place-holders
| with something more reader friendly such as E-Mail Address instead
| of "email". This simply helps us make messages a little cleaner.
|
*/

'attributes'           => array(),

];
