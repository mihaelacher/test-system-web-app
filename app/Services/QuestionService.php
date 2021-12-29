<?php

namespace App\Services;

use App\Exceptions\QuestionUpdateException;
use App\Http\Requests\MainFormRequest;
use App\Models\Question\Question;
use App\Models\Question\QuestionAnswer;
use App\Models\Question\QuestionType;
use App\Models\Test\TestQuestions;
use App\Util\LogUtil;
use App\Util\MessageUtil;
use Illuminate\Support\Facades\DB;

class QuestionService
{
    /**
     * @param MainFormRequest $request
     * @param int|null $questionId
     * @return void
     */
    public static function handleQuestionOperations(MainFormRequest $request, ?int $questionId = null)
    {
        $question = $questionId ? Question::findOrFail($questionId) : new Question();
        $questionTypeId = $request->type;
        try {
            DB::beginTransaction();

            self::deleteQuestionAnswers($questionId);

            $questionId = QuestionService::setQuestionAttributes($question, $request->text, $request->points,
                $questionTypeId, $request->instruction, $request->max_markable_answers);

            if (!$questionId) {
                throw new QuestionUpdateException('Question couldn\'t be created/updated');
            }

            if (QuestionService::isQuestionClosed($questionTypeId)) {
                QuestionService::storeQuestionAnswers($request->value, $request->is_correct, $questionId);
            }
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();

            LogUtil::logError($e->getMessage());

            MessageUtil::error('Oops...Something went wrong');
        }
    }

    /**
     * @param int|null $questionId
     * @return void
     */
    public static function destroyQuestion(?int $questionId = null)
    {
        try {
            if (is_null($questionId)) {
                throw new QuestionUpdateException('Couldn\'t delete question data, no id provided!');
            }

            DB::beginTransaction();

            self::deleteQuestionAnswers($questionId);
            TestQuestions::where('question_id', '=', $questionId)->delete();
            Question::find($questionId)->delete();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            LogUtil::logError($e->getMessage());

            MessageUtil::error('Oops...Something went wrong');
        }
    }

    /**
     * @param int $questionTypeId
     * @return bool
     */
    private static function isQuestionClosed(int $questionTypeId): bool
    {
        return in_array($questionTypeId, QuestionType::CLOSED_QUESTIONS);
    }

    /**
     * @param Question $question
     * @param string $text
     * @param float $points
     * @param int $typeId
     * @param string|null $instruction
     * @param int|null $maxMarkableAnswers
     * @return int
     */
    private static function setQuestionAttributes(Question $question, string $text, float $points, int $typeId,
                                                  ?string $instruction = null, ?int $maxMarkableAnswers = null): int
    {
        $maxMarkableAnswers = $typeId === QuestionType::MULTIPLE_CHOICE
            ? $maxMarkableAnswers
            : ($typeId === QuestionType::SINGLE_CHOICE ? 1 : null);

        $question->text = $text;
        $question->instruction = $instruction;
        $question->points = $points;
        $question->question_type_id = $typeId;
        $question->max_markable_answers = $maxMarkableAnswers;
        $question->save();

        return $question->id;
    }

    /**
     * @param array $answerValues
     * @param array $answerIsCorrect
     * @param int $questionId
     * @return void
     * @throws QuestionUpdateException
     */
    private static function storeQuestionAnswers(array $answerValues, array $answerIsCorrect, int $questionId): void
    {
        $rowsForInsert = [];

        for ($i = 0; $i < count($answerValues); $i++) {
            $rowsForInsert[] = [
                'order_num' => $i + 1,
                'value' => $answerValues[$i],
                'is_correct' => $answerIsCorrect[$i],
                'question_id' => $questionId
            ];
        }

        if (empty($rowsForInsert)) {
            throw new QuestionUpdateException('Question\'s answers couldn\'t be inserted!');
        }

        QuestionAnswer::insert($rowsForInsert);
    }

    /**
     * @param int|null $questionId
     * @return void
     */
    private static function deleteQuestionAnswers(?int $questionId)
    {
        if ($questionId) {
            QuestionAnswer::where('question_id', '=', $questionId)->delete();
        }
    }
}
