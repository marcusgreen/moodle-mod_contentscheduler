<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin strings are defined here.
 *
 * @package     mod_contentscheduler
 * @category    string
 * @copyright   2022 Marcus Green
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['modulename'] = 'Content Scheduler';
$string['modulenameplural'] = 'Content Schedulers';
$string['pluginname'] = 'Content Scheduler';
$string['name'] = 'Name';
$string['timing'] = 'Timing';
$string['activities'] = 'Activities';
$string['contentschedulersettings'] = 'Settings';
$string['contentschedulerfieldset'] = 'Fieldset';
$string['start'] = 'Start';
$string['start_help'] = 'The time periods/intervals that activities are available are set here. First at the
"Start:" section, the day, month and year that the activity cycle will start is set. The
blue calendar will open a pop-up calendar to select dates as an option.';
$string['timefinish'] = 'Finish';
$string['timefinish_help'] = '"Finish": Is the date that all activities/final session will close. The time will remain
this time to finish a session before the next one begins.';
$string['repeat'] = 'Repeat every';
$string['repeat_text'] = 'Repeat settings help';
$string['repeat_help'] =
 '"Repeat every:" is an option that decides how long the intervals are. E.g. A new
 set of activities will be available every 2 weeks and the currently available ones
 will then become unavailable.';
$string['weekcount'] = 'Week count';
$string['weekcount_text'] = 'Week count help description';
$string['weeks'] = 'week(s)';
$string['weekcount'] = 'Week count';
$string['repeatenable'] = 'Enable';
$string['numberofsessions'] = 'Number of sessions';
$string['numberofsessions_help'] = '"Number of sessions:" is an option where the total number of sessions/cycles
can be set. Depending on the start and finish dates, the length of these
sessions will be automatically evenly distributed.
E.g. Say the number of weeks between the start date and the finish date was
16 weeks. If a the number of sessions was set at 8, then the period of one
session would be 2 weeks, 16 weeks / 8 sessions. However, if the sessions was
set at 4, then the period would be 4 weeks, 16 weeks/4 sessions. Finally if the
sessions was 16, it would be a cycle every week.';
$string['numberofsessionsenable'] = 'Enable';
$string['activitiespersession'] ='Activities per session';
$string['activitiespersession_help'] ='"Activities per session" sets the number of activities that are available in each
session.';
$string['contentschedulername'] = 'String 1';
$string['pluginadministration'] = 'Content sceduler administration';
$string['name'] = 'Name';
$string['descriptions'] = 'Description';
$string['questions'] = 'Question';
$string['from'] = 'From';
$string['to'] = 'To';





