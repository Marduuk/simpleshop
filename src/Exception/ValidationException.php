<?php
declare(strict_types=1);

namespace App\Exception;

use Exception;
/**
 * Class ValidationException
 * @package App\Exception
 */
class ValidationException extends Exception
{
    /** @var array  */
    private $violations;

    public function __construct(array $violations)
    {
        $this->violations = $violations;
        parent::__construct('Validation failed.');
    }

    /**
     * @return array
     */
    public function getJoinedMessages(): array
    {
        $data = [];
        foreach ($this->violations as $key => $violations){
            foreach ($violations as $violationList) {
                foreach ($violationList as $violation){
                    $data[$key][] = $violation->getMessage();
                }
            }
        }
        return $data;
    }
}