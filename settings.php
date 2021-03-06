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


if ($ADMIN->fulltree) {
    $settings->add(new admin_setting_configtext('contentscheduler/repeatcount',
             get_string('repeatcount','contentscheduler'),
             get_string('repeatcount_text','contentscheduler'),
            '1',PARAM_ALPHANUMEXT, 3));
    $settings->add(new admin_setting_configtext('contentscheduler/sessioncount',
            get_string('sessioncount', 'contentscheduler'),
            get_string('sessioncount_text', 'contentscheduler'),
            '16', PARAM_ALPHANUMEXT,3));

$settings->add(new admin_setting_configtext('contentscheduler/activitiespersession',
            get_string('activitiespersession', 'contentscheduler'),
            get_string('activitiespersession_text', 'contentscheduler'),
            '5', PARAM_ALPHANUMEXT,3));
$settings->add(new admin_setting_configtext('contentscheduler/defaultname',
            get_string('defaultname', 'contentscheduler'),
            get_string('defaultname_text', 'contentscheduler'),
            'Content schedule', PARAM_RAW, 40));

}
