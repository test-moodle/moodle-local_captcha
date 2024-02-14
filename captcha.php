<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package    local_captcha
 * @copyright  2023 Austrian Federal Ministry of Education
 * @author     GTN solutions
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__.'/inc.php');

use Gregwar\Captcha\CaptchaBuilder;

$newCode = false;
if (!empty($SESSION->captcha_phrase) && $SESSION->captcha_time >= microtime(true) - 60 * 10 && !@$_REQUEST['regenerate_captcha']) {
    // same captcha for X minutes
    $builder = new CaptchaBuilder($SESSION->captcha_phrase);
    $builder->build(150, 40, null, $SESSION->captcha_fingerprint);
} else {
    $newCode = true;
    $builder = new CaptchaBuilder();
    $builder->build(150, 40);

    $SESSION->captcha_phrase = $builder->getPhrase();
    $SESSION->captcha_time = microtime(true);
    $SESSION->captcha_fingerprint = $builder->getFingerprint();
}

header('Content-type: image/jpeg');
$builder->output();
