<?php


namespace App\Models\Idea\Traits;

/**
 * Description of generateBusinessReference
 *
 * @author Joe
 */
trait GenerateBusinessReferenceId
{

    /**
     * Description: The following makes sure that a unique random business reference id is generated for the respective model in which this trait is applied
     * @author Shuja Ahmed - I2L
     * @param $modelName
     * @return array
     */
    public function generateBusinessReferenceId($modelName)
    {
        $uniqueBusinessReferenceIdFound = false;
        $generateBusinessReferenceId = null;
        while (!$uniqueBusinessReferenceIdFound) {
            $generateBusinessReferenceId = getBusinessReferenceId($modelName);
            $businessReferenceIdCount = $this
                ->where('business_reference_id', $generateBusinessReferenceId)->count();
            if ($businessReferenceIdCount == 0) {
                $uniqueBusinessReferenceIdFound = true;
                $this->business_reference_id = $generateBusinessReferenceId;
            }
        }
        return [
            'response' => '1',
            'data' => ['business_reference_id' => $generateBusinessReferenceId],
            'message' => 'Business Reference id generated successfully.',
        ];
    }
}
