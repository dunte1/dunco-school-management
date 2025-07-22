<?php

return [
    'name' => 'Timetable',
    'default_time_slots' => [
        '08:00-09:00',
        '09:00-10:00',
        '10:00-11:00',
        '11:00-12:00',
        '12:00-13:00',
        '14:00-15:00',
    ],
    'teaching_hours_per_week' => 30,
    'periods_per_day' => 6,
    'schedule_format' => 'daily', // options: daily, bi-weekly, rotational
];
