<?php

namespace App\Library;
use App\Models\User;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Http\Requests\ActionRequest;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class DownloadUsersIntoExcel extends DownloadExcel
{

    /**
     * @param ActionRequest $request
     * @param Action        $exportable
     *
     * @return array
     */
    public function handle(ActionRequest $request, Action $exportable): array
    {
        $retArray = [];
        $exportable->query = $this->customQuery($request);
        \Log::info(varDump($exportable->query, ' -1 handle $exportable->query::'));
        return $retArray;
    }

    private function customQuery($request)
    {
        \Log::info(' -1 customQuery $exportable->query::');
//        \Log::info(encode_json($e));
        return User::all();
    }


}
