<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BatchLogsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($this->canceled) {
            $status = 'canceled';
        } else {
            $status = sizeof($this->logs) > 0 ? $this->logs->pluck('result') : 'in process';
        }
        return [
            'id' => $this->id,
            'batch_id' => $this->id_batch,
            'progress' => $this->progress,
            'jobs' => $this->jobs,
            'successed' => $this->successed,
            'failed' => $this->failed,
            'status' => $status,
        ];
    }
}
