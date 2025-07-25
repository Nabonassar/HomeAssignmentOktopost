<?php

namespace App\Services;

use App\Exceptions\ValidationException;
use App\Models\Status;
use App\Models\Task;
use Illuminate\Database\Connection;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TaskService
{
    const DEFAULT_STATUS = 'open';

    /**
     * @var Status
     */
    protected $status;

    /**
     * @var Connection
     */
    protected $db;

    /**
     * @var [type]
     */
    protected $queryBuilderService;

    /**
     * Constructor
     *
     * @param Status $status
     * @param Connection $db
     * @param QueryBuilderService $queryBuilderService
     */
    public function __construct(Status $status, Connection $db, QueryBuilderService $queryBuilderService)
    {
        $this->status = $status;
        $this->db = $db;
        $this->queryBuilderService = $queryBuilderService;
    }

    /**
     * Inserts new or updates existing task
     *
     * @param Task $task
     * @param Request $request
     * @return Task
     */
    public function saveTask(Task $task, array $parameters): Task
    {
        $status = $parameters['status'] ?? self::DEFAULT_STATUS;
        $task->title = $parameters['title'];
        $task->description = $parameters['description'] ?? '';
        $task->status_id = $this->getStatusID($status);
        $task->save();
        $task->status = $status;
        return $task;
    }

    /**
     * Updates task status
     *
     * @param Task $task
     * @param string $status
     * @return void
     */
    public function updateStatus(Task $task, string $status)
    {
        return $task->update(['status_id' => $this->getStatusID($status)]);
    }

    /**
     * Get status ID by name
     *
     * @param string $status
     * @return int
     * @throws ValidationException
     */
    public function getStatusID(string $status): int
    {
        $status = $this->status->where('name', '=', $status)->first();
        if (!$status) {
            throw new ValidationException('Invalid Status', Response::HTTP_BAD_REQUEST);
        }
        return $status->id;
    }

    /**
     * Get status name by ID
     *
     * @param integer $id
     * @return string
     */
    public function getStatusName(int $id): string
    {
        $status = $this->status->find($id);
        if (!$status) {
            throw new ValidationException('Invalid Status', Response::HTTP_BAD_REQUEST);
        }
        return $status->name;
    }

    /**
     * Returns a list of tasks and their statuses
     *
     * @param array<string, mixed> $parameters
     * @param Task $task
     * @return Collection
     */
    public function fetch(array $parameters, Task $task): Collection
    {
        $select = ['tasks.*', 'name as status'];
        return $this->executeBuiltQuery(
            $this->getBasicTasksQuery($select),
            $parameters,
            $select,
            $task
        );
    }

    /**
     * Calculates number of tasks per status
     *
     * @param array<string, mixed> $parameters
     * @param Task $task
     * @return Collection
     */
    public function getStats(array $parameters, Task $task): Collection
    {
        $countStatement = 'count(*) as count';
        $select = ['name as status', 'status_id'];
        return $this->executeBuiltQuery(
            $this->getBasicTasksQuery([$this->db->raw($countStatement), ...$select])->groupBy('status_id'),
            $parameters,
            [$countStatement, ...$select],
            $task
        );
    }

    /**
     * Generates basic query - select from tasks table joined with statuses
     *
     * @param string[] $select
     * @return Builder
     */
    public function getBasicTasksQuery(array $select): Builder
    {
        return $this->db->table('tasks')->join('statuses', 'tasks.status_id', '=', 'statuses.id')->select($select);
    }

    /**
     * Builds and executes query
     *
     * @param Builder $basicQuery
     * @param array<string, mixed> $parameters
     * @param string[] $select
     * @param Task $task
     * @return Collection
     */
    public function executeBuiltQuery(Builder $basicQuery, array $parameters, array $select, Task $task): Collection
    {
        return $this->queryBuilderService->buildQueryFromParameters(
            $this->queryBuilderService->filterInvalidParameters($parameters, $select, $this->db->getSchemaBuilder()->getColumnListing($task->getTable())),
            $basicQuery,
            collect($select)->map(function (string $field) {
                if (!str_contains($field, ' as ')) {
                    return [];
                }
                $aliasBreakdown = explode(' as ', $field);
                return [$aliasBreakdown[1] => $aliasBreakdown[0]];
            })->collapse()->all()
        )->get();
    }
}
