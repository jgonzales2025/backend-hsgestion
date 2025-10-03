<?php

namespace App\Modules\UserAssignment\Application\DTOs;

class UserAssignmentDTO
{
    public $id;
    public $userId;
    public $assignments;
    public $status;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? null;
        $this->userId = $data['user_id'];
        $this->assignments = $data['assignments'];
        $this->status = $data['status'];

    }
}
