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