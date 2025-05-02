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

        return redirect()->to(route_to('show_discussion', $discussionId))->with('success', 'Discussion user added successfully.');
    }

    public function deleteCommentDiscussion($id)
    {
        $discussionUser = $this->discussionUserModel->find($id);

        if (!$discussionUser) {
            return redirect()->back()->with('error', 'Comment not found.');
        }

        $currentUser = $this->userProfileModel->where('user_id', user_id())->first();
        if ($discussionUser->user_profile_id !== $currentUser->id) {
            return redirect()->back()->with('error', 'You are not authorized to delete this comment.');
        }

        if (!$this->discussionUserModel->delete($id)) {
            return redirect()->back()->with('error', 'Failed to delete discussion user.');
        }

        return redirect()->back()->with('success', 'Discussion user deleted successfully.');
    }

    public function editCommentDiscussion($id)
    {
        $discussionUser = $this->discussionUserModel->find($id);

        if (!$discussionUser) {
            return redirect()->back()->with('error', 'Comment not found.');
        }

        $currentUser = $this->userProfileModel->where('user_id', user_id())->first();
        if ($discussionUser->user_profile_id !== $currentUser->id) {
            return redirect()->back()->with('error', 'You are not authorized to edit this comment.');
        }

        $data = [
            'content' => $this->request->getPost('content'),
        ];

        $discussionUser->fill($data);

        if (!$this->discussionUserModel->save($discussionUser)) {
            return redirect()->back()->with('error', 'Failed to update discussion user.');
        }

        return redirect()->to(route_to('show_discussion', $discussionUser->discussion_id))->with('success', 'Discussion user updated successfully.');
    }
}