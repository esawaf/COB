<?php

namespace app\models;

abstract class VisitStatus {

    const PENDING = 'Pending';
    const CANCELED = 'Canceled';
    const POSTPONED = 'Postponed';
    const NOSHOW = 'No Show';
    const COMPLETED = 'Compleled';
    const CHECKED_IN = 'Checked In';
    const PAID = 'Paid';

}

?>