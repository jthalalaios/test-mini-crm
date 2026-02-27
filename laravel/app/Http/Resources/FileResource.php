<?php

namespace App\Http\Resources;

class FileResource extends GeneralResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
     public function toArray($request)
    {
        $data = $this->resource;

        if(isset($data['tournament_id'])) {
            $tournament_steps_data = [];        

            $data = [
                'id' => $this->resource['file']['id'],
                'file_name' => $this->resource['file']['file_name'],
                'file_type' => $this->resource['file']['file_type'],
                'file_size' => $this->resource['file']['file_size'],
                'file_path' => $this->resource['file']['file_path'],
                'foreign_id' => $this->resource['file']['foreign_id'],
                'path' => $this->resource['file']['path'],
                'resolution' => $this->resource['file']['resolution'],
                'user_id' => $this->resource['file']['user_id'],
                'created_at' => $this->resource['file']['created_at'],
                'updated_at' => $this->resource['file']['updated_at'],
                'deleted_at' => $this->resource['file']['deleted_at'],
                'steps_dates' => $tournament_steps_data,
            ];
        }

        return 
        [
            'data' => $data
        ];
    } 
}
