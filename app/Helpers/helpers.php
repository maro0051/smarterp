<?php

use App\Helpers\ActivityHelper;

if (!function_exists('activity')) {
    /**
     * Log an activity
     */
    function activity()
    {
        return new class {
            protected $performedOn;
            protected $causedBy;
            protected $properties = [];
            protected $log;

            public function performedOn($model)
            {
                $this->performedOn = $model;
                return $this;
            }

            public function causedBy($user)
            {
                $this->causedBy = $user;
                return $this;
            }

            public function withProperties($properties)
            {
                $this->properties = $properties;
                return $this;
            }

            public function log($description)
            {
                $module = 'General';
                if ($this->performedOn) {
                    $module = class_basename($this->performedOn);
                }

                ActivityHelper::log(
                    'logged',
                    $module,
                    $description,
                    null,
                    $this->properties
                );
            }
        };
    }
}