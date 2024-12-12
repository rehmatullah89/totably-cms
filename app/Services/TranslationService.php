<?php

namespace App\Services;

class TranslationService
{
    /**
     * insert new translations.
     * @param $data
     * @param $model
     * @return bool|void
     */
    public function insertTranslations(&$data, $model)
    {
        if (empty($data['translations'])) {
            return;
        }

        $translations = $data['translations'];

        //if update
        if ($model->id) {
            $model->deleteTranslations();
        }

        foreach ($translations as $locale => $translation) {
            //remove frogein key like category_id or item_id or course_id
            unset($translation[$model->getRelationKey()]);

            //loop on each fields
            foreach ($translation as $key => $value) {
                //add new model translation record
                $model->translateOrNew($locale)->{$key} = $value;
                //remove the field from the data if it is sent too
                unset($data[$key]);
            }
        }

        unset($data['translations']);

        return true;
    }
}
