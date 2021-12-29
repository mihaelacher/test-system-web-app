<?php

namespace App\Policies;

use App\Models\Authorization\User;
use App\Models\Question\Question;
use App\Models\Test\TestExecution;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuestionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\Authorization\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return (bool) $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\Authorization\User $user
     * @param Question $question
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user)
    {
        return (bool) $user->isAdmin();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\Authorization\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return (bool) $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\Authorization\User $user
     * @param Question $question
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Question $question)
    {
        // a question can be modified only
        // 1. by creator
        // 2. if the question DOESN'T belong to existing test execution
        return $user->isAdmin()
        && $question->created_by === $user->id
        && !$this->belongsQuestionToTestExecution($question->id);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\Authorization\User $user
     * @param Question $question
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Question $question)
    {
        // a question can be deleted only
        // 1. by creator
        // 2. if the question DOESN'T belong to existing test execution
        return $user->isAdmin()
            && $question->created_by === $user->id
            && !$this->belongsQuestionToTestExecution($question->id);
    }

    //----------------------------------- Utility methods --------------------------------------------//

    /**
     * @param int $questionId
     * @return mixed
     */
    private function belongsQuestionToTestExecution(int $questionId)
    {
        return TestExecution::join('test_instances as ti', 'ti.id', '=', 'test_executions.test_instance_id')
            ->join('tests as t', 't.id', '=', 'ti.test_id')
            ->join('test_questions as tq', 'tq.test_id', '=', 't.id')
            ->where('tq.question_id', '=', $questionId)
            ->exists();
    }
}
