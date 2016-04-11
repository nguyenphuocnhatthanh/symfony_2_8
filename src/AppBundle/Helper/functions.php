<?php
/**
 * Created by PhpStorm.
 * User: nhatthanh
 * Date: 4/6/16
 * Time: 11:37 AM
 */

/**
 * Starts the timer with the specified name.
 *
 * If you start and stop the same timer multiple times, the measured intervals
 * will be accumulated.
 *
 * @param $name
 *   The name of the timer.
 */
function timer_start($name) {
    global $timers;

    $timers[$name]['start'] = microtime(TRUE);
    $timers[$name]['count'] = isset($timers[$name]['count']) ? ++$timers[$name]['count'] : 1;
}

/**
 * Reads the current timer value without stopping the timer.
 *
 * @param $name
 *   The name of the timer.
 *
 * @return
 *   The current timer value in ms.
 */
function timer_read($name) {
    global $timers;

    if (isset($timers[$name]['start'])) {
        $stop = microtime(TRUE);
        $diff = round(($stop - $timers[$name]['start']) * 1000, 2);

        if (isset($timers[$name]['time'])) {
            $diff += $timers[$name]['time'];
        }
        return $diff;
    }
    return $timers[$name]['time'];
}

function languagesSupportByTextmaster() {
    return [
        "ar-sa", "bg-bg", "bs-ba", "cs-cz", "da-dk", "de-de", "el-gr", "en-us", "en-gb", "es-es", "fi-fi",
        "fr-fr", "hr-hr", "hu-hu", "it-it", "ja-jp", "ko-kr", "nl-be", "nl-nl", "no-no", "pl-pl", "pt-br",
        "pt-pt", "ro-ro", "ru-ru", "sk-sk", "sl-si", "sr-rs", "sv-se", "tr-tr", "zh-cn"
    ];
}

function listCategoryForTextMaster() {
    return [
        "C001", "C002",  "C003", "C004", "C005", "C006", "C007", "C008", "C009", "C010", "C011", "C012", "C013", "C014",
        "C015", "C016", "C017", "C018", "C019", "C020", "C021", "C022", "C023", "C024", "C025", "C026", "C027", "C028",
        "C029", "C030", "C031", "C032"
    ];
}

function addObjectKeyForArray(array &$array, $key_exist, $object_name) {
    if (isset($array[$key_exist])) {
        $value = $array[$key_exist];
        $array[$key_exist] = [$object_name => $value];
    }
}

function addTextMasterDateForArray(array &$array) {
    if (array_key_exists('created_at', $array)) {
        $value = $array['created_at'];
        $array['created_at'] = ['TextMasterDate' => $value]; //? ['TextMasterDate' => $value] : $textmaster_date_default;
    }

    if (array_key_exists('updated_at', $array)) {
        $value = $array['updated_at'];
        $array['updated_at'] = ['TextMasterDate' => $value]; //? ['TextMasterDate' => $value] :  $textmaster_date_default;
    }

    if (array_key_exists('launched_at', $array)) {
        $value = $array['launched_at'];
        $array['launched_at'] = ['TextMasterDate' => $value]; //? ['TextMasterDate' => $value] : $textmaster_date_default;
    }

    if (array_key_exists('completed_at', $array)) {
        $value = $array['completed_at'];
        $array['completed_at'] = $value ? ['TextMasterDate' => $value] : null;
    }
}

