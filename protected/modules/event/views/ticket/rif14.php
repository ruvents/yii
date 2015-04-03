<?php
/**
 * @var User $user
 * @var Event $event
 * @var Participant|Participant[] $participant
 */

use user\models\User;
use event\models\Event;
use event\models\Participant;
use ruvents\components\QrCode;
use pay\models\OrderItem;
use pay\components\admin\Rif;

$criteria = new \CDbCriteria();
$criteria->with = ['Product'];
$criteria->addCondition('"Product"."ManagerName" = :ManagerName');
$criteria->params['ManagerName'] = 'RoomProductManager';
$roomOrderItem = OrderItem::model()->byEventId($event->Id)->byPaid(true)->byAnyOwnerId($user->Id)->find($criteria);
$roomProductManager = $roomOrderItem !== null ? $roomOrderItem->Product->getManager() : null;

$parkingReporterRoleIdList = [3,6];

$parking = null;
if ($roomProductManager !== null || in_array($role->Id, $parkingReporterRoleIdList)) {
    $command = Rif::getDb()->createCommand();
    $command->select('*')->from('ext_booked_parking')->where('ownerRunetId = :RunetId');
    $parking = $command->queryRow(true, ['RunetId' => $user->RunetId]);
}
?>

<style type="text/css">
    html {font-family:sans-serif;}
    body {margin:0}
    article,aside,details,figcaption,figure,footer,header,main,nav,section,summary {display:block}
    audio,canvas,progress,video {display:inline-block;vertical-align:baseline}
    audio:not([controls]) {display:none;height:0}
    [hidden],template {display:none}
    a {background:transparent}
    a:active,a:hover {outline:0}
    abbr[title] {border-bottom:0.2mm dotted}
    b,strong {font-weight:bold}
    dfn {font-style:italic}
    h1 {font-size:2em;margin:0.67em 0}
    mark {background:#ff0;color:#000}
    small {font-size:80%}
    sub,sup {font-size:75%;line-height:0;position:relative;vertical-align:baseline}
    sup {top:-0.5em}
    sub {bottom:-0.25em}
    img {border:0}
    svg:not(:root) {overflow:hidden}
    figure {margin:1em 10.5mm}
    hr {-moz-box-sizing:content-box;box-sizing:content-box;height:0}
    pre {overflow:auto}
    code,kbd,pre,samp {font-family:monospace, monospace;font-size:1em}
    button,input,optgroup,select,textarea {color:inherit;font:inherit;margin:0}
    button {overflow:visible}
    button,select {text-transform:none}
    button,html input[type="button"],input[type="reset"],input[type="submit"] {-webkit-appearance:button;cursor:pointer}
    button[disabled],html input[disabled] {cursor:default}
    button::-moz-focus-inner,input::-moz-focus-inner {border:0;padding:0}
    input {line-height:normal}
    input[type="checkbox"],input[type="radio"] {box-sizing:border-box;padding:0}
    input[type="number"]::-webkit-inner-spin-button,input[type="number"]::-webkit-outer-spin-button {height:auto}
    input[type="search"] {-webkit-appearance:textfield;-moz-box-sizing:content-box;-webkit-box-sizing:content-box;box-sizing:content-box}
    input[type="search"]::-webkit-search-cancel-button,input[type="search"]::-webkit-search-decoration {-webkit-appearance:none}
    fieldset {border:0.2mm solid #c0c0c0;margin:0 0.5mm;padding:0.35em 0.625em 0.75em}
    legend {border:0;padding:0}
    textarea {overflow:auto}
    optgroup {font-weight:bold}
    table {border-collapse:collapse;border-spacing:0}
    td,th {padding:0}
    * {-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}
    *:before,*:after {-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}
    html {font-size:62.5%;-webkit-tap-highlight-color:rgba(0,0,0,0)}
    body {font-family:Arial,Helvetica,sans-serif;font-size:3.7mm;line-height:1.42857143;color:#1a1a1a;background-color:#fff}
    input,button,select,textarea {font-family:inherit;font-size:inherit;line-height:inherit}
    a {color:#2eaff2;text-decoration:none}
    a:hover,a:focus {color:#0c87c7;text-decoration:underline}
    a:focus {outline:thin dotted;outline:1.3mm auto -webkit-focus-ring-color;outline-offset:-0.5mm}
    figure {margin:0}
    img {vertical-align:middle}
    .img-responsive {display:block;max-width:100%;height:auto}
    .img-rounded {border-radius:1.6mm}
    .img-thumbnail {padding:1mm;line-height:1.42857143;background-color:#fff;border:0.2mm solid #ddd;border-radius:1mm;-webkit-transition:all .2s ease-in-out;transition:all .2s ease-in-out;display:inline-block;max-width:100%;height:auto}
    .img-circle {border-radius:50%}
    hr {margin-top:5.3mm;margin-bottom:5.3mm;border:0;border-top:0.2mm solid #eee}
    .sr-only {position:absolute;width:0.2mm;height:0.2mm;margin:-0.2mm;padding:0;overflow:hidden;clip:rect(0, 0, 0, 0);border:0}
    h1,h2,h3,h4,h5,h6,.h1,.h2,.h3,.h4,.h5,.h6 {font-family:inherit;font-weight:500;line-height:1.1;color:inherit}
    h1 small,h2 small,h3 small,h4 small,h5 small,h6 small,.h1 small,.h2 small,.h3 small,.h4 small,.h5 small,.h6 small,h1 .small,h2 .small,h3 .small,h4 .small,h5 .small,h6 .small,.h1 .small,.h2 .small,.h3 .small,.h4 .small,.h5 .small,.h6 .small {font-weight:normal;line-height:1;color:#999}
    h1,.h1,h2,.h2,h3,.h3 {margin-top:1.3mm;margin-bottom:2.6mm}
    h1 small,.h1 small,h2 small,.h2 small,h3 small,.h3 small,h1 .small,.h1 .small,h2 .small,.h2 .small,h3 .small,.h3 .small {font-size:65%}
    h4,.h4,h5,.h5,h6,.h6 {margin-top:2.6mm;margin-bottom:2.6mm}
    h4 small,.h4 small,h5 small,.h5 small,h6 small,.h6 small,h4 .small,.h4 .small,h5 .small,.h5 .small,h6 .small,.h6 .small {font-size:75%}
    h1,.h1 {font-size:31.6mm}
    h2,.h2 {font-size:4.8mm}
    h3,.h3 {font-size:11.6mm}
    h4,.h4 {font-size:3.4mm}
    h5,.h5 {font-size:3.7mm}
    h6,.h6 {font-size:10.5mm}
    p {margin:0 0 2.6mm}
    .lead {margin-bottom:5.3mm;font-size:11.6mm;font-weight:200;line-height:1.4}
    small,.small {font-size:85%}
    cite {font-style:normal}
    .text-left {text-align:left}
    .text-right {text-align:right}
    .text-center {text-align:center}
    .text-justify {text-align:justify}
    .text-muted {color:#999}
    .text-primary {color:#2eaff2}
    a.text-primary:hover {color:#0e98df}
    .text-success {color:#3c763d}
    a.text-success:hover {color:#2b542c}
    .text-info {color:#31708f}
    a.text-info:hover {color:#245269}
    .text-warning {color:#8a6d3b}
    a.text-warning:hover {color:#66512c}
    .text-danger {color:#a94442}
    a.text-danger:hover {color:#843534}
    .bg-primary {color:#fff;background-color:#2eaff2}
    a.bg-primary:hover {background-color:#0e98df}
    .bg-success {background-color:#dff0d8}
    a.bg-success:hover {background-color:#c1e2b3}
    .bg-info {background-color:#d9edf7}
    a.bg-info:hover {background-color:#afd9ee}
    .bg-warning {background-color:#fcf8e3}
    a.bg-warning:hover {background-color:#f7ecb5}
    .bg-danger {background-color:#f2dede}
    a.bg-danger:hover {background-color:#e4b9b9}
    .page-header {padding-bottom:2.3mm;margin:10.5mm 0 5.3mm;border-bottom:0.2mm solid #eee}
    ul,ol {margin-top:0;margin-bottom:2.6mm}
    ul ul,ol ul,ul ol,ol ol {margin-bottom:0}
    .list-unstyled {padding-left:0;list-style:none}
    .list-inline {padding-left:0;list-style:none;margin-left:-1.3mm}
    .list-inline>li {display:inline-block;padding-left:1.3mm;padding-right:1.3mm}
    dl {margin-top:0;margin-bottom:5.3mm}
    dt,dd {line-height:1.42857143}
    dt {font-weight:bold}
    dd {margin-left:0}
    abbr[title],abbr[data-original-title] {cursor:help;border-bottom:0.2mm dotted #999}
    .initialism {font-size:90%;text-transform:uppercase}
    blockquote {padding:2.6mm 5.3mm;margin:0 0 5.3mm;font-size:17.1.3mm;border-left:1.3mm solid #eee}
    blockquote p:last-child,blockquote ul:last-child,blockquote ol:last-child {margin-bottom:0}
    blockquote footer,blockquote small,blockquote .small {display:block;font-size:80%;line-height:1.42857143;color:#999}
    blockquote footer:before,blockquote small:before,blockquote .small:before {content:'\2014 \00A0'}
    .blockquote-reverse,blockquote.pull-right {padding-right:4mm;padding-left:0;border-right:1.3mm solid #eee;border-left:0;text-align:right}
    .blockquote-reverse footer:before,blockquote.pull-right footer:before,.blockquote-reverse small:before,blockquote.pull-right small:before,.blockquote-reverse .small:before,blockquote.pull-right .small:before {content:''}
    .blockquote-reverse footer:after,blockquote.pull-right footer:after,.blockquote-reverse small:after,blockquote.pull-right small:after,.blockquote-reverse .small:after,blockquote.pull-right .small:after {content:'\00A0 \2014'}
    blockquote:before,blockquote:after {content:""}
    address {margin-bottom:5.3mm;font-style:normal;line-height:1.42857143}
    code,kbd,pre,samp {font-family:Menlo,Monaco,Consolas,"Courier New",monospace}
    code {padding:0.5mm 1mm;font-size:90%;color:#c7254e;background-color:#f9f2f4;white-space:nowrap;border-radius:1mm}
    kbd {padding:0.5mm 1mm;font-size:90%;color:#fff;background-color:#333;border-radius:0.8mm;box-shadow:inset 0 -0.2mm 0 rgba(0,0,0,0.25)}
    pre {display:block;padding:9.1.3mm;margin:0 0 2.6mm;font-size:3.4mm;line-height:1.42857143;word-break:break-all;word-wrap:break-word;color:#333;background-color:#f5f5f5;border:0.2mm solid #ccc;border-radius:1mm}
    pre code {padding:0;font-size:inherit;color:inherit;white-space:pre-wrap;background-color:transparent;border-radius:0}
    .container {margin-right:auto;margin-left:auto;padding-left:4mm;padding-right:4mm}
    .container-fluid {margin-right:auto;margin-left:auto;padding-left:4mm;padding-right:4mm}
    .row {margin-left:-4mm;margin-right:-4mm;}
    .row:after {
        content:" ";
        visibility:hidden;
        display:block;
        height:0;
        clear:both
    }
    .col-1, .col-sm-1, .col-md-1, .col-lg-1, .col-2, .col-sm-2, .col-md-2, .col-lg-2, .col-3, .col-sm-3, .col-md-3, .col-lg-3, .col-4, .col-sm-4, .col-md-4, .col-lg-4, .col-5, .col-sm-5, .col-md-5, .col-lg-5, .col-6, .col-sm-6, .col-md-6, .col-lg-6, .col-7, .col-sm-7, .col-md-7, .col-lg-7, .col-8, .col-sm-8, .col-md-8, .col-lg-8, .col-9, .col-sm-9, .col-md-9, .col-lg-9, .col-10, .col-sm-10, .col-md-10, .col-lg-10, .col-11, .col-sm-11, .col-md-11, .col-lg-11, .col-12, .col-sm-12, .col-md-12, .col-lg-12 {position:relative;min-height:0.2mm;padding-left:4mm;padding-right:4mm}
    .col-1, .col-2, .col-3, .col-4, .col-5, .col-6, .col-7, .col-8, .col-9, .col-10, .col-11, .col-12 {float:left}
    .col-12 {width:100%}
    .col-11 {width:91.66666667%}
    .col-10 {width:83.33333333%}
    .col-9 {width:75%}
    .col-8 {width:66.66666667%}
    .col-7 {width:58.33333333%}
    .col-6 {width:50%}
    .col-5 {width:41.66666667%}
    .col-4 {width:33.33333333%}
    .col-3 {width:25%}
    .col-2 {width:16.66666667%}
    .col-1 {width:8.33333333%}
    .col-offset-12 {margin-left:100%}
    .col-offset-11 {margin-left:91.66666667%}
    .col-offset-10 {margin-left:83.33333333%}
    .col-offset-9 {margin-left:75%}
    .col-offset-8 {margin-left:66.66666667%}
    .col-offset-7 {margin-left:58.33333333%}
    .col-offset-6 {margin-left:50%}
    .col-offset-5 {margin-left:41.66666667%}
    .col-offset-4 {margin-left:33.33333333%}
    .col-offset-3 {margin-left:25%}
    .col-offset-2 {margin-left:16.66666667%}
    .col-offset-1 {margin-left:8.33333333%}
    .col-offset-0 {margin-left:0}
    @import url(http://fonts.googleapis.com/css?family=Arimo:400,700&subset=latin,cyrillic);
    *,body {
        font-family:Arimo, sans-serif
    }
    header {
        border-bottom:0.2mm solid #E7E5E6;
        padding:2.6mm 0;
    }
    .row-userinfo .userinfo {
        font-size:7.5mm;
        line-height:7.4mm;
        margin:4mm 0
    }
    .row-userinfo .userinfo.status {
        color:#2DAEF2
    }
    .row-userinfo .userinfo.company {
        color:gray
    }
    .row-userinfo .qrcode > figcaption {
        text-align:center
    }
    .row-timeline {
        border-top:0.2mm solid #E7E5E6;
        color:#656565;
        padding:5.3mm 0 21.3mm;
        margin: 2.6mm 0 0;
    }
    .row-datetime {
        font-size:2.6mm;
        margin-top:2.6mm;
        margin-bottom:0;
    }
    .row-datetime .date {
        color:#222225;
        font-size:6mm;
    }
    .row-datetime .date > big {
        display:block;
        font-size:7mm
    }
    .row-datetime .time > span {
        color:#999
    }
    .row-reminder {
        background:#F2F3F4;
        color:#656565;
        padding:2.6mm 0
    }
    .row-reminder h2 {
        margin-bottom:4mm
    }
    .row-transport .title {
        color:#2DAEF2;
        font-size:5.3mm;
        line-height:5.3mm;
        margin: 5mm 0 5mm 9mm;
        padding: 0;
    }
    .row-bus_timeline {
        color:#656565;
        font-size:80%
    }
    .row-bus_timeline .title {
        font-size:5.3mm;
        margin: 0 0 5mm 0;
        padding: 0;
    }
    .row-bus_timeline h4 {
        margin-top:3mm;
        margin-bottom: 2mm
    }
    .footer {
        color:#C8C8CB;
        font-size:3mm;
    }
    ul >li {
        background: 0 0.8mm no-repeat url(data:image/png; base64,iVBORw0KGgoAAAANSUhEUgAAAA8AAAAPCAYAAAA71pVKAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAIGNIUk0AAHolAACAgwAA+f8AAIDpAAB1MAAA6mAAADqYAAAXb5JfxUYAAAESSURBVHjalNO/K8RxHMfxh08GFl0334JMyEI2SUluduEyWC47f4PZrkQhKbNSMsiirpTOhhKy6LqNjeX91amju9fyqff79fz07v2jq1KpaFIPVrCASfThHTc4wQE+M3NqAkdwiS28YQ3z2MBrxK8wlgHd8Q7jAk+RfPRbB9jEMc4xjbuEXuwFMNcCzJTlH8Lf240yRjGEuv9Vj37co5ywhCM8a08v4V9MGMepznSKiYRcG+W2Kj+X0EC+QziPRkIVxQ7hIqopZreMQptgIfzHCYeoxfrl2yj3JPyHCR9YxQDO0P8H2B/5wfB/ZLtdwwy+cItdlCJWwk7EuzAb/p/dzj6Yarqq7RhjA9dYx37zVX0PAB99QGeygeZKAAAAAElFTkSuQmCC);
        list-style:none;
        margin-bottom:0.2mm;
        padding:0.8mm 0 0.5mm 5.3mm;
        font-size:10.2mm
    }
    .row-transport, .row-userinfo {
        padding:0
    }
    .row-timeline h3,.row-transport h3 {
        font-size:3.4mm;
        font-weight:400;
        margin:0;
        padding:0 0 0.8mm
    }
    .row-bus_timeline .row-fill h3,ul {
        margin:0;
        padding:0
    }
    .page-transport {
        font-size: 2.6mm
    }
    table.booking {

    }
    table.booking td {
        padding: 0 1.3mm
    }
    .page-car {
        height:100%;
        width:100%;
        page-break-after: always
    }
    .page-car>div {
        position: absolute;
        font-size: 15.9mm;
        text-align: center;
        text-transform: uppercase
    }
    table.food-table {
        margin-top: 2mm;
        width: 100%
    }
    table.food-table td,table.food-table th {
        text-align: center;
        font-size: 2.3mm;
        padding: 0.5mm 0.2mm;
        border: 0.2mm solid #cccccc;
        background-color: #E7E5E6
    }
    .row-timeline.noborder {
        border-top: 0
    }
</style>

<htmlpagefooter name="main-footer">
    <div class="text-center footer">
        <p>Онлайн-регистрация участников — <u>RUNET-ID</u>, регистрация участников на площадке — <u>RUVENTS</u></p>
    </div>
</htmlpagefooter>

<div class="page-main">
<header>
    <div class="container">
        <div class="row">
            <!-- Логотип -->
            <div class="col-7">
                <div class="logo">
                    <img src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48IURPQ1RZUEUgc3ZnIFBVQkxJQyAiLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4iICJodHRwOi8vd3d3LnczLm9yZy9HcmFwaGljcy9TVkcvMS4xL0RURC9zdmcxMS5kdGQiPjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiIHZpZXdCb3g9IjAgMCAyMDggMzQuNTI0IiBlbmFibGUtYmFja2dyb3VuZD0ibmV3IDAgMCAyMDggMzQuNTI0IiB4bWw6c3BhY2U9InByZXNlcnZlIj48Zz48cGF0aCBmaWxsPSIjMTdBNjY5IiBkPSJNMTkuODc3LDQuNzRjMS45NDgtMC45NjYsMy44NjYtMS43NTksNS42OTctMi4zNTljMC4wNTEtMC4wMTUsMC4wODctMC4wNjEsMC4wOTItMC4xMTFjMC4wMDUtMC4wNTQtMC4wMjItMC4xMDQtMC4wNjktMC4xM2MtMC42OTUtMC4zODQtMS40MjEtMC43Mi0yLjE2Mi0xLjAwNGMtMC4wMjQtMC4wMDktMC4wNTEtMC4wMDktMC4wNzgtMC4wMDVjLTEuNzM1LDAuNDExLTMuNTE1LDAuOTcxLTUuMjkxLDEuNjY5QzguMzk4LDYuNzgzLDIuMzI5LDExLjM0NywwLjAzMywxNi4zNmMtMC4wMDYsMC4wMTUtMC4wMTIsMC4wMzMtMC4wMTIsMC4wNDljLTAuMTA0LDIuMTAyLDAuMTc1LDQuMTgzLDAuODI1LDYuMTk0YzAuMDE4LDAuMDUzLDAuMDcsMC4wOTEsMC4xMjcsMC4wOTFjMC4wMDMsMCwwLjAwNywwLDAuMDExLDBjMC4wNjEtMC4wMDYsMC4xMS0wLjA1MiwwLjEyMS0wLjExMkMxLjk4OSwxNi42OSw4LjMwNSwxMC42ODcsMTkuODc3LDQuNzR6Ii8+PHBhdGggZmlsbD0iIzE3QTY2OSIgZD0iTTIuMjk4LDkuMDYyYzAuMDMxLDAsMC4wNjEtMC4wMTEsMC4wODctMC4wMzRjMy4xOS0yLjgzMSw4LjA1LTUuMjkxLDE0LjQ0LTcuMzA5YzEuNDM4LTAuNDIzLDIuOTA5LTAuNzYzLDQuMzctMS4wMDdjMC4wNjItMC4wMSwwLjEwOC0wLjA2NCwwLjExMS0wLjEyNmMwLjAwMi0wLjA2My0wLjA0My0wLjExOS0wLjEwMy0wLjEzNEMxOS45MTYsMC4xNTMsMTguNTg4LDAsMTcuMjYzLDBDMTIuNjUxLDAsOC4zMTcsMS43OTQsNS4wNTcsNS4wNTJDMy45Myw2LjE4MywyLjk2Miw3LjQ2NiwyLjE4MSw4Ljg2NkMyLjE1LDguOTIzLDIuMTYyLDguOTkzLDIuMjE0LDkuMDMzQzIuMjM4LDkuMDU0LDIuMjY4LDkuMDYyLDIuMjk4LDkuMDYyeiIvPjxwYXRoIGZpbGw9IiMxN0E2NjkiIGQ9Ik0zMy4zMTIsMTAuOTQzYzAuMDA2LTAuMDI2LDAuMDAzLTAuMDU1LTAuMDA3LTAuMDc5Yy0wLjI4MS0wLjcwOS0wLjYyMi0xLjQxNi0xLjAxMS0yLjEwMmMtMC4wMjctMC4wNDQtMC4wNzctMC4wNzUtMC4xMjgtMC4wNjZjLTAuMDUyLDAuMDA0LTAuMDk1LDAuMDQyLTAuMTEzLDAuMDkxYy0wLjU5OCwxLjg0OS0xLjM5OCwzLjc4My0yLjM3Myw1Ljc1MmMtNS45MjIsMTEuNTE5LTEyLjE0NiwxOC4wMi0xOC4wMDIsMTguNzk3Yy0wLjA1OSwwLjAwNi0wLjExLDAuMDU3LTAuMTE1LDAuMTE4Yy0wLjAwNSwwLjA2LDAuMDMyLDAuMTE3LDAuMDksMC4xMzljMS44MDIsMC42MTgsMy42OSwwLjkzLDUuNjEyLDAuOTNjMC4xNTQsMCwwLjMwOS0wLjAwMSwwLjQ2NC0wLjAwNWMwLjAxNywwLDAuMDMzLTAuMDA0LDAuMDQ4LTAuMDExYzUuMTI2LTIuMTk1LDkuNzg0LTguMzA0LDEzLjg0Ni0xOC4xNTlDMzIuMzI5LDE0LjU0LDMyLjg5NywxMi43MjMsMzMuMzEyLDEwLjk0M3oiLz48cGF0aCBmaWxsPSIjMTdBNjY5IiBkPSJNMzQuMDc1LDEzLjMyNWMtMC4wMTMtMC4wNi0wLjA2My0wLjA5Ni0wLjEzMy0wLjEwMmMtMC4wNjMsMC4wMDItMC4xMTQsMC4wNDktMC4xMjUsMC4xMTJjLTAuMjQ1LDEuNDYzLTAuNTg1LDIuOTMzLTEuMDA2LDQuMzY4Yy0yLjAyMSw2LjM5MS00LjQ4MSwxMS4yNTItNy4zMTMsMTQuNDQ1Yy0wLjA0NCwwLjA0Ni0wLjA0NCwwLjExOS0wLjAwNSwwLjE2OWMwLjAyNywwLjAzMywwLjA2NiwwLjA1MSwwLjEwNCwwLjA1MWMwLjAyMiwwLDAuMDQ2LTAuMDA0LDAuMDY0LTAuMDE4YzEuNDA0LTAuNzgsMi42ODUtMS43NDgsMy44MTItMi44NzZDMzMuNzIzLDI1LjIyMywzNS40NDMsMTkuMTg4LDM0LjA3NSwxMy4zMjV6Ii8+PHBhdGggZmlsbD0iIzE3QTY2OSIgZD0iTTI2LjkyOSwxMS43MDZjMS40MTItMS44NSwyLjY2LTMuNjA5LDMuNzA5LTUuMjI1YzAuMDMxLTAuMDQ3LDAuMDI2LTAuMTEtMC4wMS0wLjE1M2MtMC4zNjYtMC40NDgtMC43NTMtMC44NzUtMS4xNTItMS4yNzdjLTAuMzQzLTAuMzQxLTAuNzEzLTAuNjgtMS4xMzQtMS4wMjljLTAuMDQ1LTAuMDM4LTAuMTA2LTAuMDQyLTAuMTU3LTAuMDA5Yy0xLjYyMiwxLjA1My0zLjM4MywyLjMwMS01LjIzNywzLjcxNkM4Ljc3MiwxOC42NzksNC41OTEsMjQuNjI2LDMuNTgxLDI3LjY4OWMtMC4wMTUsMC4wNDEtMC4wMDgsMC4wODksMC4wMiwwLjEyYzAuNDY1LDAuNjAzLDAuOTU1LDEuMTYzLDEuNDU2LDEuNjY0YzAuNTUzLDAuNTUyLDEuMTY1LDEuMDg0LDEuODE2LDEuNTc1YzAuMDIxLDAuMDE4LDAuMDUsMC4wMjcsMC4wNzksMC4wMjdjMC4wMTMsMCwwLjAyOC0wLjAwNCwwLjA0Mi0wLjAwOEMxMC4wNTcsMzAuMDQ0LDE2LjAwMSwyNS44NTMsMjYuOTI5LDExLjcwNnoiLz48L2c+PGc+PHBhdGggZD0iTTQ4LjY5NywyNC4zMzRWOS45MTloOC4wNDVjMi45NTcsMCwzLjk1LDIuMTEsMy45NSw1LjA2N2MwLDIuNjA2LTEuMjQxLDQuODYtMy45MjksNC44NmgtNC43MzZ2NC40ODhINDguNjk3eiBNNTUuNTg0LDE3LjA5NWMwLjg2OSwwLDEuNDQ4LTAuNiwxLjQ0OC0yLjEwOWMwLTEuNTcyLTAuNDk2LTIuMjEzLTEuNDA2LTIuMjEzaC0zLjU5OHY0LjMyMkg1NS41ODR6Ii8+PHBhdGggZD0iTTc1LjkxMywyNC4zMzRoLTMuMjg4di05LjQ5MmMtMS42OTYsMy4yMDYtMy41NTcsNi40NzMtNS40MTgsOS40OTJoLTQuMzY0VjkuOTE5aDMuMjY4djkuODg1YzEuOTg1LTMuMjQ3LDMuOTUtNi41OTcsNS43MjgtOS44ODVoNC4wNzRWMjQuMzM0eiIvPjxwYXRoIGQ9Ik04Ny43MDEsOS45MTl2MS42NTRjNC44NiwwLjI0OCw2LjI2NiwxLjgyLDYuMjY2LDUuNTIyYzAsMy43MjItMS40MjcsNS4yNzQtNi4yNjYsNS41MjJ2MS43MTZoLTMuMzN2LTEuNjk2Yy00Ljc5OC0wLjI0OC02LjA1OS0xLjgyLTYuMDU5LTUuNTQyczEuMjYyLTUuMjc0LDYuMDU5LTUuNTIyVjkuOTE5SDg3LjcwMXogTTg0LjM3MSwxOS45Mjl2LTUuNjI1Yy0yLjMxNiwwLjA4My0yLjY4OCwwLjc0NC0yLjY4OCwyLjc5MkM4MS42ODMsMTkuMTIyLDgyLjA1NSwxOS44MjUsODQuMzcxLDE5LjkyOXogTTg3LjcwMSwxNC4zMDR2NS42MjVjMi41MDItMC4wODMsMi44MzMtMC43NjUsMi44MzMtMi44MzNTOTAuMjAzLDE0LjM2Niw4Ny43MDEsMTQuMzA0eiIvPjxwYXRoIGQ9Ik05NS40OTgsMTUuMjk2aDMuNTM2di0zLjU1N2gzLjA0djMuNTU3aDMuNTU3djMuMDRoLTMuNTU3djMuNTM2aC0zLjA0di0zLjUzNmgtMy41MzZWMTUuMjk2eiIvPjxwYXRoIGQ9Ik0xMTYuOTAyLDI0LjMzNGMtMS44NDEtNS4yOTQtMy4yMDYtNi40NzMtNS40OC02LjcyMXY2LjcyMWgtMy4zM1Y5LjkxOWgzLjMzdjYuOTA3YzEuMTU4LTEuMjgyLDMuMjQ3LTMuOTI5LDUuMDg4LTYuOTA3aDMuOTcxYy0yLjM5OSwzLjcyMy00LjA3NCw1Ljg3My00LjcxNSw2LjQ3M2MxLjUzLDAuNTc5LDIuOTk5LDEuNjc1LDUuMDg4LDcuOTQxSDExNi45MDJ6Ii8+PHBhdGggZD0iTTEzNS4zNywyNC4zMzRoLTMuMjg4di05LjQ5MmMtMS42OTYsMy4yMDYtMy41NTcsNi40NzMtNS40MTgsOS40OTJIMTIyLjNWOS45MTloMy4yNjh2OS44ODVjMS45ODUtMy4yNDcsMy45NS02LjU5Nyw1LjcyOC05Ljg4NWg0LjA3NFYyNC4zMzR6Ii8+PHBhdGggZD0iTTE0OS43NDMsMTIuODM1aC03LjY1MnYyLjY0N2g0LjgzOWMyLjUyMywwLDMuODY3LDEuNDg5LDMuODY3LDQuNDQ2YzAsMy40MTItMS40NjgsNC40MDUtMy44MDUsNC40MDVoLTguMjMxVjkuOTE5aDEwLjk4MVYxMi44MzV6IE0xNDYuMTI0LDIxLjVjMC43MDMsMCwxLjA1NS0wLjQ3NiwxLjA1NS0xLjU5MmMwLTEuMTc5LTAuMzkzLTEuNzE3LTEuMTM4LTEuNzE3aC0zLjk1VjIxLjVIMTQ2LjEyNHoiLz48cGF0aCBkPSJNMTU4LjYxNSwxMC4yOTFjMS4xNzktMC40MTQsMy4wNC0wLjcyNCw0LjkwMS0wLjcyNGMzLjYxOSwwLDUuMzk4LDAuODI3LDUuMzk4LDQuMzIyYzAsMi45OTktMC44NDgsMy45NzEtNi4xNjMsNy41MjhoNi4yMjV2Mi45MTZIMTU4LjE2di0yLjcwOWM2LjgwNC01LjA2Nyw3LjIxNy01LjQ2LDcuMjE3LTcuMzgzYzAtMS4zMDMtMC41MzgtMS42NzUtMi4yMTMtMS42NzVjLTEuMzI0LDAtMi45MzcsMC4yNjktMy44NDcsMC41NThMMTU4LjYxNSwxMC4yOTF6Ii8+PHBhdGggZD0iTTE4Mi4zNzcsMTYuOTNjMCw1LjkzNS0xLjkwMiw3LjY5My01LjU2Myw3LjY5M2MtMy42NiwwLTUuNTg0LTEuNzU4LTUuNTg0LTcuNjkzYzAtNS44MzIsMS45MjMtNy4zNjIsNS41ODQtNy4zNjJDMTgwLjQ3NCw5LjU2NywxODIuMzc3LDExLjA5OCwxODIuMzc3LDE2LjkzeiBNMTc5LjA2OCwxNi45M2MwLTMuOTA5LTAuMzUyLTQuMzQzLTIuMjU0LTQuMzQzYy0xLjg2MSwwLTIuMjM0LDAuNDM0LTIuMjM0LDQuMzQzYzAsNC4wMTIsMC4zNzIsNC42MzIsMi4yMzQsNC42MzJDMTc4LjcxNiwyMS41NjIsMTc5LjA2OCwyMC45NDIsMTc5LjA2OCwxNi45M3oiLz48cGF0aCBkPSJNMTg4LjkxMiwyNC4zMzRWMTMuNTE3bC0zLjIyNiwxLjMwM2wtMC44NjgtMi44MTJsNy4zODMtMi43NTF2MTUuMDc2SDE4OC45MTJ6Ii8+PHBhdGggZD0iTTIwNy40NjIsOS45MTl2Mi45MTZoLTYuMTg0djIuNDgyYzAuNDc2LTAuMDQxLDAuOTUxLTAuMDYyLDEuNDI3LTAuMDYyYzQuMDk1LDAsNS4yOTQsMS4yNDEsNS4yOTQsNC41MDhjMCwzLjc2NC0xLjQ2OCw0Ljg2LTUuNjg3LDQuODZjLTEuMzY1LDAtMy42ODEtMC4yMDctNC44MTgtMC40OTZsMC42Mi0yLjk1N2MwLjc2NSwwLjI0OCwyLjQyLDAuMzkzLDMuNDEyLDAuMzkzYzIuMzc4LDAsMi44MzMtMC4yNjksMi44MzMtMS42NzVjMC0xLjQ0OC0wLjMzMS0xLjc3OS0yLjgxMi0xLjc3OWMtMS4wMTMsMC0yLjQ4MiwwLjA4My0zLjQxMiwwLjIwN1Y5LjkxOUgyMDcuNDYyeiIvPjwvZz48L3N2Zz4=" />
                </div>
            </div>
            <!-- Информация о проживании -->
            <?php if($roomProductManager !== null):?>
                <div class="col-4">
                    <table class="booking">
                        <tr>
                            <td class="text-muted">Пансионат <?=$pdf->y;?></td><td><?=$roomProductManager->Hotel;?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Корпус</td><td><?=$roomProductManager->Housing;?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Комната</td><td><?=$roomProductManager->Number;?></td>
                        </tr>
                    </table>
                </div>
            <?php endif;?>
        </div>
    </div>
</header>

<div role="main">
<div class="container">
<!-- Информация об участнике -->
<section class="row row-userinfo">
    <!-- Статус, ФИО, компания -->
    <div class="col-6 col-offset-1">
        <div class="userinfo status"><?=$role->Title;?></div>
        <div class="userinfo"><?=$user->GetFullName();?></div>
        <?if ($user->getEmploymentPrimary() !== null && $user->getEmploymentPrimary()->Company !== null):?>
            <div class="userinfo company"><?=$user->getEmploymentPrimary()->Company->Name;?></div>
        <?endif;?>
    </div>

    <!-- QR-код -->

    <div class="col-4">
        <figure class="qrcode text-center">
            <img src="<?=\ruvents\components\QrCode::getAbsoluteUrl($user,100);?>" />
            <figcaption>RUNET-ID / <?=$user->RunetId;?></figcaption>
        </figure>
    </div>
</section>

<?php

$rif = new \pay\components\admin\Rif();

$userHotel = $rif->getUserHotel($user->RunetId);

$foodProductMatrix = [

    23 => [1467, 1468, 2701, 1469, 1476],

    24 => [1470, 1471, 2702, 1472, null],

    25 => [1473, 1474, 2703, 1475, null]

];

$foodProductIdList = [];

foreach ($foodProductMatrix as $productIdList)

{

    $foodProductIdList = array_merge($foodProductIdList, $productIdList);

}



$criteria = new \CDbCriteria();

$criteria->addInCondition('"t"."ProductId"', $foodProductIdList);

$userFoodOrderItems = \pay\models\OrderItem::model()->byPaid(true)->byAnyOwnerId($user->Id)->findAll($criteria);

$userFoodProductIdList = \CHtml::listData($userFoodOrderItems, 'Id', 'ProductId');

$foodHotels = [];

switch ($userHotel)

{

    case \pay\components\admin\Rif::HOTEL_LD:

        $foodHotels = [

            23 => [\pay\components\admin\Rif::HOTEL_LD, \pay\components\admin\Rif::HOTEL_LD, \pay\components\admin\Rif::HOTEL_P, \pay\components\admin\Rif::HOTEL_LD, \pay\components\admin\Rif::HOTEL_P],

            24 => [\pay\components\admin\Rif::HOTEL_LD, \pay\components\admin\Rif::HOTEL_LD, \pay\components\admin\Rif::HOTEL_P, \pay\components\admin\Rif::HOTEL_LD],

            25 => [\pay\components\admin\Rif::HOTEL_LD, \pay\components\admin\Rif::HOTEL_LD, \pay\components\admin\Rif::HOTEL_P, \pay\components\admin\Rif::HOTEL_LD]

        ];

        break;



    case \pay\components\admin\Rif::HOTEL_P:

        $foodHotels = [

            23 => [\pay\components\admin\Rif::HOTEL_P, \pay\components\admin\Rif::HOTEL_P, \pay\components\admin\Rif::HOTEL_P, \pay\components\admin\Rif::HOTEL_LD, \pay\components\admin\Rif::HOTEL_P],

            24 => [\pay\components\admin\Rif::HOTEL_P, \pay\components\admin\Rif::HOTEL_P, \pay\components\admin\Rif::HOTEL_P, \pay\components\admin\Rif::HOTEL_P],

            25 => [\pay\components\admin\Rif::HOTEL_P, \pay\components\admin\Rif::HOTEL_P, \pay\components\admin\Rif::HOTEL_P, \pay\components\admin\Rif::HOTEL_P]

        ];

        break;



    case \pay\components\admin\Rif::HOTEL_S:

        $foodHotels = [

            23 => [\pay\components\admin\Rif::HOTEL_S, \pay\components\admin\Rif::HOTEL_P, \pay\components\admin\Rif::HOTEL_P, \pay\components\admin\Rif::HOTEL_LD, \pay\components\admin\Rif::HOTEL_P],

            24 => [\pay\components\admin\Rif::HOTEL_S, \pay\components\admin\Rif::HOTEL_P, \pay\components\admin\Rif::HOTEL_P, \pay\components\admin\Rif::HOTEL_P],

            25 => [\pay\components\admin\Rif::HOTEL_S, \pay\components\admin\Rif::HOTEL_P, \pay\components\admin\Rif::HOTEL_P, \pay\components\admin\Rif::HOTEL_P]

        ];

        break;



    case \pay\components\admin\Rif::HOTEL_N:

        $foodHotels = [

            23 => [\pay\components\admin\Rif::HOTEL_N, \pay\components\admin\Rif::HOTEL_P, \pay\components\admin\Rif::HOTEL_P, \pay\components\admin\Rif::HOTEL_LD, \pay\components\admin\Rif::HOTEL_P],

            24 => [\pay\components\admin\Rif::HOTEL_N, \pay\components\admin\Rif::HOTEL_P, \pay\components\admin\Rif::HOTEL_P, \pay\components\admin\Rif::HOTEL_P],

            25 => [\pay\components\admin\Rif::HOTEL_N, \pay\components\admin\Rif::HOTEL_P, \pay\components\admin\Rif::HOTEL_P, \pay\components\admin\Rif::HOTEL_P]

        ];

        break;



    default:

        $foodHotels = [

            23 => [\pay\components\admin\Rif::HOTEL_P, \pay\components\admin\Rif::HOTEL_P, \pay\components\admin\Rif::HOTEL_P, \pay\components\admin\Rif::HOTEL_LD, \pay\components\admin\Rif::HOTEL_P],

            24 => [\pay\components\admin\Rif::HOTEL_P, \pay\components\admin\Rif::HOTEL_P, \pay\components\admin\Rif::HOTEL_P, \pay\components\admin\Rif::HOTEL_P],

            25 => [\pay\components\admin\Rif::HOTEL_P, \pay\components\admin\Rif::HOTEL_P, \pay\components\admin\Rif::HOTEL_P, \pay\components\admin\Rif::HOTEL_P]

        ];

        break;

}



$foodTimes = [

    \pay\components\admin\Rif::HOTEL_P => [

        23 => ['8:00 до 10:00', '14:30 до 15:30', '14:30 до 15:30', null, '21:00'],

        24 => ['8:00 до 10:00', '14:30 до 16:00', '14:30 до 16:00', '20:30 до 23:00'],

        25 => ['8:00 до 10:00', '14:30 до 15:30', '14:30 до 15:30', '19:00 до 20:30']

    ],



    \pay\components\admin\Rif::HOTEL_LD => [

        23 => ['8:00 до 9:30', '14:30 до 15:30', null, '20:30 до 22:30'],

        24 => ['8:00 до 9:30', '14:30 до 16:00', null, '20:30 до 22:00'],

        25 => ['8:00 до 9:30', '14:30 до 15:30', null, '19:00 до 20:30']

    ],



    \pay\components\admin\Rif::HOTEL_N => [

        23 => ['8:00 до 9:30'],

        24 => ['8:00 до 9:30'],

        25 => ['8:00 до 9:30']

    ],



    \pay\components\admin\Rif::HOTEL_S => [

        23 => ['8:00 до 9:30'],

        24 => ['8:00 до 9:30'],

        25 => ['8:00 до 9:30']

    ],

];

?>

<?if (!empty($userFoodProductIdList)):?>
    <div class="row row-timeline noborder" style="padding-bottom: 2mm; padding-top: 2mm;">
        <div class="col-12">
            <table class="food-table">
                <thead>
                <tr>
                    <th></th>
                    <th colspan="2">Завтрак</th>
                    <th colspan="2">Обед</th>
                    <th colspan="2">Ланчбокс</th>
                    <th colspan="2">Ужин</th>
                    <th colspan="2">Фуршет</th>
                </tr>
                </thead>
                <tbody>
                <?for($d = 23; $d <= 25; $d++):?>
                    <tr>
                        <td><?=$d;?>.04</td>
                        <?for($i = 0; $i < 5; $i++):?>
                            <?$hotel = isset($foodHotels[$d][$i]) ? $foodHotels[$d][$i] : null;?>
                            <?if ($hotel !== null):?>
                                <td><?=mb_convert_case($hotel, MB_CASE_TITLE);?>, <?=$foodTimes[$hotel][$d][$i];?></td>
                                <td>
                                    <?if (!empty($foodProductMatrix[$d][$i]) && in_array($foodProductMatrix[$d][$i], $userFoodProductIdList)):?>
                                        +
                                    <?else:?>
                                        &nbsp;
                                    <?endif;?>
                                </td>
                            <?else:?>
                                <td colspan="2">&mdash;</td>
                            <?endif;?>
                        <?endfor;?>
                    </tr>
                <?endfor;?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row row-timeline noborder">
<?else:?>
    <div class="row row-timeline">
<?endif;?>
        <div class="row">
            <!-- Расписание работы регистрации -->
            <div class="col-1 text-right">
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyRpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoTWFjaW50b3NoKSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDpGNDM3NDFBQUI1QjExMUUzQkFGMEM1QTk5MzE1NTIxOCIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDpGNDM3NDFBQkI1QjExMUUzQkFGMEM1QTk5MzE1NTIxOCI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOjJGQUIzQTdGQjVBQzExRTNCQUYwQzVBOTkzMTU1MjE4IiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOjJGQUIzQTgwQjVBQzExRTNCQUYwQzVBOTkzMTU1MjE4Ii8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+RzvMSgAAAxZJREFUeNrMmT9sEnEUxx+krQnSAa1DBV1IhEEGUmxkrCM0OgixSR2aLq1/hy46uJXF2RqtQyetJiQ6KLgpGwnaMApNSAexHSgsRQaMxveOd80Bd3A/KHf3TT4J3P3uve/9+N3vfu+HbWlpCQaQHbmCzCEh5BLiRk7z+d/IL2QX+Y58Rb4h/0QTjQm29yD3kEXkQo92p5AzSAC5ycd+ItvIBlIW6Qk9Oos8R0rI4z7mtETXPOIYFGvqpAwuIAXkLjIBw2uCY/3g2AMbpJ//BfJW790Kaopjv0TGRQ06kA/IKoxeK8h7zqnL4Bjf2TwYp3nOOa7HID1l18F4Uc5n/QwucJebpZXOB0dp8Bw//mZrg6e1LoPrPLmaLTKX6DR4EVkG62iZ31rHBu/0motMEE3m92WDxG2wnuh9b5dXJR4LGiRPszQpX9NqEY1GIRgMth0rFovQaDQglUpptlEqkUiA3++HWCymej6fzx/HUtEcGZzROutwOMDr9bYdk7+TKUqu1qZTTqdTsw3dcA/NkEFfv75Op9OQTCZb/e7xwNrampQwFAqpttFSqVSSbkpAPhqD0yJXlMtlqNVqRo3DaerBSaGRiz3odrulz/V6/fh4OBwGn88nMr70aFLXkj8SiUgolcvloFAoQCAQkL67XC4JgfGluyY5Ur77tMaOMlmlUoFMJqM5Tk9QR2TwoJ9BMjeC5Hp0QAapay4PG4nGXzwe7zqezWaHCbtLBncUpeHAomlHba7b29sbJuyODQv3q3SjYE2FaR7MiRTSBop2JnJ23o54Y0GDr8mbXbHMblrIXFMuP2SD9BNvWcjgFu/ltNUkT5CqBcxV2UtX0VSVl9km66Gyozrr4nfIKxPNUe7tfjsLD5CPJpj7xLn7bn00ubr/bKA5ynVLbSbR2t1qIDeQTQPMbXKuhtrJXvuDf6C1/bY4oqe7yrFXOReIGpRFg9YPrY3Gk5jMmxzL3/lADGqQdMi7D7RceYrsD2Bsn6/1cqxDPRfZhvgbYpZr6hmuDM9Thcnn62yoyMu5L9D6G+KvaKL/AgwAZFjBTyIfWREAAAAASUVORK5CYII=" />
            </div>
            <div class="col-10" style="margin-bottom: 1.3mm;">
                <h3>Режим работы стойки регистрации</h3>
                <div>Регистрация участников, оплата участия. КПП «Поляны»</div>
            </div>
        </div>
        <!-- График по дням -->
        <div class="row row-datetime">
            <div class="col-2 col-offset-1">
                <div class="date"><big>21</big>апреля</div>
                <div class="time"><span>Начало</span> 12:00</div>
                <div class="time"><span>Окончание</span> 02:00</div>
            </div>
            <div class="col-2">
                <div class="date"><big>22</big>апреля</div>
                <div class="time"><span>Начало</span> 07:00</div>
                <div class="time"><span>Окончание</span> 02:00</div>
            </div>
            <div class="col-2">
                <div class="date"><big>23</big>апреля</div>
                <div class="time"><span>Начало</span> 08:00</div>
                <div class="time"><span>Окончание</span> 19:00</div>
            </div>
            <div class="col-2">
                <div class="date"><big>24</big>апреля</div>
                <div class="time"><span>Начало</span> 8:00</div>
                <div class="time"><span>Окончание</span> 16:00</div>
            </div>
        </div>
    </div>

    <!-- Расписание работы оргкомитета -->
    <div class="row row-timeline">
        <div class="row">
            <div class="col-1 text-right">
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyRpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoTWFjaW50b3NoKSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDoyRkFCM0E3REI1QUMxMUUzQkFGMEM1QTk5MzE1NTIxOCIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDoyRkFCM0E3RUI1QUMxMUUzQkFGMEM1QTk5MzE1NTIxOCI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOjJGQUIzQTdCQjVBQzExRTNCQUYwQzVBOTkzMTU1MjE4IiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOjJGQUIzQTdDQjVBQzExRTNCQUYwQzVBOTkzMTU1MjE4Ii8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+XcT0nAAAA7BJREFUeNrMWU1IG1EQHoNaNI1gaw+apIJCFGxBtIntRUxPHqQFe6hgT4I//cu1pfTWHOy5llYFT/3zkoAt1IO0Bw+FpHrx0CZQEdroQYOgTZSUls7szobddZPsRpPdDz42yc6+973Z9+a9mVSMjo5CEbAhvUg/8hLSg3Qi7Xw/hUwg48ivyM/IKPKf0Y4qDdq7kHeRw0h3HrtTyDPIi8gb/NtP5BvkFPKXEU/owVnkc+QP5MMC4nKBnnnAbVBbDSclcAj5HXkHWQ3HRzW39Y3bLlogvf4XyLd6R2sQDdz2S2SVUYG1yDByAkqPcWSI+9QlsJJHNgDlwwD3WaVHIK2ya1B+UJ/PCgkcYpebhXH1wpELPMfL32xMcVg7IvAJB1ezQeKCaoHnkSNgHYzwrpUVeDtfLDIBFMzvSQKJt8B6oP3eJp1KXBYUSJp8FJSv5rNyOp3g9/vB5RLHcHBwALFYDBYXF7M2/f390NnZqXguHo9DOp3O2mnZSAiHw0KbGvCTwO5c4qjRwcFB4fPu7i4cHh5CS0sLdHR0gNvthtnZWXFfrK0VfpdD+k6iJicnNW0k2O32XBK6SWBbLs9J4kKhUNYT9PvY2Bh4vV5IJpPCPQlkI30nu0AgIIjq6urStNGBNpqDjVp3enp6hGs0GlW8zkQiAUtLSwobLZAdef2YaCSBDs3TpVs8k5KX1FheXhau9fX1eeduU1OTeP5PpYoV6Mh55K+pqRGu29vbulujOUuUg94ALQCat8WABO7L9z75KqT509zcnPVYdmK0idOWFo0c6+vrwnMSaHDqZw1inwRuaQnc2NgQrj6fDxYWFmBvb0/hKcLa2tqRQRlYAHqwRQIpAF1Q31ldXRVeD63WYDAIkUhEiGsej0fwLHlvfn6+1ME6TgJXZKmhAhTnaJH09fVBb2+vYl6ROLlXS4SVCkzcL+OHL4Us6+rqwOFwCOGjjLhCHoxwIp13PyZvlcFjilBK2mxcjnhtwcPCK9Jmkx2zMxYSl5HSD0kgveI5Cwmc41qOIid5TDubBcQlWcuRpCkpHbNNRkDuKHVe/A45Y6K4GS7R5a0s3Ee+N0HcB+67YOkjw9n9xzKKo75uakWSXNWtNPI6croM4qa5r7SR8hvhD4jlt+ESre4ktz3BfYFRgRJo0raDWGg8iWCe4bba1QuiWIGEHa4+tCKfIjeLELbJz7ZyWzt6T9RGQDsOFdEf0VmWc+puzgwpATnNdr9ZUIyPc59A/Bvir9FR/RdgABYkE9ZZQEObAAAAAElFTkSuQmCC" />
            </div>
            <div class="col-10">
                <h3>Режим работы стойки орг. комитета</h3>
                <div>Выдача отчетных документов, оплата доп.услуг, отметка командировочных удостоверений, орг. вопросы: холл первого корпуса «Поляны»</div>
            </div>
        </div>
        <div class="row row-datetime">
            <div class="col-2 col-offset-1">
                <div class="date"><big>21</big>апреля</div>
                <div class="time"><span>Начало</span> 15:30</div>
                <div class="time"><span>Окончание</span> 22:00</div>
            </div>
            <div class="col-2">
                <div class="date"><big>22</big>апреля</div>
                <div class="time"><span>Начало</span> 8:00</div>
                <div class="time"><span>Окончание</span> 21:00</div>
            </div>
            <div class="col-2">
                <div class="date"><big>23</big>апреля</div>
                <div class="time"><span>Начало</span> 8:00</div>
                <div class="time"><span>Окончание</span> 20:00</div>
            </div>
            <div class="col-2">
                <div class="date"><big>24</big>апреля</div>
                <div class="time"><span>Начало</span> 8:00</div>
                <div class="time"><span>Окончание</span> 19:00</div>
            </div>
        </div>
    </div>
</div>
<div class="row row-reminder">
    <div class="col-3" style="padding-left: 15mm">
        <h2>Памятка участника</h2>
        <ul>
            <li>Распечатать путевой лист</li>
            <li>Выбрать вид транспорта</li>
            <li>Зарегистрироваться</li>
            <li>Оплатить дополнительные услуги</li>
            <li>Посетить выставку и конференцию РИФ+КИБ</li>
            <li>Получить отчетные документы и оформить командировочное удостоверение</li>
        </ul>
    </div>
    <div class="col-3">
        <h2>Заселение</h2>
        <ul>
            <li>Поляны (т-образный перекресток,<br/>поворот налево, 28,5 км)</li>
            <li>Лесные Дали (т-образный перекресток,<br/>поворот направо, 28,5 км)</li>
            <li>Назарьево (Поворот налево на 2-е Успенское ш.,<br/>22-й км)</li>
            <li>Сосны (Поворот направо Рублево-Успенского ш.,<br/>20-й км, пост ГАИ)</li>
        </ul>
    </div>
    <div class="col-3">
        <h2>Оплата услуг
            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyRpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoTWFjaW50b3NoKSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDoyRkFCM0E3REI1QUMxMUUzQkFGMEM1QTk5MzE1NTIxOCIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDoyRkFCM0E3RUI1QUMxMUUzQkFGMEM1QTk5MzE1NTIxOCI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOjJGQUIzQTdCQjVBQzExRTNCQUYwQzVBOTkzMTU1MjE4IiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOjJGQUIzQTdDQjVBQzExRTNCQUYwQzVBOTkzMTU1MjE4Ii8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+XcT0nAAAA7BJREFUeNrMWU1IG1EQHoNaNI1gaw+apIJCFGxBtIntRUxPHqQFe6hgT4I//cu1pfTWHOy5llYFT/3zkoAt1IO0Bw+FpHrx0CZQEdroQYOgTZSUls7szobddZPsRpPdDz42yc6+973Z9+a9mVSMjo5CEbAhvUg/8hLSg3Qi7Xw/hUwg48ivyM/IKPKf0Y4qDdq7kHeRw0h3HrtTyDPIi8gb/NtP5BvkFPKXEU/owVnkc+QP5MMC4nKBnnnAbVBbDSclcAj5HXkHWQ3HRzW39Y3bLlogvf4XyLd6R2sQDdz2S2SVUYG1yDByAkqPcWSI+9QlsJJHNgDlwwD3WaVHIK2ya1B+UJ/PCgkcYpebhXH1wpELPMfL32xMcVg7IvAJB1ezQeKCaoHnkSNgHYzwrpUVeDtfLDIBFMzvSQKJt8B6oP3eJp1KXBYUSJp8FJSv5rNyOp3g9/vB5RLHcHBwALFYDBYXF7M2/f390NnZqXguHo9DOp3O2mnZSAiHw0KbGvCTwO5c4qjRwcFB4fPu7i4cHh5CS0sLdHR0gNvthtnZWXFfrK0VfpdD+k6iJicnNW0k2O32XBK6SWBbLs9J4kKhUNYT9PvY2Bh4vV5IJpPCPQlkI30nu0AgIIjq6urStNGBNpqDjVp3enp6hGs0GlW8zkQiAUtLSwobLZAdef2YaCSBDs3TpVs8k5KX1FheXhau9fX1eeduU1OTeP5PpYoV6Mh55K+pqRGu29vbulujOUuUg94ALQCat8WABO7L9z75KqT509zcnPVYdmK0idOWFo0c6+vrwnMSaHDqZw1inwRuaQnc2NgQrj6fDxYWFmBvb0/hKcLa2tqRQRlYAHqwRQIpAF1Q31ldXRVeD63WYDAIkUhEiGsej0fwLHlvfn6+1ME6TgJXZKmhAhTnaJH09fVBb2+vYl6ROLlXS4SVCkzcL+OHL4Us6+rqwOFwCOGjjLhCHoxwIp13PyZvlcFjilBK2mxcjnhtwcPCK9Jmkx2zMxYSl5HSD0kgveI5Cwmc41qOIid5TDubBcQlWcuRpCkpHbNNRkDuKHVe/A45Y6K4GS7R5a0s3Ee+N0HcB+67YOkjw9n9xzKKo75uakWSXNWtNPI6croM4qa5r7SR8hvhD4jlt+ESre4ktz3BfYFRgRJo0raDWGg8iWCe4bba1QuiWIGEHa4+tCKfIjeLELbJz7ZyWzt6T9RGQDsOFdEf0VmWc+puzgwpATnNdr9ZUIyPc59A/Bvir9FR/RdgABYkE9ZZQEObAAAAAElFTkSuQmCC" style="width: 5.3mm; margin: 0 0.8mm;"/>
            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyRpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoTWFjaW50b3NoKSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDpGNDM3NDFBQUI1QjExMUUzQkFGMEM1QTk5MzE1NTIxOCIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDpGNDM3NDFBQkI1QjExMUUzQkFGMEM1QTk5MzE1NTIxOCI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOjJGQUIzQTdGQjVBQzExRTNCQUYwQzVBOTkzMTU1MjE4IiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOjJGQUIzQTgwQjVBQzExRTNCQUYwQzVBOTkzMTU1MjE4Ii8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+RzvMSgAAAxZJREFUeNrMmT9sEnEUxx+krQnSAa1DBV1IhEEGUmxkrCM0OgixSR2aLq1/hy46uJXF2RqtQyetJiQ6KLgpGwnaMApNSAexHSgsRQaMxveOd80Bd3A/KHf3TT4J3P3uve/9+N3vfu+HbWlpCQaQHbmCzCEh5BLiRk7z+d/IL2QX+Y58Rb4h/0QTjQm29yD3kEXkQo92p5AzSAC5ycd+ItvIBlIW6Qk9Oos8R0rI4z7mtETXPOIYFGvqpAwuIAXkLjIBw2uCY/3g2AMbpJ//BfJW790Kaopjv0TGRQ06kA/IKoxeK8h7zqnL4Bjf2TwYp3nOOa7HID1l18F4Uc5n/QwucJebpZXOB0dp8Bw//mZrg6e1LoPrPLmaLTKX6DR4EVkG62iZ31rHBu/0motMEE3m92WDxG2wnuh9b5dXJR4LGiRPszQpX9NqEY1GIRgMth0rFovQaDQglUpptlEqkUiA3++HWCymej6fzx/HUtEcGZzROutwOMDr9bYdk7+TKUqu1qZTTqdTsw3dcA/NkEFfv75Op9OQTCZb/e7xwNrampQwFAqpttFSqVSSbkpAPhqD0yJXlMtlqNVqRo3DaerBSaGRiz3odrulz/V6/fh4OBwGn88nMr70aFLXkj8SiUgolcvloFAoQCAQkL67XC4JgfGluyY5Ur77tMaOMlmlUoFMJqM5Tk9QR2TwoJ9BMjeC5Hp0QAapay4PG4nGXzwe7zqezWaHCbtLBncUpeHAomlHba7b29sbJuyODQv3q3SjYE2FaR7MiRTSBop2JnJ23o54Y0GDr8mbXbHMblrIXFMuP2SD9BNvWcjgFu/ltNUkT5CqBcxV2UtX0VSVl9km66Gyozrr4nfIKxPNUe7tfjsLD5CPJpj7xLn7bn00ubr/bKA5ynVLbSbR2t1qIDeQTQPMbXKuhtrJXvuDf6C1/bY4oqe7yrFXOReIGpRFg9YPrY3Gk5jMmxzL3/lADGqQdMi7D7RceYrsD2Bsn6/1cqxDPRfZhvgbYpZr6hmuDM9Thcnn62yoyMu5L9D6G+KvaKL/AgwAZFjBTyIfWREAAAAASUVORK5CYII=" style="width: 5.3mm;" />
        </h2>
        <ul>
            <li>Регистрационный взнос</li>
            <li>Питание на мероприятии</li>
            <li>Билет на банкет (при наличии мест)</li>
            <li>Подписка на журнал «Интернет в Цифрах»</li>
        </ul>
    </div>
</div>
</div>
<sethtmlpagefooter name="main-footer" value="on" show-this-page="1" />
</div>
</div>
<pagebreak />


<div class="page-transport">
<div style="position: absolute; width: 80mm; right: 10mm; top: 35mm;">
    <img src="data:image/jpeg;base64,/9j/4QAYRXhpZgAASUkqAAgAAAAAAAAAAAAAAP/sABFEdWNreQABAAQAAABQAAD/4QMtaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLwA8P3hwYWNrZXQgYmVnaW49Iu+7vyIgaWQ9Ilc1TTBNcENlaGlIenJlU3pOVGN6a2M5ZCI/PiA8eDp4bXBtZXRhIHhtbG5zOng9ImFkb2JlOm5zOm1ldGEvIiB4OnhtcHRrPSJBZG9iZSBYTVAgQ29yZSA1LjMtYzAxMSA2Ni4xNDU2NjEsIDIwMTIvMDIvMDYtMTQ6NTY6MjcgICAgICAgICI+IDxyZGY6UkRGIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyI+IDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSIiIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bXA6Q3JlYXRvclRvb2w9IkFkb2JlIFBob3Rvc2hvcCBDUzYgKE1hY2ludG9zaCkiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6RTIzQjkyNjVCRTIxMTFFM0FCNTJBQTk5ODA2RkUxNkYiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6RTIzQjkyNjZCRTIxMTFFM0FCNTJBQTk5ODA2RkUxNkYiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDpFMjNCOTI2M0JFMjExMUUzQUI1MkFBOTk4MDZGRTE2RiIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDpFMjNCOTI2NEJFMjExMUUzQUI1MkFBOTk4MDZGRTE2RiIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/Pv/uAA5BZG9iZQBkwAAAAAH/2wCEAAICAgICAgICAgIDAgICAwQD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.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.2mmO7KyIaGku/MKBUhhoKUptK0BWpO02Er0/acZUbf21TVeKtxucMvOUqLM4unBNowfqa8pQzKMfupKLpwd2Vrjn4xjGryqSqsWk0d59PfqRnuW8qk42bFYj4v5bKOtbGgCl2DMjstpQ8JDnmjypCStRbQN3u3ArQ3DbdPY0yuQbz1gmm+GeDliqKjqsFmlhxOntu66m/q3auRSg1caaXHJOMVllmeZUli3GP3cMETjVfLKWglPnrVvustpBR4AFVj9t6Aj5Kt3n9N3nydPSHl/2UAW4hpDjrq0tMspKluLO0JCRckk9h40B+as7+o/6scVh/Uz6nvYTJp4nzrjOVzf0Xcz/yLmD83CJflQEQ0RJiZivzHDoVNdS6hG1TRCVG+omMXJ0Ra5B9bfrHyjPcVzOGxmajMRZmLbzfGsZjX5cOMHuSSIOYx8sR4E4uScdBZ2yFqlx07yh1htQWBUlm00HpYKnVmfvxrg/CutHD5j+oL6syeVTZX01zPI5eM5fG5O1xWNL43KZxjjOOyWFXCkYqcvDPfMOO4aPl5zPlfM70gHyXVBDaoOTrtZPVXKKrjXBG9yf1A/qqaHHXMa5lOQY/IY/PYQJGLeebizZ80RcLlHnpeGxb7/yTqkhZWw0hce7xDm0uuSdPR6W5plWKxaaeHDo4rk+PxNRC5f8A1hu4CFNgwuRNyjHgAz5OBZ89yflfpoJiQhkQTsYh51tbj6lI/jONsA2C2qGlrdwuTj5deivwVV8a1JIzX1M/qKj4vDzJEHlkPlWK/MZufxMPEOu49x9mfFSxFZSxgpCpTQxp85SlvRrlxzaPNAbjQcg/RdpYcQhxAVtcAUgKSpBsrWxSoApPoIvQHl/zA3ub1cQttTYt8QcTa1ASKo/jtjcRdCzt7GxTr9lAXaAUAoDgfqCxvx+MkXUPlp6d1ulnG1o19FyKAhbkGJk5mE3GjZAY9TbwcebcYblR5LW1SFx5DLlt7agq9kqSbgHd1BA4fC/TiZg8rg5zPJ1z4eCiNxImOmxEOJZ2.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.3mmZ7Duakser1UNNbzP3I7thpuM0200izaQLes9z6SaxKVevSvTc5PFl/7OpsRQ8iuqbDqABa9qApZOt031vqL0B4IdceajsJ3yZBKYyD0FrFSlEfCnqfsHU0BsVcSxCc3g85IYbyOV43FkIxMl9htZiLmH+ckMr2723H0DyztPu6WtegOxJIcQu/sOJ2kG/XqNPv/AEUBdoBQCgKEXBHiLUB84tNqj+bDWVebBdXHeSvRYLaiBuHa6bEeg0Bc8Ol70A9Wl6AzMXlchg5KpmPIWl0gzMatW1mQB1N9djltAvv0VcdAJpw+bx+cjGTAdJ8tXlyY6xtdZcHVDiex/QeouKA21AKAUAoBQCgFAKAUAoBQCgFAKAj7lPElylry2FQlM8ndOgaJRLAFtwJ0S6B0V0V0V2IAjJKgreLFCkKLbraxtWhafeStJ1SoeFAerdR3PegK+noB0oClxoaAev7aAr+nwoBrbSgKW9froDb4HEfnuWZhuIKoETbKyZ6JKQfwmf8AOoXP7oPjQE60AoBQHL8m5AjDxvKYUFZCQn8FPXYnpvI/V6aHT23QPUyrLwrj19RGOJw07kEl5LbvljVUmc4CsJKu5FxuJPa9SWPW6uGkt4ceS9uSOu5BmZ/G5GKgQsNDlYR2.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.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.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" />
</div>
<header>
    <div class="container">
        <div class="row">
            <!-- Логотип -->
            <div class="col-7">
                <div class="logo">
                    <img src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48IURPQ1RZUEUgc3ZnIFBVQkxJQyAiLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4iICJodHRwOi8vd3d3LnczLm9yZy9HcmFwaGljcy9TVkcvMS4xL0RURC9zdmcxMS5kdGQiPjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiIHZpZXdCb3g9IjAgMCAyMDggMzQuNTI0IiBlbmFibGUtYmFja2dyb3VuZD0ibmV3IDAgMCAyMDggMzQuNTI0IiB4bWw6c3BhY2U9InByZXNlcnZlIj48Zz48cGF0aCBmaWxsPSIjMTdBNjY5IiBkPSJNMTkuODc3LDQuNzRjMS45NDgtMC45NjYsMy44NjYtMS43NTksNS42OTctMi4zNTljMC4wNTEtMC4wMTUsMC4wODctMC4wNjEsMC4wOTItMC4xMTFjMC4wMDUtMC4wNTQtMC4wMjItMC4xMDQtMC4wNjktMC4xM2MtMC42OTUtMC4zODQtMS40MjEtMC43Mi0yLjE2Mi0xLjAwNGMtMC4wMjQtMC4wMDktMC4wNTEtMC4wMDktMC4wNzgtMC4wMDVjLTEuNzM1LDAuNDExLTMuNTE1LDAuOTcxLTUuMjkxLDEuNjY5QzguMzk4LDYuNzgzLDIuMzI5LDExLjM0NywwLjAzMywxNi4zNmMtMC4wMDYsMC4wMTUtMC4wMTIsMC4wMzMtMC4wMTIsMC4wNDljLTAuMTA0LDIuMTAyLDAuMTc1LDQuMTgzLDAuODI1LDYuMTk0YzAuMDE4LDAuMDUzLDAuMDcsMC4wOTEsMC4xMjcsMC4wOTFjMC4wMDMsMCwwLjAwNywwLDAuMDExLDBjMC4wNjEtMC4wMDYsMC4xMS0wLjA1MiwwLjEyMS0wLjExMkMxLjk4OSwxNi42OSw4LjMwNSwxMC42ODcsMTkuODc3LDQuNzR6Ii8+PHBhdGggZmlsbD0iIzE3QTY2OSIgZD0iTTIuMjk4LDkuMDYyYzAuMDMxLDAsMC4wNjEtMC4wMTEsMC4wODctMC4wMzRjMy4xOS0yLjgzMSw4LjA1LTUuMjkxLDE0LjQ0LTcuMzA5YzEuNDM4LTAuNDIzLDIuOTA5LTAuNzYzLDQuMzctMS4wMDdjMC4wNjItMC4wMSwwLjEwOC0wLjA2NCwwLjExMS0wLjEyNmMwLjAwMi0wLjA2My0wLjA0My0wLjExOS0wLjEwMy0wLjEzNEMxOS45MTYsMC4xNTMsMTguNTg4LDAsMTcuMjYzLDBDMTIuNjUxLDAsOC4zMTcsMS43OTQsNS4wNTcsNS4wNTJDMy45Myw2LjE4MywyLjk2Miw3LjQ2NiwyLjE4MSw4Ljg2NkMyLjE1LDguOTIzLDIuMTYyLDguOTkzLDIuMjE0LDkuMDMzQzIuMjM4LDkuMDU0LDIuMjY4LDkuMDYyLDIuMjk4LDkuMDYyeiIvPjxwYXRoIGZpbGw9IiMxN0E2NjkiIGQ9Ik0zMy4zMTIsMTAuOTQzYzAuMDA2LTAuMDI2LDAuMDAzLTAuMDU1LTAuMDA3LTAuMDc5Yy0wLjI4MS0wLjcwOS0wLjYyMi0xLjQxNi0xLjAxMS0yLjEwMmMtMC4wMjctMC4wNDQtMC4wNzctMC4wNzUtMC4xMjgtMC4wNjZjLTAuMDUyLDAuMDA0LTAuMDk1LDAuMDQyLTAuMTEzLDAuMDkxYy0wLjU5OCwxLjg0OS0xLjM5OCwzLjc4My0yLjM3Myw1Ljc1MmMtNS45MjIsMTEuNTE5LTEyLjE0NiwxOC4wMi0xOC4wMDIsMTguNzk3Yy0wLjA1OSwwLjAwNi0wLjExLDAuMDU3LTAuMTE1LDAuMTE4Yy0wLjAwNSwwLjA2LDAuMDMyLDAuMTE3LDAuMDksMC4xMzljMS44MDIsMC42MTgsMy42OSwwLjkzLDUuNjEyLDAuOTNjMC4xNTQsMCwwLjMwOS0wLjAwMSwwLjQ2NC0wLjAwNWMwLjAxNywwLDAuMDMzLTAuMDA0LDAuMDQ4LTAuMDExYzUuMTI2LTIuMTk1LDkuNzg0LTguMzA0LDEzLjg0Ni0xOC4xNTlDMzIuMzI5LDE0LjU0LDMyLjg5NywxMi43MjMsMzMuMzEyLDEwLjk0M3oiLz48cGF0aCBmaWxsPSIjMTdBNjY5IiBkPSJNMzQuMDc1LDEzLjMyNWMtMC4wMTMtMC4wNi0wLjA2My0wLjA5Ni0wLjEzMy0wLjEwMmMtMC4wNjMsMC4wMDItMC4xMTQsMC4wNDktMC4xMjUsMC4xMTJjLTAuMjQ1LDEuNDYzLTAuNTg1LDIuOTMzLTEuMDA2LDQuMzY4Yy0yLjAyMSw2LjM5MS00LjQ4MSwxMS4yNTItNy4zMTMsMTQuNDQ1Yy0wLjA0NCwwLjA0Ni0wLjA0NCwwLjExOS0wLjAwNSwwLjE2OWMwLjAyNywwLjAzMywwLjA2NiwwLjA1MSwwLjEwNCwwLjA1MWMwLjAyMiwwLDAuMDQ2LTAuMDA0LDAuMDY0LTAuMDE4YzEuNDA0LTAuNzgsMi42ODUtMS43NDgsMy44MTItMi44NzZDMzMuNzIzLDI1LjIyMywzNS40NDMsMTkuMTg4LDM0LjA3NSwxMy4zMjV6Ii8+PHBhdGggZmlsbD0iIzE3QTY2OSIgZD0iTTI2LjkyOSwxMS43MDZjMS40MTItMS44NSwyLjY2LTMuNjA5LDMuNzA5LTUuMjI1YzAuMDMxLTAuMDQ3LDAuMDI2LTAuMTEtMC4wMS0wLjE1M2MtMC4zNjYtMC40NDgtMC43NTMtMC44NzUtMS4xNTItMS4yNzdjLTAuMzQzLTAuMzQxLTAuNzEzLTAuNjgtMS4xMzQtMS4wMjljLTAuMDQ1LTAuMDM4LTAuMTA2LTAuMDQyLTAuMTU3LTAuMDA5Yy0xLjYyMiwxLjA1My0zLjM4MywyLjMwMS01LjIzNywzLjcxNkM4Ljc3MiwxOC42NzksNC41OTEsMjQuNjI2LDMuNTgxLDI3LjY4OWMtMC4wMTUsMC4wNDEtMC4wMDgsMC4wODksMC4wMiwwLjEyYzAuNDY1LDAuNjAzLDAuOTU1LDEuMTYzLDEuNDU2LDEuNjY0YzAuNTUzLDAuNTUyLDEuMTY1LDEuMDg0LDEuODE2LDEuNTc1YzAuMDIxLDAuMDE4LDAuMDUsMC4wMjcsMC4wNzksMC4wMjdjMC4wMTMsMCwwLjAyOC0wLjAwNCwwLjA0Mi0wLjAwOEMxMC4wNTcsMzAuMDQ0LDE2LjAwMSwyNS44NTMsMjYuOTI5LDExLjcwNnoiLz48L2c+PGc+PHBhdGggZD0iTTQ4LjY5NywyNC4zMzRWOS45MTloOC4wNDVjMi45NTcsMCwzLjk1LDIuMTEsMy45NSw1LjA2N2MwLDIuNjA2LTEuMjQxLDQuODYtMy45MjksNC44NmgtNC43MzZ2NC40ODhINDguNjk3eiBNNTUuNTg0LDE3LjA5NWMwLjg2OSwwLDEuNDQ4LTAuNiwxLjQ0OC0yLjEwOWMwLTEuNTcyLTAuNDk2LTIuMjEzLTEuNDA2LTIuMjEzaC0zLjU5OHY0LjMyMkg1NS41ODR6Ii8+PHBhdGggZD0iTTc1LjkxMywyNC4zMzRoLTMuMjg4di05LjQ5MmMtMS42OTYsMy4yMDYtMy41NTcsNi40NzMtNS40MTgsOS40OTJoLTQuMzY0VjkuOTE5aDMuMjY4djkuODg1YzEuOTg1LTMuMjQ3LDMuOTUtNi41OTcsNS43MjgtOS44ODVoNC4wNzRWMjQuMzM0eiIvPjxwYXRoIGQ9Ik04Ny43MDEsOS45MTl2MS42NTRjNC44NiwwLjI0OCw2LjI2NiwxLjgyLDYuMjY2LDUuNTIyYzAsMy43MjItMS40MjcsNS4yNzQtNi4yNjYsNS41MjJ2MS43MTZoLTMuMzN2LTEuNjk2Yy00Ljc5OC0wLjI0OC02LjA1OS0xLjgyLTYuMDU5LTUuNTQyczEuMjYyLTUuMjc0LDYuMDU5LTUuNTIyVjkuOTE5SDg3LjcwMXogTTg0LjM3MSwxOS45Mjl2LTUuNjI1Yy0yLjMxNiwwLjA4My0yLjY4OCwwLjc0NC0yLjY4OCwyLjc5MkM4MS42ODMsMTkuMTIyLDgyLjA1NSwxOS44MjUsODQuMzcxLDE5LjkyOXogTTg3LjcwMSwxNC4zMDR2NS42MjVjMi41MDItMC4wODMsMi44MzMtMC43NjUsMi44MzMtMi44MzNTOTAuMjAzLDE0LjM2Niw4Ny43MDEsMTQuMzA0eiIvPjxwYXRoIGQ9Ik05NS40OTgsMTUuMjk2aDMuNTM2di0zLjU1N2gzLjA0djMuNTU3aDMuNTU3djMuMDRoLTMuNTU3djMuNTM2aC0zLjA0di0zLjUzNmgtMy41MzZWMTUuMjk2eiIvPjxwYXRoIGQ9Ik0xMTYuOTAyLDI0LjMzNGMtMS44NDEtNS4yOTQtMy4yMDYtNi40NzMtNS40OC02LjcyMXY2LjcyMWgtMy4zM1Y5LjkxOWgzLjMzdjYuOTA3YzEuMTU4LTEuMjgyLDMuMjQ3LTMuOTI5LDUuMDg4LTYuOTA3aDMuOTcxYy0yLjM5OSwzLjcyMy00LjA3NCw1Ljg3My00LjcxNSw2LjQ3M2MxLjUzLDAuNTc5LDIuOTk5LDEuNjc1LDUuMDg4LDcuOTQxSDExNi45MDJ6Ii8+PHBhdGggZD0iTTEzNS4zNywyNC4zMzRoLTMuMjg4di05LjQ5MmMtMS42OTYsMy4yMDYtMy41NTcsNi40NzMtNS40MTgsOS40OTJIMTIyLjNWOS45MTloMy4yNjh2OS44ODVjMS45ODUtMy4yNDcsMy45NS02LjU5Nyw1LjcyOC05Ljg4NWg0LjA3NFYyNC4zMzR6Ii8+PHBhdGggZD0iTTE0OS43NDMsMTIuODM1aC03LjY1MnYyLjY0N2g0LjgzOWMyLjUyMywwLDMuODY3LDEuNDg5LDMuODY3LDQuNDQ2YzAsMy40MTItMS40NjgsNC40MDUtMy44MDUsNC40MDVoLTguMjMxVjkuOTE5aDEwLjk4MVYxMi44MzV6IE0xNDYuMTI0LDIxLjVjMC43MDMsMCwxLjA1NS0wLjQ3NiwxLjA1NS0xLjU5MmMwLTEuMTc5LTAuMzkzLTEuNzE3LTEuMTM4LTEuNzE3aC0zLjk1VjIxLjVIMTQ2LjEyNHoiLz48cGF0aCBkPSJNMTU4LjYxNSwxMC4yOTFjMS4xNzktMC40MTQsMy4wNC0wLjcyNCw0LjkwMS0wLjcyNGMzLjYxOSwwLDUuMzk4LDAuODI3LDUuMzk4LDQuMzIyYzAsMi45OTktMC44NDgsMy45NzEtNi4xNjMsNy41MjhoNi4yMjV2Mi45MTZIMTU4LjE2di0yLjcwOWM2LjgwNC01LjA2Nyw3LjIxNy01LjQ2LDcuMjE3LTcuMzgzYzAtMS4zMDMtMC41MzgtMS42NzUtMi4yMTMtMS42NzVjLTEuMzI0LDAtMi45MzcsMC4yNjktMy44NDcsMC41NThMMTU4LjYxNSwxMC4yOTF6Ii8+PHBhdGggZD0iTTE4Mi4zNzcsMTYuOTNjMCw1LjkzNS0xLjkwMiw3LjY5My01LjU2Myw3LjY5M2MtMy42NiwwLTUuNTg0LTEuNzU4LTUuNTg0LTcuNjkzYzAtNS44MzIsMS45MjMtNy4zNjIsNS41ODQtNy4zNjJDMTgwLjQ3NCw5LjU2NywxODIuMzc3LDExLjA5OCwxODIuMzc3LDE2LjkzeiBNMTc5LjA2OCwxNi45M2MwLTMuOTA5LTAuMzUyLTQuMzQzLTIuMjU0LTQuMzQzYy0xLjg2MSwwLTIuMjM0LDAuNDM0LTIuMjM0LDQuMzQzYzAsNC4wMTIsMC4zNzIsNC42MzIsMi4yMzQsNC42MzJDMTc4LjcxNiwyMS41NjIsMTc5LjA2OCwyMC45NDIsMTc5LjA2OCwxNi45M3oiLz48cGF0aCBkPSJNMTg4LjkxMiwyNC4zMzRWMTMuNTE3bC0zLjIyNiwxLjMwM2wtMC44NjgtMi44MTJsNy4zODMtMi43NTF2MTUuMDc2SDE4OC45MTJ6Ii8+PHBhdGggZD0iTTIwNy40NjIsOS45MTl2Mi45MTZoLTYuMTg0djIuNDgyYzAuNDc2LTAuMDQxLDAuOTUxLTAuMDYyLDEuNDI3LTAuMDYyYzQuMDk1LDAsNS4yOTQsMS4yNDEsNS4yOTQsNC41MDhjMCwzLjc2NC0xLjQ2OCw0Ljg2LTUuNjg3LDQuODZjLTEuMzY1LDAtMy42ODEtMC4yMDctNC44MTgtMC40OTZsMC42Mi0yLjk1N2MwLjc2NSwwLjI0OCwyLjQyLDAuMzkzLDMuNDEyLDAuMzkzYzIuMzc4LDAsMi44MzMtMC4yNjksMi44MzMtMS42NzVjMC0xLjQ0OC0wLjMzMS0xLjc3OS0yLjgxMi0xLjc3OWMtMS4wMTMsMC0yLjQ4MiwwLjA4My0zLjQxMiwwLjIwN1Y5LjkxOUgyMDcuNDYyeiIvPjwvZz48L3N2Zz4=" />
                </div>
            </div>
        </div>
    </div>
</header>
<div role="main">
<div class="container">
<!-- Заголовок -->
<div class="row row-transport">
    <div class="col-12">
        <div class="title">Как добраться?</div>
        <div class="row" style="margin-bottom: 5mm;">
            <div class="col-1 text-right">
                <img alt="" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABoAAAAVCAYAAABYHP4bAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAutJREFUeNq8ls9LVFEUx+978+aHkw0WYlimIBk62CqiGgmDMgSRatUiBUHcBUGt+gtq4y6IWrsMNxNiLoQEa6MYJNMwRbscBWPQIZvf0+drzxCbwmfggcO579xzzvfee86591mjo6MmmUya+fl5I2pubjYDAwNma2vLrlQqp0KhUHu1Wj1hWdYxZAiTkPlFOXQ5dBnkWj6f/4L8Gg6HK1NTU2ZlZWXbqKenx3R2dhrH1CCcLziOM47jeQVG2tIj/7B1dZVAICDQRcYP4IW9dnYNx9PwpG3bV5DhWjY1yJatfBi/hFv2GjilUsm0tbWZpqYmw1EZVna3XC63sLo3zM/AfrM/KsI38O3luIYKhcITgE1dXZ0RhkNABTfBYNBg5Pf5fMPSQQ/hReONXuO7UF9fP0yscWIVFUsb0LEo6Y0oWlnBHWQU3Vudtwu4L5KtmyP5Rt1YrYotDBXDfZT3kFI4rtOEkuw67xvM9ZFvjM/ncInY64A9dfx+/zUG7bsCbjKedccBL+eGXwExC9AmMuKCR8Dos6jx7mg0OhiJRIbQ69hknMKpJDuPOaoSWKdyVoskRiKbzU4kEom4s7Gxsby6urpMY/ZREFEmtYvuWj3jheRP5a0R+zEYxhoZGdGWzzHxjvkjbpmqSHwHxCirgd22+E5aLpOnD2o0w6BLIAB+hnuZvIlc94ogH8r6FvKqYikmJd4lDCeXyxk+GkiYDOe0M4wF/p7xdS9ALHCpWCy+Uk9Cc/AZvhvURw7naACxBAT1A3Yb4OOAXPS6I/wuwWME/oZ/v3bCrWABZhx97CQeeRIx+R81cBR+oatnd1Fsp8ccEh0aUK33SFeEKu5ADevGbNzrWwsoS0EMIj/CQY9AeVjlPLNzBf0GisfjpqOjIx2LxdTJKvE0nHTvK+OxvOWfpPLSuuO2c2PbaRWHTz2TyWRSPH7rXEM/MHzE/Ke/Pd3/unL0wMF5gJb4FYhw9Tybnp6eSKVS5qcAAwAj+HJyQ4fmhwAAAABJRU5ErkJggg==" />
            </div>
            <div class="col-4">
                <h3>Личный автотранспорт</h3>
                38 км. от МКАД по Рублёво-Успенскому шоссе<br/>Московская область, Одинцовский район, поселок Горки-10, пансионат «Лесные Дали»
            </div>
        </div>

        <!-- Электричка -->
        <div class="row" style="margin-bottom: 5mm;">
            <div class="col-1 text-right">
                <img alt="" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABoAAAAmCAYAAADa8osYAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAABMdJREFUeNrsl01oXFUUgN+8efOXzGQyJE5k4k+kKUm1JEiLCxe2tabFhQ1IESFQ0KyqUqkiWdSN2CJqC/5sdNO60IULF4oFUVQ0QlHUCCXShcWYMMMU0iZNMiQzycz4nTf3jtfJm5nXjSsvHO59755zz/+55wbGx8etQCBgyQiHw1YsFnO/q9WqVSqVrK2tLfeb//dWKpXI3NzcTCqV6unt7d1dLpd/YW+N/y5uKBSyHMdxaRYXF921nCPDWV5edhfyo6+vr77WQ5jYtj3M/BOz3d/fv48DjoPzFNvvMp+wjCG0QrO6uuoCJDVGWpvGoQlESpC3+FXmu4zWReai7KNJCa086ZWA/zCyvEcKxAEgwprzKqLiK2JdDk7w/2sOWIbZD6z3GAJfB/4UoRoPbGQUguAk87PAXaKNGmUOnWa+GQwGH2G9yXodmAD/ToN+A/gWmALn8r8YaX8oM73J/LzpI2XG74GHdZDUuZfLR/DXpwZqFHgUYe7HxAfX1tZ+1/i2RIbYMZFI7I9GoycamahR8GBuKa28/HN7Op0+E4lE3EgWcIgivXkYXwRMZxoHHgDOAisinNovCY3GF2ElzK2aUy2EfnBoaEjMutDoo7TB5MbGxsaHMMmRP8IxhDnELCEDv4N/3zF/AV51ZWUlGI/Hx/jep/eBbi9G2jYV6I4Vi8WLErqdnZ1WsxA2zSg5A6PXWV9E0EPqvLq9bQ+iWaZvdB408ZlnzjAk3z7xwvHKo6IkYkdHR93efkZPT4/GLXgVAS9GgZqQgW3lqJXpdAVQ9L4YycmVZqWpmekMoTxNsM1HJG06n8/fls1mLaMy+NJqdnZWNNvhixEEUnpeJpFTrIVT2Ae4eOTOAbR7xpfpJJS5Lp5j+Tjr6wF/NqyC5gwPD98DTdSvj3T0ZBT4Hq3yzbb+o/E/o5a51CpumjGSwipXctYnn8Lm5uYVgmH1lhgRda8ySeLtBC60YZIlScfm5+d3kej7ycGFdoy03leBd2q5W5Ub9LQU2mbmoof7mJ7uktQ6tPqV7/NeNc9kdFNfWBDFpKQoSDbLN9WSJSR/BgYGrJmZGSuXyyWlPRDh2FvXvguOjIxoujg/nmBOAHeoe0larrek9Wpqe5pLphvgXKMEHc1kMqdYh6H/DThX135iYqJeT5FEbscx9b2uNI74iDgJngL0nWhni83x8xG2PvcynfRqx0D4UpX7mB8myoRyTgL/2DBa4nvSZLIt6pAsTy9+GHjBuMhu5aq4jI/uQ9gLho9rF6P5IS+Crq6uCK+Fx/xe4Q2C7qZ9e0CCoxGcxoqLnaekj1MNxxVCNsd3sJUisg/uXjE389sI/SOC5s1K4Ug3aai+F8QpQUC77NLS0qHu7u4Fn+XnNHCKM+6mMz2DgJOmVWz9tACiNITnVGMoD6mp6enpBb++4tDXYHJJMX4auqPm08U85SXgIaXZB5jwo2Qy6btfgFEBkJdIQbngDayV0b13cHR0VP7vgft5dff/BeGTdJ2FwcFB15HtWi4xkWImdU80OihvLGmJ+f+ZjroxNt5XeSO160XgGjZ2o7Bd9MnB0i1p4Kyz6o0kY1KeoMLIUQ8pUfdn4A8O/qpRg3b9ifjUGCXo34Mmrh52GSD1twADAOz0d+YcPWtKAAAAAElFTkSuQmCC" />
            </div>
            <div class="col-4">
                <h3>Электричка</h3>
                Электричкой с Белорусского вокзала или платформ "Беговая", "Фили", "Кунцево" (около одноименных станций метро) до платформы Жаворонки, а далее местным автобусом №32 до пансионата "Лесные Дали" (ходит редко).
            </div>
        </div>

        <!-- Автобусы конференции -->
        <div class="row" style="margin-bottom: 5mm;">
            <div class="col-1 text-right">
                <img alt="" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABoAAAAZCAYAAAAv3j5gAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA0tJREFUeNqklk1oE1EQxzebTdKGGFM/UrSWVihUbQzSYnuJFnrwpgjFc0DwJB6l0rMHD0VB6sGzgqBCb3rSgoJFD7loL1rwI0q1FFsLanezSfxN+jY8Y5Lu4sDwvmbn6/1n3oby+bwRCoWMSqViQCbzC4yn4OewDYcMf1SFIpZlja+vry/Mzc3dTCQSzuTkpBEOhw1LSXjCoxi6jtEP7E2ZphkOYojvXCifSqXODg4OLhaLxcfeoRg6ifJuFD9kvMI6zrikWHeiLYmseA7Jd8PZbHaa+VP4NDpWLDZvsBi2bXsgGo3uU8IZ9s8wbsKOz4hicATOlstlIx6Pd+VyuQwRPiDSguVJYaSTVP1megsP3jiOE2W9EolEbNbbpg/lHSjs5Y5mxXG+GcNITE+dd5Od8Bp8jeVnBQ7faZMUa2k+zN4dxo5/DHEQgWVdNf6fShgVT6PiRM0QGzOMvWyswlHJghySAt9aJRIPDEpxSZwmK6ucXWb+RbTdU/J94oGqB4O7MRqg35a4T11eDIUUkGb+Sp0YUUJuK48DkBioqAxtOaLdkeQz3O6OdGMyb2O8JIZUhoxmEQm5zRT7uacmhurwNj1YSp9iHRZLQaDdIkIxVNYjMuUSFccQLDM63uX6BYIeEUUqxSvwdtAX8xwwdeDA++EJeFeAZtpIKWCeQ/kBXYeleV3lcAB+RA2tsX4vjZWxyPgN/sH8l4ZKAU4cTsLdSvEAZXGQ+W5lpNIMDDGlRHLbJSxduB3y2gDGVvrqLcj0cghLp14kv8fgceYXifZlgJQVkL8EkCYYj8CvVfffiqhUKnnzt6RMno0eeV0x9oxxL1tj24FCHEXPPHKzyAvSjktdMl+qy2QymTpqhoaGXvD8juLVV9YbAgp5FH2iTvraCmjdKd/wvr3jhT0qSK5FNDIy4slKPhMYkUvuUZEFqaM9wto6DjDE6PeaoeXlZc8jO51OuzyAgeqnVU1hxO3v77frd5RMJuvnhH0XoUPMO7WPPjFs6H2xgQTCO/C8T2+qZOY+/LOZIalo+QOSJ/2qtneevScNpaCTi/wJLn5e1Zb8ut2mQ0zr0Lea9DV5gj+qd2kTJa+UjNsqVXSCAsM5VcAS+UJjC/sjwAAT6pRt15sXzAAAAABJRU5ErkJggg==" />
            </div>
            <div class="col-4">
                <h3>Автобусы конференции</h3>
                Автобусы отправляются от ст. м. «Молодёжная» до пансионата «Лесные Дали» см. место посадки
            </div>
        </div>

        <!-- Маршрутное такси -->
        <div class="row" style="margin-bottom: 5mm;">
            <div class="col-1 text-right">
                <img alt="" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAAZCAYAAADE6YVjAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA3NJREFUeNq0lstrE2EQwHc3mzSJsXlJmlRikKonixW9iO2hUMG7hyhePYj9F7yJgvQg3kUvguJJqh7Vgz14sD2ViiW0PkBCEDUmzfvhb7bfhm0am0RxYJjd+eY9882unk6nNYFms2lRr9erZbNZedcnJiaCjUYj2G6393E0ouu6W2R4F1IHq/C2oD9rtdovwzDa9XrdssWzVq1WtUwmo5mtVstSQtiivB+JRqNXoIcR9sEzMFIDGzzrEg/oEl+gCc8DbZmmWcZwBif3eN8UWy6XS0skEpopxt1utyYRIJTk+QHGFwOBwDOi+4DCD3hNFf0OEF30XNAoBo+VSqVpn88n+hfhZdHXqIRmSlpEYSmAl9F9XalUFqRsdnZ9QDLLIZcrl8tLOAkR1CV070iVBAxVrgQHp4hmhsPn4XBYGwZURhbm8/kX2JwFT2J3TOwbGD9DNo+QfYfwUeiGrTxAFh05j8ejxWIxbXR0NIOd4+AKQT8kYK+JkxnkzoJLCH+ClmxlaZwY+JMz4Uu5JRM7I9GH/xbMoj8diURShpRKTckXaBFa6mXYNtQNEojtSGFBjbXY82InLNN1UBkpMg2tQqGwIzIZAMFux7ZRuxeO7FrIt3BeUO8RExpXlzFL6SKhUGhXpHv1R85tGUcAMvI5FUhIynSVCfBydgDGhW4F+6L2m64uGRc2X3JHnhL4pjhZUxHNqls9kOFeIOOqHLbUlli27okwpXFqTcRA6ZFH+zsYAZPgmOwx2SQCpqMsNSkXeA78LCMIW/C7TJ29ENUNN1UgYmU/GAETGB6DHgL96N+0N4bprKOiQYQnSX2yT923V8Z2FTrPapU05NUeFsMhLw51yaBYLC6rmg4DVaWXU7Y6CTidSPo5JmJudXX1NM5uD+OBK3BD9KDnef2mSrnLiQvDXynJRiqVEqWVYRYkwa0kk9JzLaN6aTpLZEMd4SlGeSEej7/ByfVBnVjNNc1b4+PjAXoyB0sWZKPjxNFMQ0U1j9K8s6GDAMFNoffEYU/vVa7/BsYg9f6X8+6eONfDXcr1nr6kwCA3V/5WvGpDyIDIhZQLUWFzb9GPPPyP8E+gd20vJ87b/wpclF8bvtvWP4AzcudGlnO/329tYoJLO5z0nK518D7o4/uwbi+7fgMg5/JHIn8mOFojoMewtwik8xn/LcAALeLg3cqH3A8AAAAASUVORK5CYII=" />
            </div>
            <div class="col-7">
                <h3>Маршрутное такси</h3>
                На маршрутном такси №121 от станции метро "Молодежная" прямо до пансионата «Лесные Дали». Маршрутка отправляется каждые 15-20 минут по наполнению.
            </div>
        </div>
    </div>
</div>

<!-- Расписание автобусов -->

<div class="row row-bus_timeline" style="padding-left: 9mm;">
    <div class="col-12">
        <div class="row">
            <div class="col-12">
                <div class="title">Расписание автобусов</div>
            </div>
        </div>

        <div class="row row-fill">
            <div class="col-12">
                <div class="row">
                    <div class="col-3"><h3>22 апреля</h3></div>
                    <div class="col-3"><h3>23 апреля</h3></div>
                    <div class="col-3"><h3>24 апреля</h3></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <h4>M. «Молодёжная» → П-т «Лесные дали» <span>(время в пути – 60 минут без учета пробок)</span></h4>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-3">7:30, 8:00, 8:10, 8:20, 8:30, 8:40, 8:50, 9:00, 9:15, 9:30, 10:00, 10:30, 11:00, 11:30, 12:00, 12:30, 13:00, 13:30, 14:00, 14:30, 15:00, 15:30, 16:00, 16:30, 17:00</div>
                    <div class="col-3">7:30, 8:00, 8:10, 8:20, 8:30, 8:40, 8:50, 9:00, 9:15, 9:30, 10:00, 10:30, 11:00, 11:30, 12:00, 12:30, 13:00, 13:30, 14:00, 14:30, 15:00, 15:30, 16:00, 16:30, 17:00, 17:30</div>
                    <div class="col-3">7:30, 8:00, 8:15, 8:30, 8:40, 8:50, 9:00, 9:15, 9:30, 10:00, 10:30, 11:00, 11:30, 12:00, 12:30, 13:00, 13:30, 14:00, 14:30, 15:00, 15:30, 16:00, 16:30</div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <h4>П-т «Лесные дали» → M. «Молодёжная» <span>(время в пути – 60 минут без учета пробок)</span></h4>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-3">8:45, 9:15, 9:45, 10:15, 10:45, 11:15, 12:00, 12:30, 13:00, 13:30, 14:00, 14:40, 15:00, 15:30, 16:30, 17:40, 17:50, 18:30, 19:10, 20:10, 20:20, 20:30, 20:40, 20:50, 21:30, 22:10, 23:10</div>
                    <div class="col-3">8:45, 9:15, 9:45, 10:15, 10:45, 11:15, 12:00, 12:30, 13:00, 13:30, 14:00, 14:40, 15:00, 15:30, 16:00, 17:00, 18:10, 18:20, 19:00, 20:40, 20:50, 21:00, 21:10, 21:20, 22:00, 23:10</div>
                    <div class="col-3">8:45, 9:15, 9:45, 10:15, 10:45, 11:15, 12:00, 12:30, 13:00, 13:30, 14:00, 14:40, 15:00, 15:30, 16:30, 17:50, 18:00, 18:10, 18:15, 18:20, 18:25, 18:30, 18:40, 18:50, 19:00, 19:30, 20:00, 20:30</div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <h4>П-т «Поляны» → П-т «Лесные дали» <span>(время в пути – 5 минут)</span></h4>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-3">с 8.00 до 24.00</div>
                    <div class="col-3">с 8:00 до 24:00</div>
                    <div class="col-3">c 8.00 до 20.30</div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <h4>П-т «Лесные дали» → П-т «Назарьево» <span>(время в пути – 30 минут без учета пробок)</span></h4>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-3">10:00, 11:00, 12:30, 14:00, 15:00, 15:45, 18:00, 19:00, 20:20, 20:30, 22:00, 23:10</div>
                    <div class="col-3">10:00, 11:00, 12:30, 14:00, 15:00, 16:15, 18:15, 19:30, 20:50, 21:00, 22:00, 23:10</div>
                    <div class="col-3">10:00, 11:00, 12:30, 14:00, 15:00, 15:45, 18:15, 18:30, 19:45</div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <h4>П-т «Назарьево» → П-т «Лесные дали» <span>(время в пути – 30 минут без учета пробок)</span></h4>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-3">09:10, 09:20, 10:45, 11:45, 13:45, 14:45, 16:00, 17:15, 18:45, 19:45, 21:00, 22:00</div>
                    <div class="col-3">09:10, 09:20, 10:45, 11:45, 13:45, 15:15, 16:30, 17:45, 19:00, 20:15, 21:20, 22:20</div>
                    <div class="col-3">09:10, 09:20, 10:45, 11:45, 13:45, 14:45, 16:00, 17:30, 19:00</div>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
</div>
<sethtmlpagefooter name="main-footer" value="on" show-this-page="1" />
</div>
</div>

<pagebreak orientation="L"/>
<div class="text-center">
    <img src="http://2015.russianinternetforum.ru/upload/map.jpg" class="img-responsive"/>
</div>
<sethtmlpagefooter name="main-footer" value="on" show-this-page="1" />

<?php if (!empty($parking)):?>
<?php
    if (!in_array($role->Id, $parkingReporterRoleIdList)) {
        $y = 410;
        switch ($roomProductManager->Hotel) {
            case Rif::HOTEL_LD:
                $name = 'car_dali';
                break;

            case Rif::HOTEL_N:
                $name = 'car_nazarevo';
                break;

            case RIF::HOTEL_P:
                $name = 'car_polyany';
                break;
        }
    }
    else {
        $name = 'car_reporter';
        $y = 370;
    }

    $image = \Yii::app()->image->load(\Yii::getPathOfAlias('webroot.img.event.rif145.ticket.'.$name).'.jpg');
    $text1 = mb_strtoupper($parking['carNumber']);

    if (in_array($role->Id, $reporterRoles)) {
        $text2 = '23,24,25';
    }
        else {
            $dates = [];
            $datetime = new \DateTime($roomOrderItem->getItemAttribute('DateIn'));
            while ($datetime->format('Y-m-d') <= $roomOrderItem->getItemAttribute('DateOut')) {
                $dates[] = $datetime->format('d');
                $datetime->modify('+1 day');
            }
            $text2 = implode(',', $dates);
        }

        $path = '/img/event/rif14/ticket/assets/'.$user->RunetId.'.jpg';
        $image->text($text1,80,0,$y);
        $image->save(\Yii::getPathOfAlias('webroot').$path);
        $image = \Yii::app()->image->load(\Yii::getPathOfAlias('webroot').$path);
        $image->text($text2,80,150,595);
        $image->rotate(-90);
        $image->save(\Yii::getPathOfAlias('webroot').$path);
        ?>
        <div class="page-car" style="page-break-after: always">
            <img src="<?=$path;?>" />
        </div>
        <?if (in_array($role->Id, $reporterRoles)):?>
            <div class="page-carmap" style="page-break-after: always;">
                <img src="/img/event/rif14/ticket/map-reporter.jpg" />
            </div>
        <?endif;?>
    <?endif;?>
<?}?>