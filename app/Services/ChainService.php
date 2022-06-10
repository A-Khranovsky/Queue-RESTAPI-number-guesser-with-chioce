<?php


namespace App\Services;

use App\Jobs\GuessJob;
use App\Models\Param;
use Illuminate\Support\Facades\Bus;

class ChainService extends HomeControllerService
{

    public function start($request)
    {
        $links = [];

        $this->getConfigParams($request);
        $this->args['links'] = $request->links ?? config('guessjob.links');

        for ($i = 1; $i <= $this->args['links']; $i++) {
            $links[] = new GuessJob($this->args);
        }

        Bus::chain($links)->dispatch();
        $result = ' Args:';
        array_walk_recursive($this->args, function ($item, $key) use (&$result) {
            $result .= ' ' . $key . ' = ' . $item;
        });

        return response('Started, ' . $result ?? '', 200);
    }

    public function result()
    {
        $chainCount = 0;
        $result = [];
        $param = Param::all();

        $param->each(function ($item, $key) use (&$result, &$chainCount) {
            $chainCount++;
            $chainLength = json_decode($item->params, true)['links'];

            if ($chainCount === $chainLength - ($chainLength - 1)) {
                $result[] = [
                    'chain length' => $chainLength
                ];
            }
            $statusOkItem = $item->logs->filter(function ($item, $key) {
                return $item['status'] === 'OK';
            });

            if (sizeof($statusOkItem) > 0) {
                $result[] = [
                    'transaction' => $statusOkItem->first()->transaction,
                    'guess number'  => $statusOkItem->first()->guessNumber,
                    'status' => 'OK'
                ];
            } else {
                $statusFailedItem = $item->logs->filter(function ($item, $key) {
                    return $item['status'] === 'Failed';
                });
                if (sizeof($statusFailedItem) > 0) {
                    $result[] = [
                        'transaction' => $statusFailedItem->first()->transaction,
                        'guess number'  => $statusFailedItem->first()->guessNumber,
                        'status' => 'Failed'
                    ];
                } else {
                    $result[] = 'Aborted';
                }
            }

            if($chainCount == $chainLength){
                $chainCount = 0;
            }
        });

        return $result;
    }
}