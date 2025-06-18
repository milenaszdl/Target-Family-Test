<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Обратная связь");

use Bitrix\Main\Context;
use Bitrix\Main\Loader;
use Bitrix\Main\Application;

$errors = [];
$success = false;

if ($_SERVER["REQUEST_METHOD"] === "POST" && check_bitrix_sessid()) {
    $name = trim($_POST["NAME"]);
    $email = trim($_POST["EMAIL"]);
    $text = trim($_POST["TEXT"]);

    //валидация
    if ($name === "") {
        $errors[] = "Введите имя.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Введите корректный email.";
    }

    if ($text === "" || $text !== strip_tags($text)) {
        $errors[] = "Текст отзыва не должен содержать HTML.";
    }

    if (empty($errors)) {
        Loader::includeModule("iblock");

        $el = new CIBlockElement;

        $arFields = [
            "IBLOCK_ID" => 5,
            "NAME" => $name,
            "PREVIEW_TEXT" => $text,
            "ACTIVE" => "Y",
            "PROPERTY_VALUES" => [
                "NAME" => $name,
                "EMAIL" => $email,
            ],
        ];

        if ($el->Add($arFields)) {
            $success = true;
        } else {
            $errors[] = "ошибка при сохранении отзыва.";
        }
    }
}
?>

<div class="container mt-5">
    <h1>Оставьте отзыв</h1>

    <?php if ($success): ?>
        <div class="alert alert-success">Спасибо за отзыв!</div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?=htmlspecialcharsbx($error)?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST">
        <?=bitrix_sessid_post()?>
        <div class="mb-3">
            <label for="name" class="form-label">Имя</label>
            <input type="text" name="NAME" class="form-control" required value="<?=htmlspecialcharsbx($_POST['NAME'] ?? '')?>">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="EMAIL" class="form-control" required value="<?=htmlspecialcharsbx($_POST['EMAIL'] ?? '')?>">
        </div>
        <div class="mb-3">
            <label for="text" class="form-label">Текст отзыва</label>
            <textarea name="TEXT" class="form-control" rows="5" required><?=htmlspecialcharsbx($_POST['TEXT'] ?? '')?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Отправить</button>
    </form>
</div>

<?php
$APPLICATION->IncludeComponent("bitrix:news.list", "feedback_last", [
    "IBLOCK_ID" => 5,
    "NEWS_COUNT" => 3,
    "SORT_BY1" => "ID",
    "SORT_ORDER1" => "DESC",
    "PROPERTY_CODE" => ["NAME", "EMAIL"],
    "DETAIL_URL" => "",
    "CACHE_TYPE" => "A",
    "CACHE_TIME" => "3600",
    "SET_TITLE" => "N",
    "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
    "DISPLAY_NAME" => "N",
], false);
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>