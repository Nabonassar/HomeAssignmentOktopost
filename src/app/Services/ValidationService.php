<?php

namespace App\Services;

use App\Exceptions\ValidationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;

class ValidationService
{
    /**
     * @var ValidationFactory
     */
    protected $validator;

    /**
     * Constructor
     *
     * @param ValidationFactory $validator
     */
    public function __construct(ValidationFactory $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Validates request parameters according to the given rules
     *
     * @param Request $request
     * @param array<string, string> $rules
     * @return mixed[]
     * @throws ValidationException
     */
    public function validateParameters(Request $request, array $rules): array
    {
        $validation = $this->validator->make($request->all(), $rules);
        if ($validation->fails()) {
            throw new ValidationException('Invalid Parameters', Response::HTTP_BAD_REQUEST);
        }
        return  $validation->validated();
    }

    /**
     * Validates existence of entity given its ID
     *
     * @param Model $model
     * @param integer $entityID
     * @return Model
     * @throws ValidationException
     */
    public function validateModelEntity(Model $model, int $entityID): array|Model
    {
        $entity = $model->find($entityID);
        if (!$entity) {
            throw new ValidationException('Entity Not Found', Response::HTTP_NOT_FOUND);
        }
        return $entity;
    }
}
