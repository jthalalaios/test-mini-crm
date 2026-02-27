<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

abstract class BaseResource extends JsonResource
{
    protected $custom_message;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request); // Return the resource data as usual
    }

    /**
     * Set a custom message to be included in the response.
     *
     * @param string $message
     * @return $this
     */
    public function withMessage($message)
    {
        $this->custom_message = $message;
        return $this;
    }

    /**
     * Add additional data to the resource response.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function with($request)
    {
        return [
            'message' => $this->custom_message
        ];
    }
}
