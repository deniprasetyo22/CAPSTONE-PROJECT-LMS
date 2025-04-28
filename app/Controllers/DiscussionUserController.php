<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DiscussionUserModel;
use App\Models\UserProfileModel;

class DiscussionUserController extends BaseController
{
    protected $discussionUserModel;
    protected $userProfileModel;

    public function __construct()
    {
        $this->discussionUserModel = new DiscussionUserModel();
        $this->userProfileModel = new UserProfileModel();
    }

    public function addCommentDiscussion($discussionId)
    {
        $currentUser = $this->userProfileModel->where('user_id', user_id())->first();
        $data = [
            'content' => $this->request->getPost('content'),
            'discussion_id' => $discussionId,
            'user_profile_id' => $currentUser->id,
        ];

        if (!$this->discussionUserModel->save($data)) {
            return redirect()->back()->with('error', 'Failed to add discussion user.');
        }

        return redirect()->to('discussion/' . $discussionId)->with('success', 'Discussion user added successfully.');
    }
}
