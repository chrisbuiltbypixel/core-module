<?php
namespace Modules\Core\Traits;

trait FilteredSearch
{

    public static function filteredSearch($request)
    {
        if (empty($request->search)) {
            $items = self::query();
        } else {
            $items = self::search($request->search);
        }

        if ($filters = $request->filters) {
            foreach ($filters as $name => $value) {
                $items = $items->where($name, $value);
            }
        }

        $items = $items->orderBy($request->order, $request->sort)
            ->paginate($request->paginate);

        return $items;
    }
}
