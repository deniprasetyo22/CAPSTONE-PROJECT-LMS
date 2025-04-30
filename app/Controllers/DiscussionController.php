<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DiscussionModel;
use App\Models\DiscussionUserModel;
use App\Models\UserProfileModel;

class DiscussionController extends BaseController
{
    protected $discussionModel;
    protected $userProfileModel;
    protected $discussionUserModel;

    public function __construct()
    {
        $this->discussionModel = new DiscussionModel();
        $this->userProfileModel = new UserProfileModel();
        $this->discussionUserModel = new DiscussionUserModel();
    }
    public function addDiscussionForm($courseId)
    {
        $data = [
            'page_title' => 'Add Discussion',
            'course_id' => $courseId,
        ];
        return view('pages/courses/discussions/add_discussion', $data);
    }

    public function addDiscussion($courseId)
    {
        $currentUser = $this->userProfileModel->where('user_id', user_id())->first();
        $data = [
            'topic' => $this->request->getPost('topic'),
            'description' => $this->request->getPost('description'),
            'course_id' => $courseId,
            'created_by' => $currentUser->id,
        ];

        if (!$this->discussionModel->save($data)) {
            return redirect()->back()->with('error', 'Failed to add discussion.');
        }

        return redirect()->to('courses/detail/' . $courseId)->with('success', 'Discussion added successfully.');
    }

    public function editDiscussionForm($discussionId)
    {
        $discussion = $this->discussionModel->find($discussionId);

        if (!$discussion) {
            return redirect()->back()->with('error', 'Discussion not found.');
        }

        $data = [
            'page_title' => 'Edit Discussion',
            'discussion' => $discussion,
        ];
        return view('pages/courses/discussions/edit_discussion', $data);
    }

    public function editDiscussion($discussionId)
    {
        $discussion = $this->discussionModel->find($discussionId);

        if (!$discussion) {
            return redirect()->back()->with('error', 'Discussion not found.');
        }

        $data = [
            'topic' => $this->request->getPost('topic'),
            'description' => $this->request->getPost('description'),
        ];

        if (!$this->discussionModel->update($discussionId, $data)) {
            return redirect()->back()->with('error', 'Failed to update discussion.');
        }

        return redirect()->to('courses/detail/' . $discussion->course_id)->with('success', 'Discussion updated successfully.');
    }

    public function deleteDiscussion($discussionId)
    {
        $discussion = $this->discussionModel->find($discussionId);

        if (!$discussion) {
            return redirect()->back()->with('error', 'Discussion not found.');
        }

        if (!$this->discussionModel->delete($discussionId)) {
            return redirect()->back()->with('error', 'Failed to delete discussion.');
        }

        return redirect()->to('courses/detail/' . $discussion->course_id)->with('success', 'Discussion deleted successfully.');
    }

    private function getTimeAgo($dateTime)
    {
        $now = new \DateTime();
        $createdAt = new \DateTime($dateTime);
        $interval = $now->diff($createdAt);

        if ($interval->y > 0) {
            return $interval->y . ' years ago';
        } elseif ($interval->m > 0) {
            return $interval->m . ' months ago';
        } elseif ($interval->d > 0) {
            return $interval->d . ' days ago';
        } elseif ($interval->h > 0) {
            return $interval->h . ' hours ago';
        } elseif ($interval->i > 0) {
            return $interval->i . ' minutes ago';
        } else {
            return 'just now';
        }
    }

    public function showDiscussionDetail($discussionId)
    {
        $discussion = $this->discussionModel
            ->select('discussions.*, user_profiles.first_name, user_profiles.last_name')
            ->join('user_profiles', 'user_profiles.id = discussions.created_by')
            ->where('discussions.id', $discussionId)
            ->first();

        if (!$discussion) {
            return redirect()->back()->with('error', 'Discussion not found.');
        }

        $currentUser = $this->userProfileModel
            ->select('id')
            ->where('user_id', user_id())->first();

        $discussion->timeAgo = $this->getTimeAgo($discussion->created_at);

        $discussionUser = $this->discussionUserModel
            ->select('discussion_users.*, user_profiles.first_name, user_profiles.last_name')
            ->join('user_profiles', 'user_profiles.id = discussion_users.user_profile_id')
            ->where('discussion_users.discussion_id', $discussionId)
            ->findAll();

        $discussionUserFormat = array_map(function ($d) use ($currentUser) {
            $timeAgo = $this->getTimeAgo($d->created_at);
            $d->timeAgo = $timeAgo;
            if ($currentUser->id == $d->user_profile_id) {
                $d->isCurrentUser = true;
            } else {
                $d->isCurrentUser = false;
            }
            return $d;
        }, $discussionUser);

        $isTrue = true;
        if (in_groups('student')) {
            $isTrue = false;
        }


        $data = [
            'page_title' => 'Discussion Detail',
            'discussion' => $discussion,
            'discussions_users' => $discussionUserFormat,
            'hideHeader' => $isTrue
        ];
        return view('pages/courses/discussions/detail_discussion', $data);
    }
}
