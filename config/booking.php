<?php

return [
  'duration' => [
    '1800' => '30 mins',
    '3600' => '1 hr',
    '5400' => '1.5 hr',
    '7200' => '2 hr',
    '9000' => '2.5 hr',
    '10800' => '3 hr',
    '12600' => '3.5 hr',
    'am-half' => 'AM Half (08:30 - 12:30)',
    'pm-half' => 'PM Half (01:30 - 06:00)',
    'full-day' => 'Full day (8:30am-6pm)'
  ],
  'purpose_labels' => [
    'Stand up',
    'GM Meeting',
    'Daily Scrum',
    'Sprint Review',
    'Staff Meetings',
    'Sprint Planning',
    'Project Meetings',
    'Knowledge Sharing',
    'Retrospective Meeting',
    'Collaborative Meetings',
  ],
  'recursion_frequency' => [
    'daily' => 'Daily',
    'weekly' => 'Weekly',
    'monthly' => 'Monthly',
  ],
  'recursion_count' => [
    '2' => 2,
    '3' => 3,
    '4' => 4,
    '5' => 5,
  ],

  /* These hash length values are constant. You should not change
   * this if you already have users who made bookings. The link on
   * the email will not work if the value gets changed.
   */
  'hashes' => [
    'VIEW_HASH_LENGTH' => 9,
    'CONFIRMATION_HASH_LENGTH' => 5
  ]
];
